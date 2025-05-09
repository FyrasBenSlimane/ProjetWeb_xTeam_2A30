<?php
/**
 * Notifications page
 */
$filterType = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Afficher le contenu des notifications pour débogage
error_log("Nombre total de notifications: " . count($notifications));
foreach ($notifications as $index => $notification) {
    error_log("Notification #$index - Type: {$notification['type']}, Message: {$notification['message']}");
    error_log("Data: " . json_encode($notification['data']));
}

// Filtrer les notifications selon le type sélectionné
$filteredNotifications = [];
foreach ($notifications as $notification) {
    // Vérifier le contenu pour débogage
    if ($notification['type'] == 'candidature') {
        error_log("Candidature found - Status: " . ($notification['data']['status'] ?? 'undefined'));
    }
    
    if ($filterType == 'all' || 
        ($filterType == 'candidature' && $notification['type'] == 'candidature') ||
        ($filterType == 'pending' && $notification['type'] == 'candidature' && 
            (
                (isset($notification['data']['status']) && $notification['data']['status'] == 'pending') ||
                (!isset($notification['data']['status']) || empty($notification['data']['status']))
            )
        ) ||
        ($filterType == 'accepted' && $notification['type'] == 'candidature' && isset($notification['data']['status']) && $notification['data']['status'] == 'accepted') ||
        ($filterType == 'rejected' && $notification['type'] == 'candidature' && isset($notification['data']['status']) && $notification['data']['status'] == 'rejected') ||
        ($filterType == 'expired' && $notification['type'] == 'candidature' && isset($notification['data']['status']) && $notification['data']['status'] == 'expired')
    ) {
        $filteredNotifications[] = $notification;
    }
}
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-bell me-2"></i>
                        My notifications
                    </h5>
                    <div class="d-flex gap-2">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <?php 
                                $filterLabel = match($filterType) {
                                    'candidature' => 'Applications',
                                    'pending' => 'Pending applications',
                                    'accepted' => 'Accepted applications',
                                    'rejected' => 'Rejected applications',
                                    'expired' => 'Expired applications',
                                    default => 'All notifications'
                                };
                                echo $filterLabel;
                                ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item <?php echo $filterType == 'all' ? 'active' : ''; ?>" href="?page=notifications">All notifications</a></li>
                                <li><a class="dropdown-item <?php echo $filterType == 'candidature' ? 'active' : ''; ?>" href="?page=notifications&filter=candidature">All applications</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item <?php echo $filterType == 'pending' ? 'active' : ''; ?>" href="?page=notifications&filter=pending">Pending applications</a></li>
                                <li><a class="dropdown-item <?php echo $filterType == 'accepted' ? 'active' : ''; ?>" href="?page=notifications&filter=accepted">Accepted applications</a></li>
                                <li><a class="dropdown-item <?php echo $filterType == 'rejected' ? 'active' : ''; ?>" href="?page=notifications&filter=rejected">Rejected applications</a></li>
                                <li><a class="dropdown-item <?php echo $filterType == 'expired' ? 'active' : ''; ?>" href="?page=notifications&filter=expired">Expired applications</a></li>
                            </ul>
                        </div>
                        <?php if (!empty($filteredNotifications)): ?>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="markAllAsReadBtn">
                            Mark all as read
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($filteredNotifications)): ?>
                    <div class="text-center py-5">
                        <div class="display-1 text-muted mb-4">
                            <i class="bi bi-bell-slash"></i>
                        </div>
                        <h3 class="text-muted">No notifications</h3>
                        <p class="text-muted">
                            <?php if ($filterType !== 'all'): ?>
                                No notifications match this filter.
                            <?php else: ?>
                                You don't have any notifications at the moment.
                            <?php endif; ?>
                        </p>
                    </div>
                    <?php else: ?>
                        <div class="list-group notification-list">
                            <?php foreach ($filteredNotifications as $notification): 
                                // Déterminer l'icône et la couleur
                                $iconClass = '';
                                $iconBgClass = '';
                                $textClass = '';
                                $badgeClass = '';
                                $statusBadge = '';
                                
                                switch ($notification['type']) {
                                    case 'candidature':
                                        $data = $notification['data'];
                                        if (isset($data['status'])) {
                                            if ($data['status'] === 'accepted') {
                                                $iconClass = 'bi-check-circle-fill text-success';
                                                $iconBgClass = 'bg-success-subtle';
                                                $badgeClass = 'bg-success';
                                                $statusBadge = 'Accepted';
                                            } elseif ($data['status'] === 'rejected') {
                                                $iconClass = 'bi-x-circle-fill text-danger';
                                                $iconBgClass = 'bg-danger-subtle';
                                                $badgeClass = 'bg-danger';
                                                $statusBadge = 'Rejected';
                                            } elseif ($data['status'] === 'expired') {
                                                $iconClass = 'bi-clock-history text-secondary';
                                                $iconBgClass = 'bg-secondary-subtle';
                                                $badgeClass = 'bg-secondary';
                                                $statusBadge = 'Expired';
                                            } else {
                                                $iconClass = 'bi-hourglass-split text-warning';
                                                $iconBgClass = 'bg-warning-subtle';
                                                $badgeClass = 'bg-warning text-dark';
                                                $statusBadge = 'Pending';
                                            }
                                        } else {
                                            $iconClass = 'bi-briefcase-fill text-primary';
                                            $iconBgClass = 'bg-primary-subtle';
                                        }
                                        break;
                                    case 'project':
                                        $iconClass = 'bi-briefcase-fill text-primary';
                                        $iconBgClass = 'bg-primary-subtle';
                                        $badgeClass = 'bg-primary';
                                        $statusBadge = 'Project';
                                        break;
                                    case 'message':
                                        $iconClass = 'bi-chat-fill text-info';
                                        $iconBgClass = 'bg-info-subtle';
                                        $badgeClass = 'bg-info';
                                        $statusBadge = 'Message';
                                        break;
                                    default:
                                        $iconClass = 'bi-info-circle-fill text-secondary';
                                        $iconBgClass = 'bg-secondary-subtle';
                                        $badgeClass = 'bg-secondary';
                                        $statusBadge = 'System';
                                }
                                
                                // Ajouter classe pour notifications non lues
                                if (!$notification['is_read']) {
                                    $textClass = 'unread-notification';
                                }
                                
                                // Vérifier si c'est une candidature avec un délai d'expiration
                                $showExpirationTime = false;
                                $expirationText = '';
                                $expirationClass = '';
                                
                                if ($notification['type'] === 'candidature' && 
                                    isset($data['status']) && 
                                    $data['status'] === 'pending' &&
                                    isset($data['expires_at'])) {
                                    
                                    $showExpirationTime = true;
                                    $expiresAt = new DateTime($data['expires_at']);
                                    $now = new DateTime();
                                    
                                    if ($now < $expiresAt) {
                                        $diff = $now->diff($expiresAt);
                                        
                                        if ($diff->days > 0) {
                                            $expirationText = sprintf(
                                                "Expires in %dd %dh %dm",
                                                $diff->d,
                                                $diff->h,
                                                $diff->i
                                            );
                                            $expirationClass = 'text-warning';
                                        } else if ($diff->h > 5) {
                                            $expirationText = sprintf(
                                                "Expires in %dh %dm",
                                                $diff->h,
                                                $diff->i
                                            );
                                            $expirationClass = 'text-warning';
                                        } else {
                                            $expirationText = sprintf(
                                                "Expires soon: %dh %dm",
                                                $diff->h,
                                                $diff->i
                                            );
                                            $expirationClass = 'text-danger';
                                        }
                                    } else {
                                        $expirationText = "Expired";
                                        $expirationClass = 'text-secondary';
                                    }
                                }
                                
                                // Déterminer si le message contient déjà des informations d'expiration
                                $messageContainsExpiration = stripos($notification['message'], 'expire in') !== false;
                            ?>
                            <div class="list-group-item notification-item <?php echo $textClass; ?>"
                                 data-notification-id="<?php echo $notification['id']; ?>">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="notification-icon <?php echo $iconBgClass; ?>">
                                            <i class="bi <?php echo $iconClass; ?> fs-4"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge <?php echo $badgeClass; ?>"><?php echo $statusBadge; ?></span>
                                            <small class="text-muted notification-date">
                                                <?php echo date('d/m/Y H:i', strtotime($notification['created_at'])); ?>
                                            </small>
                                        </div>
                                        
                                        <div class="notification-message">
                                            <?php if ($notification['type'] === 'candidature' && isset($data['project_title'])): ?>
                                                <div class="fw-medium">
                                                    Application: <?php echo htmlspecialchars($data['project_title']); ?>
                                                </div>
                                                <?php if (isset($data['budget'])): ?>
                                                <div class="small text-muted mt-1 mb-1">
                                                    Proposed budget: <strong><?php echo number_format((float)$data['budget'], 2, ',', ' '); ?> €</strong>
                                                </div>
                                                <?php endif; ?>
                                                <?php if ($showExpirationTime): ?>
                                                <div class="small <?php echo $expirationClass; ?> mt-1 d-flex align-items-center">
                                                    <i class="bi bi-clock me-1"></i> <?php echo $expirationText; ?>
                                                </div>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php echo htmlspecialchars($notification['message']); ?>
                                                <?php if (isset($data['project_title']) && $notification['type'] !== 'candidature'): ?>
                                                <div class="small text-muted mt-1">
                                                    Project: <?php echo htmlspecialchars($data['project_title']); ?>
                                                </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="btn-group">
                                            <?php if (!$notification['is_read']): ?>
                                            <button type="button" class="btn btn-sm btn-light mark-read-btn" 
                                                    data-notification-id="<?php echo $notification['id']; ?>" 
                                                    title="Mark as read">
                                                <i class="bi bi-check2-all"></i>
                                            </button>
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-sm btn-light delete-notification-btn" 
                                                    data-notification-id="<?php echo $notification['id']; ?>" 
                                                    title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.notification-list {
    max-height: 600px;
    overflow-y: auto;
}

.notification-item {
    padding: 15px;
    transition: background-color 0.2s;
    border-left: 3px solid transparent;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-item.unread-notification {
    background-color: rgba(var(--primary-rgb), 0.04);
    border-left-color: var(--primary);
}

.notification-item:hover {
    background-color: rgba(0, 0, 0, 0.03);
}

.notification-icon {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background-color: rgba(0, 0, 0, 0.05);
}

.notification-date {
    font-size: 0.8rem;
}

.notification-message {
    line-height: 1.4;
}

.badge {
    padding: 0.4em 0.6em;
    font-weight: 500;
    letter-spacing: 0.01em;
}

/* Dark mode styles */
[data-bs-theme="dark"] .notification-item.unread-notification {
    background-color: rgba(255, 255, 255, 0.05);
}

[data-bs-theme="dark"] .notification-icon {
    background-color: rgba(255, 255, 255, 0.1);
}

[data-bs-theme="dark"] .notification-item {
    border-bottom-color: rgba(255, 255, 255, 0.05);
}

/* Style pour le filtre actif */
.dropdown-item.active {
    background-color: var(--primary);
    color: white;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gérer le clic sur "Marquer comme lu"
    const markReadBtns = document.querySelectorAll('.mark-read-btn');
    markReadBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const notificationId = this.dataset.notificationId;
            markAsRead(notificationId);
        });
    });
    
    // Gérer le clic sur "Supprimer"
    const deleteBtns = document.querySelectorAll('.delete-notification-btn');
    deleteBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const notificationId = this.dataset.notificationId;
            deleteNotification(notificationId);
        });
    });
    
    // Gérer le clic sur "Tout marquer comme lu"
    const markAllBtn = document.getElementById('markAllAsReadBtn');
    if (markAllBtn) {
        markAllBtn.addEventListener('click', function(e) {
            e.preventDefault();
            markAllAsRead();
        });
    }
    
    // Gérer le clic sur une notification
    const notificationItems = document.querySelectorAll('.notification-item');
    notificationItems.forEach(item => {
        item.addEventListener('click', function(e) {
            const notificationId = this.dataset.notificationId;
            const isRead = !this.classList.contains('unread-notification');
            
            // Ne rien faire si on a cliqué sur un bouton
            if (e.target.closest('.btn-group')) {
                return;
            }
            
            // Si non lu, marquer comme lu
            if (!isRead) {
                markAsRead(notificationId);
            }
            
            // Redirection vers la page appropriée
            // Sera implémenté via l'AJAX response
        });
    });
    
    // Mark notification as read
    function markAsRead(notificationId) {
        fetch('index.php?page=notifications&action=mark_read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'notification_id=' + notificationId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update notification in UI
                const notification = document.querySelector(`.notification-item[data-notification-id="${notificationId}"]`);
                if (notification) {
                    notification.classList.remove('unread-notification');
                    const readBtn = notification.querySelector('.mark-read-btn');
                    if (readBtn) readBtn.remove();
                }
                
                // Update unread counter
                const unreadCounter = document.getElementById('unreadCounter');
                if (unreadCounter && data.unread_count !== undefined) {
                    unreadCounter.textContent = data.unread_count;
                    if (data.unread_count === 0) {
                        unreadCounter.classList.add('d-none');
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Display error message
            showToast('An error occurred while marking the notification as read', 'danger');
        });
    }
    
    // Delete notification
    function deleteNotification(notificationId) {
        if (!confirm('Are you sure you want to delete this notification?')) {
            return;
        }
        
        fetch('index.php?page=notifications&action=delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'notification_id=' + notificationId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove notification from UI
                const notification = document.querySelector(`.notification-item[data-notification-id="${notificationId}"]`);
                if (notification) {
                    notification.remove();
                }
                
                // If no notifications are left, reload to show the empty state
                if (document.querySelectorAll('.notification-item').length === 0) {
                    location.reload(); // Reload to display "No notifications" message
                }
            } else {
                // Display error message
                showToast(data.message || 'Failed to delete notification', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while deleting the notification', 'danger');
        });
    }
    
    // Mark all notifications as read
    function markAllAsRead() {
        fetch('index.php?page=notifications&action=mark_all_read', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update UI
                document.querySelectorAll('.notification-item').forEach(notification => {
                    notification.classList.remove('unread-notification');
                    const readBtn = notification.querySelector('.mark-read-btn');
                    if (readBtn) readBtn.remove();
                });
                
                // Update unread counter
                const unreadCounter = document.getElementById('unreadCounter');
                if (unreadCounter) {
                    unreadCounter.textContent = '0';
                    unreadCounter.classList.add('d-none');
                }
                
                // Show success message
                showToast('All notifications marked as read', 'success');
            } else {
                // Display error message
                showToast(data.message || 'Failed to mark all notifications as read', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred', 'danger');
        });
    }
});
</script> 