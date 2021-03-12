<?php

    include "../config/setup.php";
    session_start();
    if (isset($_SESSION["username"]))
        if ($_SESSION["username"] != "")
            header('location:../index.php');
    if (isset($_POST["log_submit"]))
        if ($_POST["log_submit"] == "ok" && trim($_POST["log_user"]) != '' && trim($_POST["password"]) != '')
            check_login($bd);
        else
        {
            $_SESSION["log_error"] = "Please fill all the zones!";
            refrech();
        }
   

function check_login($bd){
    $req = $bd->prepare("SELECT * FROM `user` WHERE username = ? AND `password` = ?");
    $req->execute(array($_POST["log_user"], hash('whirlpool', $_POST["password"])));
    $table = $req->fetch();
    if ($table != '')
    {
        if ($table["Verification"] > 1)
        {
            $_SESSION["log_error"] = "Please Verify you email!";
            refrech();
        }
        else
        {
            $_SESSION["username"] = $table["username"];
            $_SESSION["id"] = $table["id_user"];
            $_SESSION["fullname"] = $table["fullname"];
            $_SESSION["log_error"] = "";
            header('location:../index.php');
        }
    }
    else
    {
        $_SESSION["log_error"] = "The password or the login is incorrect!";
        refrech();
    }
} 

function refrech() {
    echo"<script> location.href = 'login.php' </script>";
    exit();
}

?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/login.css">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    </head>
    <body>
       <header> <div class="container-fluid ">Camagru</div></header>
            <form class="form-signin" method="POST" id="log">
                <div class="form-group">
                    <img src="../img_using/img-01.png" class="img-fluid"/>
                </div>
                <div class="form-group">
                    <p><?php if(isset($_SESSION["log_error"]))echo $_SESSION["log_error"]; ?></p>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="log_user" placeholder="Username">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password">
                    <span> Forgot your password?</span>  <label class="to-create" onclick="reset()">Reset Password</label>
                </div>
                <div class="form-group">
                    <button type="submit" class="col-sm btn btn-primary" name="log_submit" value="ok">Submit</button>
                </div>
                <span >new here? </span> <label class="to-create" onclick="login()">Create account</label>
            </form>
            <script type="text/javascript" src="../js/login.js"></script>
            <footer style="float:right">@amanar 2020</footer>
    </body>
</html>

<?php
        $_SESSION["log_error"] = "";
?>