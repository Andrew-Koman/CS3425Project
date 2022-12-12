<?php
function is_logged_on(){
    // Returns the log in state
    return !isset($_SESSION['username']);
}

function createTable( array $result, array $headers) {
    if (count($result) == 0 || count($result[0])/2 != count($headers)){
        echo "<p style='color: red'>Error. Cannot display table</p>";
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