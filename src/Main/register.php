<?php 
    include 'db.php';
    session_start();
    /*
   هان في صفحة التسجيل الدخول راح ندخل البيانات وراح تنحفظ في 3 جداول بناءا علي روول اذا صيدلي في جدول الصيديليىة واذا دكتور في جدول الدكتور 
   ةلكن في كلا الوجهين راح تخزن في جدول ايوزر الي علي اساسه راح افحص كل البيانات اذا موجودة او لا ؟؟
   وبعدين حسب الايميل راح يروح افحص الرو اذا دكتور علي وجه الدكتور او صيديلي كذلك */
   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['FirstName'])){
        $errors =[]; 
     // var_dump($_POST);
          // تطابق الأسماء بين النموذج والكود
          $FirstName   = isset($_POST['FirstName']) ? htmlspecialchars($_POST['FirstName']) : '';
          $LastName    = isset($_POST['LastName']) ? htmlspecialchars($_POST['LastName']) : '';
          $Password    = isset($_POST['Password']) ? htmlspecialchars($_POST['Password']) : '';  // تصحيح اسم المتغير
          $email       = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
          $PhoneNumber = isset($_POST['PhoneNumber']) ? htmlspecialchars($_POST['PhoneNumber']) : '';
          $role        = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING) ?? '';

        if(empty($FirstName)){
            $errors[] = "FirstName field is required :(";
        } 

        if(empty($LastName)){
            $errors[] = "LastName field is required :( ";
        } 

        if (empty($Password) ) {
            $errors[] = "Password field is required :(";
        }

        if (empty($email) ) {
            $errors[] = "Password field is required :(";
        }

        if(empty($PhoneNumber)){ 
            $errors[] = "PhoneNumber field is required :(";
        }

        if (empty($role) ) {
            $errors[] = "ٌRole field is required :( ";
        }


// إذا كانت لا توجد أخطاء
if (empty($errors)) {
    $Password = password_hash($Password, PASSWORD_DEFAULT);

    // إدخال المستخدم في جدول users
    $sql_users = "INSERT INTO users (firstName, lastName, password, email, phoneNumber, role) 
                  VALUES ('$FirstName', '$LastName', '$Password', '$email', '$PhoneNumber', '$role')";
    if (mysqli_query($conn, $sql_users)) {
        $_SESSION['user_id'] = mysqli_insert_id($conn);  // تخزين ID المستخدم
        $_SESSION['role'] = $role;

        // التوجيه حسب الدور
        if ($role == "Pharmacists") {//اذا كات صيدلي روح خزلي اايه في جدول الصيدلي وبعدين علي صفحة الرئيسية للصيدلية 
            $sql_pharmacists = "INSERT INTO Pharmacists (firstName, lastName, password, email, phoneNumber, role) 
            VALUES ('$FirstName', '$LastName', '$Password', '$email', '$PhoneNumber', '$role')";
               
               if (mysqli_query($conn, $sql_pharmacists)) {
                $_SESSION['user_id'] = mysqli_insert_id($conn);  // تخزين ID المستخدم
                $_SESSION['role'] = $role;

                echo "Registration successful for Pharmacists!";

                } else {
                echo "Error in Pharmacists table: " . mysqli_error($conn);
                }     
            
            } elseif ($role == "Doctor") {//واذا كان طبيب خزللي اياه في جدول الاطباء وروح وديني علي صفحة الاطباء 
                $sql_doctor = "INSERT INTO doctors (firstName, lastName, password, email, phoneNumber, role) 
                VALUES ('$FirstName', '$LastName', '$Password', '$email', '$PhoneNumber', '$role')";
               
               if (mysqli_query($conn, $sql_doctor)) {
                $_SESSION['doctor_id'] = mysqli_insert_id($conn);  // تخزين ID المستخدم
                $_SESSION['role'] = $role;
                //هان اذا كان التخزين الايدي للدكتور مزبوط اطبعلي جملة هادي 
                echo "Registration successful for Doctor!";
                } else {
                     echo "Error in Doctor table: " . mysqli_error($conn);//زا  ا لا اطبعلي جملة الخطا 
                }              
              }
                  
                }

                }}
                }
                
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f2f2f2;
            color: #333;
            line-height: 1.6;
        }

        header {
            background-color: #4CAF50;
            color: white;
            padding: 15px 0;
        }

        .container {
            width: 90%;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header a {
            color: white;
            font-size: 24px;
            font-weight: 600;
            text-decoration: none;
        }

        nav ul {
            list-style: none;
            display: flex;
        }

        nav ul li {
            margin-left: 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s;
        }

        nav ul li a:hover {
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

        form {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
            width: 500px;
            text-align: center;
        }

        form h1 {
            font-size: 30px;
            margin-bottom: 20px;
            color: #333;
        }

        .form-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .form-row div {
            width: 48%;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="PhoneNumber"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        .errors {
            color: #ff4d4d;
            margin-bottom: 20px;
            text-align: left;
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
                    <li><a href="login.php">Login</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <form action="" method="POST">
            <h1>Register New Account</h1>

            <!-- عرض الأخطاء في حالة وجودها -->
            <?php if(isset($errors) && count($errors) != 0) { ?>
                <div class="errors">
                    <?php foreach($errors as $error) { ?>
                        <p><?php echo $error; ?></p>
                    <?php } ?>
                </div>
            <?php } ?>

            <div class="form-row">
                <div>
                    <label for="FirstName">First Name:</label>
                    <input type="text" id="FirstName" name="FirstName" placeholder="First Name..." value="<?php echo isset($_POST['FirstName']) ? $_POST['FirstName'] : ''; ?>">
                </div>
                <div>
                    <label for="LastName">Last Name:</label>
                    <input type="text" id="LastName" name="LastName" placeholder="Last Name..." value="<?php echo isset($_POST['LastName']) ? $_POST['LastName'] : ''; ?>">
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Email..." value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                </div>
                <div>
                    <label for="PhoneNumber">Phone Number:</label>
                    <input type="text" id="PhoneNumber" name="PhoneNumber" placeholder="Phone Number..." value="<?php echo isset($_POST['PhoneNumber']) ? $_POST['PhoneNumber'] : ''; ?>">
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label for="Password">Password:</label>
                    <input type="password" id="Password" name="Password" placeholder="Password..." value="<?php echo isset($_POST['Password']) ? $_POST['Password'] : ''; ?>">
                </div>
                <div>
                    <label for="role">Role:</label>
                    <select name="role" id="role">
                        <option value="" disabled selected>-- Select Role --</option>
                        <option value="Doctor" <?php echo isset($_POST['role']) && $_POST['role'] == 'Doctor' ? 'selected' : ''; ?>>Doctor</option>
                        <option value="Pharmacists" <?php echo isset($_POST['role']) && $_POST['role'] == 'Pharmacists' ? 'selected' : ''; ?>>Pharmacists</option>
                    </select>
                </div>
            </div>

            <button type="submit">Register</button>
        </form>
    </main>

    <footer>
    <p>&copy; 2025 All Rights Reserved to <a href="#">Jumana Dahdooh ad Esraa Hraraa</a></p>
    </footer>

</body>
</html>
