<?php $pageTitle = 'Détails de l\'événement'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?php echo htmlspecialchars($event['title']); ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="events.php" class="btn btn-sm btn-outline-secondary me-2">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
        <a href="edit-event.php?id=<?php echo $event['id']; ?>" class="btn btn-sm btn-primary">
            <i class="bi bi-pencil"></i> Modifier
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <img src="/<?php echo htmlspecialchars($event['image']); ?>" 
                 class="card-img-top" 
                 alt="<?php echo htmlspecialchars($event['title']); ?>">
            <div class="card-body">
                <div class="mb-3">
                    <h5 class="card-subtitle mb-2 text-muted">Informations</h5>
                    <p><strong>Date:</strong> <?php echo date('d/m/Y', strtotime($event['date'])); ?></p>
                    <p><strong>Lieu:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                </div>
                
                <div class="mb-3">
                    <h5 class="card-subtitle mb-2 text-muted">Description</h5>
                    <p class="card-text"><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
                </div>
            </div>
        </div>

        <!-- Liste des participants -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Participants</h5>
                    <span class="badge bg-primary"><?php echo count($participants); ?> participants</span>
                </div>

                <?php if (!empty($participants)): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Date d'inscription</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($participants as $participant): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($participant['name']); ?></td>
                                    <td><?php echo htmlspecialchars($participant['email']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($participant['registration_date'])); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="mailto:<?php echo htmlspecialchars($participant['email']); ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-envelope"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteParticipantModal<?php echo $participant['id']; ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Modal de confirmation de suppression -->
                                        <div class="modal fade" id="deleteParticipantModal<?php echo $participant['id']; ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Confirmer la suppression</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Êtes-vous sûr de vouloir supprimer l'inscription de <?php echo htmlspecialchars($participant['name']); ?> ?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                        <form action="delete-participant.php" method="POST" class="d-inline">
                                                            <input type="hidden" name="id" value="<?php echo $participant['id']; ?>">
                                                            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                                            <button type="submit" class="btn btn-danger">Supprimer</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0">Aucun participant inscrit pour le moment.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>