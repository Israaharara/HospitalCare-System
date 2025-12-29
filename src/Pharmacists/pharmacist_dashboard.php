<?php
session_start();
include "../Main/db.php";


//هلق هان راح تظهرلي شاشة الصيدلي راح تكون فيها كل انواع الادوية الموجودة  
$pharmacist_id = $_SESSION['user_id']; // معرف الصيدلي من الجلسة

// جلب جميع الأدوية التي أضافها الصيدلي
$sql = "SELECT * FROM drugs WHERE pharmacist_id = '$pharmacist_id'";
$result = mysqli_query($conn, $sql);

// حذف الدواء إذا تم إرسال معرف الدواء عبر الرابط
if (isset($_GET['id'])) {
    $drug_id = $_GET['id'];
    $sql_delete_drug = "DELETE FROM drugs WHERE id = '$drug_id'";
    if (mysqli_query($conn, $sql_delete_drug)) {
        header("Location: pharmacist_dashboard.php");
        exit();
    } else {
        echo "Error deleting drug: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacist Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f7f7f7;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 1000px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            border-radius: 8px;
            margin-right: 10px;
        }
        .table {
            margin-top: 30px;
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
        .table-bordered {
            border: 1px solid #ddd;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #ddd;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .alert {
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center mb-4">Pharmacist Dashboard</h1>

    <!-- Buttons for actions -->
    <div class="d-flex justify-content-between mb-3">
        <a class="btn btn-success" href="../Pharmacists/add_drug.php">Add New Drug</a>
        <a class="btn btn-danger" href="logout_Pharmacists.php">Logout</a>
    </div>

    <!-- Drugs table -->
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Drug Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php    
            // التحقق إذا كانت هناك نتائج
            if (mysqli_num_rows($result) > 0) {
                $i = 1;
                while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <tr>
                <td><?php echo $i; $i++; ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['description'] ?></td>
                <td>
                    <a class="btn btn-sm btn-warning" href="edit_drug.php?id=<?= $row['id']?>">Edit</a>
                    <a onclick="return confirm('Are you sure you want to delete this drug?')" class="btn btn-sm btn-danger" href="pharmacist_dashboard.php?id=<?= $row['id']?>">Delete</a>
                </td>
            </tr>
        <?php 
                }
            } else {
        ?>
            <tr>
                <td colspan="4" class="text-center">No drugs available</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
