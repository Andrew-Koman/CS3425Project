<?php

// If user is not logged in and the current page is not login page, then redirect to the login page
if (!isset($_SESSION['username']) && $_SERVER["REQUEST_URI"] != "/cs3425/cr4zy_smrt/login.php") {
    header("Location: https://classdb.it.mtu.edu/cs3425/cr4zy_smrt/login.php");
    die();
}

function createTable( array $result, array $headers): bool
{
    if (count($result) == 0 || count($result[0])/2 != count($headers)){
        echo "<p style='color: red'>Error. Cannot display table</p>";
        return false;
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
    return true;
}

?>