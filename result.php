<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Location: gameboard.php');
    exit;
} else {
    echo "Invalid request.";
}
?>
