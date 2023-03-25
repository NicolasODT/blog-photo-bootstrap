<?php
session_start();
require_once '../core/includes/connect.php';


// Vérifie si l'utilisateur est un admin
if ($_SESSION["role"] != "admin") {
    header("Location: ../index.php");
}

// Récupère la liste des utilisateurs
$query = "SELECT id, pseudo, email, role, actif FROM Utilisateur";
$stmt = $bdd->query($query);
$utilisateurs = $stmt->fetchAll();

// Recherche des utilisateurs en fonction d'une chaîne de caractères
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT id, pseudo, email, role, actif FROM Utilisateur WHERE pseudo LIKE '%$search%' OR email LIKE '%$search%'";
    $stmt = $bdd->query($query);
    $utilisateurs = $stmt->fetchAll();
}
require_once '../core/includes/header.php';
?>

    <div class="container">
        <h1 class="my-5">Liste des utilisateurs</h1>

        <form action="" method="get" class="mt-3 mb-5">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Recherche..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <button class="btn btn-primary"><i class="fas fa-search"></i></button>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Pseudo</th>
                <th scope="col">Email</th>
                <th scope="col">Role</th>
                <th scope="col">Actif</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($utilisateurs as $utilisateur) : ?>
                <tr>
                    <td><?= $utilisateur['pseudo'] ?></td>
                    <td><?= $utilisateur['email'] ?></td>
                    <td><?= $utilisateur['role'] ?></td>
                    <td><?= $utilisateur['actif'] ? 'Oui' : 'Non' ?></td>
                    <td>
                        <!-- Formulaire de modification du rôle -->
                        <form action="../core/actions/modifier_utilisateur.php" method="post" class="d-inline">
                            <input type="hidden" name="id" value="<?= $utilisateur['id'] ?>">
                            <select name="role" class="form-select">
                                <option value="utilisateur" <?= $utilisateur['role'] == 'utilisateur' ? 'selected' : '' ?>>Utilisateur</option>
                                <option value="editeur" <?= $utilisateur['role'] == 'editeur' ? 'selected' : '' ?>>Editeur</option>
                                <option value="admin" <?= $utilisateur['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                            <button class="btn btn-primary">Modifier le rôle</button>
                        </form>
                        <!-- Formulaire d'activation/désactivation -->
                        <form action="../core/actions/desactiver_utilisateur.php" method="post" class="d-inline">
                            <input type="hidden" name="id" value="<?= $utilisateur['id'] ?>">
                            <input type="hidden" name="actif" value="<?= $utilisateur['actif'] ? 0 : 1 ?>">
                            <button class="btn btn-danger"><?= $utilisateur['actif'] ? 'Désactiver' : 'Activer' ?></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<?php require_once '../core/includes/footer.php'; ?>