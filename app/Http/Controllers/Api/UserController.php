<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Complaint;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        return response()->json([
            'data' => $request->user()->load('role')
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $user = $request->user();
        $user->update($request->only(['name', 'phone', 'address']));

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => $user->load('role')
        ]);
    }

    // Simple admin methods
    public function dashboard(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $totalComplaints = Complaint::count();
        $pendingComplaints = Complaint::whereHas('status', function($query) {
            $query->where('name', 'pending');
        })->count();
        $resolvedComplaints = Complaint::whereHas('status', function($query) {
            $query->where('name', 'resolved');
        })->count();

        return response()->json([
            'stats' => [
                'total_complaints' => $totalComplaints,
                'pending_complaints' => $pendingComplaints,
                'resolved_complaints' => $resolvedComplaints,
            ]
        ]);
    }

    public function index(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $users = User::with('role')->get();

        return response()->json([
            'users' => $users
        ]);
    }

    public function analytics(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $complaintsByCategory = Complaint::with('category')
            ->selectRaw('category_id, count(*) as count')
            ->groupBy('category_id')
            ->get();

        $complaintsByStatus = Complaint::with('status')
            ->selectRaw('status_id, count(*) as count')
            ->groupBy('status_id')
            ->get();

        return response()->json([
            'complaints_by_category' => $complaintsByCategory,
            'complaints_by_status' => $complaintsByStatus,
        ]);
    }
}
