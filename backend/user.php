<?php
function login_user(mysqli $db, $email, $password) {
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
            session_start();
            session_regenerate_id(true);
            $_SESSION["user_id"] = $user["id"];
            echo "okey.";
        }else{
            echo "parola este invalida.";
        }
    }else{
        echo "utilizatorul nu exista.";
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

function user_log_out() {
    if(session_status() === PHP_SESSION_NONE) session_start();
    error_log("user_log_out() called");
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    return !isset($_SESSION["user_id"]);
}

$request_method = $_SERVER["REQUEST_METHOD"];
include_once "db.php";
$db = db_connection();
if($request_method == "POST") {
    if(isset($_POST) && !empty($_POST)) {
        if( isset($_POST["first_name"]) && !empty($_POST["first_name"])
            && isset($_POST["last_name"]) && !empty($_POST["last_name"])
            && isset($_POST["birth_date"]) && !empty($_POST["birth_date"])
            && isset($_POST["password"]) && !empty($_POST["password"])
            && isset($_POST["password_again"]) && !empty($_POST["password_again"])
            && isset($_POST["email"]) && !empty($_POST["email"])
            ) {
                $first_name = $_POST["first_name"];
                $last_name = $_POST["last_name"];
                $birth_date = $_POST["birth_date"];
                $password = $_POST["password"];
                $password_again = $_POST["password_again"];
                $email = $_POST["email"];

                if($password !== $password_again ) {
                    echo "parolele nu sunt identice.";
                }
                    
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                if(add_user($db, $first_name, $last_name, $birth_date, $hashed_password, $email)) {
                    echo "ai creat utilizatorul, acuma logheaza-te.";
                    exit;
                }
            }

        if(isset($_POST["email"]) && !empty($_POST["email"]) && isset($_POST["password"]) && !empty($_POST["password"])) {
            $email = $_POST["email"];
            $password = $_POST["password"];

                
            login_user($db, $email, $password);
            exit;
        }                
    }
}elseif($request_method == "DELETE") {
    parse_str(file_get_contents("php://input"), $delete);
    if(isset($delete["user_id"]) && !empty($delete["user_id"])) {
        if(user_log_out()) {
            echo "ai iesit de pe cont.";
        }else{
            echo "o problema la delogare.";
        }
    }else{
        echo "problema cu data introducs pt logout.";
    }

}

?>