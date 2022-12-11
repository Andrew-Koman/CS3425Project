<?php
function is_logged_on(){
    // Checks if the username is set, then returns to log in if it isn't
    if(!isset($_SESSION['username'])){
        header("Location: login.php");
    }
}

function createTable( array $result, array $headers) {
    if (count($result[0])/2 != count($headers)){
        return;
    }

//    echo "<pre>";
//    print_r($result);
//    echo "</pre>";

    $num = count($result);
    echo "<table>";
    echo "<tr>";
    foreach ($headers as $header) {
        echo "<th>$header</th>";
    }
    echo "</tr>";
    for ($i = 0; $i < $num; $i++){
        echo "<tr>";
        $row = $result[$i];
        for ($j = 0; $j < count($row) / 2; $j++){
            echo "<td>$row[$j]</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

?>