

<?php
// Vérifier si l'ID du film est défini et est numérique
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    // Récupérer l'identifiant du film à supprimer
    $filmId = $_GET['id'];

    // Connexion à la base de données
    require __DIR__ . "/db/connexion.php";

    // Requête de suppression du film
    $query = "DELETE FROM films WHERE id = :filmId";

    // Préparer la requête
    $stmt = $db->prepare($query);

    // Vérifier si la préparation de la requête a réussi
    if ($stmt) {
        // Binder les paramètres
        $stmt->bindParam(":filmId", $filmId, PDO::PARAM_INT);

        // Exécuter la requête
        $result = $stmt->execute();

        // Vérifier si la suppression a réussi
        if ($result) {
            echo "Film supprimé avec succès.";
        } else {
            echo "Erreur lors de la suppression du film: " . $stmt->errorInfo()[2];
        }

        // Fermer la déclaration préparée
        $stmt->closeCursor();
    } else {
        echo "Erreur lors de la préparation de la requête.";
    }

    // Fermer la connexion à la base de données
    $db = null;
} else {
    echo "ID du film non valide.";
}

?>
