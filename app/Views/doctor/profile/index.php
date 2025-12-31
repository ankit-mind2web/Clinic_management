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

/* APPROVAL STATUS */
$isApproved = $isApproved ?? false;

/* EMAIL VERIFIED */
$isVerified = ($user['email_verified'] ?? 0) == 1;

/* PROFILE DATA */
$gender  = $profile['gender'] ?? '';
$dob     = $profile['dob'] ?? '';
$address = $profile['address'] ?? '';

/* AGE */
$age = '—';

if (!empty($dob)) {
    $birthDate = new DateTime($dob);
    $today     = new DateTime();
    $diff = $today->diff($birthDate);
    $age = $diff->y . ' years, ' . $diff->m . ' months, ' . $diff->d . ' days';
}


/* PROFILE COMPLETE */
$isProfileComplete = ($gender !== '' && $dob !== '' && $address !== '');

/* EDIT MODE */
$isEditMode = isset($_GET['edit']);
?>

<div class="auth-wrapper">
    <div class="auth-container">

        <div class="profile-header">
            <h2>My Profile</h2>

            <?php if ($isApproved): ?>
                <a href="/doctor/profile?edit=1" class="edit-profile-icon">
                    <img src="/assets/images/edit.png" alt="Edit">
                </a>
            <?php endif; ?>
        </div>

        <?php if (!empty($message)): ?>
            <div class="auth-error"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if (!$isApproved): ?>

            <div class="auth-error">
                Your account is pending wait for admin approval.<br>
                Profile editing is locked & will open after approval.
            </div>

        <?php elseif (!$isEditMode): ?>

            <!-- VIEW MODE -->
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

            <?php if ($isVerified): ?>
                <div class="auth-success verified">
                    Email Verified ✔
                </div>
            <?php else: ?>
                <?php if ($isProfileComplete): ?>
                    <div class="auth-error">
                        Email not verified
                        <form method="post" action="/doctor/profile/send-verification">
                            <button type="submit">Verify Email</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="auth-error">
                        Complete your profile to verify email
                    </div>
                <?php endif; ?>
            <?php endif; ?>

        <?php else: ?>

            <!-- EDIT MODE -->
            <form method="post" action="/doctor/profile">

                <label>Gender</label>
                <select name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="Male" <?= $gender === 'Male' ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= $gender === 'Female' ? 'selected' : '' ?>>Female</option>
                    <option value="Others" <?= $gender === 'Others' ? 'selected' : '' ?>>Others</option>
                </select>

                <label>Date of Birth</label>
                <input type="date" name="dob"
                       value="<?= htmlspecialchars($dob) ?>"
                       required
                       max="<?= date('Y-m-d', strtotime('-23 years')) ?>">

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
