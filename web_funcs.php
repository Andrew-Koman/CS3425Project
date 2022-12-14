<?php

// If user is not logged in and the current page is not login page, then redirect to the login page
if (!isset($_SESSION['username']) && $_SERVER["REQUEST_URI"] != "/cs3425/cr4zy_smrt/login.php") {
    header("Location: https://classdb.it.mtu.edu/cs3425/cr4zy_smrt/login.php");
    die();
}

/**
 * Prints table with the given data and headers
 * Data and headers must be the same length
 */
function createTable( array $result, array $headers) {
    $num = count($result);
    echo "<table>";
    echo "<tr>";
    foreach ($headers as $header) {
        echo "<th>$header</th>";
    }
    echo "</tr>";

    // Only display values if there were any returned
    if (count($result) > 0){
        for ($i = 0; $i < $num; $i++){
            echo "<tr>";
            $row = $result[$i];
            for ($j = 0; $j < count($row) / 2; $j++){
                echo "<td>$row[$j]</td>";
            }
            echo "</tr>";
        }   
    }
    echo "</table>";
}

?>