<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body{
            font-family: Arial, sans-serif;
            background:#f5f6fa;
            margin:0;
            padding:20px;
        }
        h1{
            margin-bottom:5px;
        }
        .stats{
            display:grid;
            grid-template-columns: repeat(4, 1fr);
            gap:20px;
            margin:30px 0;
        }
        .card{
            background:#fff;
            padding:20px;
            border-radius:8px;
            box-shadow:0 4px 10px rgba(0,0,0,0.08);
        }
        .card h3{
            margin:0 0 10px;
            font-size:14px;
            color:#666;
        }
        .card p{
            font-size:26px;
            margin:0;
            font-weight:bold;
        }
        table{
            width:100%;
            border-collapse:collapse;
            background:#fff;
            border-radius:8px;
            overflow:hidden;
            box-shadow:0 4px 10px rgba(0,0,0,0.08);
        }
        th, td{
            padding:12px 15px;
            border-bottom:1px solid #eee;
            text-align:left;
        }
        th{
            background:#fafafa;
        }
        .status{
            padding:4px 10px;
            border-radius:12px;
            font-size:12px;
        }
        .status.pending{ background:#fff3cd; color:#856404; }
        .status.completed{ background:#d4edda; color:#155724; }
        .status.confirmed{ background:#cce5ff; color:#004085; }
    </style>
</head>
<body>

<h1>Dashboard</h1>
<p>Here’s what’s happening today</p>

<!-- STATS -->
<div class="stats">
    <div class="card">
        <h3>Total Patients</h3>
        <p><?= $totalPatients ?></p>
    </div>

    <div class="card">
        <h3>Active Doctors</h3>
        <p><?= $totalDoctors ?></p>
    </div>

    <div class="card">
        <h3>Total Appointments</h3>
        <p><?= $totalAppointments ?></p>
    </div>

    <div class="card">
        <h3>Pending Doctors</h3>
        <p><?= $pendingDoctors ?></p>
    </div>
</div>

<!-- RECENT APPOINTMENTS -->
<h2>Recent Appointments</h2>

<?php if (!empty($recentAppointments)): ?>
<table>
    <thead>
        <tr>
            <th>Patient</th>
            <th>Doctor</th>
            <th>Start Time</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($recentAppointments as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['patient_name']) ?></td>
                <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                <td><?= date('d M Y, h:i A', strtotime($row['start_utc'])) ?></td>
                <td>
                    <span class="status <?= $row['status'] ?>">
                        <?= ucfirst($row['status']) ?>
                    </span>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <p>No recent appointments.</p>
<?php endif; ?>

</body>
</html>
 <!-- #region -->