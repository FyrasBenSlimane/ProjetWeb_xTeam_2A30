<?php
/**
 * Events Component
 * Displays a grid of upcoming events
 */

require_once __DIR__ . '/../config/database.php';

// Connexion à la base de données
try {
    $database = new Database();
    $conn = $database->getConnection();

    // Limite le nombre d'événements pour améliorer les performances
    $stmt = $conn->prepare("SELECT * FROM events WHERE date >= CURDATE() ORDER BY date ASC LIMIT 6");
    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    error_log("Erreur de connexion : " . $e->getMessage());
    $events = [];
}

// Fonction pour gérer les chemins d'images
function getEventImagePath($imagePath) {
    if (empty($imagePath)) {
        return 'assets/images/events/default.jpg';
    }
    
    if (strpos($imagePath, 'assets/') === 0) {
        return $imagePath;
    }
    
    return 'assets/images/events/' . $imagePath;
}
?>
<style>
.events-section {
    padding: 5rem 0;
    background-color: var(--light);
    opacity: 1;
    transform: none;
}

.event-card {
    background: white;
    border-radius: var(--border-radius-md);
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    height: 100%;
    border: 1px solid rgba(0, 0, 0, 0.05);
    opacity: 1;
    transform: translateZ(0);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    will-change: transform;
}

[data-bs-theme="dark"] .event-card {
    background: rgba(30, 35, 45, 0.5);
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.event-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.event-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    background-color: #f0f0f0;
    transition: opacity 0.3s ease;
    opacity: 0;
}

.event-image.loaded {
    opacity: 1;
}

.event-content {
    padding: 1.5rem;
}

.event-date {
    color: var(--primary);
    font-weight: 600;
    font-size: var(--text-sm);
    margin-bottom: 0.5rem;
}

.event-title {
    font-size: var(--text-lg);
    margin-bottom: 1rem;
    color: var(--accent);
    font-weight: 600;
}

.event-description {
    color: var(--secondary);
    margin-bottom: 1.5rem;
    font-size: var(--text-md);
}

.event-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
}

[data-bs-theme="dark"] .event-footer {
    border-top: 1px solid rgba(255, 255, 255, 0.05);
}

.event-location {
    display: flex;
    align-items: center;
    color: var(--secondary);
    font-size: var(--text-sm);
}

.event-location i {
    margin-right: 0.5rem;
}

.view-details-btn {
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
    font-size: var(--text-sm);
    transition: var(--transition-default);
}

.view-details-btn:hover {
    color: var(--secondary);
}
</style>

<section id="events" class="events-section">
    <div class="container">
        <h2 class="text-center mb-5">Upcoming Events</h2>
        <div class="row g-4">
            <?php if (empty($events)): ?>
                <div class="col-12 text-center">
                    <p>No upcoming events scheduled at the moment.</p>
                </div>
            <?php else: ?>
                <?php foreach ($events as $index => $event): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="event-card">
                            <img src="<?php echo htmlspecialchars(getEventImagePath($event['image'])); ?>" 
                                 alt="<?php echo htmlspecialchars($event['title']); ?>" 
                                 class="event-image"
                                 loading="eager"
                                 onload="this.classList.add('loaded')"
                                 onerror="this.src='assets/images/events/default.jpg'; this.classList.add('loaded')">
                            <div class="event-content">
                                <div class="event-date">
                                    <?php echo date('F d, Y', strtotime($event['date'])); ?>
                                </div>
                                <h3 class="event-title">
                                    <?php echo htmlspecialchars($event['title']); ?>
                                </h3>
                                <p class="event-description">
                                    <?php 
                                    $description = htmlspecialchars($event['description']);
                                    echo strlen($description) > 150 ? substr($description, 0, 147) . '...' : $description;
                                    ?>
                                </p>
                                <div class="event-footer">
                                    <div class="event-location">
                                        <i class="bi bi-geo-alt"></i>
                                        <span><?php echo htmlspecialchars($event['location']); ?></span>
                                    </div>
                                    <a href="event-detail.php?id=<?php echo $event['id']; ?>" 
                                       class="view-details-btn">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
// Préchargement des images
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('.event-image');
    images.forEach(img => {
        if (img.complete) {
            img.classList.add('loaded');
        }
    });
});
</script>