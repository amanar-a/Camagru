<?php
    include "../config/setup.php";
    session_start();

    if (isset($_SESSION["id"]))
    {
        if (isset($_POST["disconnect"]))
        {
            if ($_POST["disconnect"] == "discon")
            {
                session_destroy();
                header('location:login.php');
            }
        }
        else
            header("location:../index.php");
    }
    else
        header("location:login.php");
?>