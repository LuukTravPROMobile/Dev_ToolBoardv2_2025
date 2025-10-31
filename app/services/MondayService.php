<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class MondayService
{
    private Client $client;
    private string $apiToken;
    private string $apiUrl;

    public function __construct()
    {
        $this->apiToken = config('services.monday.api_token');
        $this->apiUrl = config('services.monday.api_url');
        
        $this->client = new Client([
            'headers' => [
                'Authorization' => $this->apiToken,
                'Content-Type' => 'application/json',
            ]
        ]);
    }

    /**
     * Voer een GraphQL query uit
     */
    private function executeQuery(string $query, array $variables = []): array
    {
        try {
            $response = $this->client->post($this->apiUrl, [
                'json' => [
                    'query' => $query,
                    'variables' => $variables,
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            
            if (isset($data['errors'])) {
                Log::error('Monday.com API Error', ['errors' => $data['errors']]);
                throw new \Exception('Monday.com API error: ' . json_encode($data['errors']));
            }

            return $data['data'] ?? [];
            
        } catch (GuzzleException $e) {
            Log::error('Monday.com Request Failed', [
                'message' => $e->getMessage(),
                'query' => $query
            ]);
            throw $e;
        }
    }

    /**
     * Haal alle tickets/items op van een board
     */
    public function getTickets(int $boardId, int $limit = 100): array
    {
        $query = '
            query ($boardId: [ID!], $limit: Int!) {
                boards(ids: $boardId) {
                    items_page(limit: $limit) {
                        items {
                            id
                            name
                            state
                            created_at
                            updated_at
                            column_values {
                                id
                                title
                                text
                                value
                            }
                            creator {
                                id
                                name
                                email
                            }
                        }
                    }
                }
            }
        ';

        $variables = [
            'boardId' => [$boardId],
            'limit' => $limit
        ];

        $result = $this->executeQuery($query, $variables);
        
        return $result['boards'][0]['items_page']['items'] ?? [];
    }

    /**
     * Haal een specifiek ticket op
     */
    public function getTicketById(string $itemId): ?array
    {
        $query = '
            query ($itemId: [ID!]) {
                items(ids: $itemId) {
                    id
                    name
                    state
                    created_at
                    updated_at
                    column_values {
                        id
                        title
                        text
                        value
                    }
                    board {
                        id
                        name
                    }
                }
            }
        ';

        $variables = ['itemId' => [$itemId]];
        $result = $this->executeQuery($query, $variables);
        
        return $result['items'][0] ?? null;
    }

    /**
     * Zoek tickets op basis van column values
     */
    public function searchTickets(int $boardId, string $columnId, string $searchValue): array
    {
        $query = '
            query ($boardId: [ID!]) {
                boards(ids: $boardId) {
                    items_page {
                        items {
                            id
                            name
                            column_values {
                                id
                                title
                                text
                                value
                            }
                        }
                    }
                }
            }
        ';

        $variables = ['boardId' => [$boardId]];
        $result = $this->executeQuery($query, $variables);
        
        $items = $result['boards'][0]['items_page']['items'] ?? [];
        
        // Filter items lokaal
        return array_filter($items, function($item) use ($columnId, $searchValue) {
            foreach ($item['column_values'] as $column) {
                if ($column['id'] === $columnId && 
                    stripos($column['text'], $searchValue) !== false) {
                    return true;
                }
            }
            return false;
        });
    }
}