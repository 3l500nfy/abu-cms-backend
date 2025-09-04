<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Complaint;
use App\Models\ComplaintStatus;
use App\Models\ComplaintCategory;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check() || !Auth::user()->isStaff()) {
            return redirect('/department/login');
        }

        $user = Auth::user();
        $department = $user->staff->department ?? null;

        if (!$department) {
            return redirect('/department/login')->withErrors(['error' => 'You are not assigned to any department.']);
        }

        $stats = [
            'total_complaints' => Complaint::where('department_id', $department->id)->count(),
            'pending_complaints' => Complaint::where('department_id', $department->id)
                ->whereHas('status', function($q) {
                    $q->where('name', 'pending');
                })->count(),
            'in_progress_complaints' => Complaint::where('department_id', $department->id)
                ->whereHas('status', function($q) {
                    $q->where('name', 'in_progress');
                })->count(),
            'resolved_complaints' => Complaint::where('department_id', $department->id)
                ->whereHas('status', function($q) {
                    $q->where('name', 'resolved');
                })->count(),
        ];

        $recent_complaints = Complaint::with(['user', 'category', 'status'])
            ->where('department_id', $department->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $statuses = ComplaintStatus::all();

        return view('department.dashboard', compact('department', 'stats', 'recent_complaints', 'statuses'));
    }

    public function complaints(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isStaff()) {
            return redirect('/department/login');
        }

        $user = Auth::user();
        $department = $user->staff->department ?? null;

        if (!$department) {
            return redirect('/department/login')->withErrors(['error' => 'You are not assigned to any department.']);
        }

        $query = Complaint::with(['user', 'category', 'status'])
            ->where('department_id', $department->id);

        // Filter by status
        if ($request->filled('status')) {
            $query->whereHas('status', function($q) use ($request) {
                $q->where('name', $request->status);
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $complaints = $query->orderBy('created_at', 'desc')->paginate(20);
        $statuses = ComplaintStatus::all();
        $categories = ComplaintCategory::all();

        return view('department.complaints', compact('department', 'complaints', 'statuses', 'categories'));
    }

    public function updateStatus(Request $request, $id)
    {
        if (!Auth::check() || !Auth::user()->isStaff()) {
            return redirect('/department/login');
        }

        $user = Auth::user();
        $department = $user->staff->department ?? null;

        if (!$department) {
            return redirect('/department/login')->withErrors(['error' => 'You are not assigned to any department.']);
        }

        $request->validate([
            'status_id' => 'required|exists:complaint_statuses,id',
            'resolution_notes' => 'nullable|string'
        ]);

        $complaint = Complaint::where('id', $id)
            ->where('department_id', $department->id)
            ->firstOrFail();

        $complaint->update([
            'status_id' => $request->status_id,
            'resolution_notes' => $request->resolution_notes,
            'resolved_at' => $request->status_id == ComplaintStatus::where('name', 'resolved')->first()->id ? now() : null
        ]);

        return back()->with('success', 'Complaint status updated successfully');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Check if user is staff and has a department
            if ($user->isStaff() && $user->staff && $user->staff->department) {
                $request->session()->regenerate();
                return redirect()->intended('/department/dashboard');
            } else {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Access denied. Staff privileges and department assignment required.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/department/login');
    }
}  //elsoonfy
