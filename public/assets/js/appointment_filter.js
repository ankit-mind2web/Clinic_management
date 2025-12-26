document.addEventListener('DOMContentLoaded', function () {

    const filter = document.getElementById('filterBy');
    const rows = document.querySelectorAll('#tableBody tr');

    if (!filter) return;

    filter.addEventListener('change', function () {

        const type = this.value;

        rows.forEach(row => {

            if (type === 'all') {
                row.style.display = '';
                return;
            }

            if (type === 'name') {
                row.style.display = '';
            }

            if (type === 'date') {
                const dateCell = row.children[3];
                row.style.display = dateCell ? '' : 'none';
            }
        });
    });
});
