<?php require_once __DIR__ . '/../layout/header.php'; ?>
<link rel="stylesheet" href="/assets/css/services.css">

<div class="detail-container">
    <a href="<?= BASE_URL ?>/services" class="back-link">&larr; Back to Services</a>

    <?php if (isset($service) && $service): ?>
        
        <h1 class="service-detail-title"><?= htmlspecialchars($service['name']) ?></h1>
        
        <div class="service-detail-desc">
             Details about <?= htmlspecialchars($service['name']) ?>
        </div>

        <div class="service-content">
            <?= nl2br(htmlspecialchars($service['description'])) ?>
        </div>

    <?php else: ?>
        
        <div class="error-container">
            <h2 class="error-title">Service Not Found</h2>
            <p>Sorry, the service you are looking for does not exist or has been removed.</p>
            <br>
            <a href="<?= BASE_URL ?>/services" class="btn" style="background: #3498db; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 5px;">View All Services</a>
        </div>

    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
