<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommunityPhoto;
use App\Models\CommunityQuestion;
use App\Models\SuccessStory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CommunityController extends Controller
{
    public function __construct()
    {
        $this->middleware('gen-auth');
        $this->middleware('admin-auth');
    }

    public function index(Request $request)
    {
        $tab = $request->get('tab', 'photos');

        $pendingPhotos = CommunityPhoto::where('is_approved', false)->orderByDesc('created_at')->get();
        $approvedPhotos = CommunityPhoto::where('is_approved', true)->orderByDesc('created_at')->get();

        $pendingStories = SuccessStory::where('is_approved', false)->orderByDesc('created_at')->get();
        $approvedStories = SuccessStory::where('is_approved', true)->orderByDesc('created_at')->get();

        $pendingQuestions = CommunityQuestion::with(['answers', 'product'])
            ->where('is_published', false)->orderByDesc('created_at')->get();
        $answeredQuestions = CommunityQuestion::with(['answers', 'product'])
            ->where('is_published', true)->orderByDesc('created_at')->limit(20)->get();

        return Inertia::render('Admin/Community/Index', [
            'tab' => $tab,
            'pendingPhotos' => $pendingPhotos,
            'approvedPhotos' => $approvedPhotos,
            'pendingStories' => $pendingStories,
            'approvedStories' => $approvedStories,
            'pendingQuestions' => $pendingQuestions,
            'answeredQuestions' => $answeredQuestions,
            'stats' => [
                'pendingPhotos' => $pendingPhotos->count(),
                'pendingStories' => $pendingStories->count(),
                'pendingQuestions' => $pendingQuestions->count(),
                'totalPhotos' => CommunityPhoto::count(),
                'totalStories' => SuccessStory::count(),
            ],
        ]);
    }

    public function approvePhoto($id)
    {
        CommunityPhoto::findOrFail($id)->update(['is_approved' => true]);
        return redirect()->back()->with('success', 'Photo approved.');
    }

    public function rejectPhoto($id)
    {
        CommunityPhoto::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Photo rejected and deleted.');
    }

    public function toggleFeaturePhoto($id)
    {
        $photo = CommunityPhoto::findOrFail($id);
        $photo->update(['is_featured' => !$photo->is_featured]);
        return redirect()->back()->with('success', $photo->is_featured ? 'Photo featured.' : 'Photo unfeatured.');
    }

    public function approveStory($id)
    {
        SuccessStory::findOrFail($id)->update(['is_approved' => true]);
        return redirect()->back()->with('success', 'Story approved.');
    }

    public function rejectStory($id)
    {
        SuccessStory::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Story rejected and deleted.');
    }

    public function toggleFeatureStory($id)
    {
        $story = SuccessStory::findOrFail($id);
        $story->update(['is_featured' => !$story->is_featured]);
        return redirect()->back()->with('success', $story->is_featured ? 'Story featured.' : 'Story unfeatured.');
    }

    public function updateQuestion(Request $request, $id)
    {
        $question = CommunityQuestion::findOrFail($id);

        $validated = $request->validate([
            'question' => 'required|string',
            'admin_answer' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $question->update($validated);

        if (!empty($validated['admin_answer'])) {
            $question->update(['is_answered' => true]);
        }

        return redirect()->back()->with('success', 'Question updated.');
    }

    public function publishQuestion($id)
    {
        $question = CommunityQuestion::findOrFail($id);
        $question->update(['is_published' => !$question->is_published]);
        return redirect()->back()->with('success', $question->is_published ? 'Question published.' : 'Question unpublished.');
    }

    public function deleteQuestion($id)
    {
        $question = CommunityQuestion::findOrFail($id);
        $question->answers()->delete();
        $question->delete();
        return redirect()->back()->with('success', 'Question deleted.');
    }
}
