document.addEventListener('DOMContentLoaded', function () {

    const doctorSelect = document.getElementById('doctor_id');
    const slotsDiv = document.getElementById('slots');

    if (!doctorSelect || !slotsDiv) {
        console.error('Doctor select or slots container not found');
        return;
    }

    doctorSelect.addEventListener('change', function () {
        const doctorId = this.value;

        if (!doctorId) {
            slotsDiv.innerHTML = '<p class="empty">No doctor selected</p>';
            return;
        }

        slotsDiv.innerHTML = '<p class="empty">Loading slots...</p>';

        fetch('/patient/appointments/availability', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'doctor_id=' + encodeURIComponent(doctorId)
        })
        .then(res => {
            if (!res.ok) {
                throw new Error('Network response was not OK');
            }
            return res.json();
        })
        .then(slots => {
            console.log('Slots response:', slots); // 🔍 DEBUG

            if (!Array.isArray(slots) || slots.length === 0) {
                slotsDiv.innerHTML = '<p class="empty">No available slots</p>';
                return;
            }

            slotsDiv.innerHTML = '';

            slots.forEach(slot => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'slot-btn';

                const start = new Date(slot.start_utc.replace(' ', 'T'));
                const end   = new Date(slot.end_utc.replace(' ', 'T'));

                btn.innerText =
                    start.toLocaleString() +
                    ' - ' +
                    end.toLocaleTimeString();

                btn.addEventListener('click', () => bookSlot(slot.id));

                slotsDiv.appendChild(btn);
            });
        })
        .catch(err => {
            console.error(err);
            slotsDiv.innerHTML =
                '<p class="empty">Failed to load slots</p>';
        });
    });
});

/* =======================
   BOOK SLOT
   ======================= */
function bookSlot(slotId) {
    if (!slotId) return;

    if (!confirm('Confirm appointment booking?')) return;

    fetch('/patient/appointments/book', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'slot_id=' + encodeURIComponent(slotId)
    })
    .then(res => {
        if (!res.ok) {
            throw new Error('Booking failed');
        }
        return res.json();
    })
    .then(r => {
        console.log('Booking response:', r); // 🔍 DEBUG
        alert(r.message);

        if (r.status === 'success') {
            // reload slots for same doctor
            document.getElementById('doctor_id')
                .dispatchEvent(new Event('change'));
        }
    })
    .catch(err => {
        console.error(err);
        alert('Something went wrong while booking');
    });
}
