document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearSearch');
    const tbody = document.getElementById('tableBody');

    if (!input || !clearBtn || !tbody) return;

    const columns = input.dataset.searchColumns
        .split(',')
        .map(i => parseInt(i.trim(), 10));

    const rows = tbody.querySelectorAll('tr');

    input.addEventListener('input', function () {
        const value = this.value.toLowerCase();

        /* show / hide × button */
        clearBtn.style.display = value ? 'block' : 'none';

        rows.forEach(row => {
            let match = false;

            columns.forEach(index => {
                const cell = row.children[index];
                if (cell && cell.innerText.toLowerCase().includes(value)) {
                    match = true;
                }
            });

            row.style.display = match ? '' : 'none';
        });
    });

    clearBtn.addEventListener('click', function () {
        input.value = '';
        clearBtn.style.display = 'none';

        rows.forEach(row => row.style.display = '');
        input.focus();
    });
});
