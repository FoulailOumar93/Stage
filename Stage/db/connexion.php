<?php
    /* Connexion à une base MySQL avec l'invocation de pilote */
    $dsn = 'mysql:dbname=film;host=127.0.0.1';
    $user = 'root';
    $password = '';

    try 
    {
        $db = new PDO($dsn, $user, $password);
    }
    catch(PDOException $e) 
    {
        die("Erreur de connexion : " . $e->getMessage());
    }

?>