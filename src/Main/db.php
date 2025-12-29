<?php
$host = 'db';
$user = 'root';
$pass = 'root';
$db   = 'hospitals';

$conn = mysqli_connect($host, $user, $pass);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// إنشاء قاعدة البيانات
$createDB = "CREATE DATABASE IF NOT EXISTS $db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if (!mysqli_query($conn, $createDB)) {
    die("Error creating database: " . mysqli_error($conn));
}

mysqli_select_db($conn, $db);
mysqli_query($conn, "SET NAMES 'utf8mb4'");

// دالة لإنشاء الجداول مع معالجة الخطأ فوراً للتوقف عند وجود مشكلة
function createTable($conn, $sql, $tableName) {
    if (!mysqli_query($conn, $sql)) {
        die("Fatal Error: Could not create table '$tableName': " . mysqli_error($conn));
    }
}

// 1. جدول Users (الأساسي) - سنستخدم INT UNSIGNED للجميع
$createUsersTable = "CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(30) NOT NULL,
    lastName VARCHAR(30) NOT NULL,
    email VARCHAR(191) NOT NULL UNIQUE,
    password VARCHAR(500) NOT NULL,
    phoneNumber VARCHAR(30),
    role ENUM('doctor', 'patient', 'Pharmacists') NOT NULL
) CHARACTER SET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
createTable($conn, $createUsersTable, "users");

// 2. جدول Doctors (يجب أن يكون UNSIGNED ليطابق الربط مستقبلاً)
$createDoctorsTable = "CREATE TABLE IF NOT EXISTS doctors (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(30) NOT NULL,
    lastName VARCHAR(30) NOT NULL,
    email VARCHAR(191) NOT NULL UNIQUE,
    password VARCHAR(500) NOT NULL,
    phoneNumber VARCHAR(30),
    role ENUM('doctor') DEFAULT 'doctor'
) CHARACTER SET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
createTable($conn, $createDoctorsTable, "doctors");

// 3. جدول Patients (تعديل user_id و doctor_id ليكونوا UNSIGNED)
$createPatientsTable = "CREATE TABLE IF NOT EXISTS patients (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    doctor_id INT UNSIGNED,
    firstName VARCHAR(191) NOT NULL,
    lastName VARCHAR(191) NOT NULL,
    email VARCHAR(191) NOT NULL,
    age INT,
    password VARCHAR(255) NOT NULL,
    phoneNumber VARCHAR(30),
    gender ENUM('male', 'female') NOT NULL,
    problem TEXT NOT NULL,
    role ENUM('doctor', 'patient', 'pharmacist') NOT NULL DEFAULT 'patient',
    medical_condition TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE SET NULL
) CHARACTER SET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
createTable($conn, $createPatientsTable, "patients");

// 4. جدول Drugs
$createDrugsTable = "CREATE TABLE IF NOT EXISTS drugs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    pharmacist_id INT UNSIGNED,
    FOREIGN KEY (pharmacist_id) REFERENCES users(id) ON DELETE SET NULL
) CHARACTER SET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
createTable($conn, $createDrugsTable, "drugs");

// 5. جدول DrugAssignments (تعديل جميع الـ IDs لـ UNSIGNED)
$createDrugAssignmentsTable = "CREATE TABLE IF NOT EXISTS drug_assignments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    patient_id INT UNSIGNED,
    drug_id INT UNSIGNED,
    doctor_id INT UNSIGNED,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (drug_id) REFERENCES drugs(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE
) CHARACTER SET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
createTable($conn, $createDrugAssignmentsTable, "drug_assignments");

?>