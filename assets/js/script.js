// Form validation for project creation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('newProjectForm');
    if (!form) return;

    const fields = form.querySelectorAll('input[required], textarea[required], select[required]');
    const submitButton = form.querySelector('button[type="submit"]');
    let hasAttemptedSubmit = false; // Pour suivre la première tentative

    function clearErrors() {
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.style.display = 'none');
        form.querySelectorAll('.priority-options').forEach(el => el.classList.remove('unfilled'));
    }

    function validateField(field) {
        let isValid = true;
        field.classList.remove('is-invalid');

        // Required field validation
        if (field.hasAttribute('required') && !field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        }

        // Specific validations based on field type
        switch(field.id) {
            case 'title':
                if (field.value.trim().length < 3) {
                    field.classList.add('is-invalid');
                    isValid = false;
                }
                break;
            case 'description':
                if (field.value.trim().length < 10) {
                    field.classList.add('is-invalid');
                    isValid = false;
                }
                break;
            case 'budget':
                const budget = parseFloat(field.value);
                if (isNaN(budget) || budget <= 0) {
                    field.classList.add('is-invalid');
                    isValid = false;
                }
                break;
            case 'end_date':
                const startDate = new Date(document.getElementById('start_date').value);
                const endDate = new Date(field.value);
                if (startDate && endDate && endDate <= startDate) {
                    field.classList.add('is-invalid');
                    isValid = false;
                }
                break;
        }

        // Show/hide error message
        const errorElement = document.getElementById(field.id + 'Error');
        if (errorElement) {
            errorElement.style.display = isValid ? 'none' : 'block';
        }

        return isValid;
    }

    function validatePrioritySelection() {
        const priorityGroup = form.querySelector('.priority-options');
        const selectedPriority = form.querySelector('input[name="priority"]:checked');
        
        if (!selectedPriority) {
            priorityGroup.classList.add('unfilled');
            return false;
        }
        
        priorityGroup.classList.remove('unfilled');
        return true;
    }

    // Validation en temps réel seulement après la première tentative
    fields.forEach(field => {
        field.addEventListener('input', function() {
            if (hasAttemptedSubmit) {
                validateField(this);
            }
        });

        field.addEventListener('blur', function() {
            if (hasAttemptedSubmit) {
                validateField(this);
            }
        });
    });

    // Validation des boutons radio de priorité
    const priorityRadios = form.querySelectorAll('input[name="priority"]');
    priorityRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (hasAttemptedSubmit) {
                validatePrioritySelection();
            }
        });
    });

    // Soumission du formulaire
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        hasAttemptedSubmit = true; // Marquer qu'une tentative a été faite
        
        clearErrors();
        let isValid = true;
        
        // Valider tous les champs
        fields.forEach(field => {
            if (!validateField(field)) {
                isValid = false;
            }
        });

        // Valider la sélection de priorité
        if (!validatePrioritySelection()) {
            isValid = false;
        }

        if (isValid) {
            this.submit();
        } else {
            // Focus sur le premier champ avec erreur
            const firstError = form.querySelector('.is-invalid') || form.querySelector('.unfilled');
            if (firstError) {
                if (firstError.tagName === 'INPUT') {
                    firstError.focus();
                } else {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        }
    });

    // Réinitialisation du formulaire
    form.addEventListener('reset', function() {
        clearErrors();
        hasAttemptedSubmit = false;
    });
});