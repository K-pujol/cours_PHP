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
                'image' => $tarteInfo['picture_url'],
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
if ($_POST['action'] === 'bulk_add' && isset($_POST['cart'])) {
    foreach ($_POST['cart'] as $key => $data) {
        $quantite = (int) ($data['quantite'] ?? 0);
        if ($quantite > 0) {
            $name = $data['name'];
            if (!isset($_SESSION['cart'][$name])) {
                $_SESSION['cart'][$name] = [
                    'name' => $name,
                    'quantite' => 0,
                    'price' => $data['price'],
                    'discount' => $data['discount'],
                    'image' => $data['image'],
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

/** Envoi des données du panier */
if (isset($_POST['action']) && $_POST['action'] === 'sendDataProducts') {
    // Vérification si le panier est vide
    if (empty($_SESSION['cart'])) {
        echo '<div class="alert alert-warning">Votre panier est vide. Ajoutez des produits avant de valider.</div>';
    } else {
        foreach ($_SESSION['cart'] as $item) {
            // Vérification des données du produit "tarte-data.php"
            $categoryId = $item['category_id'] ?? 3;
            $vatId = $item['vat_id'] ?? 1;
            $name = $item['name'] ?? '';
            $description = $item['description'] ?? '';
            $price = $item['price'] ?? 0.0;
            $urlImage = $item['image'] ?? '';
            $weight = $item['weight'] ?? 14.2;
            $quantity = $item['quantite'] ?? 1;

            addProducts(
                (int)$categoryId,
                (int)$vatId,
                (string)$name,
                (string)$description,
                (float)$price,
                (string)$urlImage,
                (float)$weight,
                (int)$quantity
            );
        }
        echo '<div class="alert alert-success">Panier validé avec succès !</div>';
        // Réinitialiser le panier après validation
        $_SESSION['cart'] = [];
    }
}

if (isset($_POST['action']) && $_POST['action'] === 'sendDataCustomers') {
    $result = filterForm($_POST);

    if (!empty($result['errors'])) {
        foreach ($result['errors'] as $error) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
        }
    } else {
        $data = $result['data'];
        $firstName = $data['first_name'] ?? '';
        $lastName = $data['last_name'] ?? '';
        $email = $data['email'] ?? '';
        $address = $data['address'] ?? '';
        $postalCode = $data['postal_code'] ?? '';
        $city = $data['city'] ?? '';

        // Ajout du client à la base de données
        addCustomers(
            (string)$firstName,
            (string)$lastName,
            (string)$email,
            (string)$address,
            (int)$postalCode,
            (string)$city
        );

        echo '<div class="alert alert-success">Client ajouté avec succès !</div>';
    }
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
                        <th>Remise</th>
                        <th>Montant de la remise</th>
                        <th>Prix après remise</th>
                        <th>Supprimer</th>
                    </tr>
                </thead>



                <tbody>
                    <!-- Affichage de chaque tarte dans le panier -->
                    <!-- On utilise le nom de la tarte comme clé pour accéder aux infos -->
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
                                <!-- On utilise htmlspecialchars pour éviter les injections XSS -->
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
                            <!-- Affichage de la remise-->
                            <td><?php echo $remise . ' %'; ?></td>
                            <!-- Montant de la remise -->
                            <td><?php echo formatPrice($quantite * $prixUnitaire * ($remise / 100)); ?></td>
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

                <?php
                /** Calcul du total */
                $total = 0;
                foreach ($_SESSION['cart'] as $infos) {
                    $quantite = $infos['quantite'] ?? 0;
                    $prixUnitaire = $infos['price'] ?? 0;
                    $remise = $infos['discount'] ?? 0;
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
        <!-- bouton de réinitialisation du panier -->
        <form method="post">
            <br>
            <input type="hidden" name="reset" value="destroy">
            <button class="btn btn-danger">Reset complet du panier</button>
        </form>


        <!-- créer un customer -->
        <h2 class="mt-4">Créer un client</h2>
        <form method="post">
            <div class="mb-3">
                <label for="first_name" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="first_name" name="first_name">
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Nom</label>
                <input type="text" class="form-control" id="last_name" name="last_name">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Adresse</label>
                <input type="text" class="form-control" id="address" name="address">
            </div>
            <div class="mb-3">
                <label for="postal_code" class="form-label">Code postal</label>
                <input type="text" class="form-control" id="postal_code" name="postal_code">
            </div>
            <div class="mb-3">
                <label for="city" class="form-label">Ville</label>
                <input type="text" class="form-control" id="city" name="city">
            </div>
            <input type="hidden" name="action" value="sendDataCustomers">
            <button type="submit" class="btn btn-primary">Créer le client</button>
        </form>



    </div>

    <!-----------------------------------------Intégration SQL----------------------------------------->


    <?php

    /* Affichage des tartes depuis la base de données */
    $gateaux = getAllTartes();
    foreach ($gateaux as $gateau) {
    ?>
        <p><?= htmlspecialchars($gateau['name'] ?? ''); ?></p>
        <img src="<?= htmlspecialchars($gateau['url_image'] ?? ''); ?>" width="200">
    <?php
    }


    /* Affichage des commandes de moins de 10 jours */
    $lastCommande = lastTenDaysOrder();
    foreach ($lastCommande as $commande) {
    ?><p>Commande de moins de 10j: <br>
            Commande - <?= htmlspecialchars($commande['number']) ?? ''; ?></p>
    <?php
    }

    /* Affichage des produits d'une commande spécifique */
    $orderId = 6; // Exemple d'ID de commande
    $productFromOrder = showProductsByOrder($orderId);
    foreach ($productFromOrder as $product) {
    ?><p>Produit de la commande <?= $orderId ?>: <br>
            <?= htmlspecialchars($product['name']) ?? ''; ?> - <?= formatPrice($product['price']) ?? ''; ?> - Quantité: <?= $product['quantity'] ?? ''; ?></p>
    <?php
    }
    ?><br>
    <h2>Montant total des commandes par client</h2>
    <?php


    /* Affichage du montant total des commandes pour un client spécifique */
    $customerId = 6; // Exemple d'ID de client
    $amountByCustomer = amountOrderByCustomer($customerId);

    if (empty($amountByCustomer)) {
    ?>
        <p> <?= 'Aucun client trouvé.'; ?></p>
        <?php
    } else {
        foreach ($amountByCustomer as $customer) {
        ?><p>Client: <strong><?= htmlspecialchars($customer['first_name']) ?? ''; ?> <?= htmlspecialchars($customer['last_name']) ?? ''; ?></strong> - Montant total: <?= formatPrice($customer['total_amount']) ?? ''; ?></p>
    <?php
        }
    }
    ?>



</body>

</html>