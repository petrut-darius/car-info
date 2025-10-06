<!DOCTYPE html>
<?php
    function db_connection():mysqli {
        $host = "localhost";
        $user = "root";
        $password = "";

        try{
            $db_connection = new mysqli($host, $user, $password);
            mysqli_select_db($db_connection, "car-info");
        }catch(mysqli_sql_exception $e){
            echo "error: " . $e->getMessage() . " at line: " . $e->getLine();
        }

        return $db_connection;
        }

    function get_cars(mysqli $db, $user_id):array {
        $query = "SELECT * FROM `cars` WHERE user_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $cars = [];

        while($row = $result->fetch_assoc()) {
            $cars[] = $row;
        }

        return $cars;
    }
?>
<head>
    <link rel="stylesheet" href="../../stylesheet/style.css">
</head>
<?php
require_once "../..//backend/auth.inc.php";
require_once "../../views/includes/header.inc.php";
?>
<h1>Update car...</h1>
<?php
if(isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])) :
    $user_id = $_SESSION["user_id"];
    $db = db_connection();
    $cars = get_cars($db, $user_id);
?>
    <form class="update_car">
        <select id="car_id">
            <?php foreach($cars as $car): ?>
                <option value="<?= htmlspecialchars($car['id']) ?>">
                    <?= htmlspecialchars($car['marca'] . ' ' . $car['model'] . ' (' . $car['numar'] . ')') ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
        <input type="text" id="numar_inmatriculare_nou" placeholder="numar de inmatriculare..."><br><br>
        <input type="button" value="update car..." id="update_car"><br><br>
    </form>

<?php
endif;
?>
<script src="../jquery.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){
        let msg = sessionStorage.getItem("msg");
            if(msg) {
                $("#msg").html(msg);
                sessionStorage.removeItem("msg");
            }
    
        $("#update_car").on("click", function() {
            let numar_inmatriculare_nou = $("#numar_inmatriculare_nou").val();
            let car_id = $("#car_id").val();

            if(!numar_inmatriculare_nou || !car_id) {
                sessionStorage.setItem("msg", "completeaza toate campurile.");
                window.location.reload();
            }

            $.ajax({
                url: "/pdi/car-app/backend/car.php",
                type: "PATCH",
                data: {car_id: car_id, numar_inmatriculare_nou: numar_inmatriculare_nou},
                success: function(res) {
                    sessionStorage.setItem("msg", "ai updatat masina.");
                    window.location.reload();
                    //console.log(car_id);
                },
                error: function(xhr, status, error) {
                    sessionStorage.setItem("msg", "eroare la updatarea masinii.");
                    console.log(xhr.responseText);
                    console.log(error);
                }
            });
        });
    });
</script>