<?php
require("db.php");

if (isset($_POST['signup']) && !empty($_POST['signup'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);


    if (!empty($_POST['email']) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Please Enter Valid Email ID";
    }

    if (!empty($_POST['password']) && strlen($password) < 6) {
        $password_error = "Password must be minimum of 6 characters!";
    }

    if (!empty($_POST['password']) && !empty($_POST['cpassword']) && $password != $cpassword) {
        $cpassword_error = "ERROR! Password and Confirm Password does not match!";
    }

    if (!$email_error && !$password_error && !$cpassword_error ) {
        $query = mysqli_query($conn, "INSERT INTO users(username, password) VALUES('" . $email . "', '" . md5($password) . "')") ;
        header ( "location: home.php");
        exit;
    } else {
        echo "ERROR! Failed to create username!". mysqli_error($conn);
    }
}


mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('head.php') ?>
</head>
<?php include('navbar.php') ?>

<body>
<div class="container">
    <div class="grid-container">
        <form action="" method="post">
            <div class="medium-6 cell">
                <label>Email
                    <input type="email" name="email" class="form-control" value="" maxlength="30" required="">
                </label>
                <span class="text-danger"><?php if (isset($email_error)) echo $email_error; ?></span>
            </div>
            <div class="medium-6 cell">
                <label>Password
                    <input type="password" name="password" class="form-control" value="" maxlength="8" required="">
                </label>
                <span class="text-danger"><?php if (isset($password_error)) echo $password_error; ?></span>
            </div>
            <div class="medium-6 cell">
                <label>Confirm Password
                    <input type="password" name="cpassword" class="form-control" value="" maxlength="8" required="">
                </label>
                <span class="text-danger"><?php if (isset($cpassword_error)) echo $cpassword_error; ?></span>
            </div>
            <div class="medium-6 cell">
                <input type="submit" class="btn" name="signup" value="submit">
                Already have a account?
                <a href="login.php" class="btn">login</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
</div>
</body>
</html>
