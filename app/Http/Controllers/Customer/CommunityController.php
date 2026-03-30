<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CommunityAnswer;
use App\Models\CommunityPhoto;
use App\Models\CommunityQuestion;
use App\Models\SuccessStory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CommunityController extends Controller
{
    public function index()
    {
        $featuredPhotos = CommunityPhoto::approved()
            ->featured()
            ->with('product')
            ->latest()
            ->limit(8)
            ->get();

        $recentPhotos = CommunityPhoto::approved()
            ->with('product')
            ->latest()
            ->limit(12)
            ->get();

        $featuredStories = SuccessStory::approved()
            ->featured()
            ->with('product')
            ->latest()
            ->limit(3)
            ->get();

        $recentQuestions = CommunityQuestion::with('product')
            ->withCount('answers')
            ->latest()
            ->limit(10)
            ->get();

        $stats = [
            'total_photos' => CommunityPhoto::approved()->count(),
            'total_stories' => SuccessStory::approved()->count(),
            'total_questions' => CommunityQuestion::count(),
            'total_answers' => CommunityAnswer::count(),
        ];

        return Inertia::render('Customer/Community/Index', [
            'featuredPhotos' => $featuredPhotos,
            'recentPhotos' => $recentPhotos,
            'featuredStories' => $featuredStories,
            'recentQuestions' => $recentQuestions,
            'stats' => $stats,
        ]);
    }

    public function photos()
    {
        $photos = CommunityPhoto::approved()
            ->with('product')
            ->latest()
            ->paginate(20);

        return Inertia::render('Customer/Community/Photos', [
            'photos' => $photos,
        ]);
    }

    public function stories()
    {
        $stories = SuccessStory::approved()
            ->with('product')
            ->latest()
            ->paginate(9);

        return Inertia::render('Customer/Community/Stories', [
            'stories' => $stories,
        ]);
    }

    public function showStory($id)
    {
        $story = SuccessStory::approved()
            ->with('product')
            ->findOrFail($id);

        return Inertia::render('Customer/Community/StoryShow', [
            'story' => $story,
        ]);
    }

    public function questions(Request $request)
    {
        $query = CommunityQuestion::with(['answers', 'product']);

        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->has('search')) {
            $query->where('question', 'like', '%' . $request->search . '%');
        }

        $questions = $query->latest()->paginate(15);

        return Inertia::render('Customer/Community/Questions', [
            'questions' => $questions,
        ]);
    }

    public function storePhoto(Request $request)
    {
        $request->validate([
            'image_url' => 'required|url',
            'caption' => 'nullable|string',
            'customer_name' => 'required|string',
        ]);

        CommunityPhoto::create([
            'image_url' => $request->image_url,
            'caption' => $request->caption,
            'customer_name' => $request->customer_name,
            'is_approved' => false,
        ]);

        return redirect()->back()->with('success', 'Photo submitted successfully! It will appear after approval.');
    }

    public function storeStory(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'customer_name' => 'required|string',
            'rating' => 'required|integer|between:1,5',
            'product_id' => 'nullable|exists:products,id',
        ]);

        SuccessStory::create([
            'title' => $request->title,
            'content' => $request->content,
            'customer_name' => $request->customer_name,
            'rating' => $request->rating,
            'product_id' => $request->product_id,
            'is_approved' => false,
        ]);

        return redirect()->back()->with('success', 'Story submitted successfully! It will appear after approval.');
    }

    public function storeQuestion(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'asked_by' => 'required|string',
            'product_id' => 'nullable|exists:products,id',
        ]);

        CommunityQuestion::create([
            'question' => $request->question,
            'asked_by' => $request->asked_by,
            'product_id' => $request->product_id,
        ]);

        return redirect()->back()->with('success', 'Question submitted successfully!');
    }

    public function storeAnswer(Request $request, $questionId)
    {
        $request->validate([
            'answer' => 'required|string',
            'answered_by' => 'required|string',
        ]);

        $question = CommunityQuestion::findOrFail($questionId);

        CommunityAnswer::create([
            'question_id' => $question->id,
            'answer' => $request->answer,
            'answered_by' => $request->answered_by,
        ]);

        $question->update(['is_answered' => true]);

        return redirect()->back()->with('success', 'Answer submitted successfully!');
    }
}
