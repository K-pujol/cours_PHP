<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('my-function.php');
include('tartes-data.php');


/** Suppression d'une tarte du panier */
if (isset($_POST['action']) && $_POST['action'] === 'remove' && isset($_POST['remove'])) {
    unset($_SESSION['cart'][$_POST['remove']]);
}
/** Ajout d'une tarte au panier */
if (
    isset($_POST['action']) && $_POST['action'] === 'add' && isset($_POST['name'], $_POST['quantite'])
) {
    $key = strtolower($_POST['name']);

    if (isset($tartes[$key])) {
        $tarteInfo = $tartes[$key];

        $name = $tarteInfo['name'];
        $quantite = (int) $_POST['quantite'];

        if (!isset($_SESSION['cart'][$name])) {
            $_SESSION['cart'][$name] = [
                'name' => $name,
                'quantite' => 0,
                'price' => $tarteInfo['price'],
                'discount' => $tarteInfo['discount'],
                'image' => $tarteInfo['picture_url'],
            ];
        }

        $_SESSION['cart'][$name]['quantite'] += $quantite;
    }
}


/** Incrémentation de la quantité d'une tarte */
if ($_POST['action'] === 'increment' && isset($_POST['name'])) {
    $name = $_POST['name'];
    if (isset($_SESSION['cart'][$name])) {
        $_SESSION['cart'][$name]['quantite']++;
    }
}

/** Décrémentation de la quantité d'une tarte */
if (isset($_POST['action'], $_POST['name']) && $_POST['action'] === 'decrement') {
    $name = $_POST['name'];
    if (isset($_SESSION['cart'][$name])) {
        $_SESSION['cart'][$name]['quantite']--;
        if ($_SESSION['cart'][$name]['quantite'] <= 0) {
            unset($_SESSION['cart'][$name]);
        }
    }
}

/** Vider le panier */
if (isset($_POST['action']) && $_POST['action'] === 'clear') {
    $_SESSION['cart'] = [];
}

/** Réinitialiser complètement le panier */
if (isset($_POST['reset']) && $_POST['reset'] === 'destroy') {
    session_unset();      // Supprime toutes les variables de session
    session_destroy();    // Détruit la session
    header('Location: ' . $_SERVER['PHP_SELF']); // Recharge la page pour réinitialiser proprement
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Mon panier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>


    <div class="container py-5">
        <h1 class="mb-4">🛒 Mon Panier</h1>

        <?php if (!empty($_SESSION['cart'])): ?>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Tarte</th>
                        <th>Image</th>
                        <th>Quantité</th>
                        <th>Prix unitaire</th>
                        <th>Prix total</th>
                        <th>Remise</th>
                        <th>Prix après remise</th>
                        <th>Supprimer</th>
                    </tr>
                </thead>



                <tbody>
                    <?php foreach ($_SESSION['cart'] as $name => $infos) :
                        $quantite = $infos['quantite'] ?? 0;
                        $prixUnitaire = $infos['price'] ?? 0;
                        $remise = $infos['discount'] ?? 0;
                        $imageUrl = $infos['image'] ?? '';
                    ?>
                        <tr>
                            <!-- Affichage du nom -->
                            <td><?php echo htmlspecialchars($name); ?></td>
                            <!-- Affichage de l'image -->
                            <td>
                                <img src="<?php echo htmlspecialchars($imageUrl ?? ''); ?>" alt="<?php echo htmlspecialchars($name); ?>" width="100">
                            </td>
                            <!-- +/- pour la quantité -->
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="action" value="decrement">
                                        <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
                                        <button class="btn btn-sm btn-outline-secondary">-</button>
                                    </form>

                                    <span><?php echo $quantite; ?></span>

                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="action" value="increment">
                                        <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
                                        <button class="btn btn-sm btn-outline-secondary">+</button>
                                    </form>
                                </div>
                            </td>
                            <!-- Affichage du prix unitaire -->
                            <td><?php echo formatPrice($prixUnitaire); ?></td>
                            <!-- Affichage du prix total -->
                            <td><?php echo formatPrice($quantite * $prixUnitaire); ?></td>
                            <!-- Affichage de la remise -->
                            <td><?php echo $remise . ' %'; ?></td>
                            <!-- Affichage du prix après remise -->
                            <td><?php echo formatPrice($quantite * $prixUnitaire * (1 - $remise / 100)); ?></td>
                            <!-- Bouton de suppression -->
                            <td>
                                <form method="post" class="d-inline">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="remove" value="<?php echo htmlspecialchars($name); ?>">
                                    <button class="btn btn-sm btn-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>

            <!-- Bouton Vider tout -->
            <form method="post">
                <input type="hidden" name="action" value="clear">
                <button class="btn btn-warning">Vider le panier</button>
            </form>
        <?php else: ?>
            <div class="alert alert-info">Votre panier est vide.</div>
        <?php endif; ?>

        <hr>

        <!-- Ajout manuel -->
        <h2 class="mt-4">Ajouter une tarte</h2>
        <form method="post" class="row g-3">
            <input type="hidden" name="action" value="add">
            <div class="col-md-4">
                <select name="name" class="form-select" required>
                    <option type="hidden" value="">-- Choisir une tarte --</option>
                    <option value="fraises">Tarte aux Fraises</option>
                    <option value="pommes">Tarte aux Pommes</option>
                    <option value="poires">Tarte aux Poires</option>
                    <option value="cerises">Tarte aux Cerises</option>
                    <option value="abricots">Tarte aux Abricots</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="quantite" class="form-control" value="1" min="1" required>
            </div>


            <!-- Ajout d'un champ caché pour chaque tarte dans le panier -->
            <?php foreach ($_SESSION['cart'] as $key => $data):
                include('constructionPanier.php');
            endforeach; ?>

            <div class="col-md-2">
                <button type="submit" class="btn btn-success">Ajouter au panier</button>
            </div>
        </form>
        <form method="post">
            <input type="hidden" name="reset" value="destroy">
            <button class="btn btn-danger">Reset complet du panier</button>
        </form>
    </div>
</body>

</html>