<?php
session_start();
include __DIR__ . '/../Main/db.php';
//هان بضيف عادي دوا جديد نفس الخطوات تقريبا القبل 
$name = $description = $pharmacist_id = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name          = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
    $description   = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '';
    $pharmacist_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // التحقق من صحة البيانات المدخلة
    if (empty($name) || empty($description)) {
        $errors[] = "All fields are required.";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO drugs (pharmacist_id , name, description)
         VALUES ('$pharmacist_id' , '$name', '$description')";
        
        // التحقق من تنفيذ الاستعلام
        if (mysqli_query($conn, $sql)) {
            header("Location: pharmacist_dashboard.php?pharmacist_id=$pharmacist_id");
            exit();
        } else {
            $errors[] = "Error adding drug: " . mysqli_error($conn);
            echo "Error: " . mysqli_error($conn); // اطبع الرسالة لمعرفة السبب
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Drug</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f7fc;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-control {
            border-radius: 8px;
            padding: 12px;
            font-size: 16px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            width: 100%;
            padding: 14px;
            font-size: 18px;
            border-radius: 8px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
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
    </style>
</head>

<body>
<div class="container">
    <div class="header">
        <h1>Add New Drug</h1>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a class="btn btn-secondary" href="pharmacist_dashboard.php">Dashboard</a>
    </div>

    <form action="" method="POST">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="drug_name">Drug Name</label>
            <input type="text" class="form-control" id="drug_name" name="name" value="<?php echo $name; ?>" placeholder="Enter drug name">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" placeholder="Enter drug description"><?php echo $description; ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Add Drug</button>
    </form>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
