<?php

$nom = "éclair vanille";
$prix = "3";
$imageURL = "https://patissland.fr/cdn/shop/articles/recette-des-eclairs-vanille-patissland.jpg?v=1742153497";
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article PHP</title>
</head>

<body>
    <h1><?php echo $nom; ?></h1>
    <p>Prix: <?php echo $prix; ?> €</p>
    <img src="<?php echo $imageURL; ?>" alt="Image d'un éclair vanille" width="200">

</body>

</html>