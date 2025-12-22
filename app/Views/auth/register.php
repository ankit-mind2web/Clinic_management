<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="/../assets/css/auth.css">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-container">

        <h2>Register</h2>

        <?php if (!empty($error)): ?>
            <div class="auth-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="/auth/register">
            <input type="text" name="full_name" placeholder="Full Name">
            <input type="email" name="email" placeholder="Email">
            <input type="text" name="mobile" placeholder="Mobile">
            <input type="password" name="password" placeholder="Password">

            <select name="role">
                <option value="">Select Role</option>
                <option value="patient">Patient</option>
                <option value="doctor">Doctor</option>
            </select>

            <button type="submit">Register</button>
        </form>

        <div class="auth-footer">
            Already registered?
            <a href="/auth/login">Login</a>
        </div>

    </div>
</div>

</body>
</html>
