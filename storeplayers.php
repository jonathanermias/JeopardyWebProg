<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numPlayers = isset($_POST['numPlayers']) ? (int)$_POST['numPlayers'] : 0;
    if ($numPlayers > 0) {
        $playersFile = __DIR__.'/players.txt';
        $pf = fopen($playersFile, 'w');
        if (!$pf) {
            die("Unable to open players.txt for writing.");
        }
        for ($i = 1; $i <= $numPlayers; $i++) {
            $p = 'player'.$i;
            if (isset($_POST[$p])) {
                $name = trim($_POST[$p]);
                fwrite($pf, $name."|0\n");
            }
        }
        fclose($pf);
        $teamMode = ($numPlayers === 4) ? 1 : 0;
        $order = [];
        if ($teamMode) {
            $order = [0,2,1,3];
        } else {
            for ($x=0;$x<$numPlayers;$x++){
                $order[]=$x;
            }
        }
        $orderStr = implode(',',$order);
        $sf = fopen(__DIR__.'/gamestate.txt','w');
        if (!$sf) {
            die("Unable to open gamestate.txt for writing.");
        }
        fwrite($sf,"TURN_INDEX=0\n");
        fwrite($sf,"USED=\n");
        fwrite($sf,"TEAM_MODE=$teamMode\n");
        fwrite($sf,"TURN_ORDER=$orderStr\n");
        fclose($sf);
        header("Location: gameboard.php");
        exit;
    } else {
        echo "Invalid number of players.";
    }
} else {
    echo "Invalid request method.";
}
?>


