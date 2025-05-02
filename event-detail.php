<?php
require_once 'config/database.php';
require_once 'config/mail.php';

// Get the event ID from URL parameter
$eventId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Database connection
$database = new Database();
$db = $database->getConnection();

// Handle participant registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    
    if ($name && $email && $eventId) {
        $stmt = $db->prepare("INSERT INTO participants (name, email, phone, event_id, status) VALUES (?, ?, ?, ?, 'pending')");
        if($stmt->execute([$name, $email, $phone, $eventId])) {
            // Récupérer les détails de l'événement pour l'email
            $stmt_event = $db->prepare("SELECT title FROM events WHERE id = ?");
            $stmt_event->execute([$eventId]);
            $event_details = $stmt_event->fetch(PDO::FETCH_ASSOC);

            // Préparer et envoyer l'email initial
            $to = $email;
            $subject = "Demande d'inscription reçue - " . $event_details['title'];
            
            $message = "Bonjour " . $name . ",\n\n";
            $message .= "Nous avons bien reçu votre demande d'inscription à l'événement \"" . $event_details['title'] . "\".\n\n";
            $message .= "Notre équipe examinera votre demande et vous recevrez un email de confirmation une fois celle-ci validée.\n\n";
            $message .= "Merci de votre intérêt pour notre événement !\n\n";
            $message .= "Cordialement,\nL'équipe LenSi Events";

            sendEmail($to, $subject, $message);
            
            $success_message = "Votre demande d'inscription a été reçue ! Vous recevrez bientôt un email de confirmation.";
        }
    }
}

// Get event details
$stmt = $db->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$eventId]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

// Redirect if event not found
if (!$event) {
    header('Location: index.php#events');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($event['title']); ?> - LenSi Events</title>
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
            color: var(--accent);
            font-family: 'Inter', sans-serif;
        }

        [data-bs-theme="dark"] {
            --light: #121212;
            --dark: #F9F7F0;
            --accent: #A4C2E5;
            --accent-dark: #171821;
        }

        .event-header {
            background-color: var(--accent);
            color: white;
            padding: 4rem 0;
            margin-bottom: 3rem;
        }

        .event-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 1rem;
            margin-bottom: 2rem;
        }

        .schedule-item {
            padding: 1rem;
            border-left: 3px solid var(--primary);
            margin-bottom: 1rem;
            background-color: rgba(var(--primary-rgb), 0.05);
            border-radius: 0 0.5rem 0.5rem 0;
        }

        .speaker-item {
            padding: 0.75rem 1rem;
            background-color: white;
            border-radius: 0.5rem;
            margin-bottom: 0.75rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        [data-bs-theme="dark"] .speaker-item {
            background-color: rgba(255,255,255,0.05);
        }

        .participate-btn {
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 1rem 2rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .participate-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(var(--primary-rgb), 0.3);
            color: white;
        }

        .back-btn {
            color: var(--accent);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            margin-bottom: 2rem;
        }

        .back-btn i {
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="event-header">
        <div class="container">
            <a href="index.php#events" class="back-btn">
                <i class="bi bi-arrow-left"></i>
                Back to Events
            </a>
            <h1 class="mb-3"><?php echo htmlspecialchars($event['title']); ?></h1>
            <div class="d-flex align-items-center">
                <div class="me-4">
                    <i class="bi bi-calendar-event me-2"></i>
                    <?php echo date('F j, Y', strtotime($event['date'])); ?>
                </div>
                <div>
                    <i class="bi bi-geo-alt me-2"></i>
                    <?php echo htmlspecialchars($event['location']); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="container mb-5">
        <div class="row">
            <div class="col-lg-8">
                <?php if (!empty($event['image'])): ?>
                    <img src="<?php echo htmlspecialchars($event['image']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>" class="event-image">
                <?php endif; ?>
                
                <h2 class="mb-4">About the Event</h2>
                <p class="mb-5"><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
                
                <?php if (!empty($event['schedule'])): ?>
                    <h2 class="mb-4">Event Schedule</h2>
                    <div class="mb-5">
                        <?php foreach ($event['schedule'] as $item): ?>
                            <div class="schedule-item">
                                <?php echo htmlspecialchars($item); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <div class="text-center">
                    <button class="participate-btn" onclick="showParticipateModal()">
                        Participate in Event
                    </button>
                </div>
            </div>
            
            <?php if (!empty($event['speakers'])): ?>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-4">Speakers</h3>
                        <?php foreach ($event['speakers'] as $speaker): ?>
                            <div class="speaker-item">
                                <i class="bi bi-person-circle me-2"></i>
                                <?php echo htmlspecialchars($speaker); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Participate Modal -->
    <div class="modal fade" id="participateModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Participate in <?php echo htmlspecialchars($event['title']); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success mb-4">
                            <?php echo htmlspecialchars($success_message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form id="participateForm" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Confirm Participation</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showParticipateModal() {
            const modal = new bootstrap.Modal(document.getElementById('participateModal'));
            modal.show();
        }

        // Initialize theme based on system preference or saved theme
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme');
            const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
            const themeToUse = savedTheme || (prefersDarkScheme.matches ? 'dark' : 'light');
            
            document.documentElement.setAttribute('data-bs-theme', themeToUse);
        });
    </script>
</body>
</html>