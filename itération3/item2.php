<?php
$titrePage = "Tarte au fraise";
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
        <h1 class="text-center"> Une <?php echo $nom ?>
            <img src="<?php echo $image ?>" alt="Image d'une tarte au fraise" width="300">
    </header>
</body>

</html>



<?php include('footer.php'); ?>