<?php
session_start();  // بدء الجلسة

// قم بتدمير الجلسة
session_unset();  // إلغاء جميع المتغيرات المخزنة في الجلسة
session_destroy();  // تدمير الجلسة

// إعادة توجيه المستخدم إلى صفحة تسجيل الدخول أو الصفحة الرئيسية
header("Location: ../Main/Main.php");  // تأكد أن المسار صحيح
exit();
/*
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM patients WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'> Patient deleted successfully</div>";
    }
       // echo "you will delete the record number" .$_GET['id'];
    }

      /*   $sql = "CREATE TABLE patients (
            id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            Name VARCHAR(30) NOT NULL,
            email VARCHAR(30) NOT NULL,
            age INT,
            password VARCHAR(30) NOT NULL,
            phoneNumber VARCHAR(30),
            gender VARCHAR(10) NOT NULL,
            problem TEXT NOT NULL,
            medicalCondition VARCHAR(255),
            role VARCHAR(255)

        )";
        $res = mysqli_query($conn, $sql);
        
        if (!$res) {
            echo "Error: " . mysqli_error($conn); // عرض رسالة الخطأ
            die();
        }
        

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DashboardPatient</title>

</head>
<body>
    <div class= "d=flex justify-content-between align-items-center mb-3">
    <h1>Dashboard</h1>
    <div>
        <a class="btn btn-sm btn-primary" href ="edit_Patients.php?id=<?= $row ['id']?>">Edit</a>    
   </div>
    <a class="bbtn btn-primary" href ="logoutPatients.php">Logout</a>
</div>
    <table>
      <thead>
          <tr>
             <th>#</th>
             <th>NameDoctor</th>        
             <th>Phone_number</th>
             <th>Gender</th>
             <th>Problem</th>
             <th>Medical_Condition</th>
             <th>Action</th>
          </tr>
      </thead>
      <tbody>
        <?php
            $sql = "SELECT * FROM patients";
            $res = mysqli_query($conn , $sql);
            if(mysqli_num_rows($res)>0){
                $i = 1;
            while($row = mysqli_fetch_assoc($res)){
      ?>
        <tr>
            <td><?php 
           // echo $row['id']
            echo $i; $i++;
            ?></td>
            <td><?= $row['Name']?></td>
            <td><?= $row['email']?></td>
            <td><?= $row['age']?></td>
            <td><?= $row['password']?></td>
            <td><?= $row['phoneNumber']?></td>
            <td><?= $row['gender']?></td>
            <td><?= $row['problem']?></td>
            <td><?= $row['medicalCondition']?></td>
        
</tr>
<?php } } else{?>
    <tr>
        <td colspane="9" class="text-center"> No data found</td>
    </tr>
    <?php } ?>
</tbody>
    </table>
</body>
</html>*/
?>