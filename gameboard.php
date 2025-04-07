<?php
$players = [];
$playersFile = __DIR__.'/players.txt';
if (file_exists($playersFile)) {
    $lines = file($playersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $parts = explode('|', $line);
        if (count($parts) === 2) {
            $players[] = [
                'name' => $parts[0],
                'score'=> (int)$parts[1]
            ];
        }
    }
}
$turnIndex = 0;
$usedTiles = [];
$teamMode = 0;
$turnOrder = [];
$gameStateFile = __DIR__.'/gamestate.txt';
if (file_exists($gameStateFile)) {
    $gsLines = file($gameStateFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($gsLines as $gsLine) {
        if (strpos($gsLine, 'TURN_INDEX=') === 0) {
            $turnIndex = (int)substr($gsLine, 11);
        } elseif (strpos($gsLine, 'USED=') === 0) {
            $usedStr = substr($gsLine, 5);
            if (!empty($usedStr)) {
                $usedTiles = explode(',', $usedStr);
            }
        } elseif (strpos($gsLine, 'TEAM_MODE=') === 0) {
            $teamMode = (int)substr($gsLine, 10);
        } elseif (strpos($gsLine, 'TURN_ORDER=') === 0) {
            $orderStr = substr($gsLine, 11);
            if (!empty($orderStr)) {
                $turnOrder = array_map('intval', explode(',', $orderStr));
            }
        }
    }
}
if (empty($turnOrder)) {
    for ($i=0; $i < count($players); $i++){
        $turnOrder[] = $i;
    }
}

if (isset($_GET['end']) && $_GET['end'] === '1') {
    endGameAndAppendLeaderboard($players, $teamMode, $usedTiles);
    exit;
}

if (count($usedTiles) >= 25) {
    endGameAndAppendLeaderboard($players, $teamMode, $usedTiles);
    exit;
}

$currentIndex = $turnOrder[$turnIndex % count($turnOrder)];
$currentPlayerName = isset($players[$currentIndex]) ? $players[$currentIndex]['name'] : '???';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Jeopardy Board</title>
    <style>
        body {
            background-color: #060ce9;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        header {
            text-align: center;
            color: #fff;
            padding-top: 20px;
        }
        .logo {
            width: 200px;
        }
        .board-container {
            margin: 20px auto;
            width: 80%;
            max-width: 900px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
            color: #fff;
            background-color: #060ce9;
            border: 2px solid #000;
        }
        th, td {
            border: 2px solid #000;
            padding: 20px;
            font-size: 1.2rem;
            transition: transform 0.3s;
        }
        th {
            background-color: #060ce9;
        }
        td:hover {
            transform: scale(1.05);
        }
        a.tile-link {
            color: #fff;
            font-weight: bold;
            text-decoration: none;
            transition: color 0.3s;
        }
        a.tile-link:hover {
            text-decoration: underline;
            color: #ffec85;
        }
        .player-scores {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 30px 0;
        }
        .player-box {
            background: #FFD700;
            border-radius: 8px;
            width: 120px;
            text-align: center;
            padding: 10px;
            color: #000;
        }
        .player-box h4 {
            margin: 0 0 5px 0;
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
        .debug-info {
            color: #fff;
            margin: 10px;
            text-align: center;
            font-size: 0.9rem;
        }
        .end-btn {
            background-color: #FFD700;
            color: #000;
            border: none;
            border-radius: 5px;
            padding: 0.5rem 1rem;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 10px;
            transition: transform 0.3s;
        }
        .end-btn:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
<header>
    <img src="images/jeopardy.png" alt="Jeopardy Logo" class="logo">
    <h2>It's <?php echo htmlspecialchars($currentPlayerName); ?>'s turn!</h2>
</header>
<div class="board-container">
    <table>
        <tr>
            <th>Category 1</th>
            <th>Category 2</th>
            <th>Category 3</th>
            <th>Category 4</th>
            <th>Category 5</th>
        </tr>
        <?php
        $vals = [200,400,600,800,1000];
        for($r=0;$r<5;$r++){
            echo "<tr>";
            for($c=1;$c<=5;$c++){
                $tileKey="$c-".$vals[$r];
                if(in_array($tileKey, $usedTiles)){
                    echo "<td>—</td>";
                } else {
                    echo "<td><a class='tile-link' href='gamecard.php?cat=$c&val=".$vals[$r]."'>\$".$vals[$r]."</a></td>";
                }
            }
            echo "</tr>";
        }
        ?>
    </table>
</div>
<div class="player-scores">
    <?php foreach($players as $p): ?>
    <div class="player-box">
        <h4><?php echo htmlspecialchars($p['name']); ?></h4>
        <p>$<?php echo (int)$p['score']; ?></p>
    </div>
    <?php endforeach; ?>
</div>
<div class="debug-info">
    Used Tiles: <?php echo count($usedTiles); ?>/25
    <form style="margin-top:10px;" method="get">
        <input type="hidden" name="end" value="1">
        <button class="end-btn" type="submit">Force End Game</button>
    </form>
</div>
<div class="bottom-links">
    <a href="rules.html">Rules</a>
    <a href="leaderboard.php">Leaderboard</a>
    <a href="index.html">Home</a>
    <a href="https://www.jeopardy.com/" target="_blank">Official Jeopardy Website</a>
</div>
</body>
</html>
<?php

function endGameAndAppendLeaderboard($players, $teamMode, $usedTiles) {
    $leaderFile = __DIR__.'/leaderboard.txt';
    $lfh = fopen($leaderFile, 'a');
    if ($lfh) {
        if ($teamMode===1 && count($players)===4) {
            $teamAScore = $players[0]['score'] + $players[1]['score'];
            $teamBScore = $players[2]['score'] + $players[3]['score'];
            if ($teamAScore > $teamBScore) {
                fwrite($lfh,"TEAM: {$players[0]['name']} & {$players[1]['name']}|$teamAScore\n");
            } elseif ($teamBScore > $teamAScore) {
                fwrite($lfh,"TEAM: {$players[2]['name']} & {$players[3]['name']}|$teamBScore\n");
            } else {
                fwrite($lfh,"TEAM: {$players[0]['name']} & {$players[1]['name']}|$teamAScore\n");
                fwrite($lfh,"TEAM: {$players[2]['name']} & {$players[3]['name']}|$teamBScore\n");
            }
        } else {
            foreach($players as $pl) {
                fwrite($lfh,$pl['name'].'|'.$pl['score']."\n");
            }
        }
        fclose($lfh);
    }
    showEndScreen($players, $teamMode);
}

function showEndScreen($players, $teamMode) {
    if ($teamMode===1 && count($players)===4) {
        $scoreA=$players[0]['score']+$players[1]['score'];
        $scoreB=$players[2]['score']+$players[3]['score'];
        if ($scoreA>$scoreB) {
            $winnerLabel="TEAM: {$players[0]['name']} & {$players[1]['name']}";
            $highest=$scoreA;
        } elseif($scoreB>$scoreA) {
            $winnerLabel="TEAM: {$players[2]['name']} & {$players[3]['name']}";
            $highest=$scoreB;
        } else {
            $winnerLabel="Tie between Team A & Team B";
            $highest=$scoreA;
        }
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Jeopardy - Game Over</title>
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
                .winner-container {
                    margin-top: 40px;
                }
                .player-scores {
                    display: flex;
                    justify-content: center;
                    gap: 20px;
                    margin: 30px 0;
                }
                .player-box {
                    background: #FFD700;
                    border-radius: 8px;
                    width: 140px;
                    text-align: center;
                    padding: 10px;
                    color: #000;
                }
                h1,h2,h3 {
                    margin: 0.5em 0;
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
        <img src="images/jeopardy.png" alt="Jeopardy Logo" class="logo">
        <h1>Game Over!</h1>
        <div class="winner-container">
        <?php if(strpos($winnerLabel,'Tie')!==false): ?>
            <h2><?php echo $winnerLabel; ?></h2>
            <h3>with <?php echo $highest; ?> points each!</h3>
        <?php else: ?>
            <h2>Winner: <?php echo $winnerLabel; ?></h2>
            <h3>with <?php echo $highest; ?> points!</h3>
        <?php endif; ?>
        </div>
        <h2>Final Scores</h2>
        <div class="player-scores">
        <?php foreach($players as $plr): ?>
            <div class="player-box">
                <h4><?php echo htmlspecialchars($plr['name']); ?></h4>
                <p>$<?php echo (int)$plr['score']; ?></p>
            </div>
        <?php endforeach; ?>
        </div>
        <div class="bottom-links">
            <a href="rules.html">Rules</a>
            <a href="leaderboard.php">Leaderboard</a>
            <a href="index.html">Home</a>
            <a href="https://www.jeopardy.com/" target="_blank">Official Jeopardy Website</a>
        </div>
        </body>
        </html>
        <?php
    } else {
        $highest=-999999;
        $winners=[];
        foreach($players as $p){
            if($p['score']>$highest){
                $highest=$p['score'];
                $winners=[$p['name']];
            } elseif($p['score']===$highest){
                $winners[]=$p['name'];
            }
        }
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Jeopardy - Game Over</title>
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
                .winner-container {
                    margin-top: 40px;
                }
                .player-scores {
                    display: flex;
                    justify-content: center;
                    gap: 20px;
                    margin: 30px 0;
                }
                .player-box {
                    background: #FFD700;
                    border-radius: 8px;
                    width: 140px;
                    text-align: center;
                    padding: 10px;
                    color: #000;
                }
                h1,h2,h3 {
                    margin: 0.5em 0;
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
        <img src="images/jeopardy.png" alt="Jeopardy Logo" class="logo">
        <h1>Game Over!</h1>
        <div class="winner-container">
        <?php if(count($winners)>1): ?>
            <h2>Tie between <?php echo implode(' & ',$winners); ?></h2>
            <h3>with <?php echo $highest; ?> points each!</h3>
        <?php else: ?>
            <h2>Winner: <?php echo htmlspecialchars($winners[0]); ?></h2>
            <h3>with <?php echo $highest; ?> points!</h3>
        <?php endif; ?>
        </div>
        <h2>Final Scores</h2>
        <div class="player-scores">
        <?php foreach($players as $plr): ?>
            <div class="player-box">
                <h4><?php echo htmlspecialchars($plr['name']); ?></h4>
                <p>$<?php echo (int)$plr['score']; ?></p>
            </div>
        <?php endforeach; ?>
        </div>
        <div class="bottom-links">
            <a href="rules.html">Rules</a>
            <a href="leaderboard.php">Leaderboard</a>
            <a href="index.html">Home</a>
            <a href="https://www.jeopardy.com/" target="_blank">Official Jeopardy Website</a>
        </div>
        </body>
        </html>
        <?php
    }
}

