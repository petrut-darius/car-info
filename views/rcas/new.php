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
if(isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])) :
    $user_id = $_SESSION["user_id"];
    $db = db_connection();
    $cars = get_cars($db, $user_id);
?>
    <h1>add a rca...</h1>
    <form class="add_rca">
        <select id="car_id">
            <?php foreach($cars as $car): ?>
                <option value="<?= htmlspecialchars($car['id']) ?>">
                    <?= htmlspecialchars($car['marca'] . ' ' . $car['model'] . ' (' . $car['numar'] . ')') ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
        <input type="date" id="rca_start"><br><br>
        <select id="rca_duration">
            <option value="3 luni">3 luni</option>
            <option value="6 luni">6 luni</option>
            <option value="1 an">1 an</option>
        </select><br><br>
        <input type="button" value="add rca..." id="add_rca"><br><br>
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

        $("#add_rca").on("click", function() {
            let car_id = $("#car_id").val();
            let rca_start = $("#rca_start").val();
            let rca_duration = $("#rca_duration").val();
            
            if(!car_id || !rca_start || !rca_duration) {
                sessionStorage.setItem("msg", "completeaza toate campurile");
                window.location.reload();
            }
        
            $.post("/backend/rca.php", {car_id: car_id, rca_start: rca_start, rca_duration: rca_duration}, function(msg) {
                sessionStorage.setItem("msg", msg);
                window.location.reload();
                console.log("Sever respone:" + msg);
            }).fail(function(xhr, status, error) {
                console.log(xhr.responseText);
                console.log(error);
            });

        });
    });
</script>