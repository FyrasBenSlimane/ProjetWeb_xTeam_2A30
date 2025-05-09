<?php
session_start();
require_once '../config/database.php';
require_once '../config/mail.php';
require_once '../helpers/qrcode.php';
require_once 'check_admin.php';

$database = new Database();
$db = $database->getConnection();

// Check if user is admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php');
    exit;
}

// Récupérer les filtres
$event_filter = $_GET['event'] ?? 'all';
$status_filter = $_GET['status'] ?? 'all';

// Récupérer la liste des événements pour le filtre
$stmt = $db->query("SELECT id, title FROM events ORDER BY date DESC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les statistiques
$stats = [
    'total' => 0,
    'pending' => 0,
    'confirmed' => 0,
    'rejected' => 0
];

$stmt = $db->query("
    SELECT status, COUNT(*) as count 
    FROM participants 
    GROUP BY status
");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $stats[$row['status']] = $row['count'];
    $stats['total'] += $row['count'];
}

// Construire la requête avec les filtres
$query = "
    SELECT p.*, e.title as event_title 
    FROM participants p 
    JOIN events e ON p.event_id = e.id 
    WHERE 1=1
";
$params = [];

if ($event_filter !== 'all') {
    $query .= " AND p.event_id = ?";
    $params[] = $event_filter;
}
if ($status_filter !== 'all') {
    $query .= " AND p.status = ?";
    $params[] = $status_filter;
}

$query .= " ORDER BY p.registration_date DESC";

$stmt = $db->prepare($query);
$stmt->execute($params);
$participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traitement de la confirmation/rejet
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['participant_id'])) {
    $participant_id = $_POST['participant_id'];
    $action = $_POST['action'];
    $admin_notes = $_POST['admin_notes'] ?? '';
    
    if ($action === 'confirm' || $action === 'reject') {
        $status = ($action === 'confirm') ? 'confirmed' : 'rejected';
        
        // Mettre à jour le statut et les notes
        $stmt = $db->prepare("UPDATE participants SET status = ?, admin_notes = ? WHERE id = ?");
        if ($stmt->execute([$status, $admin_notes, $participant_id])) {
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
                // Générer le QR code HTML
                $qrCode = generateEventQRCode(
                    $participant['event_id'],
                    $participant['event_title'],
                    $participant['event_date'],
                    $participant['location']
                );

                // Préparer l'email de confirmation
                $subject = "Confirmation de participation - " . $participant['event_title'];
                $message = "
                <html>
                <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                    <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                        <h2 style='color: #3E5C76;'>Confirmation de participation</h2>
                        <p>Cher(e) <strong>" . htmlspecialchars($participant['name']) . "</strong>,</p>
                        <p>Nous avons le plaisir de vous confirmer votre participation à l'événement :</p>
                        <div style='background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                            <h3 style='color: #3E5C76; margin-top: 0;'>" . htmlspecialchars($participant['event_title']) . "</h3>
                            <p><strong>Date :</strong> " . date('d/m/Y', strtotime($participant['event_date'])) . "</p>
                            <p><strong>Lieu :</strong> " . htmlspecialchars($participant['location']) . "</p>
                        </div>
                        <p>Voici votre code de participation. Veuillez le présenter lors de votre arrivée à l'événement :</p>
                        <div style='text-align: center; margin: 30px 0;'>
                            " . $qrCode . "
                        </div>
                        <p>Nous sommes ravis de vous compter parmi nos participants !</p>
                        " . (!empty($admin_notes) ? "<p><strong>Note :</strong> " . nl2br(htmlspecialchars($admin_notes)) . "</p>" : "") . "
                        <hr style='border: none; border-top: 1px solid #eee; margin: 30px 0;'>
                        <p style='font-size: 14px; color: #666;'>
                            Cordialement,<br>
                            L'équipe LenSi Events
                        </p>
                    </div>
                </body>
                </html>";
                
                sendEmail($participant['email'], $subject, $message);
            } elseif ($action === 'reject') {
                // Envoyer l'email de rejet
                $subject = "Statut de votre demande - " . $participant['event_title'];
                $message = "
                <html>
                <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                    <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                        <h2 style='color: #3E5C76;'>Réponse à votre demande de participation</h2>
                        <p>Cher(e) <strong>" . htmlspecialchars($participant['name']) . "</strong>,</p>
                        <p>Nous avons examiné votre demande de participation à l'événement suivant :</p>
                        <div style='background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                            <h3 style='color: #3E5C76; margin-top: 0;'>" . htmlspecialchars($participant['event_title']) . "</h3>
                            <p><strong>Date :</strong> " . date('d/m/Y', strtotime($participant['event_date'])) . "</p>
                        </div>
                        <p>Malheureusement, nous ne pouvons pas donner suite à votre demande pour le moment.</p>
                        " . (!empty($admin_notes) ? "<p><strong>Motif :</strong> " . nl2br(htmlspecialchars($admin_notes)) . "</p>" : "") . "
                        <p>Nous vous remercions de l'intérêt que vous portez à nos événements.</p>
                        <hr style='border: none; border-top: 1px solid #eee; margin: 30px 0;'>
                        <p style='font-size: 14px; color: #666;'>
                            Cordialement,<br>
                            L'équipe LenSi Events
                        </p>
                    </div>
                </body>
                </html>";
                
                sendEmail($participant['email'], $subject, $message);
            }
        }
    }
}
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

        .stat-card {
            text-align: center;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 600;
        }

        .stat-label {
            font-size: 1rem;
            color: var(--secondary);
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
                    <!-- Statistics Cards -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <div class="card stat-card">
                                <div class="stat-value"><?php echo $stats['total']; ?></div>
                                <div class="stat-label">Total Participants</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card">
                                <div class="stat-value text-warning"><?php echo $stats['pending']; ?></div>
                                <div class="stat-label">En attente</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card">
                                <div class="stat-value text-success"><?php echo $stats['confirmed']; ?></div>
                                <div class="stat-label">Confirmés</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card">
                                <div class="stat-value text-danger"><?php echo $stats['rejected']; ?></div>
                                <div class="stat-label">Rejetés</div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <form class="row g-3" method="GET">
                                <div class="col-md-4">
                                    <label for="event" class="form-label">Filtrer par événement</label>
                                    <select class="form-select" id="event" name="event">
                                        <option value="all">Tous les événements</option>
                                        <?php foreach ($events as $event): ?>
                                            <option value="<?php echo $event['id']; ?>" 
                                                    <?php echo $event_filter == $event['id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($event['title']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="status" class="form-label">Filtrer par statut</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="all">Tous les statuts</option>
                                        <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>En attente</option>
                                        <option value="confirmed" <?php echo $status_filter === 'confirmed' ? 'selected' : ''; ?>>Confirmés</option>
                                        <option value="rejected" <?php echo $status_filter === 'rejected' ? 'selected' : ''; ?>>Rejetés</option>
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="bi bi-filter"></i> Filtrer
                                    </button>
                                    <a href="participants.php" class="btn btn-outline-secondary me-2">
                                        <i class="bi bi-x-circle"></i> Réinitialiser
                                    </a>
                                    <a href="export_participants.php<?php 
                                        echo '?' . http_build_query(array_filter([
                                            'event' => $event_filter,
                                            'status' => $status_filter
                                        ])); 
                                    ?>" class="btn btn-success">
                                        <i class="bi bi-download"></i> Exporter CSV
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Participants Table Card -->
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
                                            <th>Notes</th>
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
                                                    <?php if (!empty($participant['admin_notes'])): ?>
                                                        <button type="button" class="btn btn-sm btn-outline-info" 
                                                                data-bs-toggle="tooltip" data-bs-placement="top" 
                                                                title="<?php echo htmlspecialchars($participant['admin_notes']); ?>">
                                                            <i class="bi bi-info-circle"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($participant['status'] === 'pending'): ?>
                                                        <button type="button" class="btn btn-success btn-sm" 
                                                                onclick="showActionModal('confirm', <?php echo $participant['id']; ?>)">
                                                            <i class="bi bi-check-lg"></i> Confirmer
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                                onclick="showActionModal('reject', <?php echo $participant['id']; ?>)">
                                                            <i class="bi bi-x-lg"></i> Rejeter
                                                        </button>
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

    <!-- Modal de confirmation/rejet -->
    <div class="modal fade" id="actionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="participant_id" id="participantId">
                        <input type="hidden" name="action" id="actionType">
                        
                        <div class="mb-3">
                            <label for="admin_notes" class="form-label">Notes administratives</label>
                            <textarea class="form-control" id="admin_notes" name="admin_notes" rows="4" 
                                    placeholder="Ajoutez des notes ou commentaires (optionnel)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn" id="confirmButton"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        function showActionModal(action, participantId) {
            const modal = new bootstrap.Modal(document.getElementById('actionModal'));
            const title = document.querySelector('#actionModal .modal-title');
            const actionInput = document.getElementById('actionType');
            const participantIdInput = document.getElementById('participantId');
            const confirmButton = document.getElementById('confirmButton');
            
            actionInput.value = action;
            participantIdInput.value = participantId;
            
            if (action === 'confirm') {
                title.textContent = 'Confirmer la participation';
                confirmButton.textContent = 'Confirmer';
                confirmButton.className = 'btn btn-success';
            } else {
                title.textContent = 'Rejeter la participation';
                confirmButton.textContent = 'Rejeter';
                confirmButton.className = 'btn btn-danger';
            }
            
            modal.show();
        }
    </script>
</body>
</html>