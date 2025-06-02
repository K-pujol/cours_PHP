<?php
include 'my-function.php';

$clientErrors = [];
$clientData = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['first_name'])) {
  $result = filterForm($_POST);
  $clientErrors = $result['errors'];
  $clientData = $result['data'];
  // Ici, tu peux ensuite traiter $clientData si pas d'erreurs
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Formulaires dynamiques</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
</head>

<body>
  <div class="container py-5">
    <h1 class="mb-4">Ajout de formulaires</h1>

    <!-- Boutons -->
    <div class="mb-4">
      <button id="ajout-article" class="btn btn-primary me-2" onclick="toggleForm('form1')">Ajout article</button>
      <button id="ajout-utilisateur" class="btn btn-primary me-2" onclick="toggleForm('form2')">Ajout utilisateur</button>
    </div>

    <!-- Formulaire article -->
    <div id="overlayArticle">
      <div id="form-containerArticle" style="display:none">
        <form id="form-article" class="form" autocomplete="off" method="post">

          <div class="form-group">
            <label for="category_id">Catégorie:</label><br>
            <select id="category_id" name="category_id">
              <option value="" selected disabled>Choisir une catégorie</option>
              <option value="1">Catégorie 1</option>
              <option value="2">Catégorie 2</option>
              <option value="3">Catégorie 3</option>
            </select>
          </div>

          <div class="form-group ">
            <label for="vat_id">TVA:</label><br>
            <select id="vat_id" name="vat_id">
              <option value="" selected disabled>Choisir une TVA</option>
              <option value="1">TVA 1</option>
              <option value="2">TVA 2</option>
              <option value="3">TVA 3</option>
            </select>
          </div>

          <div class="form-group">
            <label for="nArticle">Nom article:</label><br>
            <input type="text" id="nArticle" name="nArticle">
          </div>

          <div class="form-group">
            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="50"></textarea>
          </div>

          <div class="form-group">
            <label for="prix">Prix:</label><br>
            <input type="number" id="prix" name="prix" step="0.10" min="0">
          </div>

          <div class="form-group">
            <label for="poster">Lien vers l'image de l'article :</label>
            <input type="url" id="poster" name="poster" placeholder="https://exemple.com/image.jpg">
          </div>

          <div class="form-group">
            <label for="posterFile">Ou choisir une image sur votre ordinateur :</label>
            <input type="file" id="posterFile" name="posterFile" accept="image/*">
          </div>

          <div class="form-group">
            <label for="poids">Poids:</label><br>
            <input type="number" id="poids" name="poids" step="0.01">
          </div>

          <div class="form-group">
            <label for="stock">Stock:</label><br>
            <input type="number" id="stock" name="stock">
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