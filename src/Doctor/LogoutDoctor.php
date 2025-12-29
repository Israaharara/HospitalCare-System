<?php
session_start();  // بدء الجلسة

// قم بتدمير الجلسة
session_unset();  // إلغاء جميع المتغيرات المخزنة في الجلسة
session_destroy();  // تدمير الجلسة

// إعادة توجيه المستخدم إلى صفحة تسجيل الدخول أو الصفحة الرئيسية
header("Location: ../Main/Main.php");  // تأكد أن المسار صحيح
exit();
/*   
$sql =" CREATE TABLE patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    firstName   VARCHAR(30) NOT NULL,
    lastName    VARCHAR(30) NOT NULL,
    email       VARCHAR(255) NOT NULL,
    age INT,
    password    VARCHAR(255) NOT NULL,
    phoneNumber VARCHAR(15),
    gender ENUM('male', 'female') NOT NULL,
    problem TEXT NOT NULL,
    medicalCondition TEXT,
    role ENUM('doctor', 'patient', 'pharmacist') NOT NULL DEFAULT 'patient',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
        $res = mysqli_query($conn, $sql);
        
        if (!$res) {
            echo "Error: " . mysqli_error($conn); // عرض رسالة الخطأ
            die();
        }*/

        /*if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}*/
/*if (isset($_GET['del'])) {
    $id = $_GET['del'];
    $sql = "DELETE FROM patients WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'> Patient deleted successfully</div>";
    }
       // echo "you will delete the record number" .$_GET['id'];
    }
*/
?>
