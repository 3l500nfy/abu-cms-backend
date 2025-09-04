<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Department;
use App\Models\Complaint;
use App\Models\Staff;
use App\Models\User;

class DepartmentController extends Controller
{
    public function index()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect('/admin/login');
        }

        $departments = Department::withCount(['complaints', 'staff'])->get();
        
        return view('admin.departments.index', compact('departments'));
    }

    public function show($id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect('/admin/login');
        }

        $department = Department::findOrFail($id);
        
        $staff = Staff::with('user')
            ->where('department_id', $id)
            ->get();

        $complaints = Complaint::with(['user', 'category', 'status'])
            ->where('department_id', $id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $stats = [
            'total_complaints' => Complaint::where('department_id', $id)->count(),
            'pending_complaints' => Complaint::where('department_id', $id)
                ->whereHas('status', function($q) {
                    $q->where('name', 'pending');
                })->count(),
            'in_progress_complaints' => Complaint::where('department_id', $id)
                ->whereHas('status', function($q) {
                    $q->where('name', 'in_progress');
                })->count(),
            'resolved_complaints' => Complaint::where('department_id', $id)
                ->whereHas('status', function($q) {
                    $q->where('name', 'resolved');
                })->count(),
        ];

        return view('admin.departments.show', compact('department', 'staff', 'complaints', 'stats'));
    }

    public function assignComplaint(Request $request, $complaintId)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'assigned_to' => 'nullable|exists:users,id'
        ]);

        $complaint = Complaint::findOrFail($complaintId);
        $complaint->update([
            'department_id' => $request->department_id,
            'assigned_to' => $request->assigned_to
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Complaint assigned successfully'
        ]);
    }

    public function getDepartmentStaff($departmentId)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $staff = Staff::with('user')
            ->where('department_id', $departmentId)
            ->where('is_active', true)
            ->get()
            ->map(function ($staff) {
                return [
                    'id' => $staff->user->id,
                    'name' => $staff->user->name,
                    'position' => $staff->position,
                    'employee_id' => $staff->employee_id
                ];
            });

        return response()->json($staff);
    }

    public function getUnassignedComplaints()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $complaints = Complaint::with(['user', 'category', 'status'])
            ->whereNull('department_id')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($complaints);
    }

    public function create()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect('/admin/login');
        }

        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect('/admin/login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:departments,code',
            'description' => 'nullable|string|max:500',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20'
        ]);

        Department::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_active' => true
        ]);

        return redirect()->route('admin.departments')->with('success', 'Department created successfully');
    }
}
