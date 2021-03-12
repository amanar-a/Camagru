<?php

    include "../config/setup.php";
    if(isset($_GET["verify"]) && $_GET["verify"] != '' && $_GET["verify"] != 1)
    {
        $table = $bd->prepare("SELECT * FROM `user` WHERE Verification =?");
        $table->execute(array($_GET["verify"]));
        $value = $table->fetch();
        if ($value)
        {
            $req = $bd->prepare('UPDATE `user` SET Verification = "1" WHERE Verification =?');
            $req->execute(array($_GET["verify"]));
        }
        else
            header('location:login.php');
    }
    else
        header('location:login.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <style>

            body{
                width: 100%;
                height: 100%;
                margin: 0%;
            }

            .msg{
                position:relative;
                width: 50%;
                text-align: center;
                font-size: 3vw;
                margin-left:auto;
                margin-right:auto;
                color:black;
                top:100px;
            }

            .head {
                background: linear-gradient(blue, rgb(152, 145, 245));
                font-weight: bold;
                font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
                width: 100%;
                box-shadow: 0px 0px 10px 2px black;
                text-align: center;
                font-size: 5vw;
                color: rgb(255, 255, 255);
            }

        </style>
    </head>
    <body>
        <header><div class="head">Camagru.</div></header>
        <div class="msg">Your Email has been verified! <br /> redirect to login page in 2 seconds <br />... </div>
        <script>
            setTimeout("document.location.href = 'login.php';",2000);
        </script>
        <footer> </footer>
    </body>
</html>