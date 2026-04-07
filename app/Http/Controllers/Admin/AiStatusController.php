<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('gen-auth');
        $this->middleware('admin-auth');
    }

    /**
     * Test connectivity and validity of the Anthropic API key.
     * Sends a tiny request and returns status, model, and any error details.
     */
    public function test()
    {
        $apiKey = config('anthropic.api_key') ?: env('ANTHROPIC_API_KEY');
        $model = env('ANTHROPIC_MODEL', 'claude-sonnet-4-20250514');

        if (empty($apiKey)) {
            return response()->json([
                'status' => 'error',
                'configured' => false,
                'message' => 'ANTHROPIC_API_KEY is not set in your .env file.',
                'fix' => 'Add ANTHROPIC_API_KEY=sk-ant-... to your .env and run "php artisan config:clear".',
            ]);
        }

        try {
            $start = microtime(true);
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])->timeout(15)->post('https://api.anthropic.com/v1/messages', [
                'model' => $model,
                'max_tokens' => 50,
                'messages' => [
                    ['role' => 'user', 'content' => 'Reply with only the word: OK'],
                ],
            ]);
            $latency = round((microtime(true) - $start) * 1000);

            if (!$response->successful()) {
                $errorBody = $response->json();
                $errorMessage = $errorBody['error']['message'] ?? 'Unknown error';
                $errorType = $errorBody['error']['type'] ?? 'unknown_error';

                $fix = match ($errorType) {
                    'authentication_error' => 'Your API key is invalid or revoked. Get a new one from console.anthropic.com.',
                    'permission_error' => 'API key lacks permissions for this model. Check your Anthropic console.',
                    'rate_limit_error' => 'Rate limit hit. Wait a moment and try again, or upgrade your Anthropic plan.',
                    'overloaded_error' => 'Anthropic API is overloaded. Try again in a few seconds.',
                    'not_found_error' => "Model '{$model}' not found. Check ANTHROPIC_MODEL in .env.",
                    default => 'Check Anthropic status page or your account credits.',
                };

                return response()->json([
                    'status' => 'error',
                    'configured' => true,
                    'http_status' => $response->status(),
                    'error_type' => $errorType,
                    'message' => $errorMessage,
                    'fix' => $fix,
                    'model' => $model,
                    'latency_ms' => $latency,
                ]);
            }

            $reply = $response->json('content.0.text', '');
            $usage = $response->json('usage', []);

            return response()->json([
                'status' => 'ok',
                'configured' => true,
                'message' => 'AI is connected and responding.',
                'model' => $model,
                'reply' => trim($reply),
                'latency_ms' => $latency,
                'input_tokens' => $usage['input_tokens'] ?? null,
                'output_tokens' => $usage['output_tokens'] ?? null,
            ]);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return response()->json([
                'status' => 'error',
                'configured' => true,
                'message' => 'Could not connect to Anthropic API: ' . $e->getMessage(),
                'fix' => 'Check your server has internet access and DNS can resolve api.anthropic.com.',
                'model' => $model,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'configured' => true,
                'message' => 'Unexpected error: ' . $e->getMessage(),
                'model' => $model,
            ]);
        }
    }
}
