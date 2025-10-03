<?php

function add_rca($db, $user_id, $car_id, $start, $end) {
    $query = "INSERT INTO `rcas` (rca_start_date, rca_end_date, car_id, user_id) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ssii", $start, $end, $car_id, $user_id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function delete_rca($db, $user_id, $car_id) {
    $query = "DELETE FROM `rcas` WHERE car_id = ? AND user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $car_id, $user_id);
    $stmt->execute();
    $affected_rows = $stmt->affected_rows;
    $stmt->close();
    return ($affected_rows > 0);
}

?>