<?php
$leaderFile = __DIR__.'/leaderboard.txt';
$entries = [];
if (file_exists($leaderFile)) {
    $lines = file($leaderFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $parts = explode('|', $line);
        if (count($parts)===2) {
            $entries[] = ['name'=>$parts[0],'score'=>(int)$parts[1]];
        }
    }
}
usort($entries, function($a,$b){
    return $b['score'] - $a['score'];
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Jeopardy Leaderboard</title>
    <style>
        body {
            background-color: #060ce9;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            color: #fff;
            text-align: center;
        }
        .logo {
            width: 200px;
            margin-top: 20px;
        }
        h1 {
            margin-top: 20px;
        }
        table {
            margin: 20px auto;
            width: 70%;
            max-width: 600px;
            border-collapse: collapse;
            background-color: #fff;
            color: #000;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        th {
            background-color: #FFD700;
        }
        .no-data {
            margin-top: 40px;
            font-size: 1.2rem;
            color: #ffec85;
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
<img src="images/jeopardy.png" alt="Jeopardy Logo" class="logo">
<h1>All-Time Leaderboard</h1>
<?php if (empty($entries)): ?>
<div class="no-data">No data yet.</div>
<?php else: ?>
<table>
    <thead>
        <tr>
            <th>Player / Team</th>
            <th>Score</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($entries as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td>$<?php echo $row['score']; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
<div class="bottom-links">
    <a href="rules.html">Rules</a>
    <a href="index.html">Home</a>
    <a href="https://www.jeopardy.com/" target="_blank">Official Jeopardy Website</a>
</div>
</body>
</html>

