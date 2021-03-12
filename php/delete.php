<?php
    include "../config/setup.php";
    session_start();

    if (isset($_SESSION["id"]))
    {
        if (isset($_POST["id"], $_POST["submit"]) && $_POST["submit"] == 'ok')
        {
            
            $req = $bd->prepare('SELECT `path` FROM post WHERE id_user=? AND id_post =?');
            $req->execute(array($_SESSION["id"], $_POST["id"]));
            $path_ = $req->fetch();

            $req = $bd->prepare('DELETE FROM post_comment WHERE id_user=? AND id_post =?');
            $req->execute(array($_SESSION["id"], $_POST["id"]));
            $req = $bd->prepare('DELETE FROM post_like WHERE id_user=? AND id_post =?');
            $req->execute(array($_SESSION["id"], $_POST["id"]));
            $req = $bd->prepare('DELETE FROM `post` WHERE id_user=? AND id_post =?');
            $req->execute(array($_SESSION["id"], $_POST["id"]));
            unlink("../".$path_["path"]);
            echo "succes";
        }
        else
            header("location:../index.php");
    }
    else
        header("location:login.php");
?>