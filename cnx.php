<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tfa_gaspard";

    try {
        // se connecter à mysql
        $pdo = new PDO("mysql:host=$host;dbname=$dbname","$username","$password");
        } catch (PDOException $exc) {
          echo $exc->getMessage();
          exit();
        }
?>