function toggleCvUpload() {
    const existant = document.getElementById('cv-existant');
    const upload = document.getElementById('cv-upload');
    const hidden = existant.querySelector('input[type="hidden"]');

    if (upload.style.display === 'none') {
        upload.style.display = 'block';
        hidden.disabled = true;
    } else {
        upload.style.display = 'none';
        hidden.disabled = false;
    }
}
