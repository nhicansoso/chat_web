const form = document.querySelector(".register_content form"),
    continueBtn = form.querySelector(".register_button"),
    errorText = form.querySelector(".error_text");
const avatarInput = document.getElementById('avatar');
const avatarImg = document.getElementById('avatarImg');
const avatarText = document.getElementById('avatarText');
form.onsubmit = (e) => {
    e.preventDefault();
}

continueBtn.onclick = () => {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/register_config.php", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let data = xhr.response;
                if (data.trim() === "Registered successfully!") {
                    location.href = "index.php";
                } else {
                    errorText.style.display = "block";
                    errorText.textContent = data;
                }
            }
        }
    }
    let formData = new FormData(form);
    xhr.send(formData);
}

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