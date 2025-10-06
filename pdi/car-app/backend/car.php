<?php
session_start();
if(isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])) {
    $user_id = intval($_SESSION["user_id"]);

    function get_all_cars(mysqli $db, $user_id):array {
        //$query = "SELECT * FROM `cars` LEFT JOIN rcas ON rcas.car_id = cars.id LEFT JOIN itps on itps.car_id = cars.id WHERE user_id = ?";
        $query = "SELECT 
                    cars.id AS car_id,
                    cars.user_id,
                    cars.marca,
                    cars.model,
                    cars.an,
                    cars.numar,
                    cars.volum_motor,
                    rcas.id AS rca_id,
                    rcas.end_date AS rca_end,
                    itps.id AS itp_id,
                    itps.end_date AS itp_end
                FROM cars
                LEFT JOIN rcas ON rcas.car_id = cars.id
                LEFT JOIN itps ON itps.car_id = cars.id
                WHERE cars.user_id = ?
                ORDER BY cars.id DESC;
                ";
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

    function add_car($db, $marca, $model, $an, $serie_sasiu, $numar_inmatriculare, $volum, $user_id) {
        $query = "INSERT INTO `cars` (marca, model, an, serie, numar, volum_motor, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("sssssii", $marca, $model, $an, $serie_sasiu, $numar_inmatriculare, $volum, $user_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }


    function update_car($db, $user_id, $car_id, $numar_inmatriculare,) {
        $query = "UPDATE `cars` SET numar = ? WHERE id = ? AND user_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("sii", $numar_inmatriculare, $car_id, $user_id);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    function delete_car($db, $car_id, $user_id) {
        $query = "DELETE FROM `cars` WHERE id = ? AND user_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ii", $car_id, $user_id);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        return ($affected_rows > 0);
    }


    $request_method = $_SERVER["REQUEST_METHOD"];
    include_once "db.php";
    $db = db_connection();
    if($request_method == "POST") {
        if(isset($_POST) && !empty($_POST)){
            if(isset($_POST["marca"]) && !empty($_POST["marca"]) && isset($_POST["model"]) && !empty($_POST["model"]) && isset($_POST["an"]) && !empty($_POST["an"]) && isset($_POST["serie_sasiu"]) && !empty($_POST["serie_sasiu"]) && isset($_POST["numar_inmatriculare"]) && !empty($_POST["numar_inmatriculare"]) && isset($_POST["volum_motor"]) && !empty($_POST["volum_motor"])) {
                $marca = $_POST["marca"];
                $model = $_POST["model"];
                $an = $_POST["an"];
                $serie_sasiu = $_POST["serie_sasiu"];
                $numar_inmatriculare = $_POST["numar_inmatriculare"];
                $volum = intval($_POST["volum_motor"]);

                if(add_car($db, $marca, $model, $an, $serie_sasiu, $numar_inmatriculare, $volum, $user_id)) {
                    echo "you added a car to the db.";
                }else{
                    echo "failed to add car.";
                }
            }else{
                echo "problem with the params you inserted.";
            }
        }
    }elseif($request_method == "GET") {
        if(isset($_GET) && empty($_GET)) {
            $cars = get_all_cars($db, $user_id);
            foreach($cars as $car) {
               echo "<p>" . $car["marca"] . " " . $car["model"] . " " . $car["numar"]. " " . (isset($car["rca_end"]) && $car["rca_end"] ? "asigurare valabila pana in: " . $car["rca_end"] . "    <input type=\"button\" value=\"delete rca...\" class=\"delete_rca\" data-car-id=" . $car["car_id"] . " data-rca-id=" . $car["rca_id"] . ">" : "<a href='/pdi/car-app/views/rcas/new.php'>adauga rca...</a>") . " " . (isset($car["itp_end"]) && $car["itp_end"] ? "itp valabil pana in: " . $car["itp_end"] . "   <input type=\"button\" value=\"delete itp...\" class=\"delete_itp\" data-car-id=" . $car["car_id"] . " data-itp-id=" . $car["itp_id"] . ">" : "<a href='/pdi/car-app/views/itps/new.php'>adauga itp...</a>") . "   <input type=\"button\" value=\"delete car...\" class=\"delete_car\" id=\"delete_car\" data-car-id=" . $car["car_id"] ."></p><br>";
            }
        }
    }elseif($request_method == "PATCH") {
        parse_str(file_get_contents("php://input"), $update);
        if(isset($update["car_id"]) && !empty($update["car_id"]) && isset($update["numar_inmatriculare_nou"]) && !empty($update["numar_inmatriculare_nou"])) {
            $car_id = filter_var($update["car_id"], FILTER_VALIDATE_INT);
            $numar_inmatriculare_nou = filter_var($update["numar_inmatriculare_nou"], FILTER_SANITIZE_STRING);
            if(update_car($db, $user_id, $car_id, $numar_inmatriculare_nou)) {
                echo "ai updatat masina";
            }else{
                echo "ceva nu a mers bine la updatarea masinii.";
            }
        }else{
            echo "parametrii trimisi nu sunt buni.";
        }
    }elseif($request_method == "DELETE") {
        parse_str(file_get_contents("php://input"), $delete);
        if(isset($delete["car_id"]) && !empty($delete["car_id"])) {
            $car_id = filter_var($delete["car_id"], FILTER_VALIDATE_INT);
            if(delete_car($db, $car_id, $user_id)) {
                echo "ai sters masina.";
            }else{
                echo "ceva nu a mers bine la stergea masinii.";
            }
        }else{
            echo "parmetrul trimis nu este bun.";
        }
    }
}
?>