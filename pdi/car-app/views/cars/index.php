<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="../../stylesheet/style.css">
</head>
<?php
require_once "../..//backend/auth.inc.php";
require_once "../../views/includes/header.inc.php";
?>
<h1>All your cars...</h1>
<p><a href="new.php" class="add_smth">add a car...</a></p>
<div id="cars"></div>


<script src="../jquery.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){
        let msg = sessionStorage.getItem("msg");
            if(msg) {
                $("#msg").html(msg);
                sessionStorage.removeItem("msg");
            }
        $.get("/pdi/car-app/backend/car.php", null, function(data) {
            $("#cars").html(data);
        });
        
        $(document).on("click", ".delete_rca", function() {
            let rca_id = $(this).data("rca-id");
            let car_id = $(this).data("car-id");
        
            if(!car_id || !rca_id) {
                sessionStorage.setItem("msg", "ceva nu e okey la parametrii luati in front-end");
                window.location.reload();
            }

            $.ajax({
                url: "/pdi/car-app/backend/rca.php",
                type: "DELETE",
                data: $.param({car_id: car_id, rca_id: rca_id}),
                success: function(res) {
                    sessionStorage.setItem("msg", "ai sters rca-ul.");
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    sessionStorage.setItem("msg", "eroare la stergea rca-ului.");
                    console.log(xhr.responseText);
                    console.log(error);
                }
            });

        });

        $(document).on("click", ".delete_itp", function() {
            let itp_id = $(this).data("itp-id");
            let car_id = $(this).data("car-id");
        
            if(!car_id || !itp_id) {
                sessionStorage.setItem("msg", "ceva nu e okey la parametrii luati in front-end.");
                window.location.reload();
            }

            $.ajax({
                url: "/pdi/car-app/backend/itp.php",
                type: "DELETE",
                data: $.param({car_id: car_id, itp_id: itp_id}),
                success: function(res) {
                    sessionStorage.setItem("msg", "ai sters itp-ul.");
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    sessionStorage.setItem("msg", "eroare la stergea itp-ului.");
                    console.log(xhr.responseText);
                    console.log(error);
                }
            });
        });

        $(document).on("click", ".delete_car", function() {
            let car_id = $(this).data("car-id");

            if(!car_id) {
                sessionStorage.setItem("msg", "ceva nu e okey la parametrii luati in front-end.");
                window.location.reload();
            }

            $.ajax({
                url: "/pdi/car-app/backend/car.php",
                type: "DELETE",
                data: $.param({car_id: car_id}),
                success: function(res) {
                    sessionStorage.setItem("msg", "ai sters masina.");
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    sessionStorage.setItem("msg", "eroare la stergea masinii.");
                    console.log(xhr.responseText);
                    console.log(error);
                }
            });
        });
    });
</script>