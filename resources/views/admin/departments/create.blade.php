<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Department - ABU CMS</title>
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
        .form-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        .btn-primary {
            background: #3498db;
            border-color: #3498db;
        }
        .btn-primary:hover {
            background: #2980b9;
            border-color: #2980b9;
        }
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
                        <h5 class="mb-0">Create New Department</h5>
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

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="form-card p-4">
                                <div class="text-center mb-4">
                                    <i class="bi bi-building-add text-primary" style="font-size: 3rem;"></i>
                                    <h4 class="mt-2">Create New Department</h4>
                                    <p class="text-muted">Add a new department to handle complaints</p>
                                </div>

                                <form method="POST" action="{{ route('admin.departments.store') }}">
                                    @csrf
                                    
                                    <div class="row">
                                        <!-- Basic Information -->
                                        <div class="col-md-6">
                                            <h5 class="mb-3">Basic Information</h5>
                                            
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Department Name *</label>
                                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                                <small class="text-muted">e.g., Information Technology, Facilities Management</small>
                                            </div>

                                            <div class="mb-3">
                                                <label for="code" class="form-label">Department Code *</label>
                                                <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}" required maxlength="10">
                                                <small class="text-muted">Short code (e.g., IT, FM, HR) - will be converted to uppercase</small>
                                            </div>
                                        </div>

                                        <!-- Contact Information -->
                                        <div class="col-md-6">
                                            <h5 class="mb-3">Contact Information</h5>
                                            
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Department Email</label>
                                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                                                <small class="text-muted">Department contact email</small>
                                            </div>

                                            <div class="mb-3">
                                                <label for="phone" class="form-label">Department Phone</label>
                                                <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                                                <small class="text-muted">Department contact phone</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <h5 class="mb-3">Department Description</h5>
                                            <div class="mb-3">
                                                <label for="description" class="form-label">Description</label>
                                                <textarea class="form-control" id="description" name="description" rows="4" maxlength="500">{{ old('description') }}</textarea>
                                                <small class="text-muted">Brief description of the department's responsibilities (max 500 characters)</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit Buttons -->
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <hr>
                                            <div class="d-flex justify-content-between">
                                                <a href="{{ route('admin.departments') }}" class="btn btn-outline-secondary">
                                                    <i class="bi bi-arrow-left me-2"></i>Back to Departments
                                                </a>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bi bi-building-add me-2"></i>Create Department
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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
