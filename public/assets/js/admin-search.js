document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearSearch');
    const searchForm = document.getElementById('searchForm');
    // searchSubmit button is implicitly handled by form submission if type="submit"

    if (!searchInput || !clearBtn) return; // Exit if elements invalid

    const searchBtn = document.querySelector('.search-btn'); // The submit button

    // Adjust X position
    // Show/Hide X button on load
    toggleClearButton();

    // Show/Hide X button on input & Trigger Search (Client-Side)
    searchInput.addEventListener('input', function () {
        toggleClearButton();
        if (!searchForm || (searchForm && searchForm.dataset.liveSearch === "true")) {
            triggerSearchEvents();
        }
    });

    // Trigger initial search on load if value exists (for persistence + live search)
    if (searchInput.value.trim() !== '') {
        if (!searchForm || (searchForm && searchForm.dataset.liveSearch === "true")) {
            triggerSearchEvents();
        }
    }

    function toggleClearButton() {
        clearBtn.style.display = searchInput.value.trim() !== '' ? 'block' : 'none';
    }

    // HANDLE SEARCH BUTTON CLICK (For Client-Side pages like Appointments/Specializations)
    if (searchBtn && !searchForm) {
        searchBtn.addEventListener('click', function () {
            triggerSearchEvents();
        });
    }

    // Clear search
    clearBtn.addEventListener('click', function () {
        searchInput.value = '';
        toggleClearButton();
        if (searchForm) {
            searchForm.submit(); // Reload page with empty search
        } else {
            triggerSearchEvents();
            searchInput.focus();
        }
    });

    function triggerSearchEvents() {
        // Trigger generic filter logic for client-side tables
        const keyword = searchInput.value.toLowerCase();

        // Try to find a table body to filter
        const tableBody = document.getElementById('specializationTable') || document.getElementById('tableBody');

        if (tableBody) {
            tableBody.querySelectorAll('tr').forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(keyword) ? '' : 'none';
            });
        }

        // Also dispatch events in case other scripts are listening (e.g. specialization.js)
        searchInput.dispatchEvent(new Event('keyup', { bubbles: true }));
        searchInput.dispatchEvent(new Event('input', { bubbles: true }));
    }
});
