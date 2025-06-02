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
  <title>Formulaires dynamiques</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script>
    // Fonction pour afficher/masquer un formulaire par ID
    function toggleForm(formId) {
      const form = document.getElementById(formId);
      form.classList.toggle('d-none');
    }
  </script>
</head>
<body>
  <div class="container py-5">
    <h1 class="mb-4">Ajout de formulaires</h1>

    <!-- Boutons -->
    <div class="mb-4">
      <button class="btn btn-primary me-2" onclick="toggleForm('form1')">Ajout 1</button>
      <button class="btn btn-primary me-2" onclick="toggleForm('form2')">Ajout 2</button>
      <button class="btn btn-primary" onclick="toggleForm('form3')">Ajout 3</button>
    </div>

    <!-- Formulaire 1 -->
    <form method="post" class="mb-4 d-none" id="form1">
      <h3>Formulaire 1</h3>
      <div class="mb-3">
        <label for="nom1" class="form-label">Nom</label>
        <input type="text" class="form-control" name="nom1" id="nom1">
      </div>
      <div class="mb-3">
        <label for="prenom1" class="form-label">Prénom</label>
        <input type="text" class="form-control" name="prenom1" id="prenom1">
      </div>
      <div class="mb-3">
        <label for="adresse1" class="form-label">Adresse</label>
        <input type="text" class="form-control" name="adresse1" id="adresse1">
      </div>
      <button type="submit" class="btn btn-success">Envoyer</button>
    </form>

    <!-- Formulaire 2 -->
    <form method="post" class="mb-4 d-none" id="form2">
      <h3>Formulaire 2</h3>
      <div class="mb-3">
        <label for="nom2" class="form-label">Nom</label>
        <input type="text" class="form-control" name="nom2" id="nom2">
      </div>
      <div class="mb-3">
        <label for="prenom2" class="form-label">Prénom</label>
        <input type="text" class="form-control" name="prenom2" id="prenom2">
      </div>
      <div class="mb-3">
        <label for="adresse2" class="form-label">Adresse</label>
        <input type="text" class="form-control" name="adresse2" id="adresse2">
      </div>
      <button type="submit" class="btn btn-success">Envoyer</button>
    </form>

    <!-- Formulaire 3 -->
    <form method="post" class="mb-4 d-none" id="form3">
      <h3>Formulaire 3</h3>
      <div class="mb-3">
        <label for="nom3" class="form-label">Nom</label>
        <input type="text" class="form-control" name="nom3" id="nom3">
      </div>
      <div class="mb-3">
        <label for="prenom3" class="form-label">Prénom</label>
        <input type="text" class="form-control" name="prenom3" id="prenom3">
      </div>
      <div class="mb-3">
        <label for="adresse3" class="form-label">Adresse</label>
        <input type="text" class="form-control" name="adresse3" id="adresse3">
      </div>
      <button type="submit" class="btn btn-success">Envoyer</button>
    </form>
  </div>
</body>
</html>