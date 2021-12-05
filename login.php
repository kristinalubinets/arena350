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

    if ($stmt = $connection->prepare($sql)) {

        // Bind variables to the prepared statement as parameters ?
        mysqli_stmt_bind_param($stmt, "s", $param_username);

        // Set parameters
        $param_username = $username;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);

            // Check if username exists, if yes then verify password
            if (mysqli_stmt_num_rows($stmt) == 1) {
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                if (mysqli_stmt_fetch($stmt)) {
                    if (md5($password) === $hashed_password) {
                        // Password is correct, so start a new session
                        session_start();

                        // Store data in session variables
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["username"] = $username;

                        $succeeded = true;
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

function load_cart_into_session($conn)
{
    $sql = "SELECT ticket_id, event_id
        FROM user_tickets
        JOIN tickets ON user_tickets.ticket_id = tickets.id
        WHERE user_id = ?
        AND tickets.status = 'CART'";

    // query tickets in user's cart
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $_SESSION['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // sync tickets to session cart
    foreach ($result as $row) {
        $event_id = $row['event_id'];
        $ticket_id = $row['ticket_id'];
        if (!isset($_SESSION['cart'][$event_id])) {
            $_SESSION['cart'][$event_id] = array($ticket_id);
        } else {
            array_push($_SESSION['cart'][$event_id], $ticket_id);
        }
    }
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
        } else {
            // populate session cart for user
            load_cart_into_session($conn);

            // Redirect user to welcome page
            header("location: home.php");
        }
    }

    // Close connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("head.php") ?>
</head>
<?php include("navbar.php") ?>
<body>
<div class="container">
    <div class="grid-container">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <?php
        if (!empty($login_err)) {
            echo '<div class="callout alert">' . $login_err . '</div>';
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="medium-6 cell">
                <label>Username
                    <input type="text" name="username"
                           class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>"
                           value="<?php echo $username; ?>">
                </label>
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <div class="medium-6 cell">
                <label>Password
                    <input type="password" name="password"
                           class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                </label>
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="medium-6 cell">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="signup.php">Sign up now</a>.</p>
        </form>
    </div>
</div>
</body>
</html>
