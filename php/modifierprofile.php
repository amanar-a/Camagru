<?php

    include "../config/setup.php";
    session_start();

    if (isset($_SESSION["id"]))
    {
        $req = $bd->prepare("SELECT * FROM`user` WHERE id_user= ?");
        $req->execute(array($_SESSION["id"]));
        $checkbx = $req->fetch();
        if ($checkbx["notification"] == 1)
            $i = "checked";
        else
            $i = "";
        if (isset($_POST["sub_info"], $_POST["ins_user"], $_POST["email"], $_POST["fullname"]))
        {
            $ind = 0;
            if (isset($_POST["notfication"]))
                $notif = 1;
            else if (!isset($_POST["notfication"]))
                $notif = 0;
            else
                $notif = $checkbx["notification"];
            $req = $bd->prepare("UPDATE user SET `notification`=? WHERE id_user= ?");
            $req->execute(array($notif, $_SESSION["id"]));
            $req = $bd->prepare("SELECT * FROM`user` WHERE id_user= ?");
            $req->execute(array($_SESSION["id"]));
            $checkbx = $req->fetch();
            if ($checkbx["notification"] == 1)
                $i = "checked";
            else
                $i = "";
            if ($_POST["sub_info"] != 'ok' || ($_POST["ins_user"] == '' && $_POST["email"] == '' && $_POST["fullname"] == '' && $notif == $checkbx["notification"]))
            {
                $_SESSION["info_err"] = "All information is on Date";
                refrech();
            }
            else
            {
                if ($ind == 0 && $_POST["email"] != '')
                {
                    if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
                    {
                        $req = $bd->prepare("UPDATE user SET email=? WHERE id_user= ?");
                        $req->execute(array($_POST["email"], $_SESSION["id"]));
                    }
                    else
                        $ind = 1;
                }
                if ($ind == 0 && $_POST["ins_user"] != '')
                {
                    $table = $bd->query("SELECT * FROM user");
                    foreach ($table as $valeur)
                    {
                        if ($valeur["username"] == $_POST["ins_user"])
                        {
                            $ind = 2;
                            break;
                        }
                    }
                    if ($ind == 0)
                    {
                        if (preg_match('/^[a-z0-9]{6,10}$/', $_POST["ins_user"]))
                        {
                            $req = $bd->prepare("UPDATE `user` SET username=? WHERE id_user= ?");
                            $req->execute(array($_POST["ins_user"], $_SESSION["id"]));
                            $req = $bd->prepare("UPDATE `post` SET `user_name`=? WHERE id_user= ?");
                            $req->execute(array($_POST["ins_user"], $_SESSION["id"]));
                            $_SESSION["username"] = $_POST["ins_user"];
                        }
                        else
                            $ind = 4;
                    }
                }
                if ($ind == 0 && $_POST["fullname"] != '')
                {
                    if (preg_match('/^[a-zA-Z ]{4,25}$/', $_POST["fullname"]))
                    {
                        $req = $bd->prepare("UPDATE user SET fullname=? WHERE id_user= ?");
                        $req->execute(array($_POST["fullname"], $_SESSION["id"]));
                        $_POST["fullname"] = $_POST["fullname"];
                    }
                    else
                        $ind = 5;
                }
                if ($ind == 1)
                    $_SESSION["info_err"] = "Email format Incorrect.";
                else if ($ind == 2)
                    $_SESSION["info_err"] = "Username exist already.";
                else if ($ind == 4)
                    $_SESSION["info_err"] = "Username: 6-10 cara lowercase/number.";
                else if ($ind == 5)
                    $_SESSION["info_err"] = "Name: 4-25 cara lowercase/uppercase.";
                else
                    $_SESSION["succes"] = "Profile has been updated.";
                refrech();
            }
        }

        if (isset($_POST["sub_pass"], $_POST["pass1"], $_POST["pass2"]))
        {
            $ind = 0;
            $uppercase = preg_match('@[A-Z]@', $_POST["pass1"]);
            $lowercase = preg_match('@[a-z]@', $_POST["pass1"]);
            $number    = preg_match('@[0-9]@', $_POST["pass1"]);
            if ($_POST["sub_pass"] != 'ok' || $_POST["pass1"] == '' || $_POST["pass2"] == '')
                $_SESSION["info_err"] = "Please fill both password.";
            else
            {
                if ($uppercase && $lowercase && $number && strlen($_POST["pass1"]) >= 8 && strlen($_POST["pass1"]) <= 20)
                {
                    if ($_POST["pass1"] == $_POST["pass2"])
                    {
                        $req = $bd->prepare("UPDATE user SET `password`=? WHERE id_user= ?");
                        $req->execute(array(hash("whirlpool",$_POST["pass1"]), $_SESSION["id"]));
                    }
                    else
                        $ind = 1;
                }
                else
                    $ind = 2;
                if ($ind == 1)
                    $_SESSION["info_err"] = "passwords doesn't match.";
                else if ($ind == 2)
                    $_SESSION["info_err"] = "Password fomat Incorrect.";
                else
                    $_SESSION["succes"] = "Password has been changed.";
                refrech();
            }
        }
    }
    else
        header("location:login.php");

function refrech() {
    echo"<script> location.href = 'modifierprofile.php' </script>";
    exit();
}

?>


<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/login.css">
        <link rel="stylesheet" href="../css/profile.css">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    </head>
    <body>
        <header>
            <div class="container-fluid "> 
                <form class="menu" action="disconect.php" method="POST">
                    <button name="disconnect" value="discon" id="discon" class="invi">Disconnect</button>
                    <label for="discon"> <i class="fas fa-sign-out-alt ico"></i ></label>
                    <label><a href="postpicture.php"><i class="fas fa-camera ico"></i></a> </label>
                    <label><a href="../index.php"><i class="fas fa-home ico"></i></a> </label>
                </form>
                <div >Camagru</div>
            </div>
        </header>
            <form id="f1" class="form-modi" method="POST" >
                <div class="form-group">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm click">
                                <i class="fas fa-3x fa-address-card "></i>
                            </div>
                            <div class="col-sm clik" onclick="switch1()">
                                <i class="fas fa-3x fa-key"></i></i>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="succes"><?php echo $_SESSION["succes"]; ?></p>
                <p><?php echo $_SESSION["info_err"]; ?></p>
                <div>
                    <ul>
                        <li><label for="checkbx" class="checkbx_label" class="">Notification :</label></li>
                        <li><input type="checkbox" <?php echo $i; ?> name="notfication" value="<?php echo $i."0"; ?>" class="checkbx"></li>
                    </ul>
                </div>
                <div>
                    <input type="text" class="form-control" name="ins_user" placeholder="New Username">
                </div>
                <div>
                    <input type="email" class="form-control" name="email" placeholder="New Email">
                </div>
                <div>
                    <input type="text" class="form-control" name="fullname" placeholder="New name">
                </div>
                <button type="submit" class="col-sm btn btn-primary" name="sub_info" value="ok">Submit</button>
            </form>
            <form id="f2" class="form-modi" method="POST" style="display: none;">
                <div class="form-group">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm clik" onclick="switch2()">
                                <i class="fas fa-3x fa-address-card"></i>
                            </div>
                            <div class="col-sm click">
                                <i class="fas fa-3x fa-key"></i></i>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="succes"><?php echo $_SESSION["succes"]; ?></p>
                <p><?php echo $_SESSION["info_err"]; ?></p>
                <div>
                    <input type="password" class="form-control" name="pass1" autocomplete="off" placeholder="New Password">
                </div>
                <div>
                    <input type="password" class="form-control" name="pass2" autocomplete="off" placeholder="Repeat New Password">
                </div>
                <button type="submit" class="col-sm btn btn-primary" name="sub_pass" value="ok">Submit</button>
            </form>
            <script type="text/javascript" src="../js/profile.js"></script>
            <footer style="float:right">@amanar 2020</footer>
    </body>
</html>
<?php

    $_SESSION["info_err"] = "";
    $_SESSION["pass_err"] = "";
    $_SESSION["succes"] = "";

?>