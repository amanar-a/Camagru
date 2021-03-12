<?php

    include "../config/setup.php";
    session_start();
    $docroot = $_SERVER["DOCUMENT_ROOT"];
    if (isset($_SESSION["username"], $_SESSION["id"]))
    {
        if (isset($_POST["submit"], $_POST["data"], $_POST["tok_img"]) && $_POST["submit"] == 'ok')
        {
            if (isset($_SESSION["tok_img"]) && $_SESSION["tok_img"] != '' && $_SESSION["tok_img"] == $_POST["tok_img"])
            {
                if (!file_exists($_SERVER["DOCUMENT_ROOT"].'/img'))
                {
                    chmod($docroot, 0777);
                    mkdir($docroot."/img");
                    chmod($docroot."/img", 0777);
                }
                if (count(explode('data:image/',$_POST["data"])) >= 1 && count(explode(';',explode('data:image/',$_POST["data"])[1])) >= 0)
                    $ext = explode(';',explode('data:image/',$_POST["data"])[1])[0];
                if(getimagesize(str_replace(' ', '+', $_POST["data"]))){
                    $path = "img/".time().".".$ext;
                    $date_ = date('Y-m-d H:i:s');
                    file_put_contents("../".$path, file_get_contents(str_replace(' ', '+', $_POST["data"])));
                    $req = $bd->prepare('INSERT INTO `post`(id_user, `user_name`, `likes`, `commentaire`, `path`, `date`) VALUES(:id_user, :fullname, :likes, :commentaire, :paths, :date)');
                    $req->execute(array(
                                'id_user' =>$_SESSION["id"],
                                'fullname' => $_SESSION["username"],
                                'likes' => 0,
                                'commentaire' => 0,
                                'paths' => $path,
                                'date' => $date_
                    ));
                    $_SESSION["tok_img"] = "";
                    echo "succes";
                }
            }
        }
        else
            header("location:../index.php");
    }
    else
        header("location:login.php");


?>