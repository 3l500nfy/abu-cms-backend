<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $department->name }} - Department Dashboard</title>
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
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
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
                    <h4 class="text-center mb-4">{{ $department->code }}</h4>
                    <p class="text-center text-muted mb-4">{{ $department->name }}</p>
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="{{ route('department.dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                        <a class="nav-link" href="{{ route('department.complaints') }}">
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
                        <h5 class="mb-0">{{ $department->name }} Dashboard</h5>
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

                    <!-- Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="stats-card primary">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3 class="mb-0">{{ $stats['total_complaints'] }}</h3>
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
                                        <h3 class="mb-0">{{ $stats['pending_complaints'] }}</h3>
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
                                        <h3 class="mb-0">{{ $stats['in_progress_complaints'] }}</h3>
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
                                        <h3 class="mb-0">{{ $stats['resolved_complaints'] }}</h3>
                                        <p class="text-muted mb-0">Resolved</p>
                                    </div>
                                    <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Complaints -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Recent Complaints</h5>
                                    <a href="{{ route('department.complaints') }}" class="btn btn-primary btn-sm">
                                        View All
                                    </a>
                                </div>
                                <div class="card-body">
                                    @if($recent_complaints->count() > 0)
                                        @foreach($recent_complaints as $complaint)
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
                                        <p class="text-muted text-center">No complaints assigned to your department yet.</p>
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
                                    <a href="{{ route('department.complaints') }}" class="btn btn-primary w-100 mb-2">
                                        <i class="bi bi-list me-2"></i> View All Complaints
                                    </a>
                                    <a href="{{ route('department.complaints') }}?status=pending" class="btn btn-outline-warning w-100 mb-2">
                                        <i class="bi bi-clock me-2"></i> Pending Complaints
                                    </a>
                                    <a href="{{ route('department.complaints') }}?status=in_progress" class="btn btn-outline-info w-100">
                                        <i class="bi bi-gear me-2"></i> In Progress
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
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
