<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="../../stylesheet/style.css">
</head>
<?php
require_once "../..//backend/guest.inc.php";
require_once "../../views/includes/header.inc.php";
?>
<h1>Logheaza-te...</h1>
<form class="login">
    <input type="email" id="login_email" placeholder="email..."><br><br>
    <input type="password" id="login_password" placeholder="parola..."><br><br>
    <input type="button" id="login_user_btn" value="conecteaza-te..."><br><br>
</form>
<script src="../jquery.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        let msg = sessionStorage.getItem("msg");
            if(msg) {
                $("#msg").html(msg);
                sessionStorage.removeItem("msg");
            }
        $("#login_user_btn").on("click", function() {
            let email = $("#login_email").val();
            let password = $("#login_password").val();
            if(!password || !email) {
                sessionStorage.setItem("msg", "completeaza toate campurile");
                window.location.reload(); 
            }

            $.post("/pdi/car-app/backend/user.php", {password: password, email: email}, function() {
                //nimica
            }).fail(function(xhr, status, error) {
                console.log(xhr.responseText);
                console.log(error);
            }).done(function(res) {
                if (res.trim() === "okey.") {
                    window.location.href = "/pdi/car-app/views/cars/index.php";
                }else{
                    sessionStorage.setItem("msg", res.trim());
                    window.location.reload();
                }
            });
        });
    });
</script>
