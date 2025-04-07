<?php
$cat = isset($_GET['cat'])?(int)$_GET['cat']:1;
$val = isset($_GET['val'])?(int)$_GET['val']:200;
$cText="Category $cat";
$vText="\$$val";
$tKey="$cat-$val";
$playersData=[];
$pFile=__DIR__.'/players.txt';
if(file_exists($pFile)){
    $ls=file($pFile,FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
    foreach($ls as $ln){
        $pr=explode('|',$ln);
        if(count($pr)===2){
            $playersData[]=['name'=>$pr[0],'score'=>(int)$pr[1]];
        }
    }
}
$tIndex=0;
$uTiles=[];
$tMode=0;
$tOrder=[];
$gFile=__DIR__.'/gamestate.txt';
if(file_exists($gFile)){
    $gLines=file($gFile,FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
    foreach($gLines as $g){
        if(strpos($g,'TURN_INDEX=')===0){
            $tIndex=(int)substr($g,11);
        }elseif(strpos($g,'USED=')===0){
            $s=substr($g,5);
            if(!empty($s))$uTiles=explode(',',$s);
        }elseif(strpos($g,'TEAM_MODE=')===0){
            $tMode=(int)substr($g,10);
        }elseif(strpos($g,'TURN_ORDER=')===0){
            $od=substr($g,11);
            if(!empty($od))$tOrder=array_map('intval',explode(',',$od));
        }
    }
}
if(empty($tOrder)){
    for($i=0;$i<count($playersData);$i++){
        $tOrder[]=$i;
    }
}
if(in_array($tKey,$uTiles)){
    header("Location: gameboard.php");
    exit;
}
$cpIndex=$tOrder[$tIndex%count($tOrder)];
$cpName=isset($playersData[$cpIndex])?$playersData[$cpIndex]['name']:'???';
$fClass="";
$revAns="";
if($_SERVER['REQUEST_METHOD']==='POST'){
    $act=isset($_POST['action'])?$_POST['action']:'';
    if($act==='skip'){
        $uTiles[]=$tKey;
        $tIndex++;
        $revAns='Skipped! Correct answer is "answer".';
        $fClass="";
    }elseif($act==='answer'){
        $ans=trim($_POST['playerAnswer']);
        $uTiles[]=$tKey;
        if(strcasecmp($ans,'answer')===0){
            $playersData[$cpIndex]['score']+=$val;
            $fClass="correct";
            $revAns='Correct! The answer is "answer".';
        }else{
            $fClass="wrong";
            $revAns='Wrong! The correct answer is "answer".';
        }
        $tIndex++;
    }
    $pfh=fopen($pFile,'w');
    if($pfh){
        foreach($playersData as $pp){
            fwrite($pfh,$pp['name']."|".$pp['score']."\n");
        }
        fclose($pfh);
    }
    $gfh=fopen($gFile,'w');
    if($gfh){
        fwrite($gfh,"TURN_INDEX=$tIndex\n");
        fwrite($gfh,"USED=".implode(',',$uTiles)."\n");
        fwrite($gfh,"TEAM_MODE=$tMode\n");
        fwrite($gfh,"TURN_ORDER=".implode(',',$tOrder)."\n");
        fclose($gfh);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Jeopardy Question</title>
    <style>
        body {
            background-color: #060ce9;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            color: #fff;
            text-align: center;
        }
        .header {
            margin-top: 20px;
        }
        .question-container {
            width: 80%;
            max-width: 900px;
            margin: 20px auto;
            background: #060ce9;
            border: 2px solid #000;
            border-radius: 8px;
            padding: 20px;
            position: relative;
        }
        @keyframes greenGlow {
            0% { box-shadow: 0 0 10px #0f0; }
            50% { box-shadow: 0 0 20px #0f0; }
            100% { box-shadow: 0 0 10px #0f0; }
        }
        @keyframes shake {
            0% { transform: translateX(0); }
            20% { transform: translateX(-8px); }
            40% { transform: translateX(8px); }
            60% { transform: translateX(-8px); }
            80% { transform: translateX(8px); }
            100% { transform: translateX(0); }
        }
        .feedback.correct {
            animation: greenGlow 1s;
            border: 2px solid #0f0;
        }
        .feedback.wrong {
            animation: shake 1s;
            border: 2px solid red;
        }
        .skip-form {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .skip-btn {
            background-color: #fff;
            color: #000;
            border: none;
            border-radius: 5px;
            padding: 0.5rem 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s;
        }
        .skip-btn:hover {
            transform: scale(1.05);
        }
        .buzz-form {
            margin-top: 40px;
        }
        .answer-input {
            width: 300px;
            padding: 8px;
            font-size: 1rem;
            margin-top: 10px;
            border-radius: 5px;
            border: none;
        }
        .submit-btn {
            background-color: #FFD700;
            color: #000;
            border: none;
            border-radius: 5px;
            padding: 0.5rem 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s, background 0.3s;
        }
        .submit-btn:hover {
            transform: scale(1.05);
            background-color: #ffec85;
        }
        .reveal-box {
            margin-top: 20px;
            font-weight: bold;
        }
        .close-link {
            display: inline-block;
            margin-top: 10px;
            color: #fff;
            text-decoration: underline;
            cursor: pointer;
            font-weight: normal;
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
        .logo {
            width:200px;
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
<div class="header">
    <img src="images/jeopardy.png" alt="Jeopardy Logo" class="logo">
</div>
<div class="question-container <?php echo $fClass ? 'feedback '.$fClass : ''; ?>">
<?php if(!$fClass && empty($revAns)): ?>
<form class="skip-form" method="post">
    <input type="hidden" name="action" value="skip">
    <button type="submit" class="skip-btn">Skip</button>
</form>
<?php endif; ?>
<h1><?php echo $cText; ?></h1>
<h3>Points Worth: <?php echo $vText; ?></h3>
<p style="font-size:1.1rem;">
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices.
</p>
<?php if(!$fClass && empty($revAns)): ?>
<div class="buzz-form">
    <p><strong>Button to buzz in and type answer</strong></p>
    <form method="post">
        <input type="hidden" name="action" value="answer">
        <input type="text" class="answer-input" name="playerAnswer" placeholder="Type 'answer' to be correct" required />
        <br><br>
        <button type="submit" class="submit-btn">Submit Answer</button>
    </form>
</div>
<?php else: ?>
<div class="reveal-box">
    <?php echo $revAns; ?>
    <div class="close-link" onclick="window.location='gameboard.php'">Click to Close</div>
</div>
<?php endif; ?>
</div>
<div class="player-scores">
<?php foreach($playersData as $p): ?>
<div class="player-box">
    <h4><?php echo htmlspecialchars($p['name']); ?></h4>
    <p>$<?php echo (int)$p['score']; ?></p>
</div>
<?php endforeach; ?>
</div>
<div class="bottom-links">
<?php
$c=basename(__FILE__);
if($c!=='rules.html') {
    echo '<a href="rules.html">Rules</a>';
}
if($c!=='leaderboard.php') {
    echo '<a href="leaderboard.php">Leaderboard</a>';
}
echo '<a href="index.html">Home</a>';
echo '<a href="https://www.jeopardy.com/" target="_blank">Official Jeopardy Website</a>';
?>
</div>
</body>
</html>
