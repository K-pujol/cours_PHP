<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$cart = $_POST['cart'] ?? [];


/** Affichage des tartes dans le panier */
foreach ($cart as $nomTarte => $infos) {
    $qty = (int) ($infos['quantite'] ?? 0);

    if ($qty <= 0) {
        unset($cart[$nomTarte]);
    }
}

/** Suppression d'une tarte du panier */
if (isset($_POST['action']) && $_POST['action'] === 'remove' && isset($_POST['remove'])) {
    unset($cart[$_POST['remove']]);
}
/** Ajout d'une tarte au panier */
if (isset($_POST['action']) && $_POST['action'] === 'add' && isset($_POST['name']) && isset($_POST['quantite'])) {
    $name = $_POST['name'];
    $qty = (int) $_POST['quantite'];

    if (isset($cart[$name])) {
        $cart[$name]['quantite'] += $qty;
    } else {
        $cart[$name] = [
            'name' => $name,
            'quantite' => $qty
        ];
    }
}


/** Incrémentation de la quantité d'une tarte */
if ($_POST['action'] === 'increment' && isset($_POST['name'])) {
    $name = $_POST['name'];
    if (isset($cart[$name])) {
        $cart[$name]['quantite']++;
    }
}

/** Décrémentation de la quantité d'une tarte */
if (isset($_POST['action'], $_POST['name']) && $_POST['action'] === 'decrement') {
    $name = $_POST['name'];
    if (isset($cart[$name])) {
        $cart[$name]['quantite']--;
        if ($cart[$name]['quantite'] <= 0) {
            unset($cart[$name]);
        }
    }
}

/** Vider le panier */
if (isset($_POST['action']) && $_POST['action'] === 'clear') {
    $cart = [];
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

        <?php if (!empty($cart)): ?>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Tarte</th>
                        <th>Quantité</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                    <!-- Affichage des tartes dans le panier -->
                    <?php foreach ($cart as $name => $infos) :
                        $quantite = $infos['quantite'] ?? 0;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($name); ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <!-- bouton moins -->
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="action" value="decrement">
                                        <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
                                        <?php foreach ($cart as $key => $data): ?>
                                            <input type="hidden" name="cart[<?php echo htmlspecialchars($key); ?>][name]" value="<?php echo htmlspecialchars($data['name']); ?>">
                                            <input type="hidden" name="cart[<?php echo htmlspecialchars($key); ?>][quantite]" value="<?php echo (int) $data['quantite']; ?>">
                                        <?php endforeach; ?>
                                        <button class="btn btn-sm btn-outline-secondary">-</button>
                                    </form>

                                    <span><?php echo $quantite; ?></span>


                                    <!-- Bouton plus -->
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="action" value="increment">
                                        <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
                                        <?php foreach ($cart as $key => $data): ?>
                                            <input type="hidden" name="cart[<?php echo htmlspecialchars($key); ?>][name]" value="<?php echo htmlspecialchars($data['name']); ?>">
                                            <input type="hidden" name="cart[<?php echo htmlspecialchars($key); ?>][quantite]" value="<?php echo (int) $data['quantite']; ?>">
                                        <?php endforeach; ?>

                                        <button class="btn btn-sm btn-outline-secondary">+</button>
                                    </form>

                                </div>
                            </td>
                            <td>
                                <!-- Bouton supprimer -->
                                <form method="post" class="d-inline">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
                                    <?php foreach ($cart as $key => $data): ?>
                                        <?php if ($key !== $name): ?>
                                            <input type="hidden" name="cart[<?php echo htmlspecialchars($key); ?>][name]" value="<?php echo htmlspecialchars($data['name']); ?>">
                                            <input type="hidden" name="cart[<?php echo htmlspecialchars($key); ?>][quantite]" value="<?php echo (int) $data['quantite']; ?>">
                                        <?php endif; ?>
                                    <?php endforeach; ?>
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
                    <option value="Fraises">Tarte aux Fraises</option>
                    <option value="Pommes">Tarte aux Pommes</option>
                    <option value="Poires">Tarte aux Poires</option>
                    <option value="Cerises">Tarte aux Cerises</option>
                    <option value="Abricots">Tarte aux Abricots</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="quantite" class="form-control" value="1" min="1" required>
            </div>

            <!-- Ajout d'un champ caché pour chaque tarte dans le panier -->
            <?php foreach ($cart as $key => $data): ?>
                <input type="hidden" name="cart[<?php echo htmlspecialchars($key); ?>][name]" value="<?php echo htmlspecialchars($data['name']); ?>">
                <input type="hidden" name="cart[<?php echo htmlspecialchars($key); ?>][quantite]" value="<?php echo (int) $data['quantite']; ?>">
            <?php endforeach; ?>

            <div class="col-md-2">
                <button type="submit" class="btn btn-success">Ajouter au panier</button>
            </div>
        </form>
    </div>
</body>

</html>