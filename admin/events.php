<?php
session_start();
require_once '../config/database.php';

// Check if user is admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php');
    exit;
}

// Get action from URL
$action = $_GET['action'] ?? 'list';
$event_id = $_GET['id'] ?? null;

// La variable $db est déjà créée dans database.php, pas besoin de getDbConnection()

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'new' || $action === 'edit') {
        $title = $_POST['title'];
        $date = $_POST['date'];
        $location = $_POST['location'];
        $description = $_POST['description'];
        
        // Handle image upload
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $target_dir = "../assets/images/events/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            $image = "event_" . time() . "." . $file_extension;
            $target_file = $target_dir . $image;
            
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = "assets/images/events/" . $image;
            }
        }

        if ($action === 'new') {
            $stmt = $db->prepare("INSERT INTO events (title, date, location, description, image) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $date, $location, $description, $image]);
            $success_message = "Event created successfully!";
        } else {
            $sql = "UPDATE events SET title=?, date=?, location=?, description=?";
            $params = [$title, $date, $location, $description];
            
            if ($image) {
                $sql .= ", image=?";
                $params[] = $image;
            }
            
            $sql .= " WHERE id=?";
            $params[] = $event_id;
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $success_message = "Event updated successfully!";
        }
        
        header("Location: events.php");
        exit;
    } elseif ($action === 'delete') {
        $stmt = $db->prepare("DELETE FROM events WHERE id = ?");
        $stmt->execute([$event_id]);
        $success_message = "Event deleted successfully!";
        header("Location: events.php");
        exit;
    }
}

// Get events for listing
if ($action === 'list') {
    $stmt = $db->query("
        SELECT e.*, COUNT(p.id) as participant_count 
        FROM events e 
        LEFT JOIN participants p ON e.id = p.event_id 
        GROUP BY e.id 
        ORDER BY e.date DESC
    ");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
} elseif ($action === 'edit' && $event_id) {
    $stmt = $db->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$event_id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$event) {
        header("Location: events.php");
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Inter:wght@300;400;500&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #3E5C76;
            --secondary: #748CAB;
            --accent: #1D2D44;
            --light: #F9F7F0;
        }

        body {
            background-color: var(--light);
            font-family: 'Inter', sans-serif;
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

        .card {
            background: white;
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }

        .action-buttons .btn {
            padding: 0.5rem;
            margin-left: 0.5rem;
        }

        .preview-image {
            max-width: 200px;
            max-height: 200px;
            object-fit: cover;
            border-radius: 0.5rem;
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
                        <a class="nav-link" href="dashboard.php">
                            <i class="bi bi-grid"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="events.php">
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
                        <h1 class="h3 mb-0">
                            <?php
                            switch ($action) {
                                case 'new':
                                    echo 'Create New Event';
                                    break;
                                case 'edit':
                                    echo 'Edit Event';
                                    break;
                                default:
                                    echo 'Manage Events';
                            }
                            ?>
                        </h1>
                        <?php if ($action === 'list'): ?>
                        <button class="btn btn-primary" onclick="window.location.href='?action=new'">
                            <i class="bi bi-plus-circle me-2"></i>
                            Add New Event
                        </button>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="container-fluid px-4">
                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($success_message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($action === 'list'): ?>
                        <!-- Events List -->
                        <div class="card">
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
                                            <?php foreach ($events as $event): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($event['title']); ?></td>
                                                <td><?php echo htmlspecialchars($event['date']); ?></td>
                                                <td><?php echo htmlspecialchars($event['location']); ?></td>
                                                <td><?php echo $event['participant_count']; ?></td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <a href="?action=edit&id=<?php echo $event['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete(<?php echo $event['id']; ?>)">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Event Form -->
                        <div class="card">
                            <div class="card-body">
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <label for="title" class="form-label">Event Title</label>
                                                <input type="text" class="form-control" id="title" name="title" required
                                                    value="<?php echo $action === 'edit' ? htmlspecialchars($event['title']) : ''; ?>">
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="date" class="form-label">Event Date</label>
                                                        <input type="date" class="form-control" id="date" name="date" required
                                                            value="<?php echo $action === 'edit' ? $event['date'] : ''; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="location" class="form-label">Location</label>
                                                        <input type="text" class="form-control" id="location" name="location" required
                                                            value="<?php echo $action === 'edit' ? htmlspecialchars($event['location']) : ''; ?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="description" class="form-label">Description</label>
                                                <textarea class="form-control" id="description" name="description" rows="5" required><?php echo $action === 'edit' ? htmlspecialchars($event['description']) : ''; ?></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="image" class="form-label">Event Image</label>
                                                <input type="file" class="form-control" id="image" name="image" accept="image/*"
                                                    <?php echo $action === 'new' ? 'required' : ''; ?>>
                                                <?php if ($action === 'edit' && !empty($event['image'])): ?>
                                                    <img src="../<?php echo htmlspecialchars($event['image']); ?>" 
                                                        alt="Current event image" class="preview-image mt-3">
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-primary">
                                            <?php echo $action === 'new' ? 'Create Event' : 'Update Event'; ?>
                                        </button>
                                        <a href="events.php" class="btn btn-outline-secondary ms-2">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(eventId) {
            if (confirm('Are you sure you want to delete this event?')) {
                window.location.href = `?action=delete&id=${eventId}`;
            }
        }

        // Preview image before upload
        document.getElementById('image')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.querySelector('.preview-image');
                    if (preview) {
                        preview.src = e.target.result;
                    } else {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('preview-image', 'mt-3');
                        e.target.parentNode.appendChild(img);
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>