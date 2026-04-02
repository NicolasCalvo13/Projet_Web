(function () {
    'use strict';

    function getFieldLabel(field) {
        const formGroup = field.closest('.form-group');
        const label = formGroup ? formGroup.querySelector('label[for="' + field.id + '"]') : null;

        if (label) {
            return label.textContent.trim().replace(/\s*\*+\s*$/, '');
        }

        if (field.getAttribute('aria-label')) {
            return field.getAttribute('aria-label').trim();
        }

        if (field.name) {
            return field.name.replace(/_/g, ' ').trim();
        }

        return 'Ce champ';
    }

    function getErrorContainer(field) {
        let error = field.parentElement.querySelector('.form-error');

        if (!error) {
            error = document.createElement('div');
            error.className = 'form-error';
            field.parentElement.appendChild(error);
        }

        return error;
    }

    function setFieldState(field, isValid, message) {
        const error = getErrorContainer(field);

        if (isValid) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
            error.textContent = '';
            error.style.display = 'none';
            field.setCustomValidity('');
        } else {
            field.classList.remove('is-valid');
            field.classList.add('is-invalid');
            error.textContent = message;
            error.style.display = 'block';
            field.setCustomValidity(message);
        }
    }

    function getCustomMessage(field) {
        const label = getFieldLabel(field);
        const validity = field.validity;

        if (validity.valueMissing) {
            return label + ' est obligatoire.';
        }

        if (validity.typeMismatch) {
            if (field.type === 'email') {
                return 'Veuillez saisir une adresse email valide.';
            }
            if (field.type === 'url') {
                return 'Veuillez saisir une URL valide.';
            }
            return 'Le format de ' + label.toLowerCase() + ' est invalide.';
        }

        if (validity.tooShort) {
            return label + ' doit contenir au moins ' + field.minLength + ' caractères.';
        }

        if (validity.tooLong) {
            return label + ' doit contenir au maximum ' + field.maxLength + ' caractères.';
        }

        if (validity.patternMismatch) {
            return 'Le format de ' + label.toLowerCase() + ' est invalide.';
        }

        if (validity.rangeUnderflow) {
            return label + ' doit être supérieur ou égal à ' + field.min + '.';
        }

        if (validity.rangeOverflow) {
            return label + ' doit être inférieur ou égal à ' + field.max + '.';
        }

        if (validity.stepMismatch) {
            return label + ' contient une valeur invalide.';
        }

        if (validity.badInput) {
            return label + ' contient une valeur invalide.';
        }

        return '';
    }

    function validateField(field) {
        if (
            field.disabled ||
            field.type === 'hidden' ||
            field.type === 'submit' ||
            field.type === 'button' ||
            field.type === 'reset'
        ) {
            return true;
        }

        field.setCustomValidity('');

        if (field.checkValidity()) {
            setFieldState(field, true, '');
            return true;
        }

        const message = getCustomMessage(field) || 'Champ invalide.';
        setFieldState(field, false, message);
        return false;
    }

    function setupForm(form) {
        form.setAttribute('novalidate', 'novalidate');

        const fields = form.querySelectorAll('input, textarea, select');

        fields.forEach((field) => {
            field.addEventListener('blur', function () {
                validateField(field);
            });

            field.addEventListener('input', function () {
                if (field.classList.contains('is-invalid')) {
                    validateField(field);
                } else {
                    const error = field.parentElement.querySelector('.form-error');
                    field.setCustomValidity('');
                    if (error) {
                        error.textContent = '';
                        error.style.display = 'none';
                    }
                    field.classList.remove('is-invalid');
                }
            });

            field.addEventListener('change', function () {
                validateField(field);
            });
        });

        form.addEventListener('submit', function (event) {
            let firstInvalidField = null;
            let isFormValid = true;

            fields.forEach((field) => {
                const isValid = validateField(field);
                if (!isValid && !firstInvalidField) {
                    firstInvalidField = field;
                }
                if (!isValid) {
                    isFormValid = false;
                }
            });

            if (!isFormValid) {
                event.preventDefault();
                if (firstInvalidField) {
                    firstInvalidField.focus();
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('form');
        forms.forEach(setupForm);
    });
})();