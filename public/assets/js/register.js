document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('registerForm');
    const errorBox = document.getElementById('registerError');
    const mobileInput = document.getElementById('mobile');

    if (!form) return;

    // INPUT
    if (mobileInput) {
        mobileInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '');
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
        });
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        errorBox.style.display = 'none';
        errorBox.innerText = '';

        const fullName = form.full_name.value.trim();
        const email = form.email.value.trim();
        const mobile = form.mobile.value.trim();
        const password = form.password.value.trim();
        const role = form.role.value;

        // REGEX
        const nameRegex = /^[A-Za-z ]{3,50}$/;
        const emailRegex = /^[a-z0-9._-]{1,25}@([a-z0-9-]+\.)+[a-z]{2,4}$/;
        const mobileRegex = /^\d{10}$/;
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/;

        if (!fullName || !email || !mobile || !password || !role) {
            showError('All fields are required');
            return;
        }

        if (!nameRegex.test(fullName)) {
            showError('Name must be 3–50 letters only');
            return;
        }

        if (!emailRegex.test(email)) {
            showError('Invalid email address');
            return;
        }

        if (!mobileRegex.test(mobile)) {
            showError('Mobile number must be exactly 10 digits');
            return;
        }

        if (!passwordRegex.test(password)) {
            showError(
                'Password must contain at least one uppercase, one lowercase, one number and one special character'
            );
            return;
        }

        fetch(window.BASE_URL + '/auth/register', {
            method: 'POST',
            body: new FormData(form)
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Registration Successful',
                    text: 'Redirecting to login...',
                    timer: 2500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = window.BASE_URL + '/auth/login';
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
