<?php
session_start();

require __DIR__ . "/db/connexion.php";

// Si l'identifiant du film à modifier est fourni dans l'URL
if (isset($_GET['id'])) {
    $film_id = $_GET['id'];

    // Récupérer les informations du film à partir de la base de données
    $query = "SELECT * FROM films WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$film_id]);
    $film = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si le film existe
    if (!$film) {
        $_SESSION['error'] = "Film non trouvé.";
        header("Location: index.php");
        exit;
    }
}

// Si les données du formulaire sont soumises
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Effectuer les validations et traitements nécessaires
    $postClean = [];
    $errors = [];

    foreach ($_POST as $key => $value) {
        $postClean[$key] = htmlspecialchars(trim($value));
    }

    if (empty($postClean['name'])) {
        $errors['name'] = "Le nom du film est obligatoire.";
    } elseif (mb_strlen($postClean['name']) > 255) {
        $errors['name'] = "Le nom ne doit pas dépasser 255 caractères.";
    }

    if (empty($postClean['actors'])) {
        $errors['actors'] = "Le nom du ou des acteurs est obligatoire.";
    } elseif (mb_strlen($postClean['actors']) > 255) {
        $errors['actors'] = "Le nom du ou des acteurs ne doit pas dépasser 255 caractères.";
    }

    if (!empty($postClean['note'])) {
        if (!is_numeric($postClean['note'])) {
            $errors['note'] = "La note doit être un nombre.";
        } elseif ($postClean['note'] < 0 || $postClean['note'] > 5) {
            $errors['note'] = "La note doit être comprise entre 0 et 5.";
        }
    }

    if (!empty($postClean['comment'])) {
        if (mb_strlen($postClean['comment']) > 1000) {
            $errors['comment'] = "Le commentaire ne doit pas dépasser 1000 caractères.";
        }
    }

    // S'il y a au moins une erreur
    if (count($errors) > 0) {
        // Sauvegardons les messages d'erreur en session
        $_SESSION['formErrors'] = $errors;

        // Rediriger l'utilisateur vers la page de modification du film
        header("Location: {$_SERVER['PHP_SELF']}?id=$film_id");
        exit;
    }

    // Mettre à jour les données du film dans la base de données
    $update_query = "UPDATE films SET name=?, actors=?, note=?, comment=? WHERE id=?";
    $update_stmt = $db->prepare($update_query);
    $update_stmt->execute([$postClean['name'], $postClean['actors'], $postClean['note'], $postClean['comment'], $film_id]);

    // Rediriger l'utilisateur vers la page d'accueil ou une autre page appropriée
    $_SESSION['success'] = "Le film a été mis à jour avec succès.";
    header("Location: index.php");
    exit;
}
?>
<link rel="stylesheet" href="CSS/create.css">
<!-- Afficher le formulaire avec les données du film pré-remplies -->
<?php require __DIR__ . "/partials/_head_update.php"; ?>
<?php require __DIR__ . "/partials/_nav.php"; ?>

<main>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5 bg-white p-4 shadow">
                <?php if(isset($_SESSION['formErrors']) && !empty($_SESSION['formErrors']) ) : ?>
                    <div class="container text-center alert alert-danger" role="alert">
                        <ul>
                            <?php foreach($_SESSION['formErrors'] as $error) : ?>
                            <li><?= $error ?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                <?php unset($_SESSION['formErrors']); ?>
                <?php endif ?>

                <form method="post">
                    <div class="mb-3">
                        <label for="name">Nom du film <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" id="name" value="<?= isset($film['name']) ? htmlspecialchars($film['name']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="actors">Nom du/des acteur(s) <span class="text-danger">*</span></label>
                        <input type="text" name="actors" class="form-control" id="actors" value="<?= isset($film['actors']) ? htmlspecialchars($film['actors']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="note">Note</label>
                        <input type="number" step=".1" name="note" class="form-control" id="note" value="<?= isset($film['note']) ? htmlspecialchars($film['note']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="comment">Commentaires</label>
                        <textarea name="comment" id="comment" class="form-control" rows="4"><?= isset($film['comment']) ? htmlspecialchars($film['comment']) : ''; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <input type="submit" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php require __DIR__ . "/partials/_footer.php"; ?>
<?php require __DIR__ . "/partials/_foot.php"; ?>
