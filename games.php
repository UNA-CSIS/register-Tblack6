<?php
session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    header("location: index.php");
    exit;
}


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "softball";

//check connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//fetch data from games
$sql = "SELECT * FROM games";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h1>Welcome,
            <?php
// put your code here
            echo htmlspecialchars($_SESSION['username']);
            ?>|
        </h1>
        <h2>Game List</h2>

        <?php
        if ($result->num_rows > 0) {
            echo '<table border="1">
                    <tr>
                        <th>ID</th>
                        <th>Opponent</th>
                        <th>Site</th>
                        <th>Result</th>
                    </tr>';
            while ($row = $result->fetch_assoc()) {
                echo '<tr>
                        <td>' . htmlspecialchars($row['id']) . '</td>
                        <td>' . htmlspecialchars($row['opponent']) . '</td>
                        <td>' . htmlspecialchars($row['site']) . '</td>
                        <td>' . htmlspecialchars($row['result']) . '</td>
                      </tr>';
            }
            echo '</table>';
        } else {
            echo '<p>No games found.</p>';
        }


        $result->free();
        $conn->close();
        ?>
    </body>
</html>
