<?php
session_start();
require_once '../config/database.php';
require_once '../config/mail.php';
require_once 'check_admin.php';

$database = new Database();
$db = $database->getConnection();

// Check if user is admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php');
    exit;
}

// Traitement de la confirmation/rejet
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['participant_id'])) {
    $participant_id = $_POST['participant_id'];
    $action = $_POST['action'];
    
    if ($action === 'confirm' || $action === 'reject') {
        $status = ($action === 'confirm') ? 'confirmed' : 'rejected';
        
        // Mettre à jour le statut
        $stmt = $db->prepare("UPDATE participants SET status = ? WHERE id = ?");
        if ($stmt->execute([$status, $participant_id])) {
            // Récupérer les informations du participant et de l'événement
            $stmt = $db->prepare("
                SELECT p.*, e.title as event_title, e.date as event_date, e.location 
                FROM participants p 
                JOIN events e ON p.event_id = e.id 
                WHERE p.id = ?
            ");
            $stmt->execute([$participant_id]);
            $participant = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($action === 'confirm') {
                // Envoyer l'email de confirmation
                $subject = "Confirmation de participation - " . $participant['event_title'];
                $message = "Cher(e) " . $participant['name'] . ",\n\n";
                $message .= "Nous avons le plaisir de vous confirmer votre participation à l'événement \"" . $participant['event_title'] . "\".\n\n";
                $message .= "Détails de l'événement :\n";
                $message .= "Date : " . date('d/m/Y', strtotime($participant['event_date'])) . "\n";
                $message .= "Lieu : " . $participant['location'] . "\n\n";
                $message .= "Nous sommes ravis de vous compter parmi nos participants !\n\n";
                $message .= "Cordialement,\nL'équipe LenSi Events";
                
                sendEmail($participant['email'], $subject, $message);
            }
        }
    }
}

// Récupérer la liste des participants avec les détails des événements
$stmt = $db->prepare("
    SELECT p.*, e.title as event_title 
    FROM participants p 
    JOIN events e ON p.event_id = e.id 
    ORDER BY p.registration_date DESC
");
$stmt->execute();
$participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Participants - Admin Dashboard</title>
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

        .participant-details {
            background: rgba(var(--primary-rgb), 0.05);
            border-radius: 0.5rem;
            padding: 1.5rem;
        }

        .participant-detail-label {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0.25rem;
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
                        <a class="nav-link" href="dashboard.php">
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
                        <a class="nav-link active" href="participants.php">
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
                        <h1 class="h3 mb-0">Gestion des Participants</h1>
                    </div>
                </div>

                <div class="container-fluid px-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Email</th>
                                            <th>Téléphone</th>
                                            <th>Événement</th>
                                            <th>Date d'inscription</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($participants as $participant): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($participant['name']); ?></td>
                                                <td><?php echo htmlspecialchars($participant['email']); ?></td>
                                                <td><?php echo htmlspecialchars($participant['phone']); ?></td>
                                                <td><?php echo htmlspecialchars($participant['event_title']); ?></td>
                                                <td><?php echo date('d/m/Y H:i', strtotime($participant['registration_date'])); ?></td>
                                                <td>
                                                    <?php
                                                    $statusClass = 'secondary';
                                                    if ($participant['status'] === 'confirmed') {
                                                        $statusClass = 'success';
                                                    } elseif ($participant['status'] === 'rejected') {
                                                        $statusClass = 'danger';
                                                    } elseif ($participant['status'] === 'pending') {
                                                        $statusClass = 'warning';
                                                    }
                                                    ?>
                                                    <span class="badge bg-<?php echo $statusClass; ?>">
                                                        <?php echo ucfirst($participant['status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($participant['status'] === 'pending'): ?>
                                                        <form method="POST" class="d-inline">
                                                            <input type="hidden" name="participant_id" value="<?php echo $participant['id']; ?>">
                                                            <button type="submit" name="action" value="confirm" class="btn btn-success btn-sm">
                                                                <i class="bi bi-check-lg"></i> Confirmer
                                                            </button>
                                                            <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">
                                                                <i class="bi bi-x-lg"></i> Rejeter
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
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