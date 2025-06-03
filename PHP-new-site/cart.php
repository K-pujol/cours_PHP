<?php
session_start();


/** Initialisation du panier */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/** Configuration des erreurs */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('my-function.php');
include('tartes-data.php');
include('database.php');


if (
    isset($_POST['action']) && $_POST['action'] === 'add'
    && isset($_POST['name'], $_POST['quantite'])
) {
    $key = strtolower($_POST['name']);
    if (isset($tartes[$key])) {
        $tarteInfo = $tartes[$key];
        var_dump($tarteInfo['description'] ?? null);
        var_dump($tarteInfo['weight'] ?? null);
        var_dump($tarteInfo['name'] ?? null);
    }
}

/** Suppression d'une tarte du panier */
if (isset($_POST['action']) && $_POST['action'] === 'remove' && isset($_POST['remove'])) {
    unset($_SESSION['cart'][$_POST['remove']]);
}
/** Ajout d'une tarte au panier */
if (
    isset($_POST['action']) && $_POST['action'] === 'add' && isset($_POST['name'], $_POST['quantite'])
) {
    $key = strtolower($_POST['name']);
    /** Vérification de l'existence de la tarte */
    if (isset($tartes[$key])) {
        $tarteInfo = $tartes[$key];

        $name = $tarteInfo['name'];
        $quantite = (int) $_POST['quantite'];

        /** Vérification de l'existence dans le panier */
        if (!isset($_SESSION['cart'][$name])) {
            $_SESSION['cart'][$name] = [
                'name' => $name,
                'quantite' => 0,
                'price' => $tarteInfo['price'],
                'discount' => $tarteInfo['discount'],
                'image' => $tarteInfo['url_image'],
                'category_id' => $tarteInfo['category_id'],
                'vat_id' => $tarteInfo['vat_id'],
                'description' => $tarteInfo['description'] ?? '',
                'weight' => $tarteInfo['weight'] ?? 0,
                'is_available' => $tarteInfo['is_available'] ?? 1,
            ];
        }

        $_SESSION['cart'][$name]['quantite'] += $quantite;
    }
}

/** Ajout d'une tarte au panier via bouton*/
if ($_POST['action'] === 'products_add' && isset($_POST['cart'])) {
    foreach ($_POST['cart'] as $key => $data) {
        $quantite = (int) ($data['quantite'] ?? 0);
        if ($quantite > 0) {
            $name = $data['name'];
            if (!isset($_SESSION['cart'][$name])) {
                $_SESSION['cart'][$name] = [
                    'name' => $name,
                    'quantite' => 0,
                    'id' => $data['id'],
                    'price' => $data['price'],
                    'discount' => $data['discount'],
                    'image' => $data['url_image'],
                    'category_id' => $data['category_id'],
                    'vat_id' => $data['vat_id'],
                    'description' => $data['description'],
                    'weight' => $data['weight'],
                    'is_available' => $data['is_available'],
                ];
            }
            $_SESSION['cart'][$name]['quantite'] += $quantite;
        }
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


/** Création commande dans BDD */

if (isset($_POST['action']) && $_POST['action'] === 'sendDataProducts') {
    // Vérification que le panier n'est pas vide
    if (empty($_SESSION['cart'])) {
        echo '<div class="alert alert-danger">Votre panier est vide. Veuillez ajouter des produits avant de valider.</div>';
        exit;
    }
    // Récupération des données du panier
    $cartData = $_SESSION['cart'];
    // Création de la commande

    // Calcul du prix total de la commande avant l'envoi
    $prixTotal = 0;
    foreach ($cartData as $item) {
        $quantite = $item['quantite'] ?? 0;
        $prixUnitaire = $item['price'] ?? 0;
        $remise = (float)($item['discount'] ?? 0);
        $prixTotal += $quantite * $prixUnitaire * (1 - $remise / 100);
    }

    $orderId = createOrders(1, '', 1, $prixTotal);

    // Création des éléments dans la commande
    foreach ($cartData as $item) {
        createOrderItems($orderId, $item['id'], $item['quantite'], $item['price']);
    }

    // Vider le panier
    $_SESSION['cart'] = [];
    echo '<div class="alert alert-success">Commande créée avec succès !</div>';
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
                        <th>Produits</th>
                        <th>Image</th>
                        <th>Quantité</th>
                        <th>Prix unitaire</th>
                        <th>Remise</th>
                        <th>Montant de la remise</th>
                        <th>Prix après remise</th>
                        <th>Supprimer</th>
                    </tr>
                </thead>

                <tbody>
                    <!-- Affichage de chaque tarte dans le panier -->
                    <?php foreach ($_SESSION['cart'] as $name => $infos) :
                        $quantite = $infos['quantite'] ?? 0;
                        $prixUnitaire = $infos['price'] ?? 0;
                        $remise = $infos['discount'] ?? 0;
                        $image = $infos['image'];

                    ?>
                        <tr>
                            <td>
                                <!-- Affichage du nom -->
                                <p><?= htmlspecialchars($infos['name'] ?? ''); ?></p>
                            </td>
                            <!-- Affichage de l'image -->
                            <td>
                                <img src="<?= htmlspecialchars($image); ?>" width="200" alt="Image de <?php echo htmlspecialchars($infos['name']); ?>">
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
                            <td>
                                <p><?= formatPrice($prixUnitaire); ?></p>
                            </td>
                            <?php $remise = 10; ?>
                            <!-- Affichage de la remise-->
                            <td><?php echo $remise . ' %'; ?></td>
                            <!-- Montant de la remise -->
                            <?php $prixRemise = $quantite * $prixUnitaire * ($remise / 100); ?>
                            <td><?php echo formatPrice($prixRemise); ?></td>
                            <!-- Affichage du prix après remise -->
                            <?php $prixTotal = $quantite * $prixUnitaire * (1 - $remise / 100); ?>
                            <td><?php echo formatPrice($prixTotal); ?></td>
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

                <?php
                /** Calcul du total */
                $total = 0;
                foreach ($_SESSION['cart'] as $infos) {
                    $quantite = $infos['quantite'] ?? 0;
                    $prixUnitaire = $infos['price'] ?? 0;
                    $remise = 10;
                    //$infos['discount'] ?? 0;
                    $total += $quantite * $prixUnitaire * (1 - $remise / 100);
                }
                ?>

            </table>

            <div class="alert alert-success text-end fw-bold d-flex justify-content-between align-items-center">
                <!-- Bouton Vider tout -->
                <form method="post" class="mb-0">
                    <input type="hidden" name="action" value="clear">
                    <button class="btn btn-warning">Vider le panier</button>
                </form>
                <span>Total à payer : <?php echo formatPrice($total); ?></span>
            </div>


            <!-- Bouton Valider le panier -->
            <form method="post" class="text-end mt-3">
                <input type="hidden" name="action" value="sendDataProducts">
                <button class="btn btn-primary">Valider le panier</button>
            </form>

        <?php else: ?>
            <div class="alert alert-info">Votre panier est vide.</div>
        <?php endif; ?>

        <hr>

        <!-- bouton de réinitialisation du panier -->
        <form method="post">
            <br>
            <input type="hidden" name="reset" value="destroy">
            <button class="btn btn-danger">Reset complet du panier</button>
        </form>

    </div>
</body>

</html>