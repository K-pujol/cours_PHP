// --------------------------Afficher formulaire------------------------------\\

const btnAjoutArticle = document.getElementById("ajout-article");
const formContainerArticle = document.getElementById("form-containerArticle");
const overlayArticle = document.getElementById("overlayArticle");

btnAjoutArticle.addEventListener("click", () => {
    formContainerArticle.style.display = "block";
    overlayArticle.style.display = "block";
});

// pour quitter le formulaire si on clic a côté
overlayArticle.addEventListener("click", (e) => {
    if (e.target === overlayArticle) {
        formContainerArticle.style.display = "none";
        overlayArticle.style.display = "none";
        document.getElementById("category_id").value = "";
        document.getElementById("vat_id").value = "";
        document.getElementById("name").value = "";
        document.getElementById("description").value = "";
        document.getElementById("price").value = "";
        document.getElementById("image").value = "";
        document.getElementById("stock").value = "";
        document.getElementById("is_available").value = "";
    }
});

// --------------------------Afficher formulaire------------------------------\\

const btnAjoutUtilisateur = document.getElementById("ajout-utilisateur");
const formContainerCustomer = document.getElementById("form-containerCustomer");
const overlayCustomer = document.getElementById("overlayCustomer");

btnAjoutUtilisateur.addEventListener("click", () => {
    formContainerCustomer.style.display = "block";
    overlayCustomer.style.display = "block";
});

// pour quitter le formulaire si on clic a côté
overlayCustomer.addEventListener("click", (e) => {
    if (e.target === overlayCustomer) {
        formContainerCustomer.style.display = "none";
        overlayCustomer.style.display = "none";
        document.getElementById("first_name").value = "";
        document.getElementById("last_name").value = "";
        document.getElementById("email").value = "";
        document.getElementById("address").value = "";
        document.getElementById("postal_code").value = "";
        document.getElementById("city").value = "";
    }
});


