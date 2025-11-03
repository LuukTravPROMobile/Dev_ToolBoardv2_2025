<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

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
                'API-Version' => '2024-10', // Voeg API versie toe
            ],
            'timeout' => 30, // Timeout toevoegen
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
                    updates {
                        id
                        body
                        created_at
                        creator {
                            name
                            email
                        }
                    }
                }
            }
        ';

        $variables = ['itemId' => [$itemId]];
        $result = $this->executeQuery($query, $variables);
        
        return $result['items'][0] ?? null;
    }

    /**
     * NIEUW: Maak een nieuw ticket aan
     */
    public function createTicket(int $boardId, string $itemName, array $columnValues = []): ?array
    {
        $query = '
            mutation ($boardId: ID!, $itemName: String!, $columnValues: JSON) {
                create_item(
                    board_id: $boardId,
                    item_name: $itemName,
                    column_values: $columnValues
                ) {
                    id
                    name
                    created_at
                    column_values {
                        id
                        text
                        value
                    }
                }
            }
        ';

        $variables = [
            'boardId' => (string)$boardId,
            'itemName' => $itemName,
            'columnValues' => json_encode($columnValues),
        ];

        $result = $this->executeQuery($query, $variables);
        
        return $result['create_item'] ?? null;
    }

    /**
     * NIEUW: Update een bestaand ticket
     */
    public function updateTicket(string $itemId, int $boardId, array $columnValues): ?array
    {
        $query = '
            mutation ($boardId: ID!, $itemId: ID!, $columnValues: JSON!) {
                change_multiple_column_values(
                    board_id: $boardId,
                    item_id: $itemId,
                    column_values: $columnValues
                ) {
                    id
                    name
                    column_values {
                        id
                        text
                        value
                    }
                }
            }
        ';

        $variables = [
            'boardId' => (string)$boardId,
            'itemId' => $itemId,
            'columnValues' => json_encode($columnValues),
        ];

        $result = $this->executeQuery($query, $variables);
        
        return $result['change_multiple_column_values'] ?? null;
    }

    /**
     * NIEUW: Verwijder een ticket
     */
    public function deleteTicket(string $itemId): bool
    {
        $query = '
            mutation ($itemId: ID!) {
                delete_item(item_id: $itemId) {
                    id
                }
            }
        ';

        $variables = ['itemId' => $itemId];
        
        try {
            $result = $this->executeQuery($query, $variables);
            return isset($result['delete_item']['id']);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * NIEUW: Voeg een update/comment toe aan een ticket
     */
    public function addUpdate(string $itemId, string $body): ?array
    {
        $query = '
            mutation ($itemId: ID!, $body: String!) {
                create_update(item_id: $itemId, body: $body) {
                    id
                    body
                    created_at
                    creator {
                        name
                        email
                    }
                }
            }
        ';

        $variables = [
            'itemId' => $itemId,
            'body' => $body,
        ];

        $result = $this->executeQuery($query, $variables);
        
        return $result['create_update'] ?? null;
    }

    /**
     * NIEUW: Haal alle boards op (met caching)
     */
    public function getBoards(bool $useCache = true): array
    {
        $cacheKey = 'monday_boards';
        
        if ($useCache && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $query = '
            query {
                boards {
                    id
                    name
                    description
                    state
                    board_kind
                    columns {
                        id
                        title
                        type
                        settings_str
                    }
                }
            }
        ';

        $result = $this->executeQuery($query);
        $boards = $result['boards'] ?? [];

        if ($useCache) {
            Cache::put($cacheKey, $boards, now()->addHours(1));
        }

        return $boards;
    }

    /**
     * NIEUW: Haal board columns op
     */
    public function getBoardColumns(int $boardId): array
    {
        $query = '
            query ($boardId: [ID!]) {
                boards(ids: $boardId) {
                    columns {
                        id
                        title
                        type
                        settings_str
                    }
                }
            }
        ';

        $variables = ['boardId' => [(string)$boardId]];
        $result = $this->executeQuery($query, $variables);
        
        return $result['boards'][0]['columns'] ?? [];
    }

    /**
     * NIEUW: Paginatie ondersteuning voor grote datasets
     */
    public function getAllTicketsPaginated(int $boardId, int $perPage = 100): array
    {
        $allItems = [];
        $cursor = null;
        
        do {
            $query = '
                query ($boardId: [ID!], $limit: Int!, $cursor: String) {
                    boards(ids: $boardId) {
                        items_page(limit: $limit, cursor: $cursor) {
                            cursor
                            items {
                                id
                                name
                                state
                                created_at
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

            $variables = [
                'boardId' => [(string)$boardId],
                'limit' => $perPage,
                'cursor' => $cursor,
            ];

            $result = $this->executeQuery($query, $variables);
            $itemsPage = $result['boards'][0]['items_page'] ?? null;
            
            if (!$itemsPage) {
                break;
            }

            $allItems = array_merge($allItems, $itemsPage['items'] ?? []);
            $cursor = $itemsPage['cursor'] ?? null;
            
        } while ($cursor);

        return $allItems;
    }

    /**
     * NIEUW: Archiveer een ticket
     */
    public function archiveTicket(string $itemId): bool
    {
        $query = '
            mutation ($itemId: ID!) {
                archive_item(item_id: $itemId) {
                    id
                }
            }
        ';

        $variables = ['itemId' => $itemId];
        
        try {
            $result = $this->executeQuery($query, $variables);
            return isset($result['archive_item']['id']);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * NIEUW: Dupliceer een ticket
     */
    public function duplicateTicket(int $boardId, string $itemId, bool $withUpdates = false): ?array
    {
        $query = '
            mutation ($boardId: ID!, $itemId: ID!, $withUpdates: Boolean) {
                duplicate_item(
                    board_id: $boardId,
                    item_id: $itemId,
                    with_updates: $withUpdates
                ) {
                    id
                    name
                }
            }
        ';

        $variables = [
            'boardId' => (string)$boardId,
            'itemId' => $itemId,
            'withUpdates' => $withUpdates,
        ];

        $result = $this->executeQuery($query, $variables);
        
        return $result['duplicate_item'] ?? null;
    }

    /**
     * NIEUW: Verplaats ticket naar ander board
     */
    public function moveTicket(string $itemId, int $targetBoardId, ?int $targetGroupId = null): ?array
    {
        $query = '
            mutation ($itemId: ID!, $boardId: ID!, $groupId: String) {
                move_item_to_board(
                    item_id: $itemId,
                    board_id: $boardId,
                    group_id: $groupId
                ) {
                    id
                    name
                    board {
                        id
                        name
                    }
                }
            }
        ';

        $variables = [
            'itemId' => $itemId,
            'boardId' => (string)$targetBoardId,
            'groupId' => $targetGroupId ? (string)$targetGroupId : null,
        ];

        $result = $this->executeQuery($query, $variables);
        
        return $result['move_item_to_board'] ?? null;
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

        $variables = ['boardId' => [(string)$boardId]];
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

    /**
     * NIEUW: Rate limiting check
     */
    public function checkRateLimits(): array
    {
        $query = '
            query {
                complexity {
                    query
                    reset_in_x_seconds
                }
            }
        ';

        $result = $this->executeQuery($query);
        
        return $result['complexity'] ?? [];
    }

    /**
     * NIEUW: Bulk update meerdere tickets tegelijk
     */
    public function bulkUpdateTickets(int $boardId, array $itemIds, array $columnValues): array
    {
        $results = [];
        
        foreach ($itemIds as $itemId) {
            try {
                $result = $this->updateTicket($itemId, $boardId, $columnValues);
                $results[$itemId] = [
                    'success' => true,
                    'data' => $result,
                ];
            } catch (\Exception $e) {
                $results[$itemId] = [
                    'success' => false,
                    'error' => $e->getMessage(),
                ];
            }
        }
        
        return $results;
    }
}