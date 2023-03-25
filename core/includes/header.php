<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon titre</title>
    <!-- Ajout des fichiers CSS et JavaScript de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
    <!-- Ajout du fichiers CSS de Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Ajout du script TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/20t4psha1silm1bfotmbo9ywlhqxr8z9w4uh90aqf099bu65/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <!-- Ajout de votre fichier JavaScript -->
    <script src="../../public/js/app.js" defer></script>
</head>
<body class="imgLogoFond">
<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="../../index.php">Focale creative</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
            <?php if (isset($_SESSION["id"])): ?>
                <?php if ($_SESSION["role"] == "admin"): ?>
                    <li class="nav-item"><a class="nav-link" href="../../templates/panel.php">Panel</a></li>
                <?php endif; ?>
                <?php if ($_SESSION["role"] == "admin" || $_SESSION["role"] == "editeur"): ?>
                    <li class="nav-item"><a class="nav-link" href="../../core/actions/add_article.php">Ajouter un article</a></li>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link" href="../../templates/profil.php">Profil</a></li>
                <li class="nav-item"><a class="nav-link" href="../../core/actions/logout.php">Déconnexion</a></li>
            <?php else: ?>
                <li class="nav-item"><a class="nav-link" href="../../templates/login.php">Connexion</a></li>
                <li class="nav-item"><a class="nav-link" href="../../templates/register.php">Inscription</a></li>
            <?php endif; ?>
        </ul>
        </div>
    </nav>
</header>


