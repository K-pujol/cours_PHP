<?php

include('my-function.php');


$tartes = [
    "fraise" => [
        "name" => "Fraises",
        "price" => 1500,
        "weight" => 1000,
        "discount" => 10,
        "picture_url" => "https://www.patisserie-et-gourmandise.com/wp-content/uploads/2018/04/recette-tarte-fraise.jpg",
    ],
    "pomme" => [
        "name" => "Pommes",
        "price" => 1000,
        "weight" => 800,
        "discount" => 5,
        "picture_url" => "https://i1.wp.com/www.la-gourmandise-avant-tout.com/wp-content/uploads/2020/11/tarte-aux-pomems-Cedric-Grolet-scaled.jpg?resize=1020%2C600&ssl=1",
    ],
    "poire" => [
        "name" => "Poires",
        "price" => 1200,
        "weight" => 900,
        "discount" => 8,
        "picture_url" => "https://fac.img.pmdstatic.net/fit/~1~fac~2022~06~27~97207b60-4031-4be3-9cbb-b3f765aeef1e.jpeg/750x562/quality/80/crop-from/center/cr/wqkgSVNUT0NLL0dFVFRZIElNQUdFUyAvIEZlbW1lIEFjdHVlbGxl/tarte-aux-poires-normande.jpeg",
    ],
    "cerise" => [
        "name" => "Cerises",
        "price" => 1600,
        "weight" => 1100,
        "discount" => 12,
        "picture_url" => "https://img.static-rmg.be/a/food/image/q75/w640/h400/1092579/tarte-aux-cerises.jpg",
    ],
    "abricot" => [
        "name" => "Abricots",
        "price" => 1400,
        "weight" => 950,
        "discount" => 7,
        "picture_url" => "https://www.cakesandsweets.fr/wp-content/uploads/2012/02/tarte-abricot.jpg",
    ],
];

$titrePage = "Tarte aux $tartes[0]";
$descriptionPage = "Page présentation d'une tarte aux $tartes[0]";
$nom = "Tarte aux $tartes[0]";
include('header.php');

$prix = "3";
$image = "https://www.patisserie-et-gourmandise.com/wp-content/uploads/2018/04/recette-tarte-fraise.jpg";
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="bg-dark text-white p-3">

        <form action="cart.php" method="post">
            <button type="submit" class="btn btn-primary">Ajouter au panier</button>
            <?php foreach ($tartes as $tarte) : ?>
                <h2 class="text-center">Tarte aux <?php echo $tarte["name"] ?></h2>
                <h3>Tarte aux <?php echo $tarte["name"] ?></h3>
                <p>Prix : <?php echo formatPrice($tarte["price"]) ?></p>
                <p>Poids : <?php echo $tarte["weight"] ?> g</p>
                <p>Remise : <?php echo $tarte["discount"] ?> %</p>
                <img src="<?php echo $tarte["picture_url"] ?>" alt="Image d'une tarte aux fraises" width="300">
                <p>Prix HT : <?php echo formatPrice(priceExcludingVAT($tarte["price"])) ?> €</p>
                <p>Prix TTC : <?php echo formatPrice($tarte["price"]) ?></p>
                <p>Prix après remise : <?php echo discountedPrice($tarte["price"], $tarte["discount"]) ?></p>


                <input type="hidden" name="cart[<?php echo $tarte["name"] ?>][name]" value="<?php echo $tarte["name"] ?>">
                <!--   <input type="hidden" name="cart[<?php echo $tarte["name"] ?>][price]" value="<?php echo $tarte["price"] ?>">
                <input type="hidden" name="cart[<?php echo $tarte["name"] ?>][discount]" value="<?php echo $tarte["discount"] ?>">
                <input type="hidden" name="cart[<?php echo $tarte["name"] ?>][weight]" value="<?php echo $tarte["weight"] ?>">
                <input type="hidden" name="cart[<?php echo $tarte["name"] ?>][picture]" value="<?php echo $tarte["picture_url"] ?>"> -->
                <input type="number" name="cart[<?php echo $tarte["name"] ?>][quantite]" value="0" min="0" max="10">
            <?php endforeach; ?>

        </form>
    </div>
    <?php include('footer.php'); ?>
</body>

</html>