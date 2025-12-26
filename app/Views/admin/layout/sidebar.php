<?php
$current = $_SERVER['REQUEST_URI'] ?? '';
$isDoctorMenuOpen =
    str_contains($current, '/admin/doctors');
?>
<script>
    function toggleSubMenu(e) {
        e.preventDefault();
        const parent = e.target.closest('.has-sub');
        parent.classList.toggle('open');
    }
</script>

<div class="sidebar">
    <div class="logo">Clinic Admin</div>

    <ul class="nav">

        <li>
            <a href="/admin/dashboard"
                class="<?= $current === '/admin/dashboard' ? 'active' : '' ?>">
                Dashboard
            </a>
        </li>

        <!-- DOCTORS DROPDOWN -->
        <li class="has-sub <?= $isDoctorMenuOpen ? 'open' : '' ?>">
            <a href="#" onclick="toggleSubMenu(event)">
                Doctors
            </a>

            <ul class="sub-menu">
                <li>
                    <a href="/admin/doctors"
                        class="<?= $current === '/admin/doctors' ? 'active' : '' ?>">
                        All Doctors
                    </a>
                </li>

                <li>
                    <a href="/admin/doctors/pending"
                        class="<?= str_contains($current, '/admin/doctors/pending') ? 'active' : '' ?>">
                        Requests
                    </a>
                </li>
            </ul>
        </li>

         <!-- SPECIALIZATIONS -->
        <li>
            <a href="/admin/specializations"
               class="<?= str_contains($current, '/admin/specializations') ? 'active' : '' ?>">
                Specializations
            </a>
        </li>

        <li>
            <a href="/admin/appointments"
                class="<?= str_contains($current, '/admin/appointments') ? 'active' : '' ?>">
                Appointments
            </a>
        </li>

        <li>
            <a href="/auth/logout">Logout</a>
        </li>

    </ul>
</div>