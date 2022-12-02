<?php
function connectDB(): PDO
{
    $config = parse_ini_file("db.ini");
    $dbh = new PDO($config['dsn'], $config['username'], $config['password']);
    $dbh -> setAttribute(PDo::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}