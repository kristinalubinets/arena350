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
        $password_error = "Password must be minimum of 6 characters";
    }

    if (!empty($_POST['password']) && !empty($_POST['cpassword']) && $password != $cpassword) {
        $cpassword_error = "Password and Confirm Password doesn't match";
    }


    if ($query = mysqli_query($conn, "INSERT INTO users(username, password) VALUES('".$email."', '".md5($password)."')")) {
        echo "Success"; // redirect on success
        $_POST = array();
        exit;
    } else {
        echo "Failed to create username: " . mysqli_error($conn);
    }
}


mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>To register for the Sport Arena pls type the info: </title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-offset-2">
            <div class="page-header">
                <h2>To register for the Sport Arena pls type the info:</h2>
            </div>
            <p>Please fill all fields in the form</p>
            <form action="" method="post">
                <div class="form-group ">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="" maxlength="30" required="">
                    <span class="text-danger"><?php if (isset($email_error)) echo $email_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" value="" maxlength="8" required="">
                    <span class="text-danger"><?php if (isset($password_error)) echo $password_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="cpassword" class="form-control" value="" maxlength="8" required="">
                    <span class="text-danger"><?php if (isset($cpassword_error)) echo $cpassword_error; ?></span>
                </div>
                <input type="submit" class="btn btn-primary" name="signup" value="submit">
                Already have a account?<a href="login.php" class="btn btn-default">Login</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
