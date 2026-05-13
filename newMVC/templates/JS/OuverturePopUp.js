function validateAdresseForm(event) {
  event.preventDefault();

  const adresse = document.getElementById("adresse").value.trim();
  const codePostal = document.getElementById("code_postal").value.trim();
  const ville = document.getElementById("ville").value.trim();

  const zone = document.querySelector("#popupAdresse .error-zone");
  zone.innerHTML = "";

  let valid = true;

  if (adresse.length < 3) {
    zone.innerHTML += `<div class="error-msg">L'adresse est trop courte.</div>`;
    valid = false;
  }

  if (!/^[0-9]{5}$/.test(codePostal)) {
    zone.innerHTML += `<div class="error-msg">Le code postal doit contenir exactement 5 chiffres.</div>`;
    valid = false;
  }

  if (/[0-9]/.test(ville)) {
    zone.innerHTML += `<div class="error-msg">Le nom de la ville ne peut pas contenir de chiffres.</div>`;
    valid = false;
  }

  if (ville.length < 3) {
    zone.innerHTML += `<div class="error-msg">Le nom de la ville est incorrect.</div>`;
    valid = false;
  }

  if (!valid) return false;

  event.target.submit();
}

function validateEmailForm(event) {
  event.preventDefault();

  const email = document.getElementById("email").value.trim();
  const zone = document.querySelector("#popupEmail .error-zone");
  zone.innerHTML = "";

  let valid = true;

  if (email.length === 0) {
    zone.innerHTML += `<div class="error-msg">Veuillez entrer une adresse email.</div>`;
    valid = false;
  }

  if (email.length < 6) {
    zone.innerHTML += `<div class="error-msg">L'adresse email est trop courte.</div>`;
    valid = false;
  }

  if (!valid) return false;

  event.target.submit();
}

function validatePasswordForm(event) {
  event.preventDefault();

  const pwd1 = document.getElementById("new_password_1").value.trim();
  const pwd2 = document.getElementById("new_password_2").value.trim();

  const zone = document.querySelector("#popupPassword .error-zone");
  zone.innerHTML = "";

  let valid = true;

  if (pwd1.length < 8) {
    zone.innerHTML += `<div class="error-msg">Le mot de passe doit contenir au moins 8 caractères.</div>`;
    valid = false;
  }

  if (!/[0-9]/.test(pwd1)) {
    zone.innerHTML += `<div class="error-msg">Le mot de passe doit contenir au moins un numéro.</div>`;
    valid = false;
  }

  if (!/[A-Z]/.test(pwd1)) {
    zone.innerHTML += `<div class="error-msg">Le mot de passe doit contenir au moins une majuscule.</div>`;
    valid = false;
  }

  if (!/[a-z]/.test(pwd1)) {
    zone.innerHTML += `<div class="error-msg">Le mot de passe doit contenir au moins une minuscule.</div>`;
    valid = false;
  }

  if (!/[^A-Za-z0-9]/.test(pwd1)) {
    zone.innerHTML += `<div class="error-msg">Le mot de passe doit contenir un caractère spécial.</div>`;
    valid = false;
  }

  if (pwd1 !== pwd2) {
    zone.innerHTML += `<div class="error-msg">Les mots de passe ne correspondent pas.</div>`;
    valid = false;
  }

  if (!valid) return false;

  event.target.submit();
}

function ouvrirPopup(page) {

  switch (page) {

    case "Adresse":
      fetch("templates/Event/Modif_Address.php")
        .then(r => r.text())
        .then(html => {
          popup.innerHTML = html;
          popupAdresse.style.display = "flex";
        });
      break;

    case "Email":
      fetch("templates/Event/Modif_Email.php")
        .then(r => r.text())
        .then(html => {
          popup.innerHTML = html;
          popupEmail.style.display = "flex";
        });
      break;

    case "Password":
      fetch("templates/Event/Modif_Password.php")
        .then(r => r.text())
        .then(html => {
          popup.innerHTML = html;
          popupPassword.style.display = "flex";
        });
      break;

    case "Bid": {

      const bidInput = document.querySelector("#bid_input_form");
      const productInput = document.querySelector("#idProduct_form");

      if (!bidInput || !productInput) {
        console.error("Popup values missing");
        return;
      }

      const newPrice = parseInt(bidInput.value);
      const currentPrice = parseInt(
        document.querySelector(".product-price span")
          .textContent
          .replace(/\s/g, "")
          .replace("€", "")
      );
      const idProduct = parseInt(productInput.value);

      console.log("Bid send:", newPrice, currentPrice, idProduct);

      if (!newPriceIsValid(newPrice, currentPrice)) return;

      fetch(`index.php?action=bid&id=${idProduct}`, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({ newPrice }),
      })
        .then(res => res.text())
        .then(data => {

          console.log("RAW RESPONSE:", JSON.stringify(data));

          data = data.trim().replaceAll('"', '');

          console.log("CLEAN RESPONSE:", data);

          if (data.includes("not_logged")) {
            window.location.href = "index.php?action=login";
            return;
          }

          if (data.includes("same")) {
            showToast(2, "Tu ne peux pas enchérir sur ton propre produit !");
            return;
          }

          if (data.includes("finished")) {
            showToast(2, "L'enchère est terminée !");
            return;
          }

          if (data.includes("user_not_accepted")) {
            showToast(3, "Vous êtes déjà le dernier enchérisseur !");
            return;
          }

          if (data.includes("price_not_accepted")) {
            showToast(2, "Prix trop bas !");
            return;
          }

          if (data.includes("success") || data.includes("time_extended")) {

            const priceSpan = document.querySelector(".product-price span");
            if (priceSpan) {
              priceSpan.textContent = newPrice.toLocaleString("fr-FR") + " €";
            }

            const hidden = document.querySelector("#currentPrice");
            if (hidden) hidden.value = newPrice;

            fermerPopupBidForm();
            showToast(0, "Enchère validée !");
            return;
          }

          showToast(2, "Erreur serveur");
        })
        .catch(err => {
          console.error("Fetch error:", err);
          showToast(2, "Erreur réseau");
        });

      break;
    }

    case "BidForm": {

      const currentPriceEl = document.querySelector("#currentPrice");
      const idProductEl = document.querySelector("#idProduct");

      if (!currentPriceEl || !idProductEl) {
        console.error("Missing product data");
        return;
      }

      const currentPrice = parseInt(currentPriceEl.value);
      const idProduct = parseInt(idProductEl.value);

      fetch("templates/Event/Bid_Form.php")
        .then(r => r.text())
        .then(html => {

          popup.innerHTML = html;

          const popupForm = document.getElementById("popupBidForm");
          popupForm.style.display = "flex";

          const idProductForm = document.getElementById("idProduct_form");
          const currentPriceForm = document.getElementById("currentPrice_form");

          if (idProductForm) idProductForm.value = idProduct;
          if (currentPriceForm) currentPriceForm.value = currentPrice;

          const bidInput = document.getElementById("bid_input_form");
          const step = addToPrice(currentPrice);

          bidInput.value = Math.round(currentPrice + step);
          bidInput.step = step;

          bidInput.addEventListener("input", () => {
            const zone = document.querySelector("#popupBidForm .error-zone");
            if (zone) zone.innerHTML = "";
            newPriceIsValid(parseInt(bidInput.value), currentPrice);
          });
        });

      break;
    }

    case "BidValidation": {

      const bidInput = document.querySelector("#bid_input_form");
      const currentPriceForm = document.getElementById("currentPrice_form");

      if (!bidInput || !currentPriceForm) return;

      const newPrice = parseInt(bidInput.value);
      const currentPrice = parseInt(currentPriceForm.value);

      if (!newPriceIsValid(newPrice, currentPrice)) return;

      fetch("templates/Event/Bid_Validation.php")
        .then(r => r.text())
        .then(html => {
          popup.innerHTML = html;

          const popupValidation = document.getElementById("popupBidValidation");
          if (!popupValidation) return;

          const textEl = document.getElementById("bidValidationText");
          if (textEl) {
            textEl.textContent =
              `Vous confirmez votre enchère de ${newPrice.toLocaleString("fr-FR")} € ?`;
          }

          popupValidation.style.display = "flex";
        });

      break;
    }

    default:
      console.log("Aucun changement");
      break;
  }
}

function fermerPopupEmail() { popupEmail.style.display = "none"; }
function fermerPopupPassword() { popupPassword.style.display = "none"; }
function fermerPopupAdresse() { popupAdresse.style.display = "none"; }
function fermerPopupBid() { popup_bid.style.display = "none"; }
function fermerPopupBidForm() { popupBidForm.style.display = "none"; }

function fermerPopupBidValidation() {
  const popupValidation = document.getElementById("popupBidValidation");
  if (popupValidation) popupValidation.style.display = "none";
}

function envoyerEnchere() {
  ouvrirPopup("Bid");
  fermerPopupBidValidation();
}

function addToPrice(currentPrice) {
  if (currentPrice < 100) return 5;
  if (currentPrice < 500) return 10;
  if (currentPrice < 1000) return 20;
  if (currentPrice < 5000) return 50;
  if (currentPrice < 10000) return 100;
  if (currentPrice < 50000) return 500;
  return 1000;
}

function newPriceIsValid(newPrice, currentPrice) {

  const zone = document.querySelector("#popupBidForm .error-zone");
  if (!zone) return false;

  zone.innerHTML = "";

  newPrice = Number(newPrice);
  currentPrice = Number(currentPrice);

  if (isNaN(newPrice) || isNaN(currentPrice)) {
    zone.innerHTML = `<div class="error-msg">Valeur invalide.</div>`;
    return false;
  }

  if (newPrice <= currentPrice) {
    zone.innerHTML = `<div class="error-msg">Le montant doit être supérieur à ${currentPrice} €</div>`;
    return false;
  }

  return true;
}

window.showToast = function (type, msg) {

  const toastBox = document.querySelector('#toastBox');

  if (!toastBox) {
    console.error("toastBox introuvable");
    return;
  }

  const toast = document.createElement('div');
  toast.classList.add('toast');

  if (type === 3) {
    toast.classList.add('warning');
    toast.innerHTML = `🔔 ${msg}`;
  } else if (type === 1) {
    toast.classList.add('invalid');
    toast.innerHTML = `⚠️ ${msg}`;
  } else if (type > 1) {
    toast.classList.add('error');
    toast.innerHTML = `❌ ${msg}`;
  } else {
    toast.innerHTML = `✅ ${msg}`;
  }

  toastBox.appendChild(toast);

  setTimeout(() => {
    toast.remove();
  }, 5000);
}