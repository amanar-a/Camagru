<?php
    include "database.php";

    try {
        $bd = new PDO("mysql:host=".$DB_HOST.";",$DB_USER,$DB_PASSWORD);
        $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $req = $bd->prepare("CREATE DATABASE IF NOT EXISTS `".$DB_NAME."`;");
        $req->execute();

        $bd = new PDO($DB_DSN,$DB_USER,$DB_PASSWORD);
    }
    catch(PDOException $e){
        echo "cannot reach the database";
        exit();
    }
    
    try{
        $req = $bd->prepare("CREATE TABLE IF NOT EXISTS `user`
        (
            `id_user` int NOT NULL AUTO_INCREMENT,
            `username` varchar(30) NOT NULL,
            `fullname` varchar(256) NOT NULL,
            `password` varchar(256) NOT NULL,
            `email` varchar(256) NOT NULL,
            `Verification` varchar(256) NOT NULL,
            `notification` int NOT NULL,
            `resetpass` varchar(256) NULL DEFAULT NULL,
            UNIQUE KEY(`id_user`,`username`)
        );");
        $req->execute();
    }
    catch (PDOException $e){
        echo"cannot reach the database";
        exit();
    }

    try{
        $req = $bd->prepare("CREATE TABLE IF NOT EXISTS `post`
        (
            `id_post` int PRIMARY KEY AUTO_INCREMENT,
            `id_user` varchar(256) NOT NULL,
            `user_name` varchar(256) NOT NULL,
            `likes` int NOT NULL,
            `commentaire` varchar(256) NOT NULL,
            `path` varchar(256) NOT NULL,
            `date` DATETIME NOT NULL
        );");
        $req->execute();
    }
    catch (PDOException $e){
        echo "cannot reach the database";
        exit();
    }

    try{
        $req = $bd->prepare("CREATE TABLE IF NOT EXISTS `post_like`
        (
            `id_like` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
            `id_post` int NOT NULL,
            `id_user` int NOT NULL,
            `check` int NOT NULL
        );");
        $req->execute();
    }
    catch (PDOException $e){
        echo "cannot reach the database";
        exit();
    }

    try{
        $req = $bd->prepare("CREATE TABLE IF NOT EXISTS `post_comment`
        (
            `id_comment` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
            `id_post` int NOT NULL,
            `id_user` int NOT NULL,
            `comment_txt` varchar(256) NOT NULL,
            `comment_date` DATETIME NOT NULL
        );");
        $req->execute();
    }
    catch (PDOException $e){
        echo "cannot reach the database";
        exit();
    }

?>