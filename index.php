<?php

// cd C:\Users\Jami Vihavainen\Documents\GitHub\Liiga
// php -S localhost:8000
        /// api peleihin
// https://liiga.fi/api/v2/games/stats/2025/93
        /// api kokoonpanoihin
// https://www.liiga.fi/api/v2/games/preview/2025/86/?lineups=false

function matchNumbers(){
    $gameData = [];
    $x = 0;

    $url = "https://www.liiga.fi/api/v2/games?tournament=runkosarja&season=2025";
    $json = shell_exec("curl -s " . escapeshellarg($url));

    if ($json === false) {
        echo "Error fetching JSON data.";
    } else {
        $data = json_decode($json, true);

        for ($i=0; $i < count($data); $i++) { 
            $aAika = $data[$i]['start'];
            $dateOnly = explode("T", $aAika)[0];
            
           // $todayDate = date("Y-m-d"); // TÄMÄ KÄYTTÖÖN OIKEASTI
            $todayDate = '2024-10-29'; // TÄMÄ VAIN DEV KÄYTÖSSÄ

            if ($dateOnly == $todayDate) {

                $gameId = $data[$i]['id'];
                $home_team_id = $data[$i]['homeTeam']['teamId'];
                $away_team_id = $data[$i]['awayTeam']['teamId'];
                $home_team_name = $data[$i]['homeTeam']['teamName'];
                $away_team_name = $data[$i]['awayTeam']['teamName'];

                $gameData[$x] = ["gameid" => $gameId, "homeid" => $home_team_id, "awayid" => $away_team_id, "homename" => $home_team_name, "awayname" => $away_team_name];

   //             echo "<h1>asdjkl</h1>";
   //             echo "<pre>";
   //             print_r($data[$i]);
   //             echo "</pre>";

                $x++;
            }
        }
    //    echo "<pre>";
    //    print_r($gameData);

     //   echo "</pre>";
        
    }
    return $gameData;

}

function parseData(){
    $gameData = matchNumbers();

    $kaikkiData = [];

    for ($i=0; $i < count($gameData); $i++) { 

        $url = "https://liiga.fi/api/v2/games/stats/2025/{$gameData[$i]['gameid']}";
        $json = shell_exec("curl -s " . escapeshellarg($url));

        if ($json === false) {
            echo "Error fetching JSON data.";
        } else {
            $data = json_decode($json, true);
        }
        $kaikkiData[$i] = $data;
    }
    
    return $kaikkiData;
}

function matchData($data){
    $playerData = [];

    $away_team = $data["awayTeam"];
    $home_team = $data["homeTeam"];


    for ($i=0; $i < count($home_team[0]['goaliePeriodStats']); $i++) { 
        $playerId = 0;
        $assists = 0;
        $goals = 0;
        $plus = 0;
        $minus = 0;
        $penaltyminutes = 0;
        $timeofice = 0;
        $voittomaali = 0;
        $alivoimaMaali = 0;
        $alivoimaSyotto = 0;
        $penaltyminutes = 0;
        $blocks = 0;
        $shots = 0;
        $saves = 0;
        $goalsAllowed = 0;
        for ($z=0; $z < count($home_team); $z++) { 
            
            $playerId = $home_team[$z]['goaliePeriodStats'][$i]['playerId'];
            $assists += $home_team[$z]['goaliePeriodStats'][$i]['period']['assists'];
            $goals += $home_team[$z]['goaliePeriodStats'][$i]['period']['validGoals'];
            $plus += $home_team[$z]['goaliePeriodStats'][$i]['period']['plus'];
            $minus += $home_team[$z]['goaliePeriodStats'][$i]['period']['minus'];
            $penaltyminutes += $home_team[$z]['goaliePeriodStats'][$i]['period']['penaltyminutes'];
            $timeofice += $home_team[$z]['goaliePeriodStats'][$i]['period']['timeofice'];
            $voittomaali += $home_team[$z]['goaliePeriodStats'][$i]['period']['winningGoal'];
            $alivoimaMaali += $home_team[$z]['goaliePeriodStats'][$i]['period']['shortHandedGoals'];
            $alivoimaSyotto += $home_team[$z]['goaliePeriodStats'][$i]['period']['penaltykillAssists'];
            $penaltyminutes += $home_team[$z]['goaliePeriodStats'][$i]['period']['penaltyminutes'];
            $blocks += $home_team[$z]['goaliePeriodStats'][$i]['period']['blockedShots'];
            $saves += $home_team[$z]['goaliePeriodStats'][$i]['period']['saves'];
            $goalsAllowed += $home_team[$z]['goaliePeriodStats'][$i]['period']['goalsAllowed'];

    }
        $playerData['homegoalie'][$i] = ["playerid" => $playerId,"assists" => $assists, "goals" => $goals, "plus" => $plus, "minus" => $minus, "penaltyminutes" => $penaltyminutes, "timeofice" => $timeofice, "voittomaali" => $voittomaali, "alivoimamaali" => $alivoimaMaali, "alivoimasyotto" => $alivoimaSyotto, "penaltyminutes" => $penaltyminutes, "blocks" => $blocks, "saves" => $saves, "goalsAllowed" => $goalsAllowed];

    }

    for ($i=0; $i < count($away_team[0]['goaliePeriodStats']); $i++) { 
        $playerId = 0;
        $assists = 0;
        $goals = 0;
        $plus = 0;
        $minus = 0;
        $penaltyminutes = 0;
        $timeofice = 0;
        $voittomaali = 0;
        $alivoimaMaali = 0;
        $alivoimaSyotto = 0;
        $penaltyminutes = 0;
        $blocks = 0;
        $shots = 0;
        $saves = 0;
        for ($z=0; $z < count($away_team); $z++) { 
            $playerId = $away_team[$z]['goaliePeriodStats'][$i]['playerId'];
            $assists += $away_team[$z]['goaliePeriodStats'][$i]['period']['assists'];
            $goals += $away_team[$z]['goaliePeriodStats'][$i]['period']['validGoals'];
            $plus += $away_team[$z]['goaliePeriodStats'][$i]['period']['plus'];
            $minus += $away_team[$z]['goaliePeriodStats'][$i]['period']['minus'];
            $penaltyminutes += $away_team[$z]['goaliePeriodStats'][$i]['period']['penaltyminutes'];
            $timeofice += $away_team[$z]['goaliePeriodStats'][$i]['period']['timeofice'];
            $voittomaali += $away_team[$z]['goaliePeriodStats'][$i]['period']['winningGoal'];
            $alivoimaMaali += $away_team[$z]['goaliePeriodStats'][$i]['period']['shortHandedGoals'];
            $alivoimaSyotto += $away_team[$z]['goaliePeriodStats'][$i]['period']['penaltykillAssists'];
            $penaltyminutes += $away_team[$z]['goaliePeriodStats'][$i]['period']['penaltyminutes'];
            $blocks += $away_team[$z]['goaliePeriodStats'][$i]['period']['blockedShots'];
            $saves += $away_team[$z]['goaliePeriodStats'][$i]['period']['saves'];
            $goalsAllowed += $away_team[$z]['goaliePeriodStats'][$i]['period']['goalsAllowed'];
        }
        $playerData['awaygoalie'][$i] = ["playerid" => $playerId,"assists" => $assists, "goals" => $goals, "plus" => $plus, "minus" => $minus, "penaltyminutes" => $penaltyminutes, "timeofice" => $timeofice, "voittomaali" => $voittomaali, "alivoimamaali" => $alivoimaMaali, "alivoimasyotto" => $alivoimaSyotto, "penaltyminutes" => $penaltyminutes, "blocks" => $blocks, "saves" => $saves, "goalsAllowed" => $goalsAllowed];
    }


    for ($i=0; $i < count($home_team[0]['periodPlayerStats']); $i++) { 

        $playerId = 0;
        $assists = 0;
        $goals = 0;
        $plus = 0;
        $minus = 0;
        $penaltyminutes = 0;
        $timeofice = 0;
        $voittomaali = 0;
        $alivoimaMaali = 0;
        $alivoimaSyotto = 0;
        $penaltyminutes = 0;
        $blocks = 0;
        $shots = 0;

        for ($z=0; $z < count($home_team); $z++) { 
            $playerId = $home_team[$z]['periodPlayerStats'][$i]['playerId'];
            $assists += $home_team[$z]['periodPlayerStats'][$i]['period']['assists'];
            $goals += $home_team[$z]['periodPlayerStats'][$i]['period']['validGoals'];
            $plus += $home_team[$z]['periodPlayerStats'][$i]['period']['plus'];
            $minus += $home_team[$z]['periodPlayerStats'][$i]['period']['minus'];
            $penaltyminutes += $home_team[$z]['periodPlayerStats'][$i]['period']['penaltyminutes'];
            $timeofice += $home_team[$z]['periodPlayerStats'][$i]['period']['timeofice'];
            $voittomaali += $home_team[$z]['periodPlayerStats'][$i]['period']['winningGoal'];
            $alivoimaMaali += $home_team[$z]['periodPlayerStats'][$i]['period']['shortHandedGoals'];
            $alivoimaSyotto += $home_team[$z]['periodPlayerStats'][$i]['period']['penaltykillAssists'];
            $blocks += $home_team[$z]['periodPlayerStats'][$i]['period']['blockedShots'];
            $shots += $home_team[$z]['periodPlayerStats'][$i]['period']['shots'];

        }

        $playerData['home'][$i] = ["playerid" => $playerId,"assists" => $assists, "goals" => $goals, "plus" => $plus, "minus" => $minus, "penaltyminutes" => $penaltyminutes, "timeofice" => $timeofice, "voittomaali" => $voittomaali, "alivoimamaali" => $alivoimaMaali, "alivoimasyotto" => $alivoimaSyotto, "penaltyminutes" => $penaltyminutes, "blocks" => $blocks, "shots" => $shots];
    }

    for ($i=0; $i < count($away_team[0]['periodPlayerStats']); $i++) { 

        $playerId = 0;
        $assists = 0;
        $goals = 0;
        $plus = 0;
        $minus = 0;
        $penaltyminutes = 0;
        $timeofice = 0;
        $voittomaali = 0;
        $alivoimaMaali = 0;
        $alivoimaSyotto = 0;
        $blocks = 0;
        $shots = 0;

        for ($z=0; $z < count($away_team); $z++) { 
            $playerId = $away_team[$z]['periodPlayerStats'][$i]['playerId'];
            $assists += $away_team[$z]['periodPlayerStats'][$i]['period']['assists'];
            $goals += $away_team[$z]['periodPlayerStats'][$i]['period']['validGoals'];
            $plus += $away_team[$z]['periodPlayerStats'][$i]['period']['plus'];
            $minus += $away_team[$z]['periodPlayerStats'][$i]['period']['minus'];
            $penaltyminutes += $away_team[$z]['periodPlayerStats'][$i]['period']['penaltyminutes'];
            $timeofice += $away_team[$z]['periodPlayerStats'][$i]['period']['timeofice'];
            $voittomaali += $away_team[$z]['periodPlayerStats'][$i]['period']['winningGoal'];
            $alivoimaMaali += $away_team[$z]['periodPlayerStats'][$i]['period']['shortHandedGoals'];
            $alivoimaSyotto += $away_team[$z]['periodPlayerStats'][$i]['period']['penaltykillAssists'];
            $blocks += $away_team[$z]['periodPlayerStats'][$i]['period']['blockedShots'];
            $shots += $away_team[$z]['periodPlayerStats'][$i]['period']['shots'];
        }

        $playerData['away'][$i] = ["playerid" => $playerId,"assists" => $assists, "goals" => $goals, "plus" => $plus, "minus" => $minus, "penaltyminutes" => $penaltyminutes, "timeofice" => $timeofice, "voittomaali" => $voittomaali, "alivoimamaali" => $alivoimaMaali, "alivoimasyotto" => $alivoimaSyotto, "penaltyminutes" => $penaltyminutes, "blocks" => $blocks, "shots" => $shots];
    }

    return $playerData;

}

function readJSON() {
    $jsonFile = 'playerData.json';
    $jsonData = file_get_contents($jsonFile);
    
    // Check if file contents have been successfully read
    if ($jsonData === false) {
        die('Error: Unable to read JSON file.');
    }

    // Decode the JSON data
    $data = json_decode($jsonData, true);  // true for associative array

    // Debug to see if decoding was successful
    if ($data === null) {
        echo "Debug: Decoded data is null\n";
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo 'Error decoding JSON: ' . json_last_error_msg() . "\n";
        }
    } else {

    }

    return $data;
}

function mergeData($jsonData, $playerData){

    $allDataHome = [];

    for ($i=0; $i < count($playerData['home']); $i++) { 
        $playerid = $playerData['home'][$i]['playerid'];
    
        if (isset($jsonData[$playerid])){
            $info = $jsonData[$playerid];

            $allDataHome[$playerid] = [$jsonData[$playerid], $playerData['home'][$i]];

        }
        
    }


    $allDataAway = [];

    for ($i=0; $i < count($playerData['away']); $i++) { 
        $playerid = $playerData['away'][$i]['playerid'];
    
        if (isset($jsonData[$playerid])){
            $info = $jsonData[$playerid];

            $allDataAway[$playerid] = [$jsonData[$playerid], $playerData['away'][$i]];
    
        }
        
    }
    
    for ($i=0; $i < count($playerData['homegoalie']); $i++) { 
        $playerid = $playerData['homegoalie'][$i]['playerid'];

        if (isset($jsonData[$playerid])) {
            $allDataHome[$playerid] = [$jsonData[$playerid], $playerData['homegoalie'][$i]];

        }

    }

    for ($i=0; $i < count($playerData['awaygoalie']); $i++) { 
        $playerid = $playerData['awaygoalie'][$i]['playerid'];
        if (isset($jsonData[$playerid])) {
            $allDataAway[$playerid] = [$jsonData[$playerid], $playerData['awaygoalie'][$i]];
        }
    }

    $allData = [$allDataHome, $allDataAway];

    return $allData;
}

$gameData = matchNumbers();
$kaikkiData = parseData();
$jsonData = readJSON();


?>
<style>
    
    .top{
        width: 80%;
        margin-left: 10%;
        text-align: center;
        background: beige;
        margin-bottom: 0px;

    }
    .top h1{
        height: 80px;
        padding-top: 50px;
    }
    h1{
        font-size: 2.5em;
        
    }
    .middle-header{
        font-size: 1.5em;
    }

    .main-content{
        width: 80%;
        background: red;
        margin-left: 10%;
        margin-top: 0px;
    }

    .team-container{
        width: 80%;
        height: auto;
        background: blue;
        margin-left: 10%;
    }
    tbody tr:nth-child(odd){
        background-color: #d4d4d4;
    }
    tbody tr:nth-child(even){
        background-color: #979998;
    }
    .chosen-table{
        width: 100%;
        background: green;
        text-align: center;
    }

    .chosen-table2{
        width: 100%;
        background: green;
        text-align: center;
    }
    .chosen-table3{
        width: 100%;
        background: green;
        text-align: center;
    }
    .search-options{
        height: 25px;
        width: 80%;
        background: pink;
    }
    .player-list{
        width: 100%;
        margin-left: 10%;
        text-align: center;
    }
    .player-table{
        width: 80%;
        background: grey;
        text-align: center;
        border: 1px solid black;
    }
    table{
        border-collapse: collapse;
    }
    tbody td{
        border: 1px solid black;
    }
    .goalie-table{
        width: 80%;
        background: grey;
        text-align: center;
    }
    .defender-table{
        width: 80%;
        background: grey;
        text-align: center;
    }

</style>
<html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Liigatesti</title>
    </head>
    <body>
        <div class="top">
            <h1>Otsikko</h1>
        </div>
        <div class="main-content">
            <div class="team-container">
                <table class="chosen-table" id="chosen-table">
                    <thead>
                        <tr>
                            <th colspan="11" class="middle-header">Hyökkääjät</th>
                        </tr>
                        <tr>
                            <th>Sukunimi</th>
                            <th>Etunimi</th>
                            <th>Joukkue</th>
                            <th>Pelipaikka</th>
                            <th>Maalit</th>
                            <th>Syötöt</th>
                            <th>Plusmiinus</th>
                            <th>Jäähyt(min)</th>
                            <th>Blockit</th>
                            <th>Laukaukset</th>
                            <th>LPP</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    
                </table>
                <table class="chosen-table2" id="chosen-table2">
                    <thead>
                        <tr>
                            <th colspan="11" class="middle-header">Puolustajat</th>
                        </tr>
                        <tr>
                            <th>Sukunimi</th>
                            <th>Etunimi</th>
                            <th>Joukkue</th>
                            <th>Pelipaikka</th>
                            <th>Maalit</th>
                            <th>Syötöt</th>
                            <th>Plusmiinus</th>
                            <th>Jäähyt(min)</th>
                            <th>Blockit</th>
                            <th>Laukaukset</th>
                            <th>LPP</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    
                </table>
                <table class="chosen-table3" id="chosen-table3">
                    <thead>
                        <tr>
                            <th colspan="10" class="middle-header">Maalivahdit</th>
                        </tr>
                        <tr>
                            <th>Sukunimi</th>
                            <th>Etunimi</th>
                            <th>Joukkue</th>
                            <th>Pelipaikka</th>
                            <th>Maalit</th>
                            <th>Syötöt</th>
                            <th>Päästetyt maalit</th>
                            <th>Jäähyt(min)</th>
                            <th>Torjunnat</th>
                            <th>LPP</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    
                    <tr>
                        <th>LPP yhteensä</th>
                        <th>LPP</th>
                    </tr>
                </table>
            </div>
            <div class="player-list">
                <div class="search-options">
                        <select name="position" id="position">
                            <option value="every">Kaikki</option>
                            <option value="attacker">Hyökkääjä</option>
                            <option value="defender">Puolustaja</option>
                            <option value="goalie">Maalivahti</option>
                        </select>
                        <select name="team" id="team">
                            <option value="every">Kaikki</option>
                            <option value="HIFK">HIFK</option>
                            <option value="HPK">HPK</option>
                            <option value="ILVES">ILVES</option>
                            <option value="JUKURIT">JUKURIT</option>
                            <option value="JYP">JYP</option>
                            <option value="KALPA">KALPA</option>
                            <option value="K-ESPOO">K-ESPOO</option>
                            <option value="KOOKOO">KOOKOO</option>
                            <option value="KARPAT">KÄRPÄT</option>
                            <option value="LUKKO">LUKKO</option>
                            <option value="PELICANS">PELICANS</option>
                            <option value="SAIPA">SAIPA</option>
                            <option value="SPORT">SPORT</option>
                            <option value="TAPPARA">TAPPARA</option>
                            <option value="TPS">TPS</option>
                            <option value="ASSAT">ÄSSÄT</option>
                        </select>

                        <button id="submit">Valitse</button>

                </div>
                <table class="player-table" id="player-table">
                    <thead>
                        <tr>
                            <th colspan="11" class="middle-header">Hyökkääjät</th>
                        </tr>
                        <tr>
                            <th>Sukunimi</th>
                            <th>Etunimi</th>
                            <th>Joukkue</th>
                            <th>Pelipaikka</th>
                            <th>Maalit</th>
                            <th>Syötöt</th>
                            <th>Plusmiinus</th>
                            <th>Jäähyt(min)</th>
                            <th>Blockit</th>
                            <th>Laukaukset</th>
                            <th>LPP</th>
                        </tr>
                    </thead>
                    <tbody>
                    
<?php


for ($i=0; $i < count($kaikkiData); $i++) { 
    $playerData = matchData($kaikkiData[$i]);
    $mergedData = mergeData($jsonData, $playerData);

    foreach ($mergedData[0] as $key) {
        $lastName = $key[0]['lastname'];
        $firstName = $key[0]['firstname'];
        $teamName = $key[0]['teamname'];
        $role = $key[0]['role'];
        $goals = $key[1]['goals'];
        $assists = $key[1]['assists'];
        $idTeamName = str_replace(['ä', 'ö', 'å'], ['a', 'o', 'a'], $key[0]['teamname']);

        if ($role == 'RIGHT_WING' || $role == 'LEFT_WING' || $role == 'CENTER'){
            $plusminus = $key[1]['plus'] - $key[1]['minus'];
            $blocks = $key[1]['blocks'];
            $shots = $key[1]['shots'];

            $role = 'A';
            $LPPGoals = $goals * 7;
            $LPPAssists = $assists * 4;
            if ($plusminus > 0) {
                $LPPPlusminus = $plusminus * 2;
            }
            elseif ($plusminus < 0) {
                $LPPPlusminus = $plusminus;
            }
            else {
                $LPPPlusminus = 0;
            }
            $LPPBlocks = $blocks;
            if ($shots != 0){
                if ($shots % 2 == 0) {
                    $LPPShots = $shots / 2;
                }
                else {
                    $LPPShots = ($shots / 2) + 0.5;
                }
            }
            else{
                $LPPShots = $shots;
            }
        }

        if ($role == 'A') {
            $LPP = $LPPGoals + $LPPAssists + $LPPPlusminus + $LPPBlocks + $LPPShots;
            echo'<tr class="';echo $idTeamName;echo'" id=';echo $idTeamName;echo'>
                        <td>';echo $key[0]['lastname'];echo'</td>
                        <td>';echo $key[0]['firstname'];echo'</td>
                        <td>';echo $key[0]['teamname'];echo'</td>
                        <td>';echo $role ;echo'</td>
                        <td>';echo $key[1]['goals'];echo'</td>
                        <td>';echo $key[1]['assists'];echo'</td>
                        <td>';echo $key[1]['plus'] - $key[1]['minus'];echo'</td>
                        <td>';echo $key[1]['penaltyminutes'];echo'</td>
                        <td>';echo $key[1]['blocks'];echo'</td>
                        <td>';echo $key[1]['shots'];echo'</td>
                        <td>';echo $LPP;echo'</td>
                        <td>
                            <button onclick="valitse(this)">Valitse</button>
                        </td>
                    </tr>';
            $goals = 0;
            $assists = 0;
            $plusminus = 0;
            $blocks = 0;
            $shots = 0;
    }
}
    foreach ($mergedData[1] as $key) {
        $lastName = $key[0]['lastname'];
        $firstName = $key[0]['firstname'];
        $teamName = $key[0]['teamname'];
        $role = $key[0]['role'];
        $goals = $key[1]['goals'];
        $assists = $key[1]['assists'];
        $idTeamName = str_replace(['ä', 'ö', 'å'], ['a', 'o', 'a'], $key[0]['teamname']);

        if ($role == 'RIGHT_WING' || $role == 'LEFT_WING' || $role == 'CENTER'){
            $plusminus = $key[1]['plus'] - $key[1]['minus'];
            $blocks = $key[1]['blocks'];
            $shots = $key[1]['shots'];
            $role = 'A';
            $LPPGoals = $goals * 7;
            $LPPAssists = $assists * 4;
            if ($plusminus > 0) {
                $LPPPlusminus = $plusminus * 2;
            }
            elseif ($plusminus < 0) {
                $LPPPlusminus = $plusminus;
            }
            else {
                $LPPPlusminus = 0;
            }
            $LPPBlocks = $blocks;
            if ($shots != 0){
                if ($shots % 2 == 0) {
                    $LPPShots = $shots / 2;
                }
                else {
                    $LPPShots = ($shots / 2) + 0.5;
                }
            }
            else{
                $LPPShots = $shots;
            }
        }
        if ($role == 'A') {
            $LPP = $LPPGoals + $LPPAssists + $LPPPlusminus + $LPPBlocks + $LPPShots;
            echo'<tr class="';echo $idTeamName;echo'" id=';echo $idTeamName;echo'>
                        <td>';echo $key[0]['lastname'];echo'</td>
                        <td>';echo $key[0]['firstname'];echo'</td>
                        <td>';echo $key[0]['teamname'];echo'</td>
                        <td>';echo $role ;echo'</td>
                        <td>';echo $key[1]['goals'];echo'</td>
                        <td>';echo $key[1]['assists'];echo'</td>
                        <td>';echo $key[1]['plus'] - $key[1]['minus'];echo'</td>
                        <td>';echo $key[1]['penaltyminutes'];echo'</td>
                        <td>';echo $key[1]['blocks'];echo'</td>
                        <td>';echo $key[1]['shots'];echo'</td>
                        <td>';echo $LPP;echo'</td>
                        <td>
                            <button onclick="valitse(this)">Valitse</button>
                        </td>
                    </tr>';
            $goals = 0;
            $assists = 0;
            $plusminus = 0;
            $blocks = 0;
            $shots = 0;
        }
    }
}


?>
                </tbody>
                </table>
                <table class="defender-table" id="defender-table">
                <thead>
                        <tr>
                            <th colspan="11" class="middle-header">Puolustajat</th>
                        </tr>
                        <tr>
                            <th>Sukunimi</th>
                            <th>Etunimi</th>
                            <th>Joukkue</th>
                            <th>Pelipaikka</th>
                            <th>Maalit</th>
                            <th>Syötöt</th>
                            <th>Plusmiinus</th>
                            <th>Jäähyt(min)</th>
                            <th>Blockit</th>
                            <th>Laukaukset</th>
                            <th>LPP</th>
                        </tr>
                    </thead>
                    <tbody>

<?php
for ($i=0; $i < count($kaikkiData); $i++) { 
    $playerData = matchData($kaikkiData[$i]);
    $mergedData = mergeData($jsonData, $playerData);

    foreach ($mergedData[0] as $key) {
        $lastName = $key[0]['lastname'];
        $firstName = $key[0]['firstname'];
        $teamName = $key[0]['teamname'];
        $role = $key[0]['role'];
        $goals = $key[1]['goals'];
        $assists = $key[1]['assists'];
        $idTeamName = str_replace(['ä', 'ö', 'å'], ['a', 'o', 'a'], $key[0]['teamname']);

        if ($role == 'LEFT_DEFENSEMAN' || $role == 'RIGHT_DEFENSEMAN') {
            $plusminus = $key[1]['plus'] - $key[1]['minus'];
            $blocks = $key[1]['blocks'];
            $shots = $key[1]['shots'];
            $role = 'D';
            $LPPGoals = $goals * 9;
            $LPPAssists = $assists * 6;
            if ($plusminus > 0) {
                $LPPPlusminus = $plusminus * 3;
            }
            elseif ($plusminus < 0) {
                $LPPPlusminus = $plusminus * 2;
            }
            else {
                $LPPPlusminus = 0;
            }
            $LPPBlocks = $blocks;
            if ($shots != 0){
                if ($shots % 2 == 0) {
                    $LPPShots = $shots / 2;
                }
                else {
                    $LPPShots = ($shots / 2) + 0.5;
                }
            }
            else{
                $LPPShots = $shots;
            }
        }
        
        if ($role == 'D') {
            $LPP = $LPPGoals + $LPPAssists + $LPPPlusminus + $LPPBlocks + $LPPShots;
            echo'<tr class="';echo $idTeamName;echo'" id=';echo $idTeamName;echo'>
                        <td>';echo $key[0]['lastname'];echo'</td>
                        <td>';echo $key[0]['firstname'];echo'</td>
                        <td>';echo $key[0]['teamname'];echo'</td>
                        <td>';echo $role ;echo'</td>
                        <td>';echo $key[1]['goals'];echo'</td>
                        <td>';echo $key[1]['assists'];echo'</td>
                        <td>';echo $key[1]['plus'] - $key[1]['minus'];echo'</td>
                        <td>';echo $key[1]['penaltyminutes'];echo'</td>
                        <td>';echo $key[1]['blocks'];echo'</td>
                        <td>';echo $key[1]['shots'];echo'</td>
                        <td>';echo $LPP;echo'</td>
                        <td>
                            <button onclick="valitse2(this)">Valitse</button>
                        </td>
                    </tr>';
            $goals = 0;
            $assists = 0;
            $plusminus = 0;
            $blocks = 0;
            $shots = 0;
        }
    }



    foreach ($mergedData[1] as $key) {
        $lastName = $key[0]['lastname'];
        $firstName = $key[0]['firstname'];
        $teamName = $key[0]['teamname'];
        $role = $key[0]['role'];
        $goals = $key[1]['goals'];
        $assists = $key[1]['assists'];
        $idTeamName = str_replace(['ä', 'ö', 'å'], ['a', 'o', 'a'], $key[0]['teamname']);
        
        if ($role == 'LEFT_DEFENSEMAN' || $role == 'RIGHT_DEFENSEMAN') {
            $plusminus = $key[1]['plus'] - $key[1]['minus'];
            $blocks = $key[1]['blocks'];
            $shots = $key[1]['shots'];
            $role = 'D';
            $LPPGoals = $goals * 9;
            $LPPAssists = $assists * 6;
            if ($plusminus > 0) {
                $LPPPlusminus = $plusminus * 3;
            }
            elseif ($plusminus < 0) {
                $LPPPlusminus = $plusminus * 2;
            }
            else {
                $LPPPlusminus = 0;
            }
            $LPPBlocks = $blocks;
            if ($shots != 0){
                if ($shots % 2 == 0) {
                    $LPPShots = $shots / 2;
                }
                else {
                    $LPPShots = ($shots / 2) + 0.5;
                }
            }
            else{
                $LPPShots = $shots;
            }
        }
        
        if ($role == 'D') {
            $LPP = $LPPGoals + $LPPAssists + $LPPPlusminus + $LPPBlocks + $LPPShots;
            echo'<tr class="';echo $idTeamName;echo'" id=';echo $idTeamName;echo'>
                        <td>';echo $key[0]['lastname'];echo'</td>
                        <td>';echo $key[0]['firstname'];echo'</td>
                        <td>';echo $key[0]['teamname'];echo'</td>
                        <td>';echo $role ;echo'</td>
                        <td>';echo $key[1]['goals'];echo'</td>
                        <td>';echo $key[1]['assists'];echo'</td>
                        <td>';echo $key[1]['plus'] - $key[1]['minus'];echo'</td>
                        <td>';echo $key[1]['penaltyminutes'];echo'</td>
                        <td>';echo $key[1]['blocks'];echo'</td>
                        <td>';echo $key[1]['shots'];echo'</td>
                        <td>';echo $LPP;echo'</td>
                        <td>
                            <button onclick="valitse2(this)">Valitse</button>
                        </td>
                    </tr>';
            $goals = 0;
            $assists = 0;
            $plusminus = 0;
            $blocks = 0;
            $shots = 0;
        }
    }
}

?>
                </tbody>
                </table>

                <table class="goalie-table" id="goalie-table">
                    <thead>
                        <tr>
                            <th colspan="10" class="middle-header">Maalivahdit</th>
                        </tr>
                        <tr>
                            <th>Sukunimi</th>
                            <th>Etunimi</th>
                            <th>Joukkue</th>
                            <th>Pelipaikka</th>
                            <th>Maalit</th>
                            <th>Syötöt</th>
                            <th>Päästetyt maalit</th>
                            <th>Jäähyt(min)</th>
                            <th>Torjunnat</th>
                            <th>LPP</th>
                        </tr>
                    </thead>
                    <tbody>
<?php



for ($i=0; $i < count($kaikkiData); $i++) { 
    $playerData = matchData($kaikkiData[$i]);
    $mergedData = mergeData($jsonData, $playerData);


    foreach ($mergedData[0] as $key) {
        $lastName = $key[0]['lastname'];
        $firstName = $key[0]['firstname'];
        $teamName = $key[0]['teamname'];
        $role = $key[0]['role'];
        $goals = $key[1]['goals'];
        $assists = $key[1]['assists'];
        $idTeamName = str_replace(['ä', 'ö', 'å'], ['a', 'o', 'a'], $key[0]['teamname']);


        
        if ($role == 'GOALIE') {

            $role = 'G';
            $saves = $key[1]['saves'];
            $LPPGoals = $goals * 25;
            $LPPAssists = $assists * 10;
            if ($saves == 0) {
                $LPPSaves = $saves;
            } else{
            $interval = floor(($saves - 1) / 5);
            $LPPSaves = $interval * 2 + 1;
            }
            $goalsAllowed = $key[1]['goalsAllowed'];

        }
        if ($role == 'G') {
            $LPP = $LPPGoals + $LPPAssists + $LPPSaves;
            echo'<tr class="';echo $idTeamName;echo'" id=';echo $idTeamName;echo'>
                        <td>';echo $key[0]['lastname'];echo'</td>
                        <td>';echo $key[0]['firstname'];echo'</td>
                        <td>';echo $key[0]['teamname'];echo'</td>
                        <td>';echo $role ;echo'</td>
                        <td>';echo $key[1]['goals'];echo'</td>
                        <td>';echo $key[1]['assists'];echo'</td>
                        <td>';echo $key[1]['goalsAllowed'];echo'</td>
                        <td>';echo $key[1]['penaltyminutes'];echo'</td>
                        <td>';echo $key[1]['saves'];echo'</td>
                        <td>';echo $LPP;echo'</td>
                        <td>
                            <button onclick="valitse3(this)">Valitse</button>
                        </td>
            </tr>';
            $goals = 0;
            $assists = 0;
            $saves = 0;
        }
    }
    foreach ($mergedData[1] as $key) {
        $lastName = $key[0]['lastname'];
        $firstName = $key[0]['firstname'];
        $teamName = $key[0]['teamname'];
        $role = $key[0]['role'];
        $goals = $key[1]['goals'];
        $assists = $key[1]['assists'];
        $idTeamName = str_replace(['ä', 'ö', 'å'], ['a', 'o', 'a'], $key[0]['teamname']);

        if ($role == 'GOALIE') {
            $role = 'G';
            $saves = $key[1]['saves'];
            $LPPGoals = $goals * 25;
            $LPPAssists = $assists * 10;
            if ($saves == 0) {
                $LPPSaves = $saves;
            } else{
            $interval = floor(($saves - 1) / 5);
            $LPPSaves = $interval * 2 + 1;
            }
            $goalsAllowed = $key[1]['goalsAllowed'];
        }
        
        if ($role == 'G'){
            $LPP = $LPPGoals + $LPPAssists + $LPPSaves;
            echo'<tr class="';echo $idTeamName;echo'" id=';echo $idTeamName;echo'>
                        <td>';echo $key[0]['lastname'];echo'</td>
                        <td>';echo $key[0]['firstname'];echo'</td>
                        <td>';echo $key[0]['teamname'];echo'</td>
                        <td>';echo $role ;echo'</td>
                        <td>';echo $key[1]['goals'];echo'</td>
                        <td>';echo $key[1]['assists'];echo'</td>
                        <td>';echo $key[1]['goalsAllowed'];echo'</td>
                        <td>';echo $key[1]['penaltyminutes'];echo'</td>
                        <td>';echo $key[1]['saves'];echo'</td>
                        <td>';echo $LPP;echo'</td>
                        <td>
                            <button onclick="valitse3(this)">Valitse</button>
                        </td>
            </tr>';
            $goals = 0;
            $assists = 0;
            $saves = 0;
        }
    }
}

?>


                </tbody>
                </table>
            </div>
        </div>
        
    </body>
    </html>
</html>
<script>
    // Function to move or return a row depending on the button state
    function valitse(button) {
        // Find the row that the button is in
        var row = button.parentNode.parentNode;

        // Check if the button text is "Valitse" (meaning move to chosen table)
        if (button.innerText === "Valitse") {
            // Move the row to the chosen-table
            var chosenTableBody = document.getElementById('chosen-table').getElementsByTagName('tbody')[0];
            chosenTableBody.appendChild(row);
            
            // Change the button text to "Peruuta"
            button.innerText = "Peruuta";

        } else {
            // Move the row back to the player-table
            var playerTableBody = document.getElementById('player-table').getElementsByTagName('tbody')[0];
            playerTableBody.appendChild(row);
            
            // Change the button text back to "Valitse"
            button.innerText = "Valitse";
        }
    }

    // Function to move or return a row depending on the button state
    function valitse2(button) {
        // Find the row that the button is in
        var row = button.parentNode.parentNode;

        // Check if the button text is "Valitse" (meaning move to chosen table)
        if (button.innerText === "Valitse") {
            // Move the row to the chosen-table
            var chosenTableBody = document.getElementById('chosen-table2').getElementsByTagName('tbody')[0];
            chosenTableBody.appendChild(row);
            
            // Change the button text to "Peruuta"
            button.innerText = "Peruuta";

        } else {
            // Move the row back to the player-table
            var playerTableBody2 = document.getElementById('defender-table').getElementsByTagName('tbody')[0];
            playerTableBody2.appendChild(row);
            
            // Change the button text back to "Valitse"
            button.innerText = "Valitse";
        }
    }

    function valitse3(button) {
        // Find the row that the button is in
        var row = button.parentNode.parentNode;

        // Check if the button text is "Valitse" (meaning move to chosen table)
        if (button.innerText === "Valitse") {
            // Move the row to the chosen-table
            var chosenTableBody = document.getElementById('chosen-table3').getElementsByTagName('tbody')[0];
            chosenTableBody.appendChild(row);
            
            // Change the button text to "Peruuta"
            button.innerText = "Peruuta";

        } else {
            // Move the row back to the player-table
            var playerTableBody2 = document.getElementById('goalie-table').getElementsByTagName('tbody')[0];
            playerTableBody2.appendChild(row);
            
            // Change the button text back to "Valitse"
            button.innerText = "Valitse";
        }
    }

const dropdown1 = document.getElementById('position');
const dropdown2 = document.getElementById('team');
const submitbutton = document.getElementById('submit');

    function handlePositionSelection(positionValue, teamValue){

        if (positionValue == 'attacker') {
            var playerTable = document.getElementById('player-table');
        } else if (positionValue == 'defender'){
            var playerTable = document.getElementById('defender-table');
        } else if (positionValue == 'goalie') {
            var playerTable = document.getElementById('goalie-table');
        } else if (positionValue == 'every') {
            var playerTable = 'every';
        }
        
        if (playerTable != 'every') {
            
            for (let i = 0; i < playerTable.rows.length; i++) {    
                let row = playerTable.rows[i];
                let rowData = [];
                let rowId = [];

                for (let j = 0; j < row.cells.length; j++) {
                    rowData.push(row.cells[j].textContent);
                    rowId = row.id;
                }
                hideTablesNotSelected(rowId, rowData, teamValue, positionValue)
                
            }

        } else{
            var attackerTable = document.getElementById('player-table');
            var defenderTable = document.getElementById('defender-table');
            var goalieTable = document.getElementById('goalie-table');

            attackerTable.style.display = 'table';
            defenderTable.style.display = 'table';
            goalieTable.style.display = 'table';

        }


        function hideTablesNotSelected(rowId, rowData, teamValue, positionValue){
            var attackerTable = document.getElementById('player-table');
            var defenderTable = document.getElementById('defender-table');
            var goalieTable = document.getElementById('goalie-table');

            // hiding every other position that is not selected
            if (positionValue == 'attacker') {
                attackerTable.style.display = 'table';
                defenderTable.style.display = 'none';
                goalieTable.style.display = 'none';

            } else if (positionValue == 'defender') {
                attackerTable.style.display = 'none';
                defenderTable.style.display = 'table';
                goalieTable.style.display = 'none';

            } else if (positionValue == 'goalie') {
                attackerTable.style.display = 'none';
                defenderTable.style.display = 'none';
                goalieTable.style.display = 'table';

            } else {
                console.log('every position in function hideTablesNotSelected()');
                
            }
            
            

            function hideRowsNotSelected(visibleTable, rowId){


                if (visibleTable != 'every') {
                    if (visibleTable) {
                        
                        const rows = visibleTable.querySelectorAll('#' + rowId);

                        rows.forEach(row => {

                            selectionId = row.id.toUpperCase();

                            if (teamValue == selectionId) {
                                row.style.display = 'table-row';
                                
                            }

                        });
                    }
                    
                }
            }
        }
        
    }

    function handleTeamSelection(positionValue, teamValue){
        var attackerTable = document.getElementById('player-table');
        var defenderTable = document.getElementById('defender-table');
        var goalieTable = document.getElementById('goalie-table');
        let rowId = '';
        
        // Jos joukkuevalinta on eri kuin kaikki joukkueet
        if (teamValue != 'every') {
            
            hideRowsBasedOnTeam(attackerTable, defenderTable, goalieTable, rowId);
            
        }
        else { // Jos joukkuevalinta on kaikki joukkueet

            showAllRowsBasedOnTeam(attackerTable, defenderTable, goalieTable, rowId);

        }


        function hideRowsBasedOnTeam(attackerTable, defenderTable, goalieTable, rowId){
            // Käy läpi kaikki rivit pöydässä
            let lastRowId = '';
            for (let i = 0; i < attackerTable.rows.length; i++) {    
                let row = attackerTable.rows[i];
                let rowData = [];

                // Käy läpi kaikki rivillä olevat cellit
                for (let j = 0; j < row.cells.length; j++) {
                    rowData.push(row.cells[j].textContent);
                    let rowId = row.id;
                    
                    if (rowId) {
                        if (rowId == lastRowId) {
                            continue;
                        }
                        else{
                            lastRowId = rowId;

                            let data = attackerTable.getElementsByClassName(rowId)
                            let classname = JSON.stringify(data[0].className);
                            let capsName = classname.toUpperCase().replace('"','').replace('"','');
                            
                            if (capsName != teamValue) {

                                for (let x = 0; x < data.length; x++) {
                                    data[x].style.display = 'none';
                                    
                                }

                            }
                            else{
                                for (let x = 0; x < data.length; x++) {
                                    data[x].style.display = 'table-row';
                                    
                                }
                            }
                        }
                    }
                }
            }

            for (let i = 0; i < defenderTable.rows.length; i++) {    
                let row = defenderTable.rows[i];
                let rowData = [];

                // Käy läpi kaikki rivillä olevat cellit
                for (let j = 0; j < row.cells.length; j++) {
                    rowData.push(row.cells[j].textContent);
                    let rowId = row.id;
                    
                    if (rowId) {
                        if (rowId == lastRowId) {
                            continue;
                        }
                        else{
                            lastRowId = rowId;

                            let data = defenderTable.getElementsByClassName(rowId)
                            let classname = JSON.stringify(data[0].className);
                            let capsName = classname.toUpperCase().replace('"','').replace('"','');
                            
                            if (capsName != teamValue) {

                                for (let x = 0; x < data.length; x++) {
                                    data[x].style.display = 'none';
                                    
                                }

                            }
                            else{
                                for (let x = 0; x < data.length; x++) {
                                    data[x].style.display = 'table-row';
                                    
                                }
                            }
                        }
                    }
                }
            }


            for (let i = 0; i < goalieTable.rows.length; i++) {    
                let row = goalieTable.rows[i];
                let rowData = [];

                // Käy läpi kaikki rivillä olevat cellit
                for (let j = 0; j < row.cells.length; j++) {
                    rowData.push(row.cells[j].textContent);
                    let rowId = row.id;
                    
                    if (rowId) {
                        if (rowId == lastRowId) {
                            continue;
                        }
                        else{
                            lastRowId = rowId;

                            let data = goalieTable.getElementsByClassName(rowId)
                            let classname = JSON.stringify(data[0].className);
                            let capsName = classname.toUpperCase().replace('"','').replace('"','');
                            
                            if (capsName != teamValue) {

                                for (let x = 0; x < data.length; x++) {
                                    data[x].style.display = 'none';
                                    
                                }

                            }
                            else{
                                for (let x = 0; x < data.length; x++) {
                                    data[x].style.display = 'table-row';
                                    
                                }
                            }
                        }
                    }
                }
            }
        }

    
        function showAllRowsBasedOnTeam(attackerTable, defenderTable, goalieTable, rowId){
            // Käy läpi kaikki rivit pöydässä
            let lastRowId = '';
            for (let i = 0; i < attackerTable.rows.length; i++) {    
                let row = attackerTable.rows[i];
                let rowData = [];

                // Käy läpi kaikki rivillä olevat cellit
                for (let j = 0; j < row.cells.length; j++) {
                    rowData.push(row.cells[j].textContent);
                    let rowId = row.id;
                    
                    if (rowId) {
                        if (rowId == lastRowId) {
                            continue;
                        }
                        else{
                            lastRowId = rowId;

                            let data = attackerTable.getElementsByClassName(rowId)
                            let classname = JSON.stringify(data[0].className);
                            let capsName = classname.toUpperCase().replace('"','').replace('"','');
                            
                            for (let x = 0; x < data.length; x++) {
                                data[x].style.display = 'table-row';
                                
                            } 
                        }
                    }
                }
            }

            // Käy läpi kaikki rivit pöydässä
            lastRowId = '';
            for (let i = 0; i < defenderTable.rows.length; i++) {    
                let row = defenderTable.rows[i];
                let rowData = [];

                // Käy läpi kaikki rivillä olevat cellit
                for (let j = 0; j < row.cells.length; j++) {
                    rowData.push(row.cells[j].textContent);
                    let rowId = row.id;
                    
                    if (rowId) {
                        if (rowId == lastRowId) {
                            continue;
                        }
                        else{
                            lastRowId = rowId;

                            let data = defenderTable.getElementsByClassName(rowId)
                            let classname = JSON.stringify(data[0].className);
                            let capsName = classname.toUpperCase().replace('"','').replace('"','');
                            
                            for (let x = 0; x < data.length; x++) {
                                data[x].style.display = 'table-row';
                                
                            } 
                        }
                    }
                }
            }


            lastRowId = '';
            for (let i = 0; i < goalieTable.rows.length; i++) {    
                let row = goalieTable.rows[i];
                let rowData = [];

                // Käy läpi kaikki rivillä olevat cellit
                for (let j = 0; j < row.cells.length; j++) {
                    rowData.push(row.cells[j].textContent);
                    let rowId = row.id;
                    
                    if (rowId) {
                        if (rowId == lastRowId) {
                            continue;
                        }
                        else{
                            lastRowId = rowId;

                            let data = goalieTable.getElementsByClassName(rowId)
                            let classname = JSON.stringify(data[0].className);
                            let capsName = classname.toUpperCase().replace('"','').replace('"','');
                            
                            for (let x = 0; x < data.length; x++) {
                                data[x].style.display = 'table-row';
                                
                            } 
                        }
                    }
                }
            }
        }
    }

submitbutton.addEventListener('click', function(){
    const positionValue = dropdown1.value;
    const teamValue = dropdown2.value;

    handlePositionSelection(positionValue, teamValue);
    handleTeamSelection(positionValue, teamValue);
    
});


</script>