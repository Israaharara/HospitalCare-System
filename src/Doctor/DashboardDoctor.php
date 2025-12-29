<?php
include __DIR__ . '/../Main/db.php';
session_start();
//بعد ما يدخل هان فاكيد idموجود ومخزن في اليميل 
// التحقق من وجود المستخدم
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'doctor') {
    header("Location:../Main/login.php");
    exit();
}

$doctor_id = $_SESSION['user']['id']; // ID الطبيب من الجلسة

// استعلام للحصول على المرضى الذين يتعامل معهم هذا الطبيب
$sql = "SELECT * FROM patients WHERE doctor_id = '$doctor_id'";
$res = mysqli_query($conn, $sql);

// التحقق من وجود أي أخطاء في تنفيذ الاستعلام
if ($res === false) {
    echo "Error: " . mysqli_error($conn);
    exit();  // التوقف إذا كان هناك خطأ
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];  // ID المريض من الرابط

    // الحصول على الأدوية المرتبطة بالمريض
    $sql_get_drugs = "SELECT * FROM drug_assignments WHERE patient_id = $id";
    $result_drugs = mysqli_query($conn, $sql_get_drugs);

    if ($result_drugs && mysqli_num_rows($result_drugs) > 0) {
        // حذف الأدوية المرتبطة بالمريض من جدول الربط
        $sql_delete_drugs = "DELETE FROM drug_assignments WHERE patient_id = $id";
        if (mysqli_query($conn, $sql_delete_drugs)) {
            echo "Drugs removed from drug_assignments table.<br>";
        } else {
            echo "Error deleting drugs: " . mysqli_error($conn);
        }
    }

    // الحصول على user_id المرتبط بالمريض
    $sql_get_user_id = "SELECT user_id FROM patients WHERE id = $id";
    $result = mysqli_query($conn, $sql_get_user_id);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['user_id'];

        // حذف المريض من جدول المرضى
        $sql_delete_patient = "DELETE FROM patients WHERE id = $id";
        if (mysqli_query($conn, $sql_delete_patient)) {
            echo "Patient deleted from patients table.<br>";

            // حذف المستخدم من جدول المستخدمين
            $sql_delete_user = "DELETE FROM users WHERE id = $user_id";
            if (mysqli_query($conn, $sql_delete_user)) {
                echo "Associated user deleted from users table.<br>";
                header("Location: DashboardDoctor.php"); // إعادة التوجيه إلى لوحة التحكم
                exit;
            } else {
                echo "Error deleting user: " . mysqli_error($conn);
            }
        } else {
            echo "Error deleting patient: " . mysqli_error($conn);
        }
    } else {
        echo "No patient found with the provided ID or user_id is missing.<br>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DashboardDoctor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        .btn {
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            background-color: #007bff;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
            margin: 5px;
        }
        .btn-primary {
            background-color: #28a745;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn:hover {
            opacity: 0.8;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        .actions {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Dashboard Doctor</h1>
            <div>
                <a class="btn btn-primary" href="add_Patients.php">Add new Patients</a>
                <a class="btn btn-primary" href="logoutDoctor.php">Logout</a>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Age</th>
                    <th>Phone Number</th>
                    <th>Gender</th>
                    <th>Problem</th>
                    <th>Medical Condition</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php    
                    // التحقق إذا كانت هناك نتائج
                    if (mysqli_num_rows($res) > 0) {
                        $i = 1;
                        while ($row = mysqli_fetch_assoc($res)) {
                ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?= $row['firstName'] ?></td>
                    <td><?= $row['lastName'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['age'] ?></td>
                    <td><?= $row['phoneNumber'] ?></td>
                    <td><?= $row['gender'] ?></td>
                    <td><?= $row['problem'] ?></td>
                    <td><?= $row['medical_condition'] ?></td>
                    <td class="actions">
                        <a class="btn btn-sm btn-primary" href="edit_Patients.php?id=<?= $row['id'] ?>">Edit</a>
                        <a onclick="return confirm('Are you sure you want to delete this patient?')" class="btn btn-sm btn-danger" href="DashboardDoctor.php?id=<?= $row['id'] ?>">Delete</a>
                    </td>
                </tr>
                <?php 
                        }
                    } else {
                ?>
                <tr>
                    <td colspan="10" class="text-center">No data found</td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
