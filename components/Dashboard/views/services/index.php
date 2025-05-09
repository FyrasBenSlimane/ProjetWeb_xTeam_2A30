<?php
/**
 * Services dashboard view
 */

// Get services data
$services = isset($serviceModel) ? $serviceModel->getUserServices() : [];
?>

<!-- Page Header -->
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="welcome-title">Mes Services</h2>
            <p class="welcome-subtitle">Gérez vos services et prestations</p>
        </div>
        <a href="?page=services&action=create" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nouveau Service
        </a>
    </div>
</div>

<!-- Services Grid -->
<div class="row g-4">
    <?php if (empty($services)): ?>
        <div class="col-12">
            <div class="dashboard-table-section text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-briefcase display-4 text-muted"></i>
                </div>
                <h4>Aucun service trouvé</h4>
                <p class="text-muted mb-4">Vous n'avez pas encore créé de services. Commencez par en créer un!</p>
                <a href="?page=services&action=create" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i> Créer un Service
                </a>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($services as $service): ?>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 service-card">
                <div class="card-img-top position-relative">
                    <?php if (!empty($service['image'])): ?>
                        <img src="<?php echo htmlspecialchars($service['image']); ?>" 
                             alt="<?php echo htmlspecialchars($service['title']); ?>"
                             class="img-fluid rounded-top">
                    <?php else: ?>
                        <div class="service-placeholder-img bg-light d-flex align-items-center justify-content-center p-4">
                            <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="service-status position-absolute top-0 end-0 m-2">
                        <span class="badge bg-<?php 
                            echo match($service['status']) {
                                'active' => 'success',
                                'pending' => 'warning',
                                'inactive' => 'danger',
                                default => 'secondary'
                            };
                        ?>">
                            <?php echo ucfirst($service['status']); ?>
                        </span>
                    </div>
                </div>
                
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($service['title']); ?></h5>
                    <p class="card-text text-muted"><?php echo htmlspecialchars(substr($service['description'], 0, 100)) . '...'; ?></p>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="service-price fw-bold">
                            <?php echo number_format($service['price'], 2, ',', ' '); ?> €
                        </span>
                        <div class="service-category">
                            <span class="badge bg-light text-dark">
                                <?php echo htmlspecialchars($service['category'] ?? 'Non catégorisé'); ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-transparent border-top-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            <a href="?page=services&action=edit&id=<?php echo $service['id']; ?>" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Modifier
                            </a>
                            <button type="button" 
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="deleteService(<?php echo $service['id']; ?>)">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                        </div>
                        <small class="text-muted">
                            Créé le <?php echo date('d/m/Y', strtotime($service['created_at'])); ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteServiceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer ce service ?</p>
                <p class="text-danger"><small>Cette action est irréversible.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Supprimer</button>
            </div>
        </div>
    </div>
</div>

<!-- Service Management Scripts -->
<script>
let serviceIdToDelete = null;
const deleteModal = new bootstrap.Modal(document.getElementById('deleteServiceModal'));

function deleteService(serviceId) {
    serviceIdToDelete = serviceId;
    deleteModal.show();
}

document.getElementById('confirmDelete').addEventListener('click', function() {
    if (serviceIdToDelete) {
        // Send delete request
        fetch(`?page=services&action=delete&id=${serviceIdToDelete}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de la suppression du service');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue');
        });
    }
    deleteModal.hide();
});