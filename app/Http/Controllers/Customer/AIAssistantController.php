<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\AIKnowledgeBase;
use App\Models\Product;
use Illuminate\Http\Request;

class AIAssistantController extends Controller
{
    public function chat(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = $validated['message'];

        $knowledgeEntries = AIKnowledgeBase::active()
            ->where(function ($query) use ($message) {
                $query->where('topic', 'LIKE', "%{$message}%")
                      ->orWhere('content', 'LIKE', "%{$message}%")
                      ->orWhere('keywords', 'LIKE', "%{$message}%");
            })
            ->limit(5)
            ->get();

        $reply = '';
        if ($knowledgeEntries->isNotEmpty()) {
            $reply = $knowledgeEntries->first()->content;

            $knowledgeEntries->each(function ($entry) {
                $entry->increment('usage_count');
            });
        } else {
            $reply = "I'm sorry, I couldn't find specific information about that. Please try rephrasing your question or contact our support team for assistance.";
        }

        $suggestedProducts = Product::with(['images', 'brand'])
            ->where('is_active', true)
            ->where(function ($query) use ($message) {
                $query->where('name', 'LIKE', "%{$message}%")
                      ->orWhere('description', 'LIKE', "%{$message}%");
            })
            ->limit(3)
            ->get();

        return response()->json([
            'reply' => $reply,
            'suggestedProducts' => $suggestedProducts,
        ]);
    }
}
