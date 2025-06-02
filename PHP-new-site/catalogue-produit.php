<?php
session_start();



include('my-function.php');
include('header.php');
include('database.php');

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Tous les produits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-4">
        <h1 class="mb-4">Choisissez vos produits</h1>

        <form method="post" action="cart.php">
            <input type="hidden" name="action" value="bulk_add">



            <div class="row g-4">
                <?php $gateaux = getAllProducts();
                foreach ($gateaux as $gateau) { ?>
                    <div class="col-md-4">
                        <div class="card h-100 bg-dark text-white d-flex flex-column">
                            <img src="<?= htmlspecialchars($gateau['url_image'] ?? ''); ?>" width="200" class="card-img-top" alt="Image de <?php echo htmlspecialchars($gateau['name']); ?>">

                            <div class="card-body flex-grow-1">
                                <h5 class="card-title"><?php echo htmlspecialchars($gateau['name']); ?></h5>
                                <p>
                                    Prix TTC : <?php echo formatPrice($gateau['price']); ?><br>
                                    Remise : <?php echo $gateau['discount']; ?> %<br>
                                    Poids : <?php echo $gateau['weight']; ?> g
                                </p>

                                <!-- Champs cachés avec les données -->
                                <input type="hidden" name="cart[<?php echo $gateau['id']; ?>][category_id]" value="<?php echo $gateau['category_id']; ?>">
                                <input type="hidden" name="cart[<?php echo $gateau['id']; ?>][vat_id]" value="<?php echo $gateau['vat_id']; ?>">
                                <input type="hidden" name="cart[<?php echo $gateau['id']; ?>][name]" value="<?php echo $gateau['name']; ?>">
                                <input type="hidden" name="cart[<?php echo $gateau['id']; ?>][description]" value="<?php echo htmlspecialchars($gateau['description']); ?>">
                                <input type="hidden" name="cart[<?php echo $gateau['id']; ?>][price]" value="<?php echo $gateau['price']; ?>">
                                <input type="hidden" name="cart[<?php echo $gateau['id']; ?>][url_image ]" value="<?php echo $gateau['url_image']; ?>">
                                <input type="hidden" name="cart[<?php echo $gateau['id']; ?>][weight]" value="<?php echo $gateau['weight']; ?>">
                                <input type="hidden" name="cart[<?php echo $gateau['id']; ?>][quantity]" value="<?php echo $gateau['quantity']; ?>">
                                <input type="hidden" name="cart[<?php echo $gateau['id']; ?>][is_available]" value="<?php echo $gateau['is_available']; ?>">
                                <input type="hidden" name="cart[<?php echo $gateau['id']; ?>][discount]" value="<?php echo $gateau['discount']; ?>">
                            </div>
                            <div class="card-footer bg-dark text-white">
                                <label for="quantite_<?php echo $key; ?>" class="form-label mb-1">Quantité :</label>
                                <input type="number" id="quantite_<?php echo $key; ?>" name="cart[<?php echo $key; ?>][quantite]" value="0" min="0" class="form-control">
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <!-- Bouton ajout -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success btn-lg">Ajouter au panier</button>
            </div>
        </form>
    </div>

    <?php include('footer.php'); ?>
</body>

</html>