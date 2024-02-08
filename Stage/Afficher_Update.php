<?php require __DIR__ . "/db/connexion.php"; ?>
<?php
// Vérifier si des données de mise à jour sont disponibles
if (isset($_POST['update_id'])) {
    // Récupérer l'ID de la ligne à mettre à jour
    $update_id = $_POST['update_id'];

    // Requête SQL pour la mise à jour
    $update_query = "UPDATE films SET `name`=?, `actors`=?, `note`=?, `comment`=?, `created_at`=? WHERE id=?";

    $update_stmt = $mysqli->prepare($update_query);

    // Vérifier si la préparation de la requête a réussi
    if ($update_stmt === false) {
        die("Erreur lors de la préparation de la requête de mise à jour: " . $mysqli->error);
    }

    // Récupérer les données du formulaire
    $name = $_POST['name'];
    $actors = $_POST['actors'];
    $note = $_POST['note'];
    $comment = $_POST['comment'];
    $created_at = $_POST['created_at'];

    // Binder les paramètres
    $update_stmt->bind_param("ssissi", $name, $actors, $note, $comment, $created_at, $update_id);

    // Exécuter la requête de mise à jour
    $update_result = $update_stmt->execute();

    // Vérifier si la mise à jour a réussi
    if ($update_result) {
        echo "Film mis à jour avec succès.";
    } else {
        echo "Erreur lors de la mise à jour du film: " . $update_stmt->error;
    }

    // Fermer la déclaration préparée de mise à jour
    $update_stmt->close();
}

// Fermer la connexion à la base de données
$mysqli->close();
?>
