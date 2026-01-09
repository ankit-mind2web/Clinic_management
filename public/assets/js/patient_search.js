document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearSearch');
    const searchForm = document.getElementById('searchForm');

    if (!searchInput || !clearBtn || !searchForm) return;

    // Show/Hide X button on load
    toggleClearButton();

    // Show/Hide X button on input
    searchInput.addEventListener('input', toggleClearButton);

    function toggleClearButton() {
        clearBtn.style.display = searchInput.value.trim() !== '' ? 'block' : 'none';
    }

    // Clear search and submit form
    clearBtn.addEventListener('click', function () {
        searchInput.value = '';
        toggleClearButton();
        searchForm.submit();
    });
});
