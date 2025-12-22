<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="/../assets/css/auth.css">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-container">

        <h2>Login</h2>

        <?php if (!empty($error)): ?>
            <div class="auth-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="/auth/login">
            <input type="text" name="login" placeholder="Email or Mobile">
            <input type="password" name="password" placeholder="Password">
            <button type="submit">Login</button>
        </form>

        <div class="auth-footer">
            Don’t have an account?
            <a href="/auth/register">Register</a>
        </div>

    </div>
</div>

</body>
</html>
