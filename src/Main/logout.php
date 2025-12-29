<?php 
session_start();
session_unset();
session_destroy();
header("location: Main.php");
/*وطبعا هان صفحة لوقوت الي في نهاية ؤاح توديني علي الصفحة الرئيسية للمشروع */