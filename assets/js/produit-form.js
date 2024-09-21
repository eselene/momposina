// A debugger
document.addEventListener('DOMContentLoaded', function () {
    const imageInput = document.querySelector('#' + document.querySelector('[name="photo1"]').id); // Utilise l'ID généré dynamiquement
    const imagePreview = document.querySelector('#image-preview img');
    const saveButton = document.querySelector('.btn-primary');
    const backToListButton = document.querySelector('.btn-secondary');
    const formElements = document.querySelectorAll('input[type="text"], textarea');

    if (imageInput) {
        imageInput.addEventListener('change', function (event) {
            if (event.target.files && event.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(event.target.files[0]);
            } else {
                imagePreview.style.display = 'none';
            }
        });
    }

    let formChanged = false;

    formElements.forEach(element => {
        element.addEventListener('input', () => {
            formChanged = true;
            saveButton.disabled = false;
            backToListButton.disabled = false;
        });
    });

    saveButton.addEventListener('click', (event) => {
        formChanged = false;
    });
    backToListButton.addEventListener('click', (event) => {
        if (formChanged) {
            event.preventDefault();
            if (confirm('Vous n\'avez pas sauvegardé votre travail. Êtes-vous sûr de vouloir quitter ?')) {
                window.location.href = backToListButton.href;
            }
        }
    });

});
