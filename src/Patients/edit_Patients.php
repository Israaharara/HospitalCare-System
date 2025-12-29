<?php
include __DIR__ . '/../Main/db.php';
session_start();

// التأكد من أن المستخدم مسجل دخوله
if (!isset($_SESSION['user']['id'])) {
    header('Location: login.php'); // توجيه المستخدم إلى صفحة تسجيل الدخول إذا لم يكن مسجلاً
    exit();
}

// جلب معرف المستخدم من الجلسة
$patient_id = $_SESSION['user']['id'];

// جلب بيانات المريض من قاعدة البيانات عشان هي الي راح يعملب عليها تعديل حسب الايدي تيبعه 
$sql = "SELECT * FROM patients WHERE user_id = '$patient_id'";
$res = mysqli_query($conn, $sql);

// التأكد من وجود بيانات المريض
if (mysqli_num_rows($res) > 0) {
    $patient = mysqli_fetch_assoc($res);
    $firstName    = $patient['firstName'];
    $lastName     = $patient['lastName'];
    $email        = $patient['email'];
    $age          = $patient['age'];
    $phoneNumber  = $patient['phoneNumber'];
    $gender       = $patient['gender'];
} else {
    die("Patient not found");
}

// معالجة نموذج التحديث
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName    = mysqli_real_escape_string($conn, $_POST['firstName'] ?? '');
    $lastName     = mysqli_real_escape_string($conn, $_POST['lastName'] ?? '');
    $email        = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $age          = mysqli_real_escape_string($conn, $_POST['age'] ?? '');
    $phoneNumber  = mysqli_real_escape_string($conn, $_POST['phoneNumber'] ?? '');
    $gender       = mysqli_real_escape_string($conn, $_POST['gender'] ?? '');

    // التحقق من المدخلات
    if (empty($firstName)){
         $errors[] = "First Name is required";
        }

    if (empty($lastName)){
         $errors[] = "Last Name is required";
    }
    if (empty($email)){
         $errors[] = "Email is required";
    }
    if (empty($age)){
     $errors[] = "Age is required";
    }
    if (empty($phoneNumber)){
     $errors[] = "Phone Number is required";
    }
    if (empty($gender)){
     $errors[] = "Gender is required";
    }

    // إذا لم توجد أخطاء
    if (empty($errors)) {
        // تحديث بيانات المريض
        $sql_patients = "UPDATE patients 
                         SET firstName = '$firstName', 
                             lastName = '$lastName', 
                             email = '$email', 
                             age = '$age', 
                             phoneNumber = '$phoneNumber', 
                             gender = '$gender' 
                         WHERE user_id = '$patient_id'";
        if (mysqli_query($conn, $sql_patients)) {
            // تحديث بيانات المستخدم في جدول users
            $sql_users = "UPDATE users 
                          SET firstName = '$firstName', 
                              lastName = '$lastName', 
                              email = '$email', 
                              phoneNumber = '$phoneNumber' 
                          WHERE id = '$patient_id'";
            if (mysqli_query($conn, $sql_users)) {
                $success = "Data updated successfully!";
                header('Location: DashboardPatients.php');
                exit(); // تأكد من استخدام exit بعد التوجيه
            } else {
                $errors[] = "Error updating user data: " . mysqli_error($conn);
            }
        } else {
            $errors[] = "Error updating patient data: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Patients</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f7fc;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 900px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-control {
            border-radius: 10px;
            padding: 15px;
            font-size: 16px;
        }
        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
            width: 100%;
            padding: 12px;
            font-size: 18px;
            border-radius: 8px;
        }
        .btn-info:hover {
            background-color: #138496;
            border-color: #138496;
        }
        .alert-danger {
            border-radius: 8px;
        }
        .header {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
        }
        .radio-btns {
            display: flex;
            align-items: center;
        }
        .radio-btns label {
            margin-right: 30px;
        }
    </style>
</head>

<body>
<div class="container">
    <div class="header">
        <h1>Edit your personal information</h1>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a class="btn btn-primary" href="DashboardPatients.php">Dashboard</a>
    </div>

    <form action="" method="POST">
        <?php if (!empty($errors)) { ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error) { ?>
                        <li><?= $error ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <div class="form-group">
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" class="form-control" value="<?= $firstName ?>" placeholder="Enter first name">
        </div>

        <div class="form-group">
            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" class="form-control" value="<?= $lastName ?>" placeholder="Enter last name">
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control" value="<?= $email ?>" placeholder="Enter email">
        </div>

        <div class="form-group">
            <label for="age">Age:</label>
            <input type="number" id="age" name="age" class="form-control" value="<?= $age ?>" placeholder="Enter age">
        </div>

        <div class="form-group">
            <label for="phoneNumber">Phone Number:</label>
            <input type="text" id="phoneNumber" name="phoneNumber" class="form-control" value="<?= $phoneNumber ?>" placeholder="Enter phone number">
        </div>

        <div class="form-group radio-btns">
            <label>Gender:</label><br>
            <label><input type="radio" name="gender" value="Male" <?= strtolower($gender) == 'male' ? 'checked' : '' ?>> Male</label>
            <label><input type="radio" name="gender" value="Female" <?= strtolower($gender) == 'female' ? 'checked' : '' ?>> Female</label>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-info">Update</button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
