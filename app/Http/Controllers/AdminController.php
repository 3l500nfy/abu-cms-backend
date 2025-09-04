<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\ComplaintStatus;
use App\Models\Role;
use App\Models\Department;
use App\Models\Staff;

class AdminController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
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
            
            // Check if user is admin
            if ($user->isAdmin()) {
                $request->session()->regenerate();
                return redirect()->intended('/admin/dashboard');
            } else {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Access denied. Admin privileges required.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function dashboard()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect('/admin/login');
        }

        $stats = [
            'total_complaints' => Complaint::count(),
            'pending_complaints' => Complaint::whereHas('status', function($q) {
                $q->where('name', 'pending');
            })->count(),
            'resolved_complaints' => Complaint::whereHas('status', function($q) {
                $q->where('name', 'resolved');
            })->count(),
            'total_users' => User::count(),
        ];

        $recent_complaints = Complaint::with(['user', 'category', 'status'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $complaints_by_category = Complaint::with('category')
            ->get()
            ->groupBy('category.display_name');

        return view('admin.dashboard', compact('stats', 'recent_complaints', 'complaints_by_category'));
    }

    public function complaints()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect('/admin/login');
        }

        $complaints = Complaint::with(['user', 'category', 'status', 'department'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $categories = ComplaintCategory::all();
        $statuses = ComplaintStatus::all();
        $departments = Department::where('is_active', true)->get();

        return view('admin.complaints', compact('complaints', 'categories', 'statuses', 'departments'));
    }

    public function updateComplaintStatus(Request $request, $id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect('/admin/login');
        }

        $request->validate([
            'status_id' => 'required|exists:complaint_statuses,id',
            'department_id' => 'nullable|exists:departments,id',
            'resolution_notes' => 'nullable|string'
        ]);

        $complaint = Complaint::findOrFail($id);
        $complaint->update([
            'status_id' => $request->status_id,
            'department_id' => $request->department_id,
            'resolution_notes' => $request->resolution_notes,
            'resolved_at' => $request->status_id == ComplaintStatus::where('name', 'resolved')->first()->id ? now() : null
        ]);

        return back()->with('success', 'Complaint status updated successfully');
    }

    public function deleteComplaint($id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect('/admin/login');
        }

        $complaint = Complaint::findOrFail($id);
        $complaint->delete();

        return back()->with('success', 'Complaint deleted successfully');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }

    public function users(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect('/admin/login');
        }

        $query = User::with('role');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->whereHas('role', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(12);
        $roles = Role::all();

        return view('admin.users', compact('users', 'roles'));
    }

    public function editUser($id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = User::with('role')->findOrFail($id);
        return response()->json($user);
    }

    public function updateUser(Request $request, $id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect('/admin/login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'student_id' => 'required|string|unique:users,student_id,' . $id,
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'student_id' => $request->student_id,
            'role_id' => $request->role_id
        ]);

        return back()->with('success', 'User updated successfully');
    }

    public function changePassword(Request $request, $id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect('/admin/login');
        }

        $request->validate([
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password changed successfully');
    }

    public function deleteUser($id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Prevent admin from deleting themselves
        if ($id == Auth::id()) {
            return response()->json(['error' => 'Cannot delete your own account'], 400);
        }

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => true]);
    }

    public function showAddStaff()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect('/admin/login');
        }

        $departments = Department::where('is_active', true)->get();
        $roles = Role::all();

        return view('admin.add-staff', compact('departments', 'roles'));
    }

    public function addStaff(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect('/admin/login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'employee_id' => 'required|string|unique:staff,employee_id',
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20'
        ]);

        // Create user with staff role
        $staffRole = Role::where('name', 'staff')->first();
        if (!$staffRole) {
            return back()->withErrors(['error' => 'Staff role not found. Please contact administrator.']);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $staffRole->id,
            'phone' => $request->phone
        ]);

        // Create staff record
        Staff::create([
            'user_id' => $user->id,
            'department_id' => $request->department_id,
            'position' => $request->position,
            'employee_id' => $request->employee_id,
            'is_active' => true
        ]);

        return redirect()->route('admin.users')->with('success', 'Staff member added successfully');
    }
}
