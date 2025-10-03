<header>
    <nav>
        <ul>
            <?php if(isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])): ?>
                <li>Cars</li>

            <?php else: ?>
                <li><a href="/php/car managment/views/users/login.php">Logheaza-te</a></li>
                <li><a href="">Inregistreaza-te</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>