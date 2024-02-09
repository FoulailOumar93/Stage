<?php
session_start();

    // Le serveur

    // var_dump($_SERVER); 

    // Si les données arrivent au serveur via la méthode "POST"
    if ( $_SERVER['REQUEST_METHOD'] === "POST" ) 
    {

        $postClean = [];
        $errors = [];
        
        // Protéger le serveur contre les failles de type XSS
        foreach ($_POST as $key => $value) 
        {
            $postClean[$key] = htmlspecialchars(trim($value));
        }
    
        // Valider les champs du formulaire
        if ( isset($postClean['name']) ) 
        {
            if ( empty($postClean['name']) ) 
            {
                $errors['name'] = "Le nom du film est obligatoire.";
            }
            else if( mb_strlen($postClean['name']) > 255 )
            {
                $errors['name'] = "Le nom ne doit pas dépasser 255 caractères.";
            }
        }

        if ( isset($postClean['actors']) ) 
        {
            if ( empty($postClean['actors']) ) 
            {
                $errors['actors'] = "Le nom du ou des acteurs est obligatoire.";
            }
            else if( mb_strlen($postClean['actors']) > 255 )
            {
                $errors['actors'] = "Le nom du ou des acteurs ne doit pas dépasser 255 caractères.";
            }
        }

        if ( isset($postClean['note']) ) 
        {
            if ( ! empty($postClean['note']) ) 
            {
                if( ! is_numeric($postClean['note']) ) 
                {
                    $errors['note'] = "La note doit être un nombre.";
                }
                else if( $postClean['note'] < '0' || $postClean['note'] > '5' ) 
                {
                    $errors['note'] = "La note doit être comprise entre 0 et 5.";
                }
            }
        }

        if ( isset($postClean['comment']) ) 
        { 
            if ( ! empty($postClean['note']) ) 
            {
                if( mb_strlen($postClean['comment']) > 1000 )
                {
                    $errors['comment'] = "Le commentaire ne doit pas dépasser 1000 caractères.";
                }
            }   
        }
    
        // S'il y a au moins une erreur
        if ( count($errors) > 0 ) 
        {
            // Sauvegardons les messages d'erreur en session
            $_SESSION['formErrors'] = $errors;

            // Sauvegardons les données précedemment envoyéres par le client en session
            $_SESSION['old'] = $postClean;

            // Rediriger l'utilisateur vers la page de laquelle proviennent les informations
            return header("Location: " . $_SERVER['HTTP_REFERER']);
        }
    
        // Dans le cas contraire,
    
        // Arrondir la note à un chiffre après la virgule
        if ( isset($postClean['note']) && $postClean['note'] !== "" ) 
        {
            $noteRounded = round($postClean['note'], 1);
        }
    
        // Connexion à la base de données
        require __DIR__ . "/db/connexion.php";
    
        // Effectuer la requête d'insertion du nouveau film dans la table "film"
        $req = $db->prepare("INSERT INTO films (name, actors, note, comment, created_at, updated_at) VALUES (:name, :actors, :note, :comment, now(), now() ) ");

        $req->bindValue(":name", $postClean['name']);
        $req->bindValue(":actors", $postClean['actors']);
        $req->bindValue(":note", $noteRounded ? $noteRounded : $postClean['note']);
        $req->bindValue(":comment", $postClean['comment']);

        $req->execute();

        $req->closeCursor();

        // Générer un message flash
        $_SESSION['success'] = "Le film a été ajouté à la liste avec succès.";

        // Effectuer une redirection vers la page d'accueil
        // Arrêter l'exécution du script
        return header("Location: index.php");

    }
    
?>
<link rel="stylesheet" href="CSS/create.css">
<?php require __DIR__ . "/partials/_head_create.php"; ?>

    <?php require __DIR__ . "/partials/_nav.php"; ?>

        <!-- Le contenu spécifique à la page -->

        <main>
    <div class="container">
        <div class="row justify-content-center"> <!-- Ajout de la classe "justify-content-center" pour centrer le contenu -->
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
                        <input type="text" name="name" class="form-control" id="name" value="<?= isset($_SESSION['old']['name']) ? $_SESSION['old']['name'] : ""; unset($_SESSION['old']['name']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="actors">Nom du/des acteur(s) <span class="text-danger">*</span></label>
                        <input type="text" name="actors" class="form-control" id="actors" value="<?= isset($_SESSION['old']['actors']) ? $_SESSION['old']['actors'] : ""; unset($_SESSION['old']['actors']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="note">Note</label>
                        <input type="number" step=".1" name="note" class="form-control" id="note" value="<?= isset($_SESSION['old']['note']) ? $_SESSION['old']['note'] : ""; unset($_SESSION['old']['note']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="comment">Commentaires</label>
                        <textarea name="comment" id="comment" class="form-control" rows="4"><?= isset($_SESSION['old']['comment']) ? $_SESSION['old']['comment'] : ""; unset($_SESSION['old']['comment']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <input type="submit" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php require __DIR__ . "/partials/_foot.php"; ?>
