
window.onload = function () {
    updateAllButtons();
};
function updateAllButtons() {
    let articles = document.querySelectorAll("article");
    articles.forEach(article => {
        let articleParams = article.id.split(',');
        updateButtons(articleParams[0], articleParams[1].replace(/\s/g, ''));
    });
}
function updateButtons(idPost, maxPeople) {
    $.ajax({
        url: "../utils/manageButtons.php",
        type: "POST",
        data: {
            id: idPost,
            action: 'update'
        },
        success: function (response) {
            let participateButton = document.getElementById("buttonPartecipa" + idPost);
            if (response.statusParticipate == "partecipa") {
                participateButton.innerHTML = "Abbandona"
            }
            if (response.statusParticipate == "nonPartecipa") {
                participateButton.innerHTML = "Partecipa"
            }
            document.getElementById("partecipaLablel" + idPost).innerHTML = "Partecipanti: " + response.participants + "/" + maxPeople;
            document.getElementById("progress" + idPost).style.width = response.participants > maxPeople ? "100%" : response.participants / maxPeople * 100 + "%";

            let salvaButton = document.getElementById("buttonSalva" + idPost);
            if (response.statusSaved == "saved") {
                salvaButton.innerHTML = '<img class="iconButton" src="../Icons/Heart.svg" alt="">Salvato';
            }
            if (response.statusSaved == "unsaved") {
                salvaButton.innerHTML = '<img class="iconButton" src="../Icons/HeartEmpty.svg" alt="">Salva';
            }
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function toggleSalva(idPost, maxPeople) {
    $.ajax({
        url: "../utils/manageButtons.php",
        type: "POST",
        data: {
            id: idPost,
            action: 'salva'
        },
        success: function (response) {
            updateButtons(idPost, maxPeople);
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function togglePartecipa(idPost, maxPeople) {
    $.ajax({
        url: "../utils/manageButtons.php",
        type: "POST",
        data: {
            id: idPost,
            action: 'partecipa'
        },
        success: function (response) {
            updateButtons(idPost, maxPeople);
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function openPopup(idPost) {
    document.getElementById("popup").style.display = "block";
    document.getElementById("overlay").style.display = "block";
    $.ajax({
        url: "../utils/manageButtons.php",
        type: "POST",
        data: {
            id: idPost,
            action: 'participants'
        },
        success: function (response) {
            let participateButton = document.getElementById("buttonPartecipa" + idPost);
            document.getElementById("popup").innerHTML = "<h3>Utenti partecipanti</h3>" + response.participants + '<button class="closePopup" onclick="closePopup()">Indietro</button>';
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function closePopup() {
    document.getElementById("popup").style.display = "none";
    document.getElementById("overlay").style.display = "none";
}