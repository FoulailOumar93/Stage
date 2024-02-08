
<?php require __DIR__ . "/partials/_nav.php"; ?>
<?php require __DIR__ . "/partials/_head_visualiser_film.php";?>
<?php require __DIR__ . "/db/connexion.php"; ?>


<main class="container">
    <?php
    // Vérifier si l'ID du film est passé en paramètre
    if(isset($_GET['id'])) {
        $film_id = $_GET['id'];
        
        // Récupérer les détails du film depuis la base de données
        $query = "SELECT * FROM films WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$film_id]);
        $film = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Afficher les détails du film
        if($film) {
            echo "<h1>Informations du Film</h1><br>"; // Ajout d'une balise <br> après le titre
            echo "<p>Nom du film: {$film['name']}</p>";
            echo "<p>Nom de l'acteur: {$film['actors']}</p>";
            echo "<p>Note: {$film['note']}</p>";
            echo "<p>Commentaire: {$film['comment']}</p>";
            echo "<p>Date d'insertion: {$film['created_at']}</p>";
            echo "<p>Date de modification: {$film['updated_at']}</p>";
        } else {
            echo "Film non trouvé.";
        }
    } else {
        echo "ID du film non spécifié.";
    }
    ?>
</main>

<?php require __DIR__ . "/partials/_foot.php"; ?>
