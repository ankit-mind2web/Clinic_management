<!-- app/Views/doctor/profile/index.php -->
<!DOCTYPE html>
<html>

<head>
    <title>Doctor Profile</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/doctor_profile.css">
    <?php require_once __DIR__ . '/../../layout/header.php'; ?>
</head>

<body>

    <?php
    $user = $_SESSION['user'] ?? [];

    $isVerified = ($user['email_verified'] ?? 0) == 1;

    /* PROFILE DATA (SAFE) */
    $gender  = $profile['gender'] ?? '';
    $dob     = $profile['dob'] ?? '';
    $address = $profile['address'] ?? '';

    /* AGE CALCULATION */
    $age = '—';
    if (!empty($dob)) {
        $birthDate = new DateTime($dob);
        $today = new DateTime();
        $age = $today->diff($birthDate)->y . ' years';
    }

    /* PROFILE COMPLETENESS */
    $isProfileComplete = ($gender !== '' && $dob !== '' && $address !== '');

    /* EDIT MODE */
    $isEditMode = isset($_GET['edit']);

    /* EMAIL COOLDOWN */
    $cooldownUntil = $_SESSION['verify_cooldown_until'] ?? 0;
    $now = time();
    $inCooldown = $cooldownUntil > $now;
    $secondsLeft = max(0, $cooldownUntil - $now);
    ?>

    <div class="auth-wrapper">
        <div class="auth-container">

            <div class="profile-header">
                <h2>My Profile</h2>

                <a href="/doctor/profile?edit=1" class="edit-profile-icon" title="Edit Profile">
                    <img src="/assets/images/edit.png" alt="Edit">
                </a>
            </div>

            <?php if (!empty($message ?? '')): ?>
                <div class="auth-error"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <!-- VIEW MODE -->
            <?php if (!$isEditMode): ?>

                <table class="profile-table">
                    <tr>
                        <th>Name</th>
                        <td><?= htmlspecialchars($user['full_name'] ?? '—') ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= htmlspecialchars($user['email'] ?? '—') ?></td>
                    </tr>
                    <tr>
                        <th>Mobile</th>
                        <td><?= htmlspecialchars($user['mobile'] ?? '—') ?></td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td><?= $gender ?: '—' ?></td>
                    </tr>
                    <tr>
                        <th>Age</th>
                        <td><?= $age ?></td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td><?= $address ?: '—' ?></td>
                    </tr>
                </table>

                <!-- EMAIL VERIFICATION -->
                <?php if ($isVerified): ?>

                    <div class="auth-success verified">
                        <span>Email Verified</span>
                        <span class="tick">✔</span>
                    </div>

                <?php else: ?>

                    <?php if ($isProfileComplete): ?>
                        <div class="auth-error">
                            Email not verified
                            <form method="post" action="/doctor/profile/send-verification" style="margin-top:10px;">
                                <button type="submit" <?= $inCooldown ? 'disabled' : '' ?>>
                                    <?= $inCooldown ? "Resend in {$secondsLeft}s" : "Verify Email" ?>
                                </button>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="auth-error">
                            Complete your profile to verify email
                        </div>
                    <?php endif; ?>

                <?php endif; ?>

                <!-- EDIT MODE -->
            <?php else: ?>

                <form method="post" action="/doctor/profile">

                    <label>Gender</label>
                    <select name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male" <?= $gender === 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= $gender === 'Female' ? 'selected' : '' ?>>Female</option>
                        <option value="Others" <?= $gender === 'Others' ? 'selected' : '' ?>>Others</option>
                    </select>

                    <label>Date of Birth</label>
                    <input type="date" name="dob" value="<?= htmlspecialchars($dob) ?>" required>

                    <label>Address</label>
                    <textarea name="address" rows="4" required><?= htmlspecialchars($address) ?></textarea>

                    <button type="submit">Save Profile</button>
                    <a href="/doctor/profile" class="btn-cancel">Cancel</a>
                </form>

            <?php endif; ?>

        </div>
    </div>

    <?php require_once __DIR__ . '/../../layout/footer.php'; ?>
</body>

</html>