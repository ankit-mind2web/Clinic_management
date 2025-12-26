<?php if (!empty($pagination) && $pagination['total_pages'] > 1): ?>
<div class="pagination">

    <?php if ($pagination['current_page'] > 1): ?>
        <a href="<?= $pagination['base_url'] ?>?page=<?= $pagination['current_page'] - 1 ?>">
            &laquo; Prev
        </a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
        <a
            href="<?= $pagination['base_url'] ?>?page=<?= $i ?>"
            class="<?= $i == $pagination['current_page'] ? 'active' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
        <a href="<?= $pagination['base_url'] ?>?page=<?= $pagination['current_page'] + 1 ?>">
            Next &raquo;
        </a>
    <?php endif; ?>

</div>
<?php endif; ?>
