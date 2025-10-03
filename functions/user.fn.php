<?php

function login_user(mysqli $db, $email, $password):string {
    $query = "SELECT * FROM `users` WHERE email = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $num_rows = $result->num_rows;
    if($num_rows === 1) {
        $user = $result->fetch_assoc();
        $stmt->close();
        if(password_verify($password, $user["password"])) {
            session_regenerate_id(true);
            $_SESSION["user_id"] = $user["id"];
            return json_encode(["msg" => "you are loged in as " . $user["first_name"] . " " . $user["last_name"] . "."], JSON_PRETTY_PRINT);
        }else{
            return json_encode(["msg" => "password invalid."], JSON_PRETTY_PRINT);
        }
    }else{
        return json_encode(["msg" => "user not found."], JSON_PRETTY_PRINT);
    }

}

function get_user_data(mysqli $db,int $id,string $column_name) {
    $allowed = ["first_name", "last_name", "email"];

    if(in_array($column_name, $allowed)) {
        $query = "SELECT " . $column_name . " FROM `users` WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->fetch_assoc();
    }else{ 
        return false;
    }
}

function add_user(mysqli $db, $first_name, $last_name, $birth_date, $password, $email) {
    $query = "INSERT INTO `users` (first_name, last_name, birth_date, password, email) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("sssss", $first_name, $last_name, $birth_date, $password, $email);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function update_password(mysqli $db, int $id, $password) {
    $query = "UPDATE `users` SET password = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("si", $password, $id);
    $result = $stmt->execute();
    $stmt->close();

    return $result;
}
?>

?>