document.addEventListener('DOMContentLoaded', () => {

    const openBtn   = document.getElementById('openForm');
    const form      = document.getElementById('availabilityForm');
    const cancelBtn = document.getElementById('cancelForm');

    if (!openBtn || !form || !cancelBtn) {
        console.error('Availability form elements missing');
        return;
    }

    /* =======================
       OPEN FORM
       ======================= */
    openBtn.addEventListener('click', () => {
        form.style.display = 'block';
    });

    /* =======================
       CANCEL FORM
       ======================= */
    cancelBtn.addEventListener('click', () => {
        form.reset();
        form.style.display = 'none';
    });

    /* =======================
       SUBMIT FORM
       ======================= */
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const date  = document.getElementById('date').value;
        const start = document.getElementById('start').value;
        const end   = document.getElementById('end').value;
        const type  = document.getElementById('type').value;

        if (!date || !start || !end) {
            alert('All fields required');
            return;
        }

        const startLocal = new Date(`${date}T${start}`);
        const endLocal   = new Date(`${date}T${end}`);

        if (startLocal >= endLocal) {
            alert('End time must be after start time');
            return;
        }

        if (startLocal < new Date()) {
            alert('Past time not allowed');
            return;
        }

        const startUtc = startLocal.toISOString().slice(0, 19).replace('T', ' ');
        const endUtc   = endLocal.toISOString().slice(0, 19).replace('T', ' ');

        fetch('/doctor/availability/add', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                start_utc: startUtc,
                end_utc: endUtc,
                status: type
            })
        })
        .then(res => res.json())
        .then(result => {
            alert(result.message);
            if (result.status === 'success') {
                form.reset();
                form.style.display = 'none';
                location.reload();
            }
        })
        .catch(err => {
            console.error(err);
            alert('Something went wrong');
        });
    });

});
