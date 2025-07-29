<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve attendance data from POST request
    $student_name = $_POST['student_name'];
    $student_age = $_POST['student_age'];
    $username = $_POST['username'];
    $bus_number = $_POST['bus_number'];
    $attendance_status = $_POST['attendance_status'];
    $attendance_date = $_POST['attendance_date'];

    // Insert the attendance record into the `attendance` table
    $query = "INSERT INTO attendance (student_name, student_age, username, bus_number, attendance_status, attendance_date)
              VALUES (:student_name, :student_age, :username, :bus_number, :attendance_status, :attendance_date)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'student_name' => $student_name,
        'student_age' => $student_age,
        'username' => $username,
        'bus_number' => $bus_number,
        'attendance_status' => $attendance_status,
        'attendance_date' => $attendance_date
    ]);

    echo 'Success';
}
