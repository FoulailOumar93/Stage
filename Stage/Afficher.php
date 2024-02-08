<?php
$dsn = 'mysql:dbname=film;host=127.0.0.1';
$user = 'root';
$password = '';

try {
    $db = new PDO($dsn, $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Pour afficher les erreurs PDO
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$query = "INSERT INTO films (`name`, `actors`, `note`, `comment`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $db->prepare($query);

if ($stmt === false) {
    die("Erreur lors de la préparation de la requête: " . $db->errorInfo()[2]);
}

$name = $_POST['name'];
$actors = $_POST['actors'];
$note = $_POST['note'];
$comment = $_POST['comment'];
$created_at = $_POST['created_at'];
$updated_at = $_POST['updated_at']; // Assurez-vous que vous avez cette valeur dans votre formulaire

try {
    $stmt->bindParam(1, $name);
    $stmt->bindParam(2, $actors);
    $stmt->bindParam(3, $note);
    $stmt->bindParam(4, $comment);
    $stmt->bindParam(5, $created_at);
    $stmt->bindParam(6, $updated_at); // Assurez-vous que vous avez cette valeur dans votre formulaire

    $result = $stmt->execute();

    if ($result) {
        // Redirection vers la page de confirmation dans un nouvel onglet
        echo "<script>window.open('confirmation.php', '_blank');</script>";
    } else {
        echo "Erreur lors de l'ajout du film: " . $stmt->errorInfo()[2];
    }
} catch (PDOException $e) {
    echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
}

$stmt->closeCursor(); // Facultatif : ferme le curseur pour permettre la réutilisation de la requête
$db = null; // Ferme la connexion à la base de données
?>
