<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $department->name }} - Complaints</title>
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
        .complaint-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border-left: 4px solid #3498db;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .priority-badge {
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        .priority-high { background: #ffebee; color: #c62828; }
        .priority-medium { background: #fff3e0; color: #ef6c00; }
        .priority-low { background: #e8f5e8; color: #2e7d32; }
        .filter-card {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="p-3">
                    <h4 class="text-center mb-4">{{ $department->code }}</h4>
                    <p class="text-center text-muted mb-4">{{ $department->name }}</p>
                    <nav class="nav flex-column">
                        <a class="nav-link" href="{{ route('department.dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                        <a class="nav-link active" href="{{ route('department.complaints') }}">
                            <i class="bi bi-exclamation-triangle me-2"></i> Complaints
                        </a>
                        <a class="nav-link" href="{{ route('department.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
                        <h5 class="mb-0">{{ $department->name }} - Complaints</h5>
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

                    <!-- Filters -->
                    <div class="filter-card">
                        <form method="GET" action="{{ route('department.complaints') }}" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Priority</label>
                                <select name="priority" class="form-select">
                                    <option value="">All Priorities</option>
                                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select">
                                    <option value="">All Categories</option>
                                    @foreach($categories ?? [] as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bi bi-funnel me-1"></i> Filter
                                </button>
                                <a href="{{ route('department.complaints') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Clear
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Complaints List -->
                    @if($complaints->count() > 0)
                        <div class="row">
                            @foreach($complaints as $complaint)
                                <div class="col-12">
                                    <div class="complaint-card">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h5 class="mb-1">{{ $complaint->title }}</h5>
                                                    <div>
                                                        <span class="status-badge" style="background: {{ $complaint->status->color }}20; color: {{ $complaint->status->color }};">
                                                            {{ $complaint->status->display_name }}
                                                        </span>
                                                        <span class="priority-badge priority-{{ $complaint->priority }}">
                                                            {{ ucfirst($complaint->priority) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <p class="text-muted mb-2">{{ $complaint->description }}</p>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <small class="text-muted">
                                                            <strong>Complaint ID:</strong> {{ $complaint->complaint_id }}<br>
                                                            <strong>Submitted by:</strong> {{ $complaint->user->name }} ({{ $complaint->user->email }})<br>
                                                            <strong>Category:</strong> {{ $complaint->category->display_name }}<br>
                                                            <strong>Location:</strong> {{ $complaint->location ?: 'Not specified' }}
                                                        </small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <small class="text-muted">
                                                            <strong>Submitted:</strong> {{ $complaint->created_at->format('M d, Y H:i') }}<br>
                                                            <strong>Last Updated:</strong> {{ $complaint->updated_at->format('M d, Y H:i') }}<br>
                                                            @if($complaint->resolved_at)
                                                                <strong>Resolved:</strong> {{ $complaint->resolved_at->format('M d, Y H:i') }}
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                                @if($complaint->resolution_notes)
                                                    <div class="mt-2">
                                                        <small class="text-muted">
                                                            <strong>Resolution Notes:</strong> {{ $complaint->resolution_notes }}
                                                        </small>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-4">
                                                <form method="POST" action="{{ route('department.complaints.update-status', $complaint->id) }}">
                                                    @csrf
                                                    <div class="mb-2">
                                                        <label class="form-label">Update Status</label>
                                                        <select name="status_id" class="form-select form-select-sm" required>
                                                            @foreach($statuses as $status)
                                                                <option value="{{ $status->id }}" {{ $complaint->status_id == $status->id ? 'selected' : '' }}>
                                                                    {{ $status->display_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label class="form-label">Resolution Notes</label>
                                                        <textarea name="resolution_notes" class="form-control form-control-sm" rows="3" placeholder="Add resolution notes...">{{ $complaint->resolution_notes }}</textarea>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                                        <i class="bi bi-check-circle me-1"></i> Update Status
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $complaints->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                            <h5 class="text-muted mt-3">No complaints found</h5>
                            <p class="text-muted">There are no complaints assigned to your department matching your criteria.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('department.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
