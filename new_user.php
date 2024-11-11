<?php
// session start here...
session_start();
include_once 'validate.php';


$username = test_input($_POST['user']);
$password = test_input($_POST['pwd']);
$repeatPassword = test_input($_POST['repeat']);

//make sure they match!
if ($password !== $repeatPassword) {
    echo "Passwords do not match.";
    exit();
}

// create the password_hash using the PASSWORD_DEFAULT argument
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// login to the database
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "softball";

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// making new user is not already in the database
$sql = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "Username already exists.";
    exit();
}

// insert username and password hash into db (put the username in the session
// or make them login)

$sql = "INSERT INTO users (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $hashed_password);

if ($stmt->execute()) {
    $_SESSION['username'] = $username;
    echo "Registration successful! Welcome, " . htmlspecialchars($username) . "!";
    header("location: index.php");
} else {
    echo "Registration failed. Please try again.";
}


$stmt->close();
$conn->close();
?>

