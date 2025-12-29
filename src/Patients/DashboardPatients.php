<?php
session_start();
include __DIR__ . '/../Main/db.php';
// التحقق من الجلسة
if (!isset($_SESSION['user']['id'])) {
    echo "User not logged in.";
    exit();
}

$patient_id = $_SESSION['user']['id'];

// جلب بيانات المريض من قاعدة البيانات
$patient = mysqli_query($conn, "SELECT * FROM patients WHERE user_id = '$patient_id'");

// التحقق من وجود خطأ في الاستعلام
if (!$patient) {
    die("Query failed: " . mysqli_error($conn));
}

// جلب البيانات من الاستعلام
$patient_info = mysqli_fetch_assoc($patient);

// التحقق إذا كانت بيانات المريض موجودة
if (!$patient_info) {
    echo "<h1>No patient data found</h1>";
    exit();
}

$doctor_id = $patient_info['doctor_id']; // تعريف doctor_id من بيانات المريض
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 50px;
        }
        h1, h3, h4 {
            color: #007bff;
        }
        table {
            margin-top: 20px;
        }
        .btn-primary, .btn-danger {
            border-radius: 8px;
        }
        .btn {
            font-size: 16px;
        }
        .table th, .table td {
            text-align: center;
        }
        .table th {
            background-color: #007bff;
            color: white;
        }
        .table td {
            background-color: #f1f1f1;
        }
        .table tr:nth-child(even) td {
            background-color: #e9ecef;
        }
        .table tr:hover td {
            background-color: #d6d8db;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Hello <?= htmlspecialchars($patient_info['firstName']) ?>!</h1>
    <a href="logout_Patients.php" class="btn btn-danger">Logout</a>
    
    <hr>

    <h3>Personal Information</h3>
    <table class="table table-bordered">
        <tr>
            <th>First Name</th>
            <td><?= htmlspecialchars($patient_info['firstName']) ?></td>
        </tr>
        <tr>
            <th>Last Name</th>
            <td><?= htmlspecialchars($patient_info['lastName']) ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?= htmlspecialchars($patient_info['email']) ?></td>
        </tr>
        <tr>
            <th>Age</th>
            <td><?= htmlspecialchars($patient_info['age']) ?></td>
        </tr>
        <tr>
            <th>Phone Number</th>
            <td><?= htmlspecialchars($patient_info['phoneNumber']) ?></td>
        </tr>
        <tr>
            <th>Gender</th>
            <td><?= htmlspecialchars($patient_info['gender']) ?></td>
        </tr>
        <tr>
            <th></th>
            <td><a class="btn btn-sm btn-primary" href="edit_Patients.php?id=<?= htmlspecialchars($patient_info['id']) ?>">Edit</a></td>
        </tr>
    </table>

    <h3>Information of the Responsible Doctor</h3>
    <?php
    // جلب بيانات الطبيب المسؤول
    $doctor_info_query = mysqli_query($conn, "SELECT * FROM users WHERE id = '{$patient_info['doctor_id']}'");

    // التحقق من وجود خطأ في الاستعلام
    if (!$doctor_info_query) {
        die("Doctor query failed: " . mysqli_error($conn));
    }

    $doctor_info = mysqli_fetch_assoc($doctor_info_query);

    if (!$doctor_info) {
        echo "<p>The doctor has not been found.</p>";
    } else {
        echo "<h4>Responsible Doctor:</h4> " . htmlspecialchars($doctor_info['firstName']) . " " . htmlspecialchars($doctor_info['lastName']);
        echo "<table class='table table-bordered'>
                <tr>
                    <th>Doctor Name</th>
                    <td>" . htmlspecialchars($doctor_info['firstName']) . " " . htmlspecialchars($doctor_info['lastName']) . "</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>" . htmlspecialchars($doctor_info['email']) . "</td>
                </tr>
                <tr>
                    <th>Phone Number</th>
                    <td>" . htmlspecialchars($doctor_info['phoneNumber']) . "</td>
                </tr>
              </table>";
    }
    ?>

    <h3>Prescribed Medications</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Drug Name</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // جلب الأدوية المخصصة للمريض بناءً على الـ patient_id في جدول drug_assignments
            $sql = "SELECT dr.name AS drug_name
                    FROM drug_assignments da
                    LEFT JOIN drugs dr ON da.drug_id = dr.id
                    WHERE da.patient_id = '$patient_id'";

            $drug_result = mysqli_query($conn, $sql);

            // التحقق إذا كان هناك أدوية مخصصة
            if ($drug_result && mysqli_num_rows($drug_result) > 0) {
                $i = 1;
                // عرض الأدوية
                while ($row = mysqli_fetch_assoc($drug_result)) {
                    echo "<tr><td>$i</td><td>" . htmlspecialchars($row['drug_name']) . "</td></tr>";
                    $i++;
                }
            } else {
                echo "<tr><td colspan='2' class='text-center'>No medications have been identified.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
