document.addEventListener('click', function (e) {

    const link = e.target.closest('.pagination a');
    if (!link) return;

    e.preventDefault();

    const url = link.getAttribute('href');

    fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.text())
    .then(html => {
        document.getElementById('ajaxContainer').innerHTML = html;
    });
});
