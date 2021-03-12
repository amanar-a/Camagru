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

function show(e){
    var id_sec = e.getAttribute('id_sec');
    var comment_sec = document.getElementById(id_sec);

    if(comment_sec.style.display == "block")
        comment_sec.style.display = "none"
    else
        comment_sec.style.display = "block"
}

function sendlike(e){
    const xhr = new XMLHttpRequest();
    var id_post =e.getAttribute('id_post');
    var like = e.getAttribute('class');
    var parent = e.parentNode;
    var child = parent.parentNode.childNodes[3].childNodes[2];
    

    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if (this.response != "") {
                child.innerHTML = parseInt(child.innerHTML) + parseInt(this.responseText);
                
                if(like == "white-like")
                    e.setAttribute("class", "bleu-like");
                else
                    e.setAttribute("class", "white-like");
            }
        }
    };
    xhr.open("POST", "../php/like.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("id_post="+id_post+"&submit=ok");
   
}

function sendcomment(e){
    const xhr = new XMLHttpRequest();
    var id_post = e.getAttribute('id_post');
    var id_input = e.getAttribute('id_input');
    var text = document.getElementById(id_input).value;
    var child = e.parentNode.parentNode.parentNode.childNodes[3].childNodes[3].childNodes[0];

    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if (this.response != "")
            {
                child.innerHTML = parseInt(child.innerHTML) + 1;
            }
        }
    };

    xhr.open("POST", "../php/comment.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("id_post="+id_post+"&text="+text+"&submit=ok");
    document.getElementById(id_input).value = "";
}
