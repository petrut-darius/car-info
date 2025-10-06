<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<header>
    <nav>
        <ul>
            <?php if(isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])): ?>
                <a href="/views/cars/index.php"><li>Cars</li></a>
                <a href="/views/cars/update.php"><li>Update car</li></a>
                <li id="logout">Delogare</li>
            <?php else: ?>
                <a href="/views/users/login.php"><li>Logheaza-te</li></a>
                <a href="/views/users/register.php"><li>Inregistreaza-te</li></a>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<p id="msg"></p>

<script src="../jquery.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
    <?php if(isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])):?>
        let user_id = <?php echo isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;?>
    <?php endif;?>

    $("#logout").on("click", function() {
        $.ajax({
            url: "/backend/user.php",
            type: "DELETE",
            data: $.param({user_id: user_id}),
            success: function(res) {
                window.location.reload();
                $("#msg").html(res);
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                console.log(error);
            }
        });
    });
});

</script>