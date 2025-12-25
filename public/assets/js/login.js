document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('loginForm');
    const errorBox = document.getElementById('loginError');

    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        errorBox.style.display = 'none';
        errorBox.innerText = '';

        const login = form.login.value.trim();
        const password = form.password.value.trim();

        // REGEX
        const emailRegex = /^[a-z0-9._-]{1,25}@([a-z0-9-]+\.)+[a-z]{2,4}$/;
        const mobileRegex = /^\d{10}$/;

        if (!login || !password) {
            showError('All fields are required');
            return;
        }

        if (!emailRegex.test(login) && !mobileRegex.test(login)) {
            showError('Enter a valid email or 10-digit mobile number');
            return;
        }

        if (password.length < 8) {
            showError('Password must be at least 8 characters');
            return;
        }

        fetch(window.BASE_URL + '/auth/login', {
            method: 'POST',
            body: new FormData(form)
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Login Successful',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = window.BASE_URL + data.redirect;
                });
            } else {
                showError(data.message);
            }
        })
        .catch(() => {
            showError('Something went wrong. Please try again.');
        });
    });

    function showError(msg) {
        errorBox.style.display = 'block';
        errorBox.innerText = msg;
    }
});
