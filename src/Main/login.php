<?php
session_start();
include "db.php";

$email = $password = '';
$errors = [];

// التحقق إذا كان المستخدم مسجلاً بالفعل عشان يفوت علي الصفحات 
if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
    $role = $_SESSION['user']['role']; // الحصول على الدور من الجلسة بناءا علي اليوزر والرول
    // التوجيه بناءً على الدور
    if ($role == 'doctor') {
        header('Location: ../Doctor/DashboardDoctor.php');
        exit();
    } elseif ($role == 'pharmacists') {
        header('Location: ../Pharmacists/pharmacist_dashboard.php');
        exit();
    } elseif ($role == 'patient') {
        header('Location: ../Patients/DashboardPatients.php');
        exit();
    }
}

// تحقق إذا كان النموذج قد تم إرساله
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = trim($_POST['password']); // إزالة المسافات الزائدة

        // تحقق من وجود البريد الإلكتروني في قاعدة البيانات
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $res = mysqli_query($conn, $sql);

        if ($res && mysqli_num_rows($res) > 0) {
            $user = mysqli_fetch_assoc($res);

            // تحقق من كلمة المرور باستخدام password_verify() لانه لازم يكون من نفس النوع مشفريين لازم 
            if (password_verify($password, $user['password'])) {
//اذا كانت الكلمة صحيحة وراح يروح يخزلي البيانات في سيشن 
                $_SESSION['user'] = [
                    'username' => $user['firstName'] . ' ' . $user['lastName'],
                    'email' => $user['email'],
                    'role' => strtolower($user['role']),
                    'id' => $user['id']
                ];

                $role = $_SESSION['user']['role']; // الحصول على الدور من الجلسة

                // التوجيه بناءً على الدور
                if ($role == 'doctor') {
                    header('Location: ../Doctor/DashboardDoctor.php');
                    exit();
                } elseif ($role == 'pharmacists') {
                    header('Location: ../Pharmacists/pharmacist_dashboard.php');
                    exit();
                } elseif ($role == 'patient') {
                    header('Location: ../Patients/DashboardPatients.php');
                    exit();
                } else {
                    $errors[] = "Role not defined for this user.";
                }
            } else {
                $errors[] = "Incorrect password.";
            }
        } else {
            $errors[] = "Email not found.";
        }
    } else {
        $errors[] = "Please provide both email and password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        
        header {
            background-color: #4CAF50;
            padding: 15px 0;
            color: white;
            font-size: 18px;
        }

        header .container {
            width: 80%;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header a {
            color: white;
            text-decoration: none;
            font-size: 24px;
            font-weight: bold;
        }

        header nav ul {
            list-style: none;
            display: flex;
        }

        header nav ul li {
            margin-left: 20px;
        }

        header nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s;
        }

        header nav ul li a:hover {
            color: #ffcc00;
        }

        main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('https://www.w3schools.com/w3images/hospital.jpg') no-repeat center center;
            background-size: cover;
        }

        .login-container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }

        .login-container h2 {
            color: #333;
            margin-bottom: 30px;
        }

        .login-container input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .login-container button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-container button:hover {
            background-color: #45a049;
        }

        .errors {
            color: #ff4d4d;
            text-align: left;
            margin-bottom: 20px;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 15px 0;
        }

        footer a {
            color: #ffcc00;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <header>
        <div class="container">
            <a href="Main.php">HospitalCare</a>
            <nav>
                <ul>
                    <li><a href="Main.php">Home</a></li>
                    <li><a href="register.php">Register</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="login-container">
            <h2>Login to your account</h2>
            
            <!-- عرض الأخطاء في حالة وجودها -->
            <?php if (isset($errors) && count($errors) > 0) { ?>
                <div class="errors">
                    <?php foreach ($errors as $error) { ?>
                        <p><?php echo $error; ?></p>
                    <?php } ?>
                </div>
            <?php } ?>

            <form action="" method="POST">
                <div>
                    <input type="email" id="email" name="email" placeholder="Email..." value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                </div>
                <div>
                    <input type="password" id="password" name="password" placeholder="Password..." value="" required>
                </div>
                <div>
                    <button type="submit">Login</button>
                </div>
            </form>
        </div>
    </main>

    <footer>
    <p>&copy; 2025 All Rights Reserved to <a href="#">Jumana Dahdooh ad Esraa Hraraa</a></p>
    </footer>

</body>
</html>
