<?php
session_start(); // store user information once logged-in
require("db.php");

// check if existing user is logged in
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: home.php");
    exit;
}

function login($username, $password, $connection)
{
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $succeeded = false;

    echo "in login";

    if ($stmt = $connection->prepare($sql)) {
        echo "preparing mysql statement";

        // Bind variables to the prepared statement as parameters ?
        mysqli_stmt_bind_param($stmt, "s", $param_username);

        // Set parameters
        $param_username = $username;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            echo "executed mysql statement";
            // Store result
            mysqli_stmt_store_result($stmt);

            // Check if username exists, if yes then verify password
            if (mysqli_stmt_num_rows($stmt) == 1) {
                echo "Found user";
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                if (mysqli_stmt_fetch($stmt)) {
                    echo "Fetched values into vars";
                    if (md5($password) === $hashed_password) {
                        echo "Verified password matching";
                        // Password is correct, so start a new session
                        session_start();

                        // Store data in session variables
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["username"] = $username;

                        $succeeded = true;

                        // Redirect user to welcome page
                        header("location: home.php");
                    }
                }
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        echo htmlspecialchars($connection->error);
    }
    return $succeeded;
}


$username = $password = "";
$username_err = $password_err = $login_err = "";

// login in inputted credentials
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement

        $login_succeeded = login($username, $password, $conn);
        if (!$login_succeeded) {
            $login_err = "Invalid username or password";
        }
    }
    // Close connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font: 14px sans-serif;
        }

        .wrapper {
            width: 360px;
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <h2>Login</h2>
    <p>Please fill in your credentials to login.</p>

    <?php
    if (!empty($login_err)) {
        echo '<div class="alert alert-danger">' . $login_err . '</div>';
    }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username"
                   class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>"
                   value="<?php echo $username; ?>">
            <span class="invalid-feedback"><?php echo $username_err; ?></span>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password"
                   class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
            <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Login">
        </div>
        <p>Don't have an account? <a href="signup.php">Sign up now</a>.</p>
    </form>
</div>
</body>
</html>
