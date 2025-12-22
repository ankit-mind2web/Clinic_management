document.addEventListener('DOMContentLoaded', function () {

    const featureCards = document.querySelectorAll('.feature-card');
    const doctorCards  = document.querySelectorAll('.doctor-card');
    const showAllBtn   = document.getElementById('showAllDoctors');
    const doctorsSection = document.getElementById('doctors');

    featureCards.forEach(card => {
        card.addEventListener('click', function () {

            const specialization = this.dataset.specialization;

            featureCards.forEach(c => c.classList.remove('active'));
            this.classList.add('active');

            doctorCards.forEach(doc => {
                doc.style.display =
                    doc.dataset.specialization === specialization
                        ? 'flex'
                        : 'none';
            });

            doctorsSection.scrollIntoView({ behavior: 'smooth' });
        });
    });

    /* RESET / ALL DOCTORS */
    if (showAllBtn) {
        showAllBtn.addEventListener('click', function () {

            featureCards.forEach(c => c.classList.remove('active'));

            doctorCards.forEach(doc => {
                doc.style.display = 'flex';
            });

            doctorsSection.scrollIntoView({ behavior: 'smooth' });
        });
    }

});
