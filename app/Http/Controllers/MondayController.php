<?php

namespace App\Http\Controllers;

use App\Services\MondayService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class MondayController extends Controller
{
    private MondayService $mondayService;

    public function __construct(MondayService $mondayService)
    {
        $this->mondayService = $mondayService;
    }

    /**
     * GET /api/monday/tickets
     * Haal alle tickets op van een board
     */
    public function getTickets(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'board_id' => 'required|integer',
                'limit' => 'sometimes|integer|min:1|max:500',
                'paginated' => 'sometimes|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $boardId = $request->input('board_id');
            $limit = $request->input('limit', 100);
            $paginated = $request->input('paginated', false);

            $tickets = $paginated 
                ? $this->mondayService->getAllTicketsPaginated($boardId, $limit)
                : $this->mondayService->getTickets($boardId, $limit);

            return response()->json([
                'success' => true,
                'data' => $tickets,
                'count' => count($tickets),
                'board_id' => $boardId,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch tickets',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/monday/tickets/{id}
     * Haal een specifiek ticket op
     */
    public function getTicket(string $id): JsonResponse
    {
        try {
            $ticket = $this->mondayService->getTicketById($id);

            if (!$ticket) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ticket not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $ticket,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch ticket',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/monday/tickets
     * Maak een nieuw ticket aan
     */
    public function createTicket(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'board_id' => 'required|integer',
                'name' => 'required|string|max:255',
                'column_values' => 'sometimes|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $ticket = $this->mondayService->createTicket(
                $request->input('board_id'),
                $request->input('name'),
                $request->input('column_values', [])
            );

            if (!$ticket) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create ticket',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Ticket created successfully',
                'data' => $ticket,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create ticket',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * PUT /api/monday/tickets/{id}
     * Update een bestaand ticket
     */
    public function updateTicket(Request $request, string $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'board_id' => 'required|integer',
                'column_values' => 'required|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $ticket = $this->mondayService->updateTicket(
                $id,
                $request->input('board_id'),
                $request->input('column_values')
            );

            if (!$ticket) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update ticket',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Ticket updated successfully',
                'data' => $ticket,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update ticket',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * DELETE /api/monday/tickets/{id}
     * Verwijder een ticket
     */
    public function deleteTicket(string $id): JsonResponse
    {
        try {
            $deleted = $this->mondayService->deleteTicket($id);

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete ticket',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Ticket deleted successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete ticket',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/monday/tickets/{id}/archive
     * Archiveer een ticket
     */
    public function archiveTicket(string $id): JsonResponse
    {
        try {
            $archived = $this->mondayService->archiveTicket($id);

            if (!$archived) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to archive ticket',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Ticket archived successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to archive ticket',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/monday/tickets/{id}/duplicate
     * Dupliceer een ticket
     */
    public function duplicateTicket(Request $request, string $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'board_id' => 'required|integer',
                'with_updates' => 'sometimes|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $ticket = $this->mondayService->duplicateTicket(
                $request->input('board_id'),
                $id,
                $request->input('with_updates', false)
            );

            if (!$ticket) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to duplicate ticket',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Ticket duplicated successfully',
                'data' => $ticket,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to duplicate ticket',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/monday/tickets/{id}/move
     * Verplaats ticket naar ander board
     */
    public function moveTicket(Request $request, string $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'target_board_id' => 'required|integer',
                'target_group_id' => 'sometimes|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $ticket = $this->mondayService->moveTicket(
                $id,
                $request->input('target_board_id'),
                $request->input('target_group_id')
            );

            if (!$ticket) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to move ticket',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Ticket moved successfully',
                'data' => $ticket,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to move ticket',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/monday/tickets/{id}/comments
     * Voeg een comment toe aan een ticket
     */
    public function addComment(Request $request, string $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'body' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $update = $this->mondayService->addUpdate(
                $id,
                $request->input('body')
            );

            if (!$update) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to add comment',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully',
                'data' => $update,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add comment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/monday/boards
     * Haal alle boards op
     */
    public function getBoards(Request $request): JsonResponse
    {
        try {
            $useCache = $request->input('cache', true);
            $boards = $this->mondayService->getBoards($useCache);

            return response()->json([
                'success' => true,
                'data' => $boards,
                'count' => count($boards),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch boards',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/monday/boards/{id}/columns
     * Haal columns van een board op
     */
    public function getBoardColumns(int $id): JsonResponse
    {
        try {
            $columns = $this->mondayService->getBoardColumns($id);

            return response()->json([
                'success' => true,
                'data' => $columns,
                'count' => count($columns),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch board columns',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/monday/tickets/search
     * Zoek tickets op basis van column values
     */
    public function searchTickets(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'board_id' => 'required|integer',
                'column_id' => 'required|string',
                'search_value' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $tickets = $this->mondayService->searchTickets(
                $request->input('board_id'),
                $request->input('column_id'),
                $request->input('search_value')
            );

            return response()->json([
                'success' => true,
                'data' => $tickets,
                'count' => count($tickets),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to search tickets',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/monday/tickets/bulk-update
     * Update meerdere tickets tegelijk
     */
    public function bulkUpdateTickets(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'board_id' => 'required|integer',
                'item_ids' => 'required|array',
                'item_ids.*' => 'required|string',
                'column_values' => 'required|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $results = $this->mondayService->bulkUpdateTickets(
                $request->input('board_id'),
                $request->input('item_ids'),
                $request->input('column_values')
            );

            $successCount = count(array_filter($results, fn($r) => $r['success']));
            $failCount = count($results) - $successCount;

            return response()->json([
                'success' => true,
                'message' => "Updated {$successCount} tickets, {$failCount} failed",
                'data' => $results,
                'summary' => [
                    'total' => count($results),
                    'successful' => $successCount,
                    'failed' => $failCount,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to bulk update tickets',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/monday/rate-limits
     * Check rate limits
     */
    public function getRateLimits(): JsonResponse
    {
        try {
            $limits = $this->mondayService->checkRateLimits();

            return response()->json([
                'success' => true,
                'data' => $limits,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch rate limits',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}