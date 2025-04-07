<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Game Select</title>
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
        header {
            margin-top: 20px;
            text-align: center;
        }
        .logo {
            width: 200px;
            display: block;
            margin: 0 auto;
        }
        h1 {
            color: #fff;
            margin: 30px 0 20px 0;
            font-size: 2rem;
            text-align: center;
        }
        .selection-container {
            background: #fff;
            border-radius: 8px;
            padding: 20px 40px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            text-align: center;
            margin-bottom: 30px;
        }
        form {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        label {
            font-size: 1rem;
            color: #000;
        }
        select {
            padding: 6px;
            font-size: 1rem;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .next-btn {
            background-color: #FFD700;
            color: #000;
            border: none;
            border-radius: 5px;
            padding: 0.75rem 1.5rem;
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
            transition: transform 0.3s, background 0.3s;
        }
        .next-btn:hover {
            transform: scale(1.05);
            background-color: #ffec85;
        }
        .bottom-links {
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
<header>
    <img src="images/jeopardy.png" alt="Jeopardy Logo" class="logo">
</header>
<h1>Welcome to Jeopardy!</h1>
<div class="selection-container">
    <form action="playername.php" method="get">
        <label for="players">How many players?</label>
        <select name="players" id="players">
            <option value="1">1 Player</option>
            <option value="2">2 Players</option>
            <option value="3">3 Players</option>
            <option value="4">4 Players (2 Teams)</option>
        </select>
        <button type="submit" class="next-btn">Next</button>
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
