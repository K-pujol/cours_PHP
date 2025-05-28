<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Traitement du formulaire ici (validation, insertion en BDD, etc.)
    $category_id = $_POST['category_id'] ?? '';
    $vat_id = $_POST['vat_id'] ?? '';
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $url_image = $_POST['url_image'] ?? '';
    $weight = $_POST['weight'] ?? '';
    $stock = $_POST['stock'] ?? '';
    $is_available = isset($_POST['is_available']) ? 1 : 0;
    // Logique d'insertion en base de données ici
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Créer un article</h2>
    <form method="post" action="" class="row g-3">
        <div class="col-md-6">
            <label for="category_id" class="form-label">Catégorie :</label>
            <input type="number" name="category_id" id="category_id" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="vat_id" class="form-label">TVA :</label>
            <input type="number" name="vat_id" id="vat_id" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="name" class="form-label">Nom :</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="price" class="form-label">Prix :</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" required>
        </div>
        <div class="col-12">
            <label for="description" class="form-label">Description :</label>
            <textarea name="description" id="description" class="form-control" required></textarea>
        </div>
        <div class="col-md-6">
            <label for="url_image" class="form-label">URL de l'image :</label>
            <input type="text" name="url_image" id="url_image" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="weight" class="form-label">Poids :</label>
            <input type="number" step="0.01" name="weight" id="weight" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="stock" class="form-label">Stock :</label>
            <input type="number" name="stock" id="stock" class="form-control" required>
        </div>
        <div class="col-12">
            <div class="form-check">
                <input type="checkbox" name="is_available" id="is_available" value="1" class="form-check-input">
                <label for="is_available" class="form-check-label">Disponible</label>
            </div>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Créer l'article</button>
        </div>
    </form>
</div>
</body>
</html>