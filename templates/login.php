<?php
session_start();

// Vérifie si les informations de connexion ont été envoyées par le formulaire
if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST['g-recaptcha-response'])) {

    // Vérifie le recaptcha
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptchaSecret = '6LeMHRElAAAAAI40BMkqAmWsQ5MaJiLizJqdp4p4';

    $recaptcha = file_get_contents($recaptchaUrl . '?secret=' . $recaptchaSecret . '&response=' . $recaptchaResponse);
    $recaptcha = json_decode($recaptcha);

    // Si le recaptcha est validé
    if ($recaptcha->success) {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        require_once "../core/includes/connect.php";


        // Requête pour récupérer les informations de l'utilisateur
        $sql = "SELECT * FROM utilisateur WHERE email LIKE :email OR pseudo LIKE :email;";
        $query = $bdd->prepare($sql);
        $query->bindParam(":email", $email, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetch();
        // Si l'utilisateur existe dans la base de données
        if ($results) {
            // Vérifie le mot de passe
            if (password_verify($password, $results['hash'])) {
                // Vérifie si le compte est actif
                if (!$results['actif']) {
                    header("Location: ../core/actions/deactived.php");
                    exit();
                }
                // Stocke les informations de l'utilisateur en session
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $results['role'];
                $_SESSION['id'] = $results['id'];
                $_SESSION['pseudo'] = $results['pseudo'];
                header('location: ../index.php');
            } else {
                $errorMsg = 'Mot de passe incorrect';
            }
        } else {
            $errorMsg = 'Email non trouvé';
        }
    } else {
        $errorMsg = 'Erreur reCAPTCHA';
    }
}

require_once '../core/includes/header.php';
?>

<main class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Connexion</h1>
                </div>
                <div class="card-body ">
                    <form action="" method="post" class="form-login">
                        <?php if (isset($errorMsg)) : ?>
                            <p class="alert alert-danger"><?= $errorMsg ;?></p>
                        <?php endif; ?>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email ou pseudo</label>
                            <input type="text" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <div class="mb-3 d-flex justify-content-center">
                            <div class="g-recaptcha" data-sitekey="6LeMHRElAAAAACgEgUGBNqALUexnqykdbjJq7z-O"></div>
                        </div>
                        <button class="btn btn-primary w-100">Connexion</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php
require_once '../core/includes/footer.php';
?>