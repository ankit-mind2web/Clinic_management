document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('profileForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch('/doctor/profile', {
            method: 'POST',
            body: formData
        })
        .then(res => {
            if (!res.ok) throw new Error('Failed');
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
                window.location.href = '/doctor/profile';
            });
        })
        .catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong'
            });
        });
    });
});
