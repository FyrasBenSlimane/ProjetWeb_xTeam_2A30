<?php 
$pageTitle = isset($event) ? 'Modifier l\'événement' : 'Nouvel événement';
$formAction = isset($event) ? "edit-event.php?id={$event['id']}" : "create-event.php";
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?php echo $pageTitle; ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="events.php" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <form action="<?php echo $formAction; ?>" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="title" class="form-label">Titre de l'événement</label>
                <input type="text" 
                       class="form-control" 
                       id="title" 
                       name="title" 
                       value="<?php echo isset($event) ? htmlspecialchars($event['title']) : ''; ?>"
                       required>
                <div class="invalid-feedback">
                    Veuillez entrer un titre pour l'événement.
                </div>
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" 
                       class="form-control" 
                       id="date" 
                       name="date" 
                       value="<?php echo isset($event) ? date('Y-m-d', strtotime($event['date'])) : ''; ?>"
                       required>
                <div class="invalid-feedback">
                    Veuillez sélectionner une date.
                </div>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Lieu</label>
                <input type="text" 
                       class="form-control" 
                       id="location" 
                       name="location" 
                       value="<?php echo isset($event) ? htmlspecialchars($event['location']) : ''; ?>"
                       required>
                <div class="invalid-feedback">
                    Veuillez entrer un lieu pour l'événement.
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" 
                          id="description" 
                          name="description" 
                          rows="5" 
                          required><?php echo isset($event) ? htmlspecialchars($event['description']) : ''; ?></textarea>
                <div class="invalid-feedback">
                    Veuillez entrer une description pour l'événement.
                </div>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <?php if (isset($event) && $event['image']): ?>
                    <div class="mb-2">
                        <img src="/<?php echo htmlspecialchars($event['image']); ?>" 
                             alt="Image actuelle" 
                             class="img-thumbnail" 
                             style="max-width: 200px;">
                    </div>
                <?php endif; ?>
                <input type="file" 
                       class="form-control" 
                       id="image" 
                       name="image" 
                       accept="image/*"
                       <?php echo !isset($event) ? 'required' : ''; ?>>
                <div class="invalid-feedback">
                    Veuillez sélectionner une image pour l'événement.
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <?php echo isset($event) ? 'Mettre à jour' : 'Créer l\'événement'; ?>
            </button>
        </form>
    </div>
</div>

<script>
// Validation des formulaires Bootstrap
(function() {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>