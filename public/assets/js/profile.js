document.getElementById('profileForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);

    fetch('/patient/profile', {
        method: 'POST',
        body: formData
    })
        .then(res => {
            if (!res.ok) throw new Error('Network error');
            return res.text();
        })
        .then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Profile Saved',
                text: 'Your profile has been updated successfully',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                window.location.href = '/patient/profile';
            });
        })
        .catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong. Please try again.'
            });
        });
});
const verifyEmailForm = document.getElementById('verifyEmailForm');
if (verifyEmailForm) {
    verifyEmailForm.addEventListener('submit', function (e) {
        const btn = document.getElementById('verifyBtn');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = 'Sending... <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        }
    });
}
