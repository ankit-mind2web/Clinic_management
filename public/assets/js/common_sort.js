// common sorting for mutiple pages
document.addEventListener('DOMContentLoaded', function () {

    const sortSelect = document.getElementById('sortSelect');
    const tbody = document.getElementById('tableBody');

    if (!sortSelect || !tbody) return;

    sortSelect.addEventListener('change', function () {

        const rows = Array.from(tbody.querySelectorAll('tr'));
        const sortType = this.value;

        if (!sortType) return;

        rows.sort((a, b) => {

            /* NAME */
            if (sortType === 'name_asc' || sortType === 'name_desc') {
                const aText = a.children[0].innerText.toLowerCase();
                const bText = b.children[0].innerText.toLowerCase();
                return sortType === 'name_asc'
                    ? aText.localeCompare(bText)
                    : bText.localeCompare(aText);
            }

            /* AGE */
            if (sortType === 'age_asc' || sortType === 'age_desc') {
                const aAge = parseInt(a.children[2].innerText) || 0;
                const bAge = parseInt(b.children[2].innerText) || 0;
                return sortType === 'age_asc'
                    ? aAge - bAge
                    : bAge - aAge;
            }

            /* DATE */
            if (sortType === 'date_asc' || sortType === 'date_desc') {
                const aDate = new Date(a.dataset.date);
                const bDate = new Date(b.dataset.date);
                return sortType === 'date_asc'
                    ? aDate - bDate
                    : bDate - aDate;
            }

            return 0;
        });

        // Re-append rows in new order
        rows.forEach(row => tbody.appendChild(row));
    });
});
