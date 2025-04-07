<?php
$numPlayers = isset($_GET['players']) ? (int)$_GET['players'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enter Player Names</title>
    <style>
        body {
            background-color: #060ce9;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h1 {
            color: #fff;
            margin-top: 30px;
        }
        .form-container {
            background: #fff;
            border-radius: 8px;
            padding: 20px 40px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            margin-top: 20px;
        }
        .form-container label {
            display: block;
            margin: 10px 0 5px;
        }
        .form-container input {
            width: 200px;
            padding: 8px;
            font-size: 1rem;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .submit-btn {
            background-color: #FFD700;
            color: #000;
            border: none;
            border-radius: 5px;
            padding: 0.75rem 1.5rem;
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
            margin-top: 10px;
            transition: transform 0.3s, background 0.3s;
        }
        .submit-btn:hover {
            transform: scale(1.05);
            background-color: #ffec85;
        }
        .note {
            color: red;
            margin-top: 10px;
        }
        .bottom-links {
            margin-top: 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        .bottom-links a {
            text-decoration: none;
            color: #FFD700;
            margin: 0 10px;
            font-size: 1rem;
        }
    </style>
</head>
<body>
<h1>Enter Player Names</h1>
<div class="form-container">
    <form action="storeplayers.php" method="post">
        <?php if ($numPlayers > 0): ?>
            <input type="hidden" name="numPlayers" value="<?php echo $numPlayers; ?>">
            <?php for ($i = 1; $i <= $numPlayers; $i++): ?>
                <label for="player<?php echo $i; ?>">Player <?php echo $i; ?> Name:</label>
                <input type="text" name="player<?php echo $i; ?>" id="player<?php echo $i; ?>" required>
            <?php endfor; ?>
            <button type="submit" class="submit-btn">Start Game</button>
        <?php else: ?>
            <p class="note">No valid player count specified. <br>
            <a href="gameselect.php" style="color:#000;text-decoration:underline;">Go back</a></p>
        <?php endif; ?>
    </form>
</div>
<div class="bottom-links">
<?php
$c = basename(__FILE__);
if ($c !== 'rules.html') {
    echo '<a href="rules.html">Rules</a>';
}
if ($c !== 'leaderboard.php') {
    echo '<a href="leaderboard.php">Leaderboard</a>';
}
echo '<a href="index.html">Home</a>';
echo '<a href="https://www.jeopardy.com/" target="_blank">Official Jeopardy Website</a>';
?>
</div>
</body>
</html>

