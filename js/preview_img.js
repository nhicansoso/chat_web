const avatarInput = document.getElementById('avatar');
const avatarImg = document.getElementById('avatarImg');
const avatarText = document.getElementById('avatarText');

avatarInput.addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function () {
            avatarImg.src = reader.result;
            avatarImg.style.display = 'block';
            avatarText.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});