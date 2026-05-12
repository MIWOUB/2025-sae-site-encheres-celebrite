function ouvrirPopup(page) {
  let newPrice = null;
  let currentPrice = null;
  let idProduct = null;
  switch (page) {
    case "Adresse":
      fetch("templates/Event/Modif_Address.php")
        .then((response) => response.text())
        .then((html) => {
          document.getElementById("popup").innerHTML = html;
          document.getElementById("popupAdresse").style.display = "flex";
        });
      break;
    case "Email":
      fetch("templates/Event/Modif_Email.php")
        .then((response) => response.text())
        .then((html) => {
          document.getElementById("popup").innerHTML = html;
          document.getElementById("popupEmail").style.display = "flex";
        });
      break;
    case "Password":
      fetch("templates/Event/Modif_Password.php")
        .then((response) => response.text())
        .then((html) => {
          document.getElementById("popup").innerHTML = html;
          document.getElementById("popupPassword").style.display = "flex";
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

        const priceSpan = document.querySelector(".product-price span");
        const currentPrice = parseInt(
            priceSpan.textContent
                .replace(/\s/g, "")
                .replace("€", "")
        );

        const idProduct = parseInt(productInput.value);

        console.log("Bid send:", newPrice, currentPrice, idProduct);

        if (!newPriceIsValid(newPrice, currentPrice)) {
            return;
        }

        fetch(`index.php?action=bid&id=${idProduct}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: new URLSearchParams({
                newPrice: newPrice
            }),
        })
        .then(res => res.text())
        .then(data => {

            console.log("RAW RESPONSE:", JSON.stringify(data));

            //  nettoyage robuste
            data = data.trim().replaceAll('"', '');

            console.log("CLEAN RESPONSE:", data);

            // =========================
            // AUTH / ERROR CASES
            // =========================
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

            // =========================
            // SUCCESS CASES
            // =========================
            if (data.includes("success") || data.includes("time_extended")) {

                if (priceSpan) {
                    priceSpan.textContent =
                        newPrice.toLocaleString("fr-FR") + " €";
                }

                const hidden = document.querySelector("#currentPrice");
                if (hidden) {
                    hidden.value = newPrice;
                }

                fermerPopupBidForm();
                showToast(0, "Enchère validée !");
                return;
            }

            // fallback
            showToast(2, "Erreur serveur");
        })
        .catch(err => {
            console.error("Fetch error:", err);
            showToast(2, "Erreur réseau");
        });

        break;
    }
    case "BidForm":
    {
      const currentPriceEl = document.querySelector("#currentPrice");
      const idProductEl = document.querySelector("#idProduct");

      if (!currentPriceEl || !idProductEl) {
        console.error("Missing product data");
        return;
      }

      const currentPrice = parseInt(currentPriceEl.value);

      const idProduct = parseInt(idProductEl.value);

      fetch("templates/Event/Bid_Form.php")
        .then(res => res.text())
        .then(html => {
          document.getElementById("popup").innerHTML = html;

          const popup = document.getElementById("popup_bid_form");
          popup.style.display = "block";

          document.getElementById("idProduct_form").value = idProduct;
          document.getElementById("currentPrice_form").value = currentPrice;

          const bidInput = document.getElementById("bid_input_form");

          const step = addToPrice(currentPrice);

          bidInput.value = Math.round(currentPrice + step);
          bidInput.step = step;

          bidInput.addEventListener("input", () => {
            document.querySelectorAll(".error-msg").forEach(e => e.remove());

            newPriceIsValid(
              parseInt(bidInput.value),
              currentPrice
            );
          });
        });

      break;
    }
    default:
      console.log("Aucun changement");
      break;
  }
}

//#region close popup
function fermerPopupEmail() {
  document.getElementById("popupEmail").style.display = "none";
}

function fermerPopupPassword() {
  document.getElementById("popupPassword").style.display = "none";
}

function fermerPopupAdresse() {
  document.getElementById("popupAdresse").style.display = "none";
}

function fermerPopupBid() {
  document.getElementById("popup_bid").style.display = "none";
}

function fermerPopupBidForm() {
  document.getElementById("popup_bid_form").style.display = "none";
}
//#endregion

async function checkupNewPWD(event) {
  event.preventDefault();

  // récupération mdp
  const pwd1 = document.querySelector(".new_password_1").value;
  const pwd2 = document.querySelector(".new_password_2").value;

  if (pwd1 != pwd2) {
    document.querySelector(".div_erreur").innerHTML =
      '<p style="color: red;"> Password error ! </p>';
  } else {
    document.querySelector(".div_erreur").innerHTML = "<p> </p>";
    event.target.submit();
  }
}

function addToPrice(currentPrice) {
  if (currentPrice < 100) return 5;
  else if (currentPrice < 500) return 10;
  else if (currentPrice < 1000) return 20;
  else if (currentPrice < 5000) return 50;
  else if (currentPrice < 10000) return 100;
  else if (currentPrice < 50000) return 500;
  return 1000;
}

function newPriceIsValid(newPrice, currentPrice) {

  newPrice = Number(newPrice);
  currentPrice = Number(currentPrice);

  if (isNaN(newPrice) || isNaN(currentPrice)) {
    console.error("Invalid price values:", newPrice, currentPrice);
    return false;
  }

  if (newPrice <= currentPrice) {
    const bidLabel = document.querySelector("#bid-label-form");

    if (!bidLabel) return false;

    bidLabel.querySelectorAll(".error-msg").forEach((e) => e.remove());

    const star = document.createElement("span");
    star.classList.add("error-msg");
    star.textContent =
      "Le montant doit être supérieur à " + currentPrice + " €";
    star.style.marginLeft = "5px";

    bidLabel.appendChild(star);

    return false;
  }

  return true;
}

// =========================
// TOAST GLOBAL
// =========================
window.showToast = function(type, msg) {

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
    }
    else if (type === 1) {
        toast.classList.add('invalid');
        toast.innerHTML = `⚠️ ${msg}`;
    }
    else if (type > 1) {
        toast.classList.add('error');
        toast.innerHTML = `❌ ${msg}`;
    }
    else {
        toast.innerHTML = `✅ ${msg}`;
    }

    toastBox.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 5000);
}