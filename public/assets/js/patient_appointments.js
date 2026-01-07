console.log('patient_appointments.js loaded');

document.addEventListener('DOMContentLoaded', function () {

    const doctorSelect = document.getElementById('doctor_id');
    const dateInput   = document.getElementById('appointment_date'); // ✅ ADDED
    const searchBtn   = document.getElementById('searchSlots');
    const slotsDiv    = document.getElementById('slots');

    if (!doctorSelect || !searchBtn || !slotsDiv || !dateInput) {
        console.error('Required elements missing');
        return;
    }

    searchBtn.addEventListener('click', function () {

        const doctorId = doctorSelect.value;
        const date     = dateInput.value; // ✅ ADDED

        if (!doctorId || !date) {
            alert('Please select doctor and date');
            return;
        }

        slotsDiv.innerHTML = '<p class="empty">Loading slots...</p>';

        fetch(window.BASE_URL + '/patient/appointments/availability', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body:
                'doctor_id=' + encodeURIComponent(doctorId) +
                '&date=' + encodeURIComponent(date) // ✅ ADDED
        })
        .then(res => res.json())
        .then(slots => {
            console.log('Slots:', slots);

            if (!Array.isArray(slots) || slots.length === 0) {
                slotsDiv.innerHTML =
                    '<p class="empty">No available slots</p>';
                return;
            }

            slotsDiv.innerHTML = '';

            slots.forEach(slot => {
                const btn = document.createElement('button');
                btn.className = 'slot-btn';
                btn.type = 'button';

                // UTC → LOCAL CONVERSION
                const start = new Date(slot.start_utc.replace(' ', 'T') + 'Z');
                const end   = new Date(slot.end_utc.replace(' ', 'T') + 'Z');

                const dateStr = start.toLocaleDateString(undefined, {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });

                const timeStr =
                    start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) +
                    ' - ' +
                    end.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                btn.textContent = `${dateStr}, ${timeStr}`;
                btn.onclick = () => bookSlot(slot.id);

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


// BOOK SLOT (UNCHANGED)
function bookSlot(slotId) {
    fetch(window.BASE_URL + '/patient/appointments/book', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'slot_id=' + encodeURIComponent(slotId)
    })
    .then(res => res.json())
    .then(data => {
        console.log('Booking response:', data);
        alert(data.message);

        if (data.status === 'success') {
            document.getElementById('searchSlots').click();
        }
    })
    .catch(err => {
        console.error('Booking error:', err);
        alert('Booking failed');
    });
}
