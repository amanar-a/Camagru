<?php
    include "../config/setup.php";
    session_start();
    if(isset($_GET["resetpass"], $_GET["username"]) && $_GET["resetpass"] != "" && $_GET["username"] != "")
    {
        $req = $bd->prepare("SELECT * FROM `user` WHERE username = ? AND resetpass = ?");
        $req->execute(array($_GET["username"], $_GET["resetpass"]));
        $table = $req->fetch();
        if ($table != "")
        {
            if (isset($_POST["sub_pass"], $_POST["pass1"], $_POST["pass2"]))
            {
                $ind = 0;
                $uppercase = preg_match('@[A-Z]@', $_POST["pass1"]);
                $lowercase = preg_match('@[a-z]@', $_POST["pass1"]);
                $number    = preg_match('@[0-9]@', $_POST["pass1"]);
                if ($_POST["sub_pass"] != 'ok' || $_POST["pass1"] == '' || $_POST["pass2"] == '')
                {
                    $_SESSION["pass_err"] = "Veuillez remplir tous les zones!";
                    refrech();
                }
                else
                {
                    if ($uppercase && $lowercase && $number && strlen($_POST["pass1"]) >= 8 && strlen($_POST["pass1"]) <= 20)
                    {
                        if ($_POST["pass1"] == $_POST["pass2"])
                        {
                            $req = $bd->prepare("UPDATE `user` SET `password`=? , resetpass=NULL WHERE username= ?");
                            $req->execute(array(hash("whirlpool",$_POST["pass1"]), $_GET["username"]));
                            header("location:login.php");
                        }
                        else
                            $ind = 1;
                    }
                    else
                        $ind = 2;
                    if ($ind == 1)
                        $_SESSION["pass_err"] = "passwords doesn't match!";
                    else if ($ind == 2)
                        $_SESSION["pass_err"] = "password must be alteast 8-20 caratere / uppercase / lowercase / number";
                    refrech();
                }
            }
        }
        else
            header("location:login.php");
    }
    else
        header("location:login.php");

function refrech() {
    echo "<script> location.href = 'reset_password.php?resetpass=".$_GET["resetpass"]."&username=".$_GET["username"]."' </script>";
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
            <div class="container-fluid ">Camagru</div>
        </header>
            <form id="f2" class="form-modi" method="POST">
                <div class="form-group">
                    <p><?php echo $_SESSION["pass_err"]; ?></p>
                </div>
                <div>
                    <input type="password" class="form-control" name="pass1" autocomplete="off" placeholder="New Password">
                </div>
                <div>
                    <input type="password" class="form-control" name="pass2" autocomplete="off" placeholder="Repeat New Password">
                </div>
                <button type="submit" class="col-sm btn btn-primary" name="sub_pass" value="ok">Submit</button>
            </form>
        <footer style="float:right">@amanar 2020</footer>
    </body>
</html>

<?php
    $_SESSION["pass_err"] = "";
?>