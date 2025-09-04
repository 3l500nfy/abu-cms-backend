<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $department->name }} - Department Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar {
            background: #2c3e50;
            min-height: 100vh;
            color: white;
        }
        .sidebar .nav-link {
            color: #bdc3c7;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 0;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: #34495e;
            color: white;
        }
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid;
        }
        .stats-card.primary { border-left-color: #3498db; }
        .stats-card.success { border-left-color: #2ecc71; }
        .stats-card.warning { border-left-color: #f39c12; }
        .stats-card.info { border-left-color: #9b59b6; }
        .complaint-card {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .priority-badge {
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .priority-high { background: #ffebee; color: #c62828; }
        .priority-medium { background: #fff3e0; color: #ef6c00; }
        .priority-low { background: #e8f5e8; color: #2e7d32; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="p-3">
                    <h4 class="text-center mb-4">Admin Panel</h4>
                    <nav class="nav flex-column">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                        <a class="nav-link" href="{{ route('admin.complaints') }}">
                            <i class="bi bi-exclamation-triangle me-2"></i> Complaints
                        </a>
                        <a class="nav-link" href="{{ route('admin.users') }}">
                            <i class="bi bi-people me-2"></i> Users
                        </a>
                        <a class="nav-link active" href="{{ route('admin.departments') }}">
                            <i class="bi bi-building me-2"></i> Departments
                        </a>
                        <a class="nav-link" href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content p-0">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light">
                    <div class="container-fluid">
                        <h5 class="mb-0">{{ $department->name }} - Department Details</h5>
                        <div class="navbar-nav ms-auto">
                            <span class="navbar-text">
                                Welcome, {{ Auth::user()->name }}
                            </span>
                        </div>
                    </div>
                </nav>

                <!-- Content -->
                <div class="p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Department Info -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Department Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Department Code:</strong> {{ $department->code }}</p>
                                            <p><strong>Department Name:</strong> {{ $department->name }}</p>
                                            <p><strong>Email:</strong> {{ $department->email }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Phone:</strong> {{ $department->phone }}</p>
                                            <p><strong>Status:</strong> 
                                                <span class="badge {{ $department->is_active ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $department->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </p>
                                            <p><strong>Created:</strong> {{ $department->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                    @if($department->description)
                                        <div class="mt-3">
                                            <strong>Description:</strong>
                                            <p class="text-muted">{{ $department->description }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <a href="{{ route('admin.departments') }}" class="btn btn-outline-primary w-100 mb-2">
                                        <i class="bi bi-arrow-left me-2"></i>Back to Departments
                                    </a>
                                    <button class="btn btn-outline-warning w-100 mb-2" data-bs-toggle="modal" data-bs-target="#unassignedModal">
                                        <i class="bi bi-list-check me-2"></i>View Unassigned Complaints
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Department Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="stats-card primary">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3 class="mb-0">{{ $stats['total_complaints'] ?? 0 }}</h3>
                                        <p class="text-muted mb-0">Total Complaints</p>
                                    </div>
                                    <i class="bi bi-exclamation-triangle text-primary" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card warning">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3 class="mb-0">{{ $stats['pending_complaints'] ?? 0 }}</h3>
                                        <p class="text-muted mb-0">Pending</p>
                                    </div>
                                    <i class="bi bi-clock text-warning" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card info">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3 class="mb-0">{{ $stats['in_progress_complaints'] ?? 0 }}</h3>
                                        <p class="text-muted mb-0">In Progress</p>
                                    </div>
                                    <i class="bi bi-gear text-info" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card success">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3 class="mb-0">{{ $stats['resolved_complaints'] ?? 0 }}</h3>
                                        <p class="text-muted mb-0">Resolved</p>
                                    </div>
                                    <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Department Staff -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Department Staff</h5>
                                </div>
                                <div class="card-body">
                                    @if($staff->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Position</th>
                                                        <th>Employee ID</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($staff as $member)
                                                        <tr>
                                                            <td>{{ $member->user->name }}</td>
                                                            <td>{{ $member->user->email }}</td>
                                                            <td>{{ $member->position }}</td>
                                                            <td>{{ $member->employee_id }}</td>
                                                            <td>
                                                                <span class="badge {{ $member->is_active ? 'bg-success' : 'bg-danger' }}">
                                                                    {{ $member->is_active ? 'Active' : 'Inactive' }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted text-center">No staff members assigned to this department.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Complaints -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Recent Complaints</h5>
                                </div>
                                <div class="card-body">
                                    @if($complaints->count() > 0)
                                        @foreach($complaints as $complaint)
                                            <div class="complaint-card">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="mb-1">{{ $complaint->title }}</h6>
                                                        <p class="text-muted mb-1">{{ $complaint->description }}</p>
                                                        <small class="text-muted">
                                                            By: {{ $complaint->user->name }} | 
                                                            Category: {{ $complaint->category->display_name }} |
                                                            {{ $complaint->created_at->diffForHumans() }}
                                                        </small>
                                                    </div>
                                                    <div class="text-end">
                                                        <span class="status-badge" style="background: {{ $complaint->status->color }}20; color: {{ $complaint->status->color }};">
                                                            {{ $complaint->status->display_name }}
                                                        </span>
                                                        <br>
                                                        <span class="priority-badge priority-{{ $complaint->priority }}">
                                                            {{ ucfirst($complaint->priority) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted text-center">No complaints assigned to this department yet.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Unassigned Complaints Modal -->
    <div class="modal fade" id="unassignedModal" tabindex="-1" aria-labelledby="unassignedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="unassignedModalLabel">Unassigned Complaints</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="unassignedComplaintsList">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load unassigned complaints when modal opens
        document.getElementById('unassignedModal').addEventListener('show.bs.modal', function () {
            fetch('{{ route("admin.departments.unassigned") }}')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('unassignedComplaintsList').innerHTML = html;
                })
                .catch(error => {
                    document.getElementById('unassignedComplaintsList').innerHTML = '<p class="text-danger">Error loading unassigned complaints.</p>';
                });
        });
    </script>
</body>
</html>
