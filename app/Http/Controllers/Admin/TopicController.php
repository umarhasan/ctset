<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Subject;

class TopicController extends Controller
{

    public function index()
    {
        $topics = Topic::with('subject')->get();
        return view('admin.topics.index', compact('topics'));
    }

    // Return topic data for edit (AJAX)
    public function edit(Topic $topic)
    {
        return response()->json($topic);
    }

    // Store new topic
    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'topic_name' => 'required|string|max:255',
        ]);

        $topic = Topic::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Topic created successfully.',
            'topic' => $topic
        ]);
    }

    // Update topic
    public function update(Request $request, Topic $topic)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'topic_name' => 'required|string|max:255',
        ]);

        $topic->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Topic updated successfully.',
            'topic' => $topic
        ]);
    }

    // Delete topic
    public function destroy(Topic $topic)
    {
        $topic->delete();

        return response()->json([
            'success' => true,
            'message' => 'Topic deleted successfully.',
            'topic_id' => $topic->id
        ]);
    }
}
