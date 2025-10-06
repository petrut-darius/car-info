<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="../../stylesheet/style.css">
</head>
<?php
require_once "../..//backend/auth.inc.php";
require_once "../../views/includes/header.inc.php";
?>
<h1>Add a car...</h1>
<form id="add_car">
    <input type="text" id="marca" placeholder="marca..."><br><br>
    <input type="text" id="model" placeholder="model..."><br><br>
    <input type="date" id="an" placeholder="an..."><br><br>
    <input type="text" id="serie_sasiu" placeholder="serie sasiu..."><br><br>
    <input type="number" min="1" max="20000" id="volum_motor" placeholder="volumul motorului..."><br><br>
    <input type="text" id="numar_inmatriculare" placeholder="numar de inmatriculare..."><br><br>
    <input type="button" id="car_btn" value="add..."><br><br>
</form>
<script src="../jquery.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){
        let msg = sessionStorage.getItem("msg");
            if(msg) {
                $("#msg").html(msg);
                sessionStorage.removeItem("msg");
            }
        $("#car_btn").on("click", function() {
            let marca = $("#marca").val();
            let model = $("#model").val();
            let an = $("#an").val();
            let serie_sasiu = $("#serie_sasiu").val();
            let numar_inmatriculare = $("#numar_inmatriculare").val();
            let volum_motor = $("#volum_motor").val();
            if (!marca || !model || !an || !serie_sasiu || !numar_inmatriculare || !volum_motor) {
                sessionStorage.setItem("msg", "completeaza toate campurile");
                window.location.reload();
            }

            if(volum_motor > 20000) {
                sessionStorage.setItem("msg", "volumul motorului nu poate depasii 20,000cm³");
                window.location.reload();
            }

            $.post("/pdi/car-app/backend/car.php", { marca: marca, model: model, an: an, serie_sasiu: serie_sasiu, numar_inmatriculare: numar_inmatriculare, volum_motor: volum_motor}, function(msg) {
                sessionStorage.setItem("msg", msg);
                window.location.reload();
                console.log("Server response:", msg);
            }).fail(function(xhr, status, error) {
                console.log(xhr.responseText);
                console.log(error);
            })
        });
    });
</script>