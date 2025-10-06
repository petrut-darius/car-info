<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="../../stylesheet/style.css">
</head>

<?php
require_once "../..//backend/guest.inc.php";
require_once "../../views/includes/header.inc.php";
?>
<h1>Creaza utilizatorul...</h1>
<form class="register">
    <input type="text" id="first_name" autocomplete="family-name" placeholder="first_name..."><br><br>
    <input type="text" id="last_name" placeholder="last_name..."><br><br>
    <input type="date" id="birth_date" placeholder="brithday..."><br><br>
    <input type="email" id="email" autocomplete="email" placeholder="email..."><br><br>
    <input type="password" id="password" placeholder="parola..."><br><br>
    <input type="password" id="password_again" placeholder="parola din nou..."><br><br>
    <input type="button" id="add_user_btn" value="creaza user-ul..."><br><br>
</form>
<script src="../jquery.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        let msg = sessionStorage.getItem("msg");
            if(msg) {
                $("#msg").html(msg);
                sessionStorage.removeItem("msg");
            }
        $("#add_user_btn").on("click", function() {
            let first_name = $("#first_name").val();
            let last_name = $("#last_name").val();
            let birth_date = $("#birth_date").val();
            let email = $("#email").val();
            let password = $("#password").val();
            let password_again = $("#password_again").val();

            if(!first_name || !last_name || !birth_date || !password || !password_again || !email) {
                sessionStorage.setItem("msg", "completeaza toate campurile.");
                window.location.reload();
            }

            if(password != password_again) {
                sessionStorage.setItem("msg", "parolele nu sunt identice.");
                window.location.reload();
            }

            $.post("/pdi/car-app/backend/user.php", {first_name: first_name, last_name: last_name, birth_date: birth_date, password: password, password_again: password_again, email: email}, function(msg) {
                sessionStorage.setItem("msg", msg);
            }).fail(function(xhr, status, error) {
                console.log("Server error: " + error);
            }).done(function(res) {
                if (res.trim() === "ai creat utilizatorul, acuma logheaza-te.") {
                    sessionStorage.setItem("msg", "acum tot ce trebuie sa faci este sa te loghezi:)");
                    window.location.href = "/pdi/car-app/views/users/login.php";
                }else{
                    sessionStorage.setItem("msg", res.trim());
                    window.location.reload();
                }
            });
        });
    });
</script>