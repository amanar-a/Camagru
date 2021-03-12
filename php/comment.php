<?php

    include "../config/setup.php";
    session_start();

    if (isset($_SESSION["id"]))
    {
        if(isset($_POST["submit"], $_POST["text"]) && $_POST["submit"] == "ok")
        {
            if(trim($_POST["text"]) != "" && strlen($_POST["text"]) <= 256)
            {
                $req = $bd->prepare("INSERT INTO post_comment(id_post, id_user, comment_txt, comment_date) VALUES(:id_post, :id_user, :comment_txt, :comment_date)");
                $req->execute(array(
                    "id_post" => $_POST["id_post"],
                    "id_user" => $_SESSION["id"],
                    "comment_txt" => htmlspecialchars($_POST["text"]),
                    "comment_date" => date('Y-m-d h:i:s')
                ));
                $req = $bd->query("SELECT commentaire, id_user FROM post WHERE id_post=".$_POST["id_post"]);
                $post = $req->fetch();
                $req = $bd->prepare("UPDATE post SET commentaire=? WHERE id_post=".$_POST["id_post"]);
                $req->execute(array($post[0] + 1));
                $req = $bd->query("SELECT id_user, email, `notification` FROM `user` WHERE id_user=".$post["id_user"]);
                $user = $req->fetch();
                if ($user["notification"] == 1 && $user["id_user"] != $_SESSION["id"])
                {
                    $body = "<p>".$_SESSION["username"].": commented in your post</p>";
                    $body .= "<br /><p>".$_POST["text"]."</p>";
                    $subject = "Notification Camagru";
                    $header = "Content-type: text/html";
                    mail($user["email"], $subject, $body, $header);
                }
                echo 1;
            }
        }
        else
            header("location:../index.php");
    }
    else
        header("location:login.php");

?>