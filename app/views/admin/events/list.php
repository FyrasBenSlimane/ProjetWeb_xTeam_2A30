<?php $pageTitle = 'Gestion des événements'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestion des événements</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="create-event.php" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg"></i> Nouvel événement
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Titre</th>
                <th>Date</th>
                <th>Lieu</th>
                <th>Participants</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $event): ?>
            <tr>
                <td><?php echo htmlspecialchars($event['id']); ?></td>
                <td>
                    <?php if ($event['image']): ?>
                        <img src="/<?php echo htmlspecialchars($event['image']); ?>" 
                             alt="<?php echo htmlspecialchars($event['title']); ?>"
                             class="img-thumbnail" style="max-width: 50px;">
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($event['title']); ?></td>
                <td><?php echo date('d/m/Y', strtotime($event['date'])); ?></td>
                <td><?php echo htmlspecialchars($event['location']); ?></td>
                <td>
                    <span class="badge bg-info">
                        <?php echo $event['participant_count']; ?>
                    </span>
                </td>
                <td>
                    <div class="btn-group">
                        <a href="edit-event.php?id=<?php echo $event['id']; ?>" 
                           class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="view-event.php?id=<?php echo $event['id']; ?>" 
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                        <button type="button" 
                                class="btn btn-sm btn-outline-danger" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteModal<?php echo $event['id']; ?>">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>

                    <!-- Modal de confirmation de suppression -->
                    <div class="modal fade" id="deleteModal<?php echo $event['id']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Confirmer la suppression</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Êtes-vous sûr de vouloir supprimer l'événement "<?php echo htmlspecialchars($event['title']); ?>" ?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <form action="delete-event.php" method="POST" class="d-inline">
                                        <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
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