<!DOCTYPE html>
<html lang="en">
<head>
<!-- هذه الصفحة بس واجهة للمستشفي فيها بعض الازرارا لتسجشل الدخلووالتسجيل وللخروج   -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page - Hospital</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            background-color: #f7f7f7;
        }

        header {
            background-color: #4CAF50;
            color: white;
            padding: 10px 0;
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
            text-align: center;
            padding: 50px 0;
            background: url('https://www.w3schools.com/w3images/hospital.jpg') no-repeat center center;
            background-size: cover;
            color: white;
        }

        main h1 {
            font-size: 60px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        main p {
            font-size: 20px;
            margin-bottom: 30px;
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
                    <li><a href="register.php">Register</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <h1>Welcome to Our Hospital</h1>
        <p>Your health is our priority. We provide exceptional care with compassion and innovation.</p>
        <p>From emergency care to specialized treatments, we are here for all your healthcare needs.</p>
        <p>Explore our services and get the care you deserve with our state-of-the-art facilities and expert team.</p>
    </main>

    <footer>
        <p>&copy; 2025 All Rights Reserved to <a href="#">Jumana Dahdooh ad Esraa Hraraa</a></p>
    </footer>

</body>
</html>
