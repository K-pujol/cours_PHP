<?php
include('my-function.php');

$fruits = [
    "name" => "Fraise",
    "price" => 1500,
    "weight" => 1000,
    "discount" => 10,
    "picture_url" => "https://www.patisserie-et-gourmandise.com/wp-content/uploads/2018/04/recette-tarte-fraise.jpg",
];
$titrePage = "Tarte au $fruits[name]";
$descriptionPage = "Page présentation d'une tarte au fraise";
$nom = "Tarte au fraise";
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
    <title><?php $titrePage ?></title>
</head>
<body>
    <div class="bg-dark text-white p-3">
        <h3>Tarte aux <?php echo $fruits["name"] ?></h3>
        <p>Poids : <?php echo $fruits["weight"] ?> g</p>        
        <img src="<?php echo $fruits["picture_url"] ?>" alt="Image d'une tarte aux fraises" width="300">
        <p>Prix HT: <?php echo formatPrice(priceExcludingVAT($fruits["price"])) ?></p>
        <p>Prix TTC: <?php echo formatPrice($fruits["price"]) ?></p>
        <p>Remise : <?php echo $fruits["discount"] ?> %</p>
        <p>Prix après remise : <?php echo discountedPrice($fruits["price"], $fruits["discount"]) ?></p>
    </div>
</body>
</html>
<?php include('footer.php'); ?>
