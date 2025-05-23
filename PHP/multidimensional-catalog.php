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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <form action="cart.php" method="post" class="mb-4 p-3 rounded">
        <div class="bg-dark text-white p-3">
            <button type="submit" class="btn btn-primary">Ajouter au panier</button>
            <?php foreach ($tartes as $key => $tarte): ?>

                <h2 class="text-center">Tarte aux <?php echo $tarte['name']; ?></h2>
                <p>Prix : <?php echo formatPrice($tarte['price']); ?></p>
                <p>Poids : <?php echo $tarte['weight']; ?> g</p>
                <p>Remise : <?php echo $tarte['discount']; ?> %</p>
                <img src="<?php echo $tarte['picture_url']; ?>" alt="Image de <?php echo $tarte['name']; ?>" width="300">
                <p>Prix HT : <?php echo formatPrice(priceExcludingVAT($tarte['price'])); ?> €</p>
                <p>Prix après remise : <?php echo discountedPrice($tarte['price'], $tarte['discount']); ?> €</p>
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="name" value="<?php echo $key; ?>">
                <div class="d-flex align-items-center gap-2">
                    <input type="number" name="quantite" value="0" min="0" class="form-control w-auto">
                </div>
    </form>
<?php endforeach; ?>

</div>
<?php include('footer.php'); ?>
</body>

</html>