<?php
$fruits = ["Fraise", "Pomme", "Poire", "Cerise", "Abricot"];
$titrePage = "Tarte au fruits";
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
    <header class="bg-dark text-white p-3">
        <?php for ($i = 0; $i < count($fruits); $i++) { ?>
            <h2 class="text-center">Tarte au <?php echo $fruits[$i] ?></h2>
        <?php } ?>

        <?php sort($fruits); ?>
        <p><?php echo "1er fruit en ordre croissant: " . $fruits[0] ?></p>
        <p><?php echo "Dernier fruit en ordre croissant: " . $fruits[count($fruits) - 1] ?></p>
    </header>
</body>
</html>
<?php include('footer.php'); ?>
