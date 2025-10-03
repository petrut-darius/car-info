<?php

function get_all_cars(mysqli $db, $user_id):array {
    //$query = "SELECT * FROM `cars` LEFT JOIN rcas ON rcas.car_id = cars.id LEFT JOIN itps on itps.car_id = cars.id WHERE user_id = ?";
    $query = "SELECT * FROM `cars`";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $user_id);
    $result = $stmt->get_result();
    $cars = [];

    while($row = mysqli_fetch_assoc($result)) {
        $cars[] = $row; 
    }

    return $cars;
}
/*
function add_car($db, $marca, $model, $an, $serie_sasiu, $numar_inmatriculare) {
    $query = "INSERT INTO `cars` (marca, model, an, serie, numar) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("sssss", $marca, $model, $an, $serie_sasiu, $numar_inmatriculare);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
*/


function update_car($db, int $id, $numar_inmatriculare) {
    $query = "UPDATE `cars` SET (numar_inmatriculare) VALUES(?) WHERE id = " . $id;
    $stmt = $db->prepare($query);
    $stmt->bind_param($numar_inmatriculare);
    $result = $stmt->execute();
    $stmt->close();

    return $result;
}

function delete_car($db, $car_id) {
    $query = "DELETE FROM `cars` WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $affected_rows = $stmt->affected_rows;
    $stmt->close();
    return ($affected_rows > 0);
}

?>