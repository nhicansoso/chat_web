const form = document.querySelector(".login_form"),
    loginBtn = form.querySelector(".login_button"),
    errorText = form.querySelector(".error_text");
const toggle = document.getElementById('togglePassword');
const password = document.getElementById('password');

form.onsubmit = (e) => {
    e.preventDefault();
};

// Xử lý khi nhấn nút icon hide
if (toggle && password) {
    toggle.addEventListener('click', () => {
        const type = password.type === 'password' ? 'text' : 'password';
        password.type = type;

        toggle.classList.toggle('ri-eye-line');
        toggle.classList.toggle('ri-eye-off-line');
    });
}

// Xử lý khi nhấn nút đăng nhập
loginBtn.onclick = () => {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/login_config.php", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            let data = xhr.response.trim();

            if (data === "success") {
                window.location.href = "users.php";
            } else {
                errorText.style.display = "block";
                errorText.textContent = data;
            }
        }
    };
    const formData = new FormData(form);
    xhr.send(formData);
};

