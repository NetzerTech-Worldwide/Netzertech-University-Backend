<?php

namespace App\Http\Controllers\Api\V1\Ai;

use App\Http\Controllers\Controller;
use App\Services\Ai\AIAdvisorService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AIAdvisorController extends Controller
{
    use ApiResponse;

    /**
     * Send prompt to the AI Academic Advisor.
     */
    public function chat(Request $request, AIAdvisorService $aiService): JsonResponse
    {
        $request->validate([
            'prompt' => 'required|string|max:1000',
            'session_id' => 'nullable|string|max:80',
        ]);

        $result = $aiService->respond(
            $request->user(),
            $request->input('prompt'),
            $request->input('session_id')
        );

        return $this->success($result, 'AI Advisor response generated.');
    }

    /**
     * View conversation transcript history.
     */
    public function history(Request $request, AIAdvisorService $aiService): JsonResponse
    {
        $history = $aiService->getHistory(
            $request->user(),
            $request->query('session_id')
        );

        return $this->success($history, 'Conversation history retrieved.');
    }

    /**
     * Reset/clear current conversation session.
     */
    public function clearHistory(Request $request, AIAdvisorService $aiService): JsonResponse
    {
        $aiService->clearHistory(
            $request->user(),
            $request->input('session_id')
        );

        return $this->success(null, 'Conversation session reset successfully.');
    }
}
