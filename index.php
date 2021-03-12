<?php
    include "config/setup.php";
    session_start();
    $req = $bd->query("SELECT COUNT(*) FROM post");
    $nb_post = $req->fetch();
    $nb_page = ceil($nb_post[0] / 5);
    if (isset($_GET["nb_page"]) && is_numeric($_GET["nb_page"]) && $_GET["nb_page"] > 0)
        $offset = ((int)$_GET["nb_page"] - 1) * 5;
    else
        $offset = 0;
    $table = $bd->query("SELECT * FROM post  ORDER BY `date` DESC LIMIT 5 OFFSET ".$offset);
    if (isset($_POST["disconnect"]))
        if ($_POST["disconnect"] == "discon")
        {
            session_destroy();
            header('location:php/login.php');
        }
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="css/index.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V" crossorigin="anonymous">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
    </head>
    <body>
        <header>
            <div class="container-fluid upnav">
                <?php
                    if (isset($_SESSION["id"])){
                        echo '<form class="menu" action="index.php" method="POST">';
                            echo '<button name="disconnect" value="discon" id="discon" class="invi">Disconnect</button>';
                            echo '<label for="discon"> <i class="fas fa-sign-out-alt ico"></i ></label>';
                            echo '<label><a href="php/modifierprofile.php"><i class="fas fa-user-edit ico"></i></a> </label>';
                            echo '<label><a href="php/postpicture.php"><i class="fas fa-camera ico"></i></a> </label>';
                        echo '</form>';
                    }
                    else{
                        session_destroy();
                        echo '<form class="menu">';
                            echo '<label><a href="php/login.php"><i class="fas fa-sign-in-alt ico"></i></a></label>';
                        echo '</form>';
                    }
                ?>
                <div>Camagru</div>
            </div>
        </header>
        <?php
            $verri = 0;
            foreach($table as $valeur)
            {
                echo'<div class="parent">
                        <div class="child1">
                            <div class="child1-div"><img src="../img_using/img05.png"  class="child1-img"/> </div>';
                            echo '<div class="name"><p>'.$valeur["user_name"].': </p><p class="post_date"> '.$valeur["date"].'</p></div>';
                            if (isset($_SESSION["id"]) && $_SESSION["id"] == $valeur["id_user"])
                                echo '<label class="lab" ><i post_id="'.$valeur["id_post"].'"onclick="delet(this)" class="fas fa-1x fa-trash-alt" "></i></label>';
                    echo '</div>';
                    echo'<div class="child2"><img src="../'.$valeur["path"].'" ></div>';
                    echo'<div class="child3">
                        <div class="child3-div1 >"
                              <button name="like" value="like" id="like'.$verri.'" class="invi" "></button>';
                              
                            if(isset($_SESSION["id"]))
                            {
                                $req = $bd->query("SELECT COUNT(*) FROM post_like WHERE id_post =".$valeur["id_post"]." AND id_user =".$_SESSION["id"]." AND `check`=1");                             
                                $likes = $req->fetch();
                                if ($likes[0] == 1)
                                    echo'<label for="like'.$verri.'" class="bleu-like" id_post="'.$valeur["id_post"].'"onclick="sendlike(this)"><i class="far fa-thumbs-up" ></i> Like </label>';
                                else
                                    echo'<label for="like'.$verri.'" class="white-like" id_post="'.$valeur["id_post"].'"onclick="sendlike(this)"><i class="far fa-thumbs-up" ></i> Like </label>';
                            }
                            else
                                echo'<label for="like'.$verri.'" class="white-like" id_post="'.$valeur["id_post"].'"><i class="far fa-thumbs-up" ></i> Like </label>';
                            echo '<button name="comment" value="comment" class="invi" id="comment'.$verri.'" id_sec="'.$valeur["id_post"].'" onclick="show(this)"></button>
                                  <label for="comment'.$verri.'" class="comment"><i class="far fa-comment-dots"></i> comment</label>
                                </div>
                                <div class="child3-div2 >"
                                    <p class="child3-p"><span>'.$valeur["commentaire"].'</span> comment <span>'.$valeur["likes"].'</span> like</p>
                                </div>
                    </div>';
                    echo '<div id="'.$valeur["id_post"].'" style="display:none">';
                        if (isset($_SESSION["id"]) == $valeur["id_user"])
                        {   
                            echo'<div class="child4" id="child4'.$verri.'" > 
                                    <input type="text" class="form-control" id="'.$valeur["id_post"].$verri.'" />
                                    <button class="btn_ btn-primary" type="submit" id_input="'.$valeur["id_post"].$verri.'" id_post="'.$valeur["id_post"].'" onclick="sendcomment(this)">Button</button> 
                                </div>';
                            
                        }
                        $table2 = $bd->query("SELECT * FROM post_comment WHERE id_post =".$valeur["id_post"]);
                        foreach($table2 as $valeur2)
                        {
                            echo'<div class="div_comment">';
                                $req = $bd->query("SELECT fullname FROM user WHERE id_user=".$valeur2["id_user"]);
                                $name = $req->fetch();
                                echo'<p class="p_name">'.$name["fullname"].': </p> <br />';
                                echo'<p class="p_comment">'.$valeur2["comment_txt"].' </p>';
                            echo'</div>';
                        }
                    echo'</div>';
                echo'</div>';
                $verri++;
            }
            echo '<form  action="index.php" method="GET">';
                echo '<div class="container-fluid change_pg">
                        <div class="row">';

                        for ($i = 1; $i <= $nb_page; $i++)
                        {
                            echo '<button name="nb_page" value="'.$i.'" class="nb">'.$i.'</button>';
                        }
                    echo'</div>';
                echo '</div>';
            echo '</form>';
        ?>
        <script type="text/javascript" src="js/index.js" ></script>
        <footer style="float:right">@amanar 2020</footer>
    </body>
</html>