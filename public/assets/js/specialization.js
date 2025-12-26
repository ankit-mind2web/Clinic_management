document.addEventListener('DOMContentLoaded', function () {

    // ADD SPECIALIZATION

    const addForm = document.getElementById('specializationForm');
    if (addForm) {
        addForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(addForm);

            fetch('/admin/specializations/create', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'error') {
                        Swal.fire('Error', data.message, 'error');
                        return;
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '/admin/specializations';
                    });
                })
                .catch(() => {
                    Swal.fire('Error', 'Something went wrong', 'error');
                });
        });
    }


    //   EDIT SPECIALIZATION

    const editForm = document.getElementById('editSpecializationForm');
    if (editForm) {
        editForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(editForm);

            fetch('/admin/specializations/edit', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'error') {
                        Swal.fire('Error', data.message, 'error');
                        return;
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Updated',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '/admin/specializations';
                    });
                })
                .catch(() => {
                    Swal.fire('Error', 'Something went wrong', 'error');
                });
        });
    }


    //   DELETE SPECIALIZATION

    document.querySelectorAll('.deleteBtn').forEach(btn => {

        btn.addEventListener('click', function () {

            const id = this.dataset.id;

            Swal.fire({
                title: 'Are you sure?',
                text: 'This specialization will be deleted',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Yes, delete'
            }).then(result => {

                if (!result.isConfirmed) return;

                fetch('/admin/specializations/delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id=' + encodeURIComponent(id)
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'error') {
                            Swal.fire('Error', data.message, 'error');
                            return;
                        }

                        const row = document.getElementById('row-' + id);
                        if (row) row.remove();

                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    })
                    .catch(() => {
                        Swal.fire('Error', 'Something went wrong', 'error');
                    });
            });
        });

    });

    // SEARCH SPECIALIZATION (LIVE)
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.getElementById('specializationTable');

    if (searchInput && tableBody) {
        searchInput.addEventListener('keyup', function () {
            const keyword = this.value.toLowerCase();

            tableBody.querySelectorAll('tr').forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(keyword) ? '' : 'none';
            });
        });
    }

    const clearBtn = document.getElementById('clearSearch');

    if (searchInput && clearBtn && tableBody) {

        searchInput.addEventListener('input', function () {
            clearBtn.style.display = this.value ? 'block' : 'none';
        });

        clearBtn.addEventListener('click', function () {
            searchInput.value = '';
            clearBtn.style.display = 'none';

            tableBody.querySelectorAll('tr').forEach(row => {
                row.style.display = '';
            });

            searchInput.focus();
        });
    }

});
