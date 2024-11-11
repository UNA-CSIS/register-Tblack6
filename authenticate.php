<?php
// Start session
session_start();

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $input_username = $_POST['user'];
    $input_password = $_POST['pwd'];

    // Login to the softball database
    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $dbname = "softball";

    // Connect to the database
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Select password from users where username = <what the user typed in>
    $sql = "SELECT password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $input_username);
    $stmt->execute();
    $stmt->store_result();

    // If no rows, then username is not valid (but don't tell Mallory), just send her back to the login
    if ($stmt->num_rows > 0) {
        
        $stmt->bind_result($db_password_hash);
        $stmt->fetch();

        // password_verify(password from form, password from db)
        if (password_verify($input_password, $db_password_hash)) {
            // If good, put username in session
            $_SESSION['username'] = $input_username;
            
            header("location: games.php");
            exit;
        } else {
            // Otherwise send back to login
            header("location: index.php");
            exit;
        }
    } else {
       
        header("location: index.php");
        exit;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    header("location: index.php");
    exit;
}
