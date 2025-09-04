<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABU CMS - Admin Dashboard</title>
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
        .stats-card {
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
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
                        <a class="nav-link active" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                        <a class="nav-link" href="{{ route('admin.complaints') }}">
                            <i class="bi bi-exclamation-triangle me-2"></i> Complaints
                        </a>
                        <a class="nav-link" href="{{ route('admin.users') }}">
                            <i class="bi bi-people me-2"></i> Users
                        </a>
                        <a class="nav-link" href="{{ route('admin.departments') }}">
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
                        <h5 class="mb-0">Dashboard</h5>
                        <div class="navbar-nav ms-auto">
                            <span class="navbar-text">
                                Welcome, {{ Auth::user()->name }}
                            </span>
                        </div>
                    </div>
                </nav>

                <!-- Content -->
                <div class="p-4">
                    <!-- Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <a href="{{ route('admin.complaints') }}" class="text-decoration-none">
                                <div class="stats-card primary">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3 class="mb-0">{{ $stats['total_complaints'] }}</h3>
                                            <p class="text-muted mb-0">Total Complaints</p>
                                        </div>
                                        <i class="bi bi-exclamation-triangle text-primary" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.complaints') }}?status=pending" class="text-decoration-none">
                                <div class="stats-card warning">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3 class="mb-0">{{ $stats['pending_complaints'] }}</h3>
                                            <p class="text-muted mb-0">Pending</p>
                                        </div>
                                        <i class="bi bi-clock text-warning" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.complaints') }}?status=resolved" class="text-decoration-none">
                                <div class="stats-card success">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3 class="mb-0">{{ $stats['resolved_complaints'] }}</h3>
                                            <p class="text-muted mb-0">Resolved</p>
                                        </div>
                                        <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.users') }}" class="text-decoration-none">
                                <div class="stats-card info">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3 class="mb-0">{{ $stats['total_users'] }}</h3>
                                            <p class="text-muted mb-0">Total Users</p>
                                        </div>
                                        <i class="bi bi-people text-info" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Recent Complaints -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Recent Complaints</h5>
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
                                                    <span class="status-badge" style="background: {{ $complaint->status->color }}20; color: {{ $complaint->status->color }};">
                                                        {{ $complaint->status->display_name }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted text-center">No complaints found.</p>
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
                                    <a href="{{ route('admin.complaints') }}" class="btn btn-primary w-100 mb-2">
                                        <i class="bi bi-list me-2"></i> View All Complaints
                                    </a>
                                    <a href="{{ route('admin.complaints') }}" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-plus-circle me-2"></i> Manage Complaints
                                    </a>
                                </div>
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
</body>
</html>
