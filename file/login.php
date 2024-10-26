<?php 

session_start();

$servername = "localhost"; 
$dbname = "yosifirst"; // Your database name
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Prepare SQL query to fetch user data
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify the password (using password_verify for hashed passwords)
        if (password_verify($pass, $row['password'])) {
            // Success: Store user info in session
            $_SESSION['username'] = $user;
            echo "success"; // Send success message for JS handling
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found. Please sign up.";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
