<?php
    include "../config/setup.php";

    session_start();

    if (isset($_SESSION["username"]))
        if ($_SESSION["username"] != "")
            header('location:../index.php');
    if (isset($_POST["reset_pass"], $_POST["user_rese"]))
        if ($_POST["reset_pass"] == "ok" && trim($_POST["user_rese"]) != "")
            check_email($bd);
    else
    {
        $_SESSION["user_error"] = "please enter The Username!";
        refrech();
    }


function check_email($bd){
    $req = $bd->prepare("SELECT * FROM `user` WHERE username = ?");
    $req->execute(array($_POST["user_rese"]));
    $table = $req->fetch();
    if ($table != ''){
        $restpass = hash('whirlpool', date("Y-m-d H:i:s").rand(2,9999));
        $req = $bd->prepare("UPDATE `user` SET resetpass = ? WHERE username = ?");
        $req->execute(array($restpass, $_POST["user_rese"]));
        $verrificationlink = "http://".$_SERVER["HTTP_HOST"]."/php/reset_password.php?resetpass=".$restpass."&username=".$_POST["user_rese"];
        $body = "<p> Please click the link bellow to Reset your Password </p>";
        $body .= "<a href='".$verrificationlink."' target=_blank>".$verrificationlink."</a>";
        $subject = "Reset Password";
        $header = "Content-type: text/html";
        mail($table["email"], $subject, $body, $header);
        $_SESSION["user_succes"] = "An email has been send";
        refrech();
    }
    else
    {
        $_SESSION["user_error"] = "Invalid Username";
        refrech();
    }
}

function refrech() {
    echo"<script> location.href = 'sendmail_pass.php' </script>";
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
        <header><div class="container-fluid ">Camagru</div></header>
            <form class="form-signin" method="POST" id="reset">
                <div class="form-group">
                    <img src="../img_using/img-01.png" class="img-fluid"/>
                </div>
                <div class="form-group">
                    <p class="succes"><?php if(isset($_SESSION["user_succes"]))echo $_SESSION["user_succes"]; ?></p>
                    <p><?php if(isset($_SESSION["user_error"]))echo $_SESSION["user_error"]; ?></p>
                </div>
                <input type="text" class="form-control" name="user_rese" placeholder="Username">
                <div class="form-group">
                    <button type="submit" class="col-sm btn btn-primary" name="reset_pass" value="ok">Submit</button>
                </div>
                <label class="to-create" onclick="signup()">Back</label>
            </form>
            <script type="text/javascript" src="../js/login.js"></script>
            <footer style="float:right"> @amanar 2020 </footer>
    </body>
</html>

<?php

    $_SESSION["user_succes"] = "";
    $_SESSION["user_error"] = "";
?>