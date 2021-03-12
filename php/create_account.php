<?php
    include "../config/setup.php";
    session_start();
    $_SESSION["token"] = hash("whirlpool",date("y-m-d"));
    
    $table = $bd->query("SELECT * FROM `user`");

    if(!isset($_SESSION["id"]))
    {
        if (isset($_POST["insc_submit"], $_POST["ins_user"], $_POST["full_name"], $_POST["email"], $_POST["password1"], $_POST["password2"]))
        {
            if (isset($_POST["token"]) && $_SESSION["token"] == $_POST["token"])
            {
                if ($_POST["insc_submit"] == "ok" && trim($_POST["ins_user"]) != "" && trim($_POST["full_name"]) != "" && $_POST["email"] != "" && $_POST["password1"] != "" && $_POST["password2"] != "")
                    check_inscription($table, $bd);
                else
                {
                    $_SESSION["insc_error"] = "Please fill all the zones";
                    refrech();
                }
            }
        }
    }
    else
        header("location:../index.php");

function check_inscription($table, $bd){
    $index = 0;
    $uppercase = preg_match('@[A-Z]@', $_POST["password1"]);
    $lowercase = preg_match('@[a-z]@', $_POST["password1"]);
    $number    = preg_match('@[0-9]@', $_POST["password1"]);
    if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
    {
        if ($uppercase && $lowercase && $number && strlen($_POST["password1"]) >= 8 && strlen($_POST["password1"]) <= 20)
        {
            if(preg_match('/^[a-zA-Z ]{4,25}$/', $_POST["full_name"]))
            {
                if (preg_match('/^[a-z0-9]{6,10}$/', $_POST["ins_user"]))
                {
                    if ($_POST["password1"] == $_POST["password2"])
                    {
                        $_SESSION["pass_error"] = "";
                        $verri = hash('whirlpool',date('Y-m-d H:i:s').$_POST["ins_user"]);
                        if ($table != '')
                        {
                            foreach ($table as $valeur)
                            {
                                if ($valeur["username"] == $_POST["ins_user"])
                                {
                                    $index = 1;
                                    break;
                                }
                            }
                        }
                        if ($index == 0)
                        {
                            $req = $bd->prepare('INSERT INTO `user`(username, fullname, `password`, email, Verification, `notification`) VALUES(:username, :fullname, :pass, :email, :Verification, :notification)');
                            $req->execute(array(
                                'username' => trim($_POST["ins_user"]),
                                'fullname' => trim($_POST["full_name"]),
                                'pass' => hash('whirlpool',$_POST["password1"]),
                                'email' => $_POST["email"],
                                'Verification' => $verri,
                                'notification' => 1
                            ));
                            $_SESSION["insc_succes"] = "succes";

                            $verrificationlink = "http://".$_SERVER["HTTP_HOST"]."/php/verify.php?verify=".$verri;
                            $body = "<p> Please click the link bellow to verify your email </p>";
                            $body .= "<a href='".$verrificationlink."' target=_blank>".$verrificationlink."</a>";
                            $subject = "Verification email";
                            $header = "Content-type: text/html";
                            mail($_POST["email"], $subject, $body, $header);
                            header('location:login.php');
                        }
                        else
                        {
                            $_SESSION["insc_error"] = "Username already exist!";
                            refrech();
                        }
                    }
                    else
                    {
                        $_SESSION["pass_error"] = "The passwords doesn't match";
                        refrech();
                    }
                }
                else
                {
                    $_SESSION["insc_error"] = "Username must be between 6 and 10 and only lowercases and numbers";
                    refrech();
                }
            }
            else
            {
                $_SESSION["insc_error"] = "Name must be between 4 and 25 and only alpha";
                refrech();
            }
        }
        else
        {
            $_SESSION["pass_error"] = "The password must contain atleast:\n1 lowercase\n1 uppercase\n1 number\n8-20 caractere";
            refrech();
        }
    }
    else
    {
        $_SESSION["email_error"] = "The Email format is Invalide!";
        refrech();
    }
}

function refrech() {
    echo"<script> location.href = 'create_account.php' </script>";
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
       <header> <div class="container-fluid ">Camagru</div> </header>
            <form class="form-signin creat" method="POST">
                <img src="../img_using/img-01.png" class="img-fluid"/>
                <div class="form-group">
                    <p><?php  if(isset($_SESSION["insc_succes"]))echo $_SESSION["insc_succes"]; ?></p>
                    <p><?php  if(isset($_SESSION["insc_error"]))echo $_SESSION["insc_error"]; ?></p>
                    <input type="hidden" value="<?php echo $_SESSION["token"]; ?>" name="token">
                </div>
                <div class="container">
                    <div class="row">
                        <input type="text" class="form-control col-sm-6" name="ins_user" placeholder="Username">
                        <input type="text" class="form-control col-sm-6" name="full_name" placeholder="name">
                    </div>
                </div>
                <div class="form-group">
                    <p><?php if(isset($_SESSION["email_error"])) echo $_SESSION["email_error"]; ?></p>
                </div>
                <input type="email" class="form-control" name="email" placeholder="Email">
                <div class="form-group">
                    <p><?php if(isset($_SESSION["pass_error"])) echo $_SESSION["pass_error"]; ?></p>
                </div>
                <div class="container">
                    <div class="row">
                        <input type="password" class="form-control col-sm-6" name="password1" autocomplete="off" placeholder="Password">
                        <input type="password" class="form-control col-sm-6" name="password2" autocomplete="off" placeholder="Repeat Password">
                    </div>
                </div>
                <button type="submit" class="col-sm btn btn-primary" name="insc_submit" value="ok">Submit</button>
                <br />
                <label class="to-create" onclick="signup()">login</label>
            </form>
            <script type="text/javascript" src="../js/login.js"></script>
            <footer style="float:right">@amanar 2020</footer>
    </body>
</html>

<?php
    $_SESSION["insc_succes"] = "";
    $_SESSION["insc_error"] = "";
    $_SESSION["email_error"] = "";
    $_SESSION["pass_error"] = "";
?>
