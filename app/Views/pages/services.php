<?php
// Define the base URL if not already defined (assuming it's available in the view context)
// In this project, BASE_URL seems to be a constant based on header.php usage.

// Include the Header
require_once __DIR__ . '/../layout/header.php';
?>
<link rel="stylesheet" href="/assets/css/services.css">

<div class="services-page">
    <h1 class="section-title">Our Services</h1>

    <!-- 3. Loop through Services -->
    <div class="services-grid">
        <?php if (!empty($services)): ?>
            <?php foreach ($services as $service): ?>
                <div class="service-card">
                    
                    <!-- Service Title with Link -->
                    <h2 class="service-title">
                        <a href="<?= BASE_URL ?>/services/detail?service=<?= urlencode(strtolower($service['name'])) ?>">
                            <?= htmlspecialchars($service['name']) ?>
                        </a>
                    </h2>

                    <!-- Service Description -->
                    <p class="service-desc">
                        <?= htmlspecialchars($service['description']) ?>
                    </p>

                    <!-- Optional 'Read More' Button -->
                    <a href="<?= BASE_URL ?>/services/detail?service=<?= urlencode(strtolower($service['name'])) ?>" class="read-more">
                        Learn More &rarr;
                    </a>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center;">No services available at the moment.</p>
        <?php endif; ?>
    </div>
</div>

<?php 
// Include the Footer
require_once __DIR__ . '/../layout/footer.php'; 
?>