<?php
include __DIR__ . '/../Main/db.php';

session_start();
// تعريف المتغيرات
$user_id = $doctor_id  = $firstName = $lastName = $email = $age = $password = $PhoneNumber = $gender = $problem = $role = '';
$medical_condition = []; 

$errors = [];

// إذا تم إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['firstName'])) {
        
        // التأكد من وجود الحقول
        $firstName         = isset($_POST['firstName']) ? htmlspecialchars($_POST['firstName']) : '';
        $lastName          = isset($_POST['lastName']) ? htmlspecialchars($_POST['lastName']) : '';
        $email             = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
        $age               = isset($_POST['age']) ? htmlspecialchars($_POST['age']) : '';
        $password          = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';  
        $PhoneNumber       = isset($_POST['PhoneNumber']) ? htmlspecialchars($_POST['PhoneNumber']) : '';
        $gender            = isset($_POST['gender']) ? htmlspecialchars($_POST['gender']) : '';  
        $problem           = isset($_POST['problem']) ? htmlspecialchars($_POST['problem']) : '';
        $role              = isset($_POST['role']) && !empty($_POST['role']) ? htmlspecialchars($_POST['role']) : 'Patient';
        $medical_condition = isset($_POST['name']) && is_array($_POST['name']) ? $_POST['name'] : []; // الأدوية المختارة
        $doctor_id         = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        if ($doctor_id === null) {
            $errors[] = "The Doctor is not logged in.";
        }

        // التحقق من الأدوية المختارة
        if (empty($medical_condition)) {
            $errors[] = "You must select at least one medicine.";
        }

        // التحقق من الحقول الأخرى
        if (empty($firstName)) {
            $errors[] = "First Name field is required.";
        } 
        if (empty($lastName)) {
            $errors[] = "Last Name field is required.";
        }
        if (empty($email)) {
            $errors[] = "Email field is required.";
        }
        if (empty($age)) {
            $errors[] = "Age field is required.";
        }
        if (empty($password)) {
            $errors[] = "Password field is required.";
        }
        if (empty($PhoneNumber)) {
            $errors[] = "Phone Number field is required.";
        }
        if (empty($gender)) {
            $errors[] = "Gender field is required.";
        }
        if (empty($problem)) {
            $errors[] = "Problem field is required.";
        }
        if (empty($role)) {
            $errors[] = "Role field is required.";
        }

        // إذا لم توجد أخطاء، نقوم بحفظ البيانات في قاعدة البيانات
        if (empty($errors)) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            
            // استعلام لإدخال المستخدم
            $sql_insert_user = "INSERT INTO users (firstName, lastName, email, password, PhoneNumber, role) 
                                VALUES ('$firstName','$lastName','$email','$password','$PhoneNumber','patient')";
            if (mysqli_query($conn, $sql_insert_user)) {
                $user_id = mysqli_insert_id($conn); // الحصول على الـ user_id الجديد
                
                // تحويل الأدوية المختارة إلى نصعشان اقدر تظهر في جدول 
                $selected_drugs_text = implode(', ', $medical_condition); // تحويل المصفوفة إلى نص مفصول بفواصل
                
                // إدخال المريض في جدول المرضى مع الأدوية المختارة كـ نص
                $sql_insert_patient = "INSERT INTO patients (user_id, doctor_id, firstName, lastName, email, age, password, PhoneNumber, gender, problem, medical_condition) 
                                       VALUES ('$user_id', '$doctor_id', '$firstName', '$lastName', '$email', '$age', '$password', '$PhoneNumber', '$gender', '$problem', '$selected_drugs_text')";
                if (mysqli_query($conn, $sql_insert_patient)) {
                    // الآن إضافة الأدوية المختارة للطبيب فقط في جدول الربط
                    foreach ($medical_condition as $drug_name) {
                        // جلب ID الدواء بناءً على الاسم
                        $sql_get_drug_id = "SELECT id FROM drugs WHERE name = '$drug_name'";
                        $result = mysqli_query($conn, $sql_get_drug_id);
                        if ($row = mysqli_fetch_assoc($result)) {
                            $drug_id = $row['id'];
                            // إدخال الدواء في جدول الربط
                            $sql_insert_drug_assignment = "INSERT INTO drug_assignments (patient_id, doctor_id, drug_id) 
                                                           VALUES ('$user_id', '$doctor_id', '$drug_id')";
                            if (!mysqli_query($conn, $sql_insert_drug_assignment)) {
                                echo "Error assigning drug to patient: " . mysqli_error($conn);
                            }
                        }
                    }
                    echo "Patient added successfully with the selected medical conditions.";
                    header("Location: DashboardDoctor.php?doctor_id=$doctor_id");
                    exit();
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Patients</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body {
            background-color: #f7f7f7;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 40px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .form-control {
            border-radius: 8px;
            padding: 15px;
            font-size: 16px;
            box-shadow: none;
        }
        .btn {
            border-radius: 8px;
            padding: 12px 20px;
            font-size: 16px;
            width: 100%;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .alert {
            border-radius: 8px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .header {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group label {
            font-size: 16px;
            font-weight: bold;
            color: #555;
        }
        .form-group select, .form-group input, .form-group textarea {
            font-size: 16px;
            padding: 10px;
            width: 100%;
            border-radius: 8px;
            margin-top: 5px;
        }
        .btn-block {
            width: 100%;
            text-align: center;
        }
    </style>
</head>

<body>
<div class="container">
    <div class="header">
        <h1>Add New Patient</h1>
    </div>
    <a class="btn btn-primary mb-3" href="DashboardDoctor.php">Go to Dashboard</a>

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
            <label for="firstName">First Name:</label><br> 
            <input type="text" id="firstName" name="firstName" class="form-control" value="<?= $firstName ?>" placeholder="Enter First Name...">
        </div>

        <div class="form-group">
            <label for="lastName">Last Name:</label><br> 
            <input type="text" id="lastName" name="lastName" class="form-control" value="<?= $lastName ?>" placeholder="Enter Last Name...">
        </div>

        <div class="form-group">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" class="form-control" value="<?= $email ?>" placeholder="Enter Email...">
        </div>

        <div class="form-group">
            <label for="age">Age:</label><br>
            <input type="number" id="age" name="age" class="form-control" value="<?= $age ?>" placeholder="Enter Age...">
        </div>

        <div class="form-group">
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" class="form-control" value="<?= $password ?>" placeholder="Enter Password...">
        </div>

        <div class="form-group">
            <label for="PhoneNumber">Phone Number:</label><br>
            <input type="text" id="PhoneNumber" name="PhoneNumber" class="form-control" value="<?= $PhoneNumber ?>" placeholder="Enter Phone Number...">
        </div>

        <div class="form-group">
            <label>Gender:</label><br>
            <label><input type="radio" name="gender" value="Male" <?= $gender == 'Male' ? 'checked' : '' ?>> Male</label>
            <label><input type="radio" name="gender" value="Female" <?= $gender == 'Female' ? 'checked' : '' ?>> Female</label>
        </div>

        <div class="form-group">
            <label for="problem">Problem Description:</label><br>
            <textarea id="problem" name="problem" class="form-control" placeholder="Describe the problem..."><?= $problem ?></textarea>
        </div>

        <div class="form-group">
            <label for="name">Select Medicines:</label>
            <select name="name[]" class="form-control" multiple>
                <?php
                // جلب الأدوية من قاعدة البيانات
                $sql_medicines = "SELECT * FROM drugs";
                $res_medicines = mysqli_query($conn, $sql_medicines);
                while ($row = mysqli_fetch_assoc($res_medicines)) {
                    $selected = in_array($row['name'], $medical_condition) ? 'selected' : ''; 
                    echo "<option value='{$row['name']}' $selected>{$row['name']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="role">Choose Role:</label>
            <select name="role_display" class="form-control" disabled>
                <option value="">-- Select Role --</option>
                <option value="Patient" <?= $role == 'Patient' || empty($role) ? 'selected' : '' ?>>Patient</option>
            </select>
            <input type="hidden" name="role" value="<?= $role ?>">
        </div>

        <br><br>
        <div class="form-group">
            <button type="submit" class="btn btn-success btn-block">Save Patient</button>
        </div>
    </form>   
</div>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>