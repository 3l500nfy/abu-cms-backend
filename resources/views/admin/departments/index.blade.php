<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABU CMS - Department Management</title>
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
        .department-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .department-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .stats-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .stats-complaints { background: #e3f2fd; color: #1976d2; }
        .stats-staff { background: #f3e5f5; color: #7b1fa2; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="p-3">
                    <h4 class="text-center mb-4">ABU CMS</h4>
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
                        <h5 class="mb-0">Department Management</h5>
                        <div class="navbar-nav ms-auto">
                            <a href="{{ route('admin.departments.create') }}" class="btn btn-primary me-3">
                                <i class="bi bi-building-add me-2"></i>Create Department
                            </a>
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

                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="mb-1">Departments Overview</h4>
                            <p class="text-muted mb-0">Manage departments and assign complaints</p>
                        </div>
                        <a href="{{ route('admin.complaints') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Assign Complaints
                        </a>
                    </div>

                    <!-- Departments Grid -->
                    <div class="row">
                        @if($departments->count() > 0)
                            @foreach($departments as $department)
                                <div class="col-md-6 col-lg-4">
                                    <div class="department-card">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h6 class="mb-1">{{ $department->name }}</h6>
                                                <p class="text-muted mb-1">{{ $department->code }}</p>
                                                <small class="text-muted">{{ $department->description }}</small>
                                            </div>
                                            <i class="bi bi-building text-primary" style="font-size: 2rem;"></i>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="d-flex gap-2 mb-2">
                                                <span class="stats-badge stats-complaints">
                                                    {{ $department->complaints_count }} Complaints
                                                </span>
                                                <span class="stats-badge stats-staff">
                                                    {{ $department->staff_count }} Staff
                                                </span>
                                            </div>
                                            @if($department->email)
                                                <small class="text-muted">
                                                    <i class="bi bi-envelope me-1"></i>{{ $department->email }}
                                                </small>
                                            @endif
                                        </div>

                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.departments.show', $department->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye me-1"></i>View Details
                                            </a>
                                            <button class="btn btn-sm btn-outline-secondary" onclick="viewUnassignedComplaints()">
                                                <i class="bi bi-list me-1"></i>Unassigned
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <i class="bi bi-building text-muted" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3 text-muted">No departments found</h5>
                                    <p class="text-muted">Departments will appear here once created.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Unassigned Complaints Modal -->
    <div class="modal fade" id="unassignedComplaintsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Unassigned Complaints</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function viewUnassignedComplaints() {
            fetch('/admin/departments/unassigned-complaints')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('unassignedComplaintsList');
                    
                    if (data.length === 0) {
                        container.innerHTML = '<p class="text-center text-muted">No unassigned complaints found.</p>';
                    } else {
                        let html = '<div class="list-group">';
                        data.forEach(complaint => {
                            html += `
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">${complaint.title}</h6>
                                            <p class="text-muted mb-1">${complaint.description}</p>
                                            <small class="text-muted">
                                                By: ${complaint.user.name} | 
                                                Category: ${complaint.category.display_name} |
                                                ${new Date(complaint.created_at).toLocaleDateString()}
                                            </small>
                                        </div>
                                        <span class="badge bg-warning">Unassigned</span>
                                    </div>
                                </div>
                            `;
                        });
                        html += '</div>';
                        container.innerHTML = html;
                    }
                    
                    new bootstrap.Modal(document.getElementById('unassignedComplaintsModal')).show();
                });
        }
    </script>
</body>
</html>
