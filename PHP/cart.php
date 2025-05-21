<?php
$cart = $_POST['cart'] ?? [];

/** Suppression d'une tarte du panier */
if (isset($_POST['action']) && $_POST['action'] === 'remove' && isset($_POST['remove'])) {
    unset($cart[$_POST['remove']]);
}
/** Ajout d'une tarte au panier */
if (isset($_POST['action']) && $_POST['action'] === 'add' && isset($_POST['name']) && isset($_POST['quantite'])) {
    $name = htmlspecialchars($_POST['name']);
    $qty = (int) $_POST['quantite'];

    if (isset($cart[$name])) {
        $cart[$name] += $qty;
    } else {
        $cart[$name] = $qty;
    }
}

/** Incrémentation de la quantité d'une tarte */
if (isset($_POST['action'], $_POST['name']) && $_POST['action'] === 'increment') {
    $name = $_POST['name'];
    if (isset($cart[$name])) {
        $cart[$name]++;
    }
}

/** Décrémentation de la quantité d'une tarte */
if (isset($_POST['action'], $_POST['name']) && $_POST['action'] === 'decrement') {
    $name = $_POST['name'];
    if (isset($cart[$name])) {
        $cart[$name]--;
        if ($cart[$name] <= 0) {
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                    <?php foreach ($cart as $name => $qty): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($name); ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="action" value="decrement">
                                        <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
                                        <?php foreach ($cart as $key => $valeur): ?>
                                            <input type="hidden" name="cart[<?php echo htmlspecialchars($key); ?>]" value="<?php echo $valeur; ?>">
                                        <?php endforeach; ?>
                                        <button class="btn btn-sm btn-outline-secondary">-</button>
                                    </form>

                                    <span><?php echo $qty; ?></span>

                                    <!-- Bouton + -->
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="action" value="increment">
                                        <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
                                        <?php foreach ($cart as $key => $valeur): ?>
                                            <input type="hidden" name="cart[<?php echo htmlspecialchars($key); ?>]" value="<?php echo $valeur; ?>">
                                        <?php endforeach; ?>
                                        <button class="btn btn-sm btn-outline-secondary">+</button>
                                    </form>
                                </div>
                            </td>
                            <td>
                                <form method="post" class="d-inline">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
                                    <?php foreach ($cart as $key => $valeur): ?>
                                        <?php if ($key !== $name): ?>
                                            <input type="hidden" name="cart[<?php echo htmlspecialchars($key); ?>]" value="<?php echo $valeur; ?>">
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

            <?php foreach ($cart as $name => $qty): ?>
                <input type="hidden" name="cart[<?php echo htmlspecialchars($name); ?>]" value="<?php echo $qty; ?>">
            <?php endforeach; ?>

            <div class="col-md-2">
                <button type="submit" class="btn btn-success">Ajouter au panier</button>
            </div>
        </form>
    </div>
</body>

</html>