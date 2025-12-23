(function () {

    function showError(message) {
        if (window.Swal) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: message,
                confirmButtonColor: '#6fbf9c'
            });
        } else {
            alert(message);
        }
    }

    function validateEmail(email) {
        return /^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email);
    }

    function validatePhone(phone) {
        return /^[0-9]{10}$/.test(phone);
    }

    /*  LOGIN  */
    var loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {

            var login = document.getElementById('login').value.trim();
            var password = document.getElementById('password').value;

            if (!login || !password) {
                e.preventDefault();
                showError('Email / Mobile and password are required.');
                return;
            }

            if (login.includes('@') && !validateEmail(login)) {
                e.preventDefault();
                showError('Please enter a valid email address.');
                return;
            }

            if (password.length < 6) {
                e.preventDefault();
                showError('Password must be at least 6 characters.');
                return;
            }
        });
    }

    /*  REGISTER  */
    var registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {

            var name     = document.getElementById('full_name').value.trim();
            var email    = document.getElementById('email').value.trim();
            var mobile   = document.getElementById('mobile').value.trim();
            var password = document.getElementById('password').value;
            var role     = document.getElementById('role').value;

            if (!name || !email || !mobile || !password || !role) {
                e.preventDefault();
                showError('All fields are required.');
                return;
            }

            if (name.length < 3) {
                e.preventDefault();
                showError('Full name must be at least 3 characters.');
                return;
            }

            if (!validateEmail(email)) {
                e.preventDefault();
                showError('Please enter a valid email address.');
                return;
            }

            if (!validatePhone(mobile)) {
                e.preventDefault();
                showError('Mobile number must be exactly 10 digits.');
                return;
            }

            if (password.length < 6) {
                e.preventDefault();
                showError('Password must be at least 6 characters.');
                return;
            }
        });
    }

    /*  EMAIL AVAILABILITY CHECK  */
    var regEmailInput = document.getElementById('email');
    if (regEmailInput) {
        var emailTimer = null;

        regEmailInput.addEventListener('input', function () {
            clearTimeout(emailTimer);
            var val = this.value.trim();

            if (!validateEmail(val)) return;

            emailTimer = setTimeout(function () {
                fetch('/auth/check-email', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email: val })
                })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.exists) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Email Already Used',
                            text: 'This email is already registered.',
                            confirmButtonColor: '#6fbf9c'
                        });
                    }
                })
                .catch(function () {});
            }, 600);
        });
    }

})();
