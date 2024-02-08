<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste Des films - Cinema</title>
    <script src="https://kit.fontawesome.com/0736e85c93.js" crossorigin="anonymous"></script>   
    <!-- Inclusion du fichier de styles CSS -->

    <link rel="stylesheet" href="CSS/button.css">
    <?php require __DIR__ . "/partials/_nav.php"; ?>
    <?php require __DIR__ . "/db/connexion.php"; ?> <!-- Assurez-vous que ce fichier est correctement inclus -->
    <?php require __DIR__ . "/partials/button.php"; ?>

</head>
<body class="bg-light">
    <main class="container">
        <?php
        // Récupérer la liste des films depuis la base de données
        $query = "SELECT * FROM films";
        $stmt = $db->query($query);

        // Afficher chaque film dans un conteneur différent
        while ($film = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<div class='film-container'>";
            echo "<div class='film-box'>";
            echo "<h2>{$film['name']}</h2>";
            echo "<p><strong>Acteurs:</strong> {$film['actors']}</p>";
            echo "<p><strong>Note:</strong> {$film['note']}</p>";
            echo "<p><strong>Commentaire:</strong> {$film['comment']}</p>";
           
            // Ajout des boutons avec des icônes pour visualiser, modifier et supprimer à l'intérieur de la div film-box
            echo "<div class='action-buttons'>";
            echo "<a href='Visualiser_Film.php?id={$film['id']}'><i class='fas fa-eye'></i></a>"; // Icône pour visualiser
            echo "<a href='Update.php?id={$film['id']}'><i class='fas fa-edit'></i></a>"; // Icône pour modifier 
            echo "<a href='javascript:void(0);' onclick='confirmerSuppression({$film['id']})'><i class='fas fa-trash-alt'></i></a>"; // Icône pour supprimer avec la fonction JavaScript
            echo "</div>"; // Fermeture de la div action-buttons
            echo "</div>"; // Fermeture de la div film-box
            echo "</div>"; // Fermeture de la div film-container
        }
        ?>
    </main>
</body>

        </div>
    </main>
    <script src="confirmation.js"></script> <!-- Inclure le fichier JavaScript contenant la fonction de confirmation -->
    <script>
        // Fonction pour demander une confirmation avant de supprimer le film
        function confirmerSuppression(filmId) {
            // Afficher une boîte de dialogue de confirmation
            var confirmation = confirm("Êtes-vous sûr de vouloir supprimer ce film ?");

            // Si l'utilisateur clique sur OK, rediriger vers la page de suppression avec l'ID du film
            if (confirmation) {
                window.location.href = 'Delete.php?id=' + filmId;
            }
        }
    </script>
</body>
</html>

<style> 

/* Styles généraux */
body {
    font-family: Arial, sans-serif;
    background-color: beige;
    color: #333; /* Couleur de texte par défaut */
}

.container {
    margin-top: 20px;
}

/* Styles pour la zone des films */
.film-container {
    border: 2px solid #ccc; /* Bordure grise */
    border-radius: 10px; /* Coins arrondis */
    padding: 10px; /* Espacement intérieur */
    margin-bottom: 20px; /* Marge en bas */
    background-color: #f9f9f9; /* Couleur de fond */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Ombre douce */
}

/* Styles pour le titre des films */
.film-container h2 {
    color: #333; /* Couleur de texte */
    font-size: 24px; /* Taille de police */
    margin-bottom: 10px; /* Marge en bas */
}

/* Styles pour les détails des films */
.film-container p {
    color: #666; /* Couleur de texte plus claire */
    font-size: 16px; /* Taille de police */
    margin-bottom: 8px; /* Marge en bas */
}

/* Styles pour les boutons d'icônes */
.action-buttons {
    margin-top: 10px; /* Marge en haut */
}

.action-buttons a {
    display: inline-block;
    margin-right: 10px;
    color: #333; /* Couleur de texte pour les boutons */
    text-decoration: none;
    font-size: 20px;
}

.action-buttons a:hover {
    color: #007bff; /* Couleur de texte au survol des boutons */
}

</style>
</body>
</html>
