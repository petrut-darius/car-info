<?php
session_start();
if (isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])) {
    require_once "db.php";
    $user_id = $_SESSION["user_id"];

    function get_user_email($db, $user_id) {
        $query = "SELECT email FROM `users` WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $email = $result->fetch_assoc();
        return $email['email'] ?? null; 
    }

    function get_all_cars($db, $user_id): array {
        $query = "SELECT id FROM `cars` WHERE user_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $cars = [];

        while ($row = $result->fetch_assoc()) {
            $cars[] = $row['id'];
        }

        return $cars;
    }

    function check_expiring($db, $table, $car_id, $oneMonthLater) {
        $query = "SELECT end_date FROM `" . $table . "` WHERE car_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $car_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();

        if (!$data) return false;
        return ($data['end_date'] === $oneMonthLater);
    }

    $today = date("Y-m-d");
    $oneMonthLater = date('Y-m-d', strtotime('+1 month'));

    $cars = get_all_cars($db, $user_id);
    $user_email = get_user_email($db, $user_id);

    foreach ($cars as $car_id) {
        if (check_expiring($db, 'itps', $car_id, $oneMonthLater)) {
            $subject = "ITP-ul expiră în curând – verifică data!";
            $message = "Bună, ITP-ul uneia dintre mașinile tale va expira peste o lună. Te rugăm să verifici și să faci o programare.";
            $headers = "From: eminoviciidarius@gmail.com";
            mail($user_email, $subject, $message, $headers);
        }

        if (check_expiring($db, 'rcas', $car_id, $oneMonthLater)) {
            $subject = "RCA-ul expiră în curând – verifică data!";
            $message = "Bună, RCA-ul uneia dintre mașinile tale va expira peste o lună. Te rugăm să verifici și să îl reînnoiești.";
            $headers = "From: eminoviciidarius@gmail.com";
            mail($user_email, $subject, $message, $headers);
        }
    }
}
?>
