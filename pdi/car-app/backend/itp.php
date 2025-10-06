<?php
session_start();
function add_itp($db, $car_id, $start, $end) {
    $query = "INSERT INTO `itps` (start_date, end_date, car_id) VALUES (?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ssi", $start, $end, $car_id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function delete_itp($db, $car_id, $itp_id) {
    $query = "DELETE FROM `itps` WHERE car_id = ? AND id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $car_id, $itp_id);
    $stmt->execute();
    $affected_rows = $stmt->affected_rows;
    $stmt->close();
    return ($affected_rows > 0);
}

if(isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])) {
    $user_id = intval($_SESSION["user_id"]);
    include_once "db.php";
    $db = db_connection();
    $request_method = $_SERVER["REQUEST_METHOD"];
    if($request_method == "POST") {
        if(isset($_POST["car_id"]) && !empty($_POST["car_id"]) && isset($_POST["itp_start"]) && !empty($_POST["itp_start"]) && isset($_POST["itp_duration"]) && !empty($_POST["itp_duration"])) {
            $car_id = intval($_POST["car_id"]);
            $start_date = $_POST["itp_start"];
            $duration  = $_POST["itp_duration"];
            if($duration == "1 an") {
                $end_date = date("Y-m-d", strtotime("+1 year", strtotime($start_date)));
            }elseif($duration == "3 luni") {
                $end_date = date("Y-m-d", strtotime("+3 months", strtotime($start_date)));
            }elseif($duration == "6 luni") {
                $end_date = date("Y-m-d", strtotime("+6 months", strtotime($start_date)));
            }else{
                echo "selecteaza durata itp.";
            }

            if(add_itp($db, $car_id, $start_date, $end_date)) {
                echo "you added a itp for the car.";
            }
        }else{ 
            echo "problema cu parametrii trimisi.";
        }
    }elseif($request_method == "DELETE") {
        parse_str(file_get_contents("php://input"), $delete);
        if(isset($delete["car_id"]) && !empty($delete["car_id"]) && isset($delete["itp_id"]) && !empty($delete["itp_id"])) {
            if(delete_itp($db, $delete["car_id"], $delete["itp_id"])) {
                echo "ai sters itp-ul masinii.";
            }else{
                echo "problema la stergerea itp-ului masinii.";
            }
        }else{
            echo "parametrii trimisi nu sunt buni.";
        }
    }
}

?>