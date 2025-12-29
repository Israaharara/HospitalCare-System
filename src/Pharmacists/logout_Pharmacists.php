<?php 
session_start();  // بدء الجلسة
session_unset();  // إلغاء جميع المتغيرات المخزنة في الجلسة
session_destroy();  
// إعادة توجيه المستخدم إلى صفحة تسجيل الدخول أو الصفحة الرئيسية
header("Location: ../Main/Main.php");  
exit();