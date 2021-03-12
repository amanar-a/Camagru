<?php

    include "../config/setup.php";
    session_start();
    $_SESSION["tok_img"] = hash('whirlpool', date('Y-m-d H:i:s'));
    if (isset($_SESSION["id"]))
        $table = $bd->query("SELECT * FROM post WHERE id_user ='".$_SESSION["id"]."' ORDER BY `date` DESC");
    else
        header('location:login.php');
    
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/picture.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <header>
            <div class="container-fluid ">
                <form class="menu" action="disconect.php" method="POST">
                    <button name="disconnect" value="discon" id="discon" class="invi">Disconnect</button>
                    <label for="discon"> <i class="fas fa-sign-out-alt ico"></i ></label>
                    <label><a href="modifierprofile.php"><i class="fas fa-user-edit ico"></i></a> </label>
                    <label><a href="../index.php"><i class="fas fa-home ico"></i></a> </label>
                </form>
                <div class="upper_div" >Camagru</div>
            </div>
        </header>
        <div class="container">
            <div class="row">
                <div class="form-signin" id="form-signin">
                    <p>Welcome <?php echo $_SESSION["fullname"]; ?> </p>
                    <br />
                    <p>upload or take a photo </p>
                    <br />
                        <div class="pic" id ="pic">
                            <video id="video" width="100%" heigth="100%" autoplay></video>
                            <img id="img" width="100%" heigth="100%" style="max-heigth:404px" class="invi ">
                        </div>
                    <br />
                    <div id="emoji" style="display:none;">
                        <img src="../img_using/cool.png" class="filt" onclick="add_emoj(this)"/>
                        <img src="../img_using/wink.png" class="filt" onclick="add_emoj(this)"/>
                        <img src="../img_using/in-love.png" class="filt" onclick="add_emoj(this)"/>
                        <img src="../img_using/fire.png" class="filt" onclick="add_emoj(this)"/>
                        <img src="../img_using/angry.png" class="filt" onclick="add_emoj(this)"/>
                        <img src="../img_using/clear.png" class="filt" onclick="remove_emoj()"/>
                    </div>
                    <br />
                    <input  type="file" name="file" id="file" onchange="previewFile()" class="hidden">
                    <label for="file" id="file_"><i class="fas fa-arrow-circle-up fa-3x"></i></label>
                    <input  type="button" name="photo" id="photo" class="hidden">
                    <label for="photo" id="photo_" style="display:none"><i class="fas fa-camera-retro fa-3x"></i></label>
                    <input  type="button" name="previous" id="previous" class="hidden">
                    <label for="previous" class="invi" id="previous_"><i class="fas fa-chevron-circle-left fa-3x"></i></label>
                    <br />   
                    <button  id="send_data" name="submit" class="col-sm btn btn-primary" onclick="senddata()" style="display:none" >Submit</button>
                </div>
            </div>
        </div>
        <div class="container">
        <div class="row">
                <div class="histo">
                    <span> Historique </span>
                    <br />
                        <?php
                        $i = 0;
                        foreach($table as $value)
                        {
                            echo '<div class="histo-div">';
                                echo '<img src="../'.$value["path"].'" class="histo-img" />';
                                    echo '<p class="histo-date">'.$value['date'].'</p>';
                                    echo '<label class="lab" ><i post_id="'.$value["id_post"].'"onclick="delet(this)" class="fas fa-1x fa-trash-alt" "></i></label>';
                            echo '</div>';
                            $i++;
                        }
                        if ($i == 0)
                        {
                            echo '<div class="histo-div">';
                                echo '<p class="histo-date">you did not upload any photo yet!</p>';
                            echo '</div>';
                        }
                        ?>
                </div>
            </div>
            <input type="hidden" value="<?php if(isset($_SESSION["tok_img"])) echo $_SESSION["tok_img"]; ?>" id="tok_img" />
        </div>    
        <script type="text/javascript" src="../js/picture.js"></script>
        <footer style="float:right">@amanar 2020</footer>
    </body>
</html>