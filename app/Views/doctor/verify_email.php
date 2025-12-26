<?php
$title = 'Verify Email';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="auth-wrapper">
    <div class="auth-container">

        <h2>Email Verification Required</h2>

        <p>Your email is not verified. Please verify to continue.</p>

        <form method="post" action="/doctor/send-verification">
            <button type="submit">Send Verification Email</button>
        </form>

    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
