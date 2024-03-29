<?php
session_start();

// Check if the user is already logged in, redirect to home page
// if (isset($_SESSION["user_id"])) {
//     header("Location: index.php");
//     exit();
// }

require_once('db.php'); // Include your database connection file

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate the login credentials
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Use prepared statement to avoid SQL injection
    $query = "SELECT id FROM users WHERE username = ? AND password = ?";
    $stmt = mysqli_prepare($conn, $query);
    
    // Bind parameters
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    
    // Execute the statement
    mysqli_stmt_execute($stmt);
    
    // Bind result variable
    mysqli_stmt_bind_result($stmt, $user_id);

    // Fetch the result
    mysqli_stmt_fetch($stmt);

    if ($user_id) {
        $_SESSION["user_id"] = $user_id;
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Add your CSS styles or link to Bootstrap here -->
</head>

<body>
    <div class="container">
        <h2>Login</h2>
        <?php
        // Display error message if there is any
        if (isset($error)) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
        ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
    </div>
</body>

</html>
