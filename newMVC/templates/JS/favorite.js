const btnFav = document.querySelector('#btn_Favoris');
let active = false;

const fullStar = '<i class="fa-solid fa-star"></i>';
const emptyStar = '<i class="fa-regular fa-star"></i>';



async function getLikes(id_product) {
    try {
        const response = await fetch(`index.php?action=getLikes&id_product=${id_product}`);
        const data = await response.json();

        console.log("likes =", data);

        if (data.nbLike !== undefined) {
            console.log("Nombre de likes :", data.nbLike);
        }

    } catch (e) {
        console.error("Erreur getLikes :", e);
    }
}



if (btnFav) {
    btnFav.addEventListener("click", async (e) => {
        e.preventDefault();

        if (active) return;

        try {
            active = true;

            const idProduct = document.querySelector('#idProduct')?.value;

            if (!idProduct) {
                console.error("id_product manquant !");
                active = false;
                return;
            }

            const value = !(btnFav.dataset.isFav === "true");

            let url = value
                ? "index.php?action=favorite&id=" + idProduct
                : "index.php?action=unfavorite&id=" + idProduct;

            const response = await fetch(url);
            const data = await response.text();

            if (data === "not_logged") {
                window.location.href = "index.php?action=connection";
                return;
            }

            btnFav.innerHTML = value ? fullStar : emptyStar;
            btnFav.dataset.isFav = value;

            // refresh likes
            await getLikes(idProduct);

            setTimeout(() => active = false, 500);

        } catch (e) {
            console.error("Erreur :", e);
            active = false;
        }
    });
}



document.addEventListener('DOMContentLoaded', () => {
    const idProduct = document.querySelector('#idProduct')?.value;

    if (idProduct) {
        getLikes(idProduct);
    }
});