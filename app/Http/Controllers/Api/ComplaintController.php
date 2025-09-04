<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\ComplaintStatus;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $complaints = $request->user()->complaints()
            ->with(['category', 'status'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($complaints);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:complaint_categories,id',
            'location' => 'nullable|string|max:255',
        ]);

        $complaint = Complaint::create([
            'user_id' => $request->user()->id,
            'category_id' => $request->category_id,
            'status_id' => ComplaintStatus::where('name', 'pending')->first()->id,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'priority' => 'medium',
        ]);

        return response()->json($complaint->load(['category', 'status']), 201);
    }

    public function show(string $id)
    {
        $complaint = Complaint::with(['category', 'status', 'attachments'])
            ->where('id', $id)
            ->first();

        if (!$complaint) {
            return response()->json(['message' => 'Complaint not found'], 404);
        }

        return response()->json($complaint);
    }

    public function update(Request $request, string $id)
    {
        $complaint = Complaint::find($id);

        if (!$complaint) {
            return response()->json(['message' => 'Complaint not found'], 404);
        }

        // Only allow users to update their own complaints
        if ($complaint->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'location' => 'nullable|string|max:255',
        ]);

        $complaint->update($request->only(['title', 'description', 'location']));

        return response()->json($complaint->load(['category', 'status']));
    }

    public function destroy(string $id)
    {
        $complaint = Complaint::find($id);

        if (!$complaint) {
            return response()->json(['message' => 'Complaint not found'], 404);
        }

        // Only allow users to delete their own complaints
        if ($complaint->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $complaint->delete();

        return response()->json([
            'message' => 'Complaint deleted successfully'
        ]);
    }

    public function categories()
    {
        $categories = ComplaintCategory::where('is_active', true)->get();

        return response()->json($categories);
    }

    public function statuses()
    {
        $statuses = ComplaintStatus::all();

        return response()->json($statuses);
    }
}
