<?php
session_start();



include('my-function.php');
include('tartes-data.php');
include('header.php');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Toutes les tartes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-4">
        <h1 class="mb-4">Choisissez vos tartes</h1>

        <form method="post" action="cart.php">
            <input type="hidden" name="action" value="bulk_add">

            <div class="row g-4">
                <?php foreach ($tartes as $key => $tarte): ?>
                    <div class="col-md-4">
                        <div class="card h-100 bg-dark text-white d-flex flex-column">
                            <img src="<?php echo htmlspecialchars($tarte['picture_url']); ?>" class="card-img-top" alt="Image de <?php echo htmlspecialchars($tarte['name']); ?>">

                            <div class="card-body flex-grow-1">
                                <h5 class="card-title">Tarte aux <?php echo htmlspecialchars($tarte['name']); ?></h5>
                                <p>
                                    Prix TTC : <?php echo formatPrice($tarte['price']); ?><br>
                                    Remise : <?php echo $tarte['discount']; ?> %<br>
                                    Poids : <?php echo $tarte['weight']; ?> g
                                </p>

                                <!-- Champs cachés avec les données -->
                                <input type="hidden" name="cart[<?php echo $key; ?>][name]" value="<?php echo $tarte['name']; ?>">
                                <input type="hidden" name="cart[<?php echo $key; ?>][price]" value="<?php echo $tarte['price']; ?>">
                                <input type="hidden" name="cart[<?php echo $key; ?>][discount]" value="<?php echo $tarte['discount']; ?>">
                                <input type="hidden" name="cart[<?php echo $key; ?>][image]" value="<?php echo $tarte['picture_url']; ?>">
                            </div>
                            <div class="card-footer bg-dark text-white">
                                <label for="quantite_<?php echo $key; ?>" class="form-label mb-1">Quantité :</label>
                                <input type="number" id="quantite_<?php echo $key; ?>" name="cart[<?php echo $key; ?>][quantite]" value="0" min="0" class="form-control">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
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