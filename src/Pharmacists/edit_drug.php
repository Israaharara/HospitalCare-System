<?php
session_start();
include __DIR__ . '/../Main/db.php';
//تعديل البيانات تعت الدوا ممكن عادي اعدلها وراح تتعدلعلي طول في الجدول عادي 

if (!isset($_GET['id'])) {
    header("Location: pharmacist_dashboard.php");
    exit();
}

$id = $_GET['id'];

// جلب بيانات الدواء
$sql = "SELECT * FROM drugs WHERE id = '$id'";
$res = mysqli_query($conn, $sql);
$drug = mysqli_fetch_assoc($res);

$name = $drug['name'];
$description = $drug['description'];

$errors = [];

// إذا تم إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name        = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
    $description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '';
   
    // التحقق من الحقول
    if (empty($name)) {
        $errors[] = "Drug name is required.";
    }
    if (empty($description)) {
        $errors[] = " Description is required.";
    }

    // إذا لم توجد أخطاء، نقوم بتحديث الدواء
    if (empty($errors)) {
        $sql = "UPDATE drugs SET name = '$name', description = '$description'
                 WHERE id = '$id'";
        
        if (mysqli_query($conn, $sql)) {
            echo "Drug updated successfully.";
            header("Location: pharmacist_dashboard.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Drug</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
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
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .alert-danger {
            border-radius: 8px;
        }
        label {
            font-weight: bold;
        }
        h1 {
            font-size: 1.8rem;
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        /* تعديل حجم صندوق الوصف */
        #description {
            height: 150px;
        }
    </style>
</head>

<body>

<div class="container">
    <h1>Update Drug Information</h1>

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
            <label for="name">Drug Name:</label>
            <input type="text" id="name" name="name" class="form-control" value="<?= $drug['name'] ?>" >
        </div>

        <div class="mb-3">
            <label for="description">Description:</label>
            <textarea id="description" name="description" class="form-control"><?= $drug['description'] ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update Drug</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
