<?php
include 'my-function.php';
include 'database.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['action']) && $_POST['action'] === 'sendDataCustomers') {
  $resultCustomers = filterFormCustomer($_POST);

  if (!empty($resultCustomers['errors'])) {
    foreach ($resultCustomers['errors'] as $error) {
      echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
    }
  } else {
    $data = $resultCustomers['data'];
    $firstName = $data['first_name'] ?? '';
    $lastName = $data['last_name'] ?? '';
    $email = $data['email'] ?? '';
    $address = $data['address'] ?? '';
    $postalCode = $data['postal_code'] ?? '';
    $city = $data['city'] ?? '';

    // Ajout du client à la base de données
    addCustomers(
      (string)$firstName,
      (string)$lastName,
      (string)$email,
      (string)$address,
      (int)$postalCode,
      (string)$city
    );

    echo '<div class="alert alert-success">Client ajouté avec succès !</div>';
  }
}

if (isset($_POST['action']) && $_POST['action'] === 'sendDataProducts') {
  $resultProducts = filterFormProduct($_POST);

  if (!empty($resultProducts['errors'])) {
    foreach ($resultProducts['errors'] as $error) {
      echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
    }
  } else {
    $data = $resultProducts['data'];
    $categoryId = $data['category_id'] ?? '';
    $vatId = $data['vat_id'] ?? '';
    $productName = $data['product_name'] ?? '';
    $description = $data['description'] ?? '';
    $prix = $data['price'] ?? '';
    $urlImage = $data['url_image'] ?? '';
    $poids = $data['weight'] ?? '';
    $stock = $data['quantity'] ?? '';
    $isAvailable = isset($data['is_available']) ? true : false;

    // Ajout du produit à la base de données
    addProducts(
      (int)$categoryId,
      (int)$vatId,
      (string)$productName,
      (string)$description,
      (float)$prix,
      (string)$urlImage,
      (float)$poids,
      (int)$stock,
      (bool)$isAvailable
    );

    echo '<div class="alert alert-success">Produit ajouté avec succès !</div>';
  }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Formulaires ajout</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
</head>

<body>
  <div class="container py-5">
    <h1 class="mb-4">Ajout BDD</h1>

    <!-- Boutons -->
    <div class="mb-4">
      <button id="ajout-Products" class="btn btn-primary me-2">Ajout Article</button>
      <button id="ajout-utilisateur" class="btn btn-primary me-2">Ajout utilisateur</button>
    </div>

    <!-- Formulaire Products -->
    <div id="overlayProducts">
      <div id="form-containerProducts" style="display:none">
        <form id="form-Products" class="form" autocomplete="off" method="post">
          <input type="hidden" name="action" value="sendDataProducts">

          <div class="form-group">
            <label for="category_id">Catégorie:</label><br>
            <select id="category_id" name="category_id">
              <option value="" selected disabled>Choisir une catégorie</option>
              <option value="1">cat 1</option>
              <option value="2">cat 2</option>
              <option value="3">cat 3</option>
              <option value="4">cat 4</option>
              <option value="5">cat 5</option>
            </select>
          </div>

          <div class="form-group ">
            <label for="vat_id">TVA:</label><br>
            <select id="vat_id" name="vat_id">
              <option value="" selected disabled>Choisir une TVA</option>
              <option value="1">5.5</option>
              <option value="2">10</option>
              <option value="3">20</option>
            </select>
          </div>

          <div class="form-group">
            <label for="product_name">Nom article:</label><br>
            <input type="text" id="product_name" name="product_name">
          </div>

          <div class="form-group">
            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="50"></textarea>
          </div>

          <div class="form-group">
            <label for="prix">Prix:</label><br>
            <div class="input-group">
              <input type="number" id="price" name="price" step="0.10" min="0" class="form-control">
              <span class="input-group-text">cts €</span>
            </div>
          </div>

          <div class="form-group">
            <label for="poster">Lien vers l'image de l'article :</label>
            <input type="url" id="url_image" name="url_image" placeholder="https://exemple.com/image.jpg">
          </div>

          <div class="form-group">
            <label for="poids">Poids:</label><br>
            <div class="input-group">
              <input type="number" id="weight" name="weight" step="0.01" class="form-control">
              <span class="input-group-text">g</span>
            </div>
          </div>

          <div class="form-group">
            <label for="stock">Stock:</label><br>
            <input type="number" id="quantity" name="quantity">
          </div>

          <div class="form-group">
            <label for="is_available">Disponible ?</label>
            <input type="checkbox" id="is_available" name="is_available">
          </div>
          <button type="submit" class="btn btn-success">Valider</button>
        </form>

      </div>
    </div>

    <!-- Formulaire client -->
    <div id="overlayCustomer">
      <div id="form-containerCustomer" style="display:none">
        <form id="form-client" class="form" autocomplete="off" method="post">
          <input type="hidden" name="action" value="sendDataCustomers">

          <div class="form-group">
            <label for="first_name">Prénom:</label><br>
            <input type="text" id="first_name" name="first_name">
          </div>

          <div class="form-group">
            <label for="last_name">Nom:</label><br>
            <input type="text" id="last_name" name="last_name">
          </div>

          <div class="form-group">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email">
          </div>

          <div class="form-group">
            <label for="address">Adresse:</label><br>
            <input type="text" id="address" name="address">
          </div>

          <div class="form-group">
            <label for="postal_code">Code postal:</label><br>
            <input type="text" id="postal_code" name="postal_code">
          </div>

          <div class="form-group">
            <label for="city">Ville:</label><br>
            <input type="text" id="city" name="city">
          </div>

          <button type="submit" class="btn btn-success">Valider</button>
        </form>

      </div>
    </div>

    <script src="./script.js"></script>
</body>

</html>