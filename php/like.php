<?php
    include "../config/setup.php";
    session_start();

    if(isset($_SESSION["id"], $_POST["id_post"]) && $_POST["submit"] == "ok")
    {
        $count = 0;
        $req = $bd->query("SELECT likes, id_user FROM `post` WHERE id_post=".$_POST["id_post"]);
        $post = $req->fetch();
        $req = $bd->query("SELECT * FROM post_like WHERE id_post=".$_POST["id_post"]." AND id_user=".$_SESSION["id"]);
        $table = $req->fetch();

        if(is_array($table))
        {
            if($table["check"] == 1)
            {
                $req = $bd->exec("UPDATE post_like SET `check`= 0 WHERE id_post=".$_POST["id_post"]." AND id_user=".$_SESSION["id"]);
                $count = -1;
            }
            else
            {
                $req = $bd->exec("UPDATE post_like SET `check`= 1 WHERE id_post=".$_POST["id_post"]." AND id_user=".$_SESSION["id"]);
                $count = 1;
            }
        }
        else
        {
            $req = $bd->prepare("INSERT INTO post_like(id_post, id_user, `check`) VALUE (:id_post, :id_user, :check)");
            $req->execute(array(
                "id_post" => $_POST["id_post"],
                "id_user" => $_SESSION["id"],
                "check" => 1
            ));
            $count = 1;
            $req = $bd->query("SELECT id_user, email, `notification` FROM `user` WHERE id_user=".$post["id_user"]);
            $user = $req->fetch();
            if ($user["notification"] == 1 && $user["id_user"] != $_SESSION["id"]) 
            {
                $body = "<p>".$_SESSION["username"].": liked ur post</p>";
                $subject = "Notification Camagru";
                $header = "Content-type: text/html";
                mail($user["email"], $subject, $body, $header);
            }
        }
        $req = $bd->prepare("UPDATE post SET likes=? WHERE id_post=".$_POST["id_post"]);
        $req->execute(array($post["likes"] + $count));
        echo $count;
    }
    else
        header("location:login.php");
?>