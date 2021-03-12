var image_ = document.createElement('img');
var final_image = document.createElement('img');
var emo = document.createElement('img');
var canvas = document.createElement('canvas');
var Context = canvas.getContext('2d');
var incre = 0;
var arr_emoji = new Array();


function previewFile() {
    var preview = document.querySelector('img');
    var file    = document.querySelector('input[type=file]').files[0];
    var reader  = new FileReader();
    
    reader.onloadend = function () {
        var img_ = new Image();
        if (reader.result != "data:")
        {
            img_.src = reader.result;
            img_.onload = function() {
                display_none();
                preview.style.display = "block";
                previous_.style.display = "inline";
                photo_.style.display = "none";
                emoji.style.display = "block";
                send_data.style.display = "inline";
                incre = 0;
                arr_emoji = new Array();
                image_.src = img_.src;
                image_.onload = function () {
                    canvas.width = 720
                    canvas.height = 404;
                    Context.drawImage(image_, 0, 0, 720, 404);
                    preview.src = canvas.toDataURL("image/jpeg");
                }
            }
        }
    }


    if (file) {
        var ext = file.name.split('.').pop();
        if (ext == "png" || ext == "jpg" || ext == "jpeg")
            reader.readAsDataURL(file);
        else
            alert("file format Incorrect");
    }
}

function senddata() {
    const xhr = new XMLHttpRequest();
    var data = document.getElementById("img");
    var tok_img = document.getElementById("tok_img");
    
    if(data.src != "")
    {
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if (this.response == "succes") {
                    window.location.reload();
                }
            }
        };
        xhr.open("POST", "../php/picture.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("tok_img="+tok_img.value+"&data="+data.src+"&submit=ok");
    }
}

window.addEventListener("load", () => {
    var video = document.getElementById('video');
    var file_ = document.getElementById('file_');
    var photo_ = document.getElementById('photo_');
    var previous_ = document.getElementById('previous_');
    var emoji = document.getElementById('emoji');
    var send_data = document.getElementById('send_data');

    navigator.mediaDevices.getUserMedia({
      video: {
        width: 10000,
        height: 10000,
      }
    })
    .then((stream) => {
        if (typeof video.srcObject == "object") 
                video.srcObject = stream;
        else
            video.src = URL.createObjectURL(stream);
        video.play();
        photo_.style.display = "inline";
    })
    .catch((error) => {
        console.log(error);
    });
    document.getElementById("photo").addEventListener("click", function() {
        drawpicture();
        final_image.src = canvas.toDataURL("image/jpeg");
        image_.src = canvas.toDataURL("image/jpeg");
        img.setAttribute("src", final_image.src);
        display_none();
        emoji.style.display = "block";
        img.style.display = "block";
        previous_.style.display = "inline";
        photo_.style.display = "none";
        file_.style.display = "none";
        send_data.style.display = "inline";
    });
    document.getElementById("previous").addEventListener("click", function() {
        window.location.reload();
    });
});

function add_emoj(e){
    var canv_i = [0, 540, 0, 540];
    var canv_j = [0, 0, 303, 303];
    var img = document.getElementById('img');

    canvas.width = 720;
    canvas.height = 404;
    Context.drawImage(image_, 0, 0, 720, 404);
    if (incre > 3)
        incre = 0;
    arr_emoji[incre++] = e;
    for(var i = 0;i < 4; i++)
        if (arr_emoji[i])
            Context.drawImage(arr_emoji[i], canv_i[i], canv_j[i], 720 / 4, 404 / 4);
    final_image.src = canvas.toDataURL("image/jpeg");
    img.setAttribute("src", final_image.src);
   
}

function remove_emoj(){
    var img = document.getElementById('img');

    incre = 0;
    arr_emoji = new Array();
    canvas.width = 720;
    canvas.height = 404;
    Context.drawImage(image_, 0, 0, 720, 404);
    final_image.src = canvas.toDataURL("image/jpeg");
    img.setAttribute("src", final_image.src);
}

function drawpicture(){
    var video = document.getElementById('video');

    canvas.width = 720;
    canvas.height = 404;
    Context.drawImage(video, 0, 0, 720, 404);
}


function display_none(){
    var video = document.getElementById('video');
    var img = document.getElementById('img');

    video.style.display = "none";
    img.style.display = "none";
}

function delet(e){
    var id = e.getAttribute('post_id');
    const xhr = new XMLHttpRequest();
    var confi = confirm("Confirm delete");

    if (confi == true)
    {

        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if (this.response == "succes") {
                    window.location.reload();
                }
            }
        };
        xhr.open("POST", "../php/delete.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("id="+id+"&submit=ok");
        
    }
}