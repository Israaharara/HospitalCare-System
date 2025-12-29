<?php
include __DIR__ . '/../Main/db.php';

// التحقق من وجود معرف المريض في الرابط
if (!isset($_GET['id'])) {
    header("Location: DashboardDoctor.php");
    exit();
}

$id =  $_GET['id'];

// جلب بيانات المريض من قاعدة البيانات
$sql = "SELECT * FROM patients WHERE id = '$id'";
$res = mysqli_query($conn, $sql);
if (mysqli_num_rows($res) == 0) {
    echo "Patient not found.";
    exit();
}
$patient = mysqli_fetch_assoc($res);

// الحصول على بيانات المريض
$firstName         = $patient['firstName'];
$lastName          = $patient['lastName'];
$email             = $patient['email'];
$age               = $patient['age'];
$password          = $patient['password'];
$phoneNumber       = $patient['phoneNumber'];
$gender            = $patient['gender'];
$problem           = $patient['problem'];
$medical_condition = $patient['medical_condition']; // ملاحظة: الأدوية تكون مفصولة بـ "," في قاعدة البيانات
$role              = $patient['role'];

$errors = [];

// إذا تم إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // التأكد من وجود الحقول
    $firstName        = isset($_POST['firstName']) ? htmlspecialchars($_POST['firstName']) : '';
    $lastName         = isset($_POST['lastName']) ? htmlspecialchars($_POST['lastName']) : '';
    $email            = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
    $age              = isset($_POST['age']) ? htmlspecialchars($_POST['age']) : '';
    $password         = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';
    $phoneNumber      = isset($_POST['phoneNumber']) ? htmlspecialchars($_POST['phoneNumber']) : '';
    $gender           = isset($_POST['gender']) ? htmlspecialchars($_POST['gender']) : '';
    $problem          = isset($_POST['problem']) ? htmlspecialchars($_POST['problem']) : '';
    $medical_condition = isset($_POST['name']) && is_array($_POST['name']) ? implode(',', $_POST['name']) : '';
    $role             = isset($_POST['role']) && !empty($_POST['role']) ? htmlspecialchars($_POST['role']) : 'Patient';

    // التحقق من الحقول
    if (empty($firstName)) { 
        $errors[] = "First name field is required :(";
    } 
    if (empty($lastName)) {
        $errors[] = "Last name field is required :("; 
    }
    if (empty($age)) {
        $errors[] = "Age field is required :("; 
    }
    if (empty($password)) {
        $errors[] = "Password field is required :(";
    }
    if (empty($phoneNumber)) {
        $errors[] = "Phone number field is required :(";
    }
    if (empty($gender)) { 
        $errors[] = "Gender field is required :("; 
    }
    if (empty($problem)) { 
        $errors[] = "Problem field is required :(";
    }
    if (empty($medical_condition)) {
        $errors[] = "Medical condition field is required :("; 
    }
    if (empty($role)) { 
        $errors[] = "Role field is required :("; 
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['firstName'])) {
            // التحقق من الحقول
            if (empty($errors)) {
                $password = password_hash($password, PASSWORD_DEFAULT); // إذا كانت كلمة المرور جديدة
    
                //$selected_drugs_text = implode(', ', $medical_condition); // تحويل المصفوفة إلى نص
    
                // استعلام لتحديث بيانات المريض
                $sql_update_patient = "UPDATE patients 
                SET firstName = '$firstName', lastName = '$lastName', email = '$email', 
                    age = '$age', password = '$password', phoneNumber = '$phoneNumber', 
                    gender = '$gender', problem = '$problem', medical_condition = '$medical_condition'
                WHERE id = '$id'";

                if (mysqli_query($conn, $sql_update_patient)) {
                    // تحديث الأدوية في جدول الربط
                    foreach ($medical_condition as $drug_name) {
                        // جلب ID الدواء بناءً على الاسم
                        $sql_get_drug_id = "SELECT id FROM drugs WHERE name = '$drug_name'";
                        $result = mysqli_query($conn, $sql_get_drug_id);
                        if ($row = mysqli_fetch_assoc($result)) {
                            $drug_id = $row['id'];
                            // إدخال الدواء في جدول الربط
                            $sql_insert_drug_assignment = "INSERT INTO drug_assignments (patient_id, doctor_id, drug_id) 
                                                           VALUES ('$user_id', '$doctor_id', '$drug_id')";
                            mysqli_query($conn, $sql_insert_drug_assignment);
                        }
                    }
                    echo "Patient updated successfully.";
                    header("Location: DashboardDoctor.php?doctor_id=$doctor_id");
                    exit();
                } else {
                    echo "Error updating patient: " . mysqli_error($conn);
                }
            }
        }
    }}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Patients</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f7fa;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 30px;
            font-weight: 600;
        }
        .form-control, .btn {
            border-radius: 10px;
        }
        .alert {
            border-radius: 10px;
        }
        .btn-info {
            background-color: #17a2b8;
        }
        .btn-info:hover {
            background-color: #138496;
        }
    </style>
</head>
<body>
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Update Patient Information</h1>
        <a class="btn btn-primary" href="DashboardDoctor.php">Dashboard</a>
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

        <div class="mb-3">
            <label for="firstName">First Name:</label><br> 
            <input type="text" id="firstName" name="firstName" class="form-control" value="<?= $firstName ?>">
        </div>  

        <div class="mb-3">
            <label for="lastName">Last Name:</label><br> 
            <input type="text" id="lastName" name="lastName" class="form-control" value="<?= $lastName ?>">
        </div>

        <div class="mb-3">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" class="form-control" value="<?= $email ?>">
        </div>

        <div class="mb-3">
            <label for="age">Age:</label><br>
            <input type="number" id="age" name="age" class="form-control" value="<?= $age ?>">
        </div>

        <div class="mb-3">
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" class="form-control" value="<?= $password ?>">
        </div>

        <div class="mb-3">
            <label for="phoneNumber">Phone Number:</label><br>
            <input type="text" id="phoneNumber" name="phoneNumber" class="form-control" value="<?= $phoneNumber ?>">
        </div>

        <div class="mb-3">
            <label for="gender">Gender:</label><br>
            <label><input type="radio" name="gender" value="Male" <?= strtolower($gender) == 'male' ? 'checked' : '' ?>> Male</label>
            <label><input type="radio" name="gender" value="Female" <?= strtolower($gender) == 'female' ? 'checked' : '' ?>> Female</label>
        </div>

        <div class="mb-3">
            <label for="problem">Problem:</label><br>
            <textarea id="problem" name="problem" class="form-control"><?= $problem ?></textarea>
        </div>

        <div class="mb-3">
            <label for="name">Choose Medical Conditions:</label>
            <select name="name[]" id="name" class="form-control" multiple>
                <?php
                $sql_medicines = "SELECT * FROM drugs";
                $res_medicines = mysqli_query($conn, $sql_medicines);
                $selected_medical_conditions = explode(',', $medical_condition);
                while ($row = mysqli_fetch_assoc($res_medicines)) {
                    $selected = in_array($row['name'], $selected_medical_conditions) ? 'selected' : '';
                    echo "<option value='{$row['name']}' $selected>{$row['name']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="role">Role:</label>
            <input type="text" class="form-control" disabled value="Patient">
        </div>

        <br><br>
        <div class="mb-3">
            <button type="submit" class="btn btn-info px-5">Update</button>
        </div>
    </form>   
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
