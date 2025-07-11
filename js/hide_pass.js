const toggle = document.getElementById('togglePassword');
const password = document.getElementById('password');

if (toggle && password) {
    toggle.addEventListener('click', () => {
        const type = password.type === 'password' ? 'text' : 'password';
        password.type = type;

        toggle.classList.toggle('ri-eye-line');
        toggle.classList.toggle('ri-eye-off-line');
    });
}