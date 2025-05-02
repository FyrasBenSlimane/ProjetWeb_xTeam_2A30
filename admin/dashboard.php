<?php
session_start();

// Check if user is admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - LenSi</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Inter:wght@300;400;500&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #3E5C76;
            --primary-rgb: 62, 92, 118;
            --secondary: #748CAB;
            --accent: #1D2D44;
            --accent-dark: #0D1B2A;
            --light: #F9F7F0;
            --dark: #0D1B2A;
        }

        body {
            background-color: var(--light);
            font-family: 'Inter', sans-serif;
        }

        [data-bs-theme="dark"] {
            --light: #121212;
            --dark: #F9F7F0;
            --accent: #A4C2E5;
        }

        .admin-sidebar {
            background: var(--accent);
            min-height: 100vh;
            color: white;
            padding: 2rem 0;
        }

        .admin-brand {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            font-weight: 600;
            padding: 0 1.5rem;
            margin-bottom: 2rem;
        }

        .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
        }

        .nav-link i {
            margin-right: 0.75rem;
        }

        .content-header {
            background: white;
            padding: 1.5rem;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        [data-bs-theme="dark"] .content-header {
            background: rgba(255,255,255,0.05);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .card {
            background: white;
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }

        [data-bs-theme="dark"] .card {
            background: rgba(255,255,255,0.05);
        }

        .stat-card {
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            background: rgba(var(--primary-rgb), 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--secondary);
            font-size: 0.9rem;
        }

        .action-buttons .btn {
            padding: 0.5rem;
            margin-left: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 col-xl-2 admin-sidebar">
                <div class="admin-brand">
                    <i class="bi bi-shield-lock me-2"></i>
                    Admin Panel
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <i class="bi bi-grid"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="events.php">
                            <i class="bi bi-calendar-event"></i>
                            Events
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="participants.php">
                            <i class="bi bi-people"></i>
                            Participants
                        </a>
                    </li>
                    <li class="nav-item mt-4">
                        <a class="nav-link" href="logout.php">
                            <i class="bi bi-box-arrow-left"></i>
                            Logout
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-xl-10 p-0">
                <div class="content-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="h3 mb-0">Dashboard Overview</h1>
                        <button class="btn btn-primary" onclick="window.location.href='events.php?action=new'">
                            <i class="bi bi-plus-circle me-2"></i>
                            Add New Event
                        </button>
                    </div>
                </div>

                <div class="container-fluid px-4">
                    <!-- Statistics Cards -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="card stat-card">
                                <div class="stat-icon">
                                    <i class="bi bi-calendar-event"></i>
                                </div>
                                <div class="stat-value">12</div>
                                <div class="stat-label">Total Events</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card stat-card">
                                <div class="stat-icon">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="stat-value">245</div>
                                <div class="stat-label">Total Participants</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card stat-card">
                                <div class="stat-icon">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                                <div class="stat-value">5</div>
                                <div class="stat-label">Upcoming Events</div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Events Table -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Recent Events</h5>
                            <a href="events.php" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Event Name</th>
                                            <th>Date</th>
                                            <th>Location</th>
                                            <th>Participants</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Tech Conference 2025</td>
                                            <td>April 25, 2025</td>
                                            <td>Silicon Valley</td>
                                            <td>89</td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="events.php?action=edit&id=1" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="events.php?action=delete&id=1" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- Add more rows as needed -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Participants Table -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Recent Participants</h5>
                            <a href="participants.php" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Event</th>
                                            <th>Registration Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>John Doe</td>
                                            <td>john@example.com</td>
                                            <td>Tech Conference 2025</td>
                                            <td>2025-04-20</td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="participants.php?action=view&id=1" class="btn btn-sm btn-outline-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="participants.php?action=delete&id=1" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- Add more rows as needed -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>