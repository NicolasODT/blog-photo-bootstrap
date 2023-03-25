<?php
session_start();

require_once './core/includes/header.php';
?>

<main class="container">
    <div class="logotext d-flex align-items-center justify-content-between text-center">
        <img src="./public/media/FOCALE_CREATIVE.jpg" alt="">
        <h1>FOCALE CREATIVE</h1>
    </div>
    <form class="d-flex justify-content-center my-3" action="" method="get">
        <div class="input-group">
            <input type="search" name="search" class="form-control form-control-lg" placeholder="Recherche..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
        </div>
    </form>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php
        require_once './core/includes/connect.php';

        // Définition des variables de pagination
        $limit = 10;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        // Construction de la requête SQL en fonction de la présence ou non d'un mot-clé de recherche
        if (isset($_GET['search'])) {
            $search = '%' . $_GET['search'] . '%';
            $sql = "SELECT a.*, u.pseudo FROM Article a JOIN Utilisateur u ON a.id_utilisateur = u.id WHERE a.titre LIKE ? OR u.pseudo LIKE ? ORDER BY a.date_creation DESC LIMIT $limit OFFSET $offset";
            $stmt = $bdd->prepare($sql);
            $stmt->execute([$search, $search ]);
        } else {
            $sql = "SELECT a.*, u.pseudo FROM Article a JOIN Utilisateur u ON a.id_utilisateur = u.id ORDER BY a.date_creation DESC LIMIT $limit OFFSET $offset";
            $stmt = $bdd->prepare($sql);
            $stmt->execute();
        }

        $result = $stmt->fetchAll();

        // Affichage des cartes d'articles
        if (count($result) > 0) {
            foreach ($result as $row) {
        ?>
                <div class="col">
                    <div class="card h-100">
                        <img src="<?= $row["image"] ?>" class="card-img-top" alt="" style="width: auto;height:250px;object-fit: cover;">
                        <div class=" card-body">
                            <h2 class="card-title"><?= substr($row["titre"], 0, 20); ?>...</h2>
                            <p class="card-text"><?= strip_tags(substr($row["contenu"], 0, 50)); ?>...</p>
                            <p class="card-text"><?= $row["pseudo"] ?? ""; ?></p>
                        </div>
                        <div class="card-footer">
                            <a href="./templates/article.php?slug=<?= $row["slug"] ?>" class="btn btn-primary">Lire l'article</a>
                        </div>
                    </div>
                </div>
        <?php
            }
        } else {
            echo '<p class="alert alert-danger m-auto mt-4 w-75">Aucun article trouvé.</p>';
        }
        ?>
    </div>

    <?php

     // Construction de la chaîne de requête pour la pagination en fonction de la présence ou non d'un mot-clé de recherche
    $search_query = isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '';
    ?>
    <div class="d-flex justify-content-center my-3">
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php if ($page > 1) : ?>
                    <li class="page-item">
                        <a href="?page=<?= $page - 1 . $search_query ?>" class="page-link">Précédent</a>
                    </li>
                <?php endif; ?>
    
                <?php
                 // Calcul du nombre total de pages
                $total_pages = ceil($bdd->query('SELECT COUNT(*) FROM Article')->fetchColumn() / $limit);
                 // Affichage des liens de pagination
                for ($i = 1; $i <= $total_pages; $i++) :
                    $active_class = ($i === $page) ? 'active' : '';
                ?>
                    <li class="page-item <?= $active_class ?>">
                        <a href="?page=<?= $i . $search_query ?>" class="page-link"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
    
                <?php if ($page < $total_pages) : ?>
                    <li class="page-item">
                        <a href="?page=<?= $page + 1 . $search_query ?>" class="page-link">Suivant</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
    </main>

<?php
require_once './core/includes/footer.php';
?>