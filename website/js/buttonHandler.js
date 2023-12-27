let address = "";
let startPost = 0;
let range = 100;

window.onload = function () {
    updateAllButtons(0);
    updateAllMaps(0);
};
function updateAllButtons(startButton) {
    let articles = document.querySelectorAll("article");
    articles.forEach(article => {
        let articleParams = article.id.split(',');
        if (articleParams[0] > startButton) {
            updateButtons(articleParams[0], articleParams[1].replace(/\s/g, ''));
        }
    });
}
function updateAllMaps(startMapId) {
    let maps = document.querySelectorAll(".openmap");
    maps.forEach(map => {
        let children = Array.from(map.children);
        children.forEach(child => { child.remove(); });
    });
    let articles = document.querySelectorAll("article");
    articles.forEach(article => {
        let articleParams = article.id.split(',');
        if (articleParams[0] > startMapId) {
            $.ajax({
                url: "../utils/manageButtons.php",
                type: "POST",
                data: {
                    id: articleParams[0],
                    action: 'getPost'
                },
                success: function (response) {
                    activateMap(articleParams[0], response.post["PosizioneLongitudine"], response.post["PosizioneLatitudine"], response.post["Indirizzo"]);
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
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
            let displayString = response.participants == "" ? "<p>Nessun Partecipante</p>" : response.participants;
            document.getElementById("popup").innerHTML = "<h3>Utenti partecipanti</h3>" + displayString + '<button class="closePopup" onclick="closePopup()">Indietro</button>';
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function closePopup() {
    document.getElementById("popup").style.display = "none";
    document.getElementById("overlay").style.display = "none";
    document.getElementById("popup").innerHTML = "";
}

function morePosts() {
    startPost = document.querySelector('article:last-of-type').id.split(',')[0];

    $.ajax({
        url: "../utils/manageButtons.php",
        type: "POST",
        data: {
            startId: startPost,
            address: address,
            range: range,
            action: 'morePosts'
        },
        success: function (response) {
            if (response.articles != "") {
                document.querySelector(".buttonAltriPost").remove();
                document.querySelector(".articles").innerHTML += response.articles + ' <button type="button" class="buttonAltriPost" onclick="morePosts()">Altri Post</button>';
                updateAllMaps(0);
                updateAllButtons(startPost);
            }
            else {
                if (document.getElementById("nessunRisultato") == null) {
                    document.querySelector(".articles").innerHTML += '<p id="nessunRisultato">Nessun altro post trovato</p>';
                }
            }
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function activateMap(id, lon, lat, label) {
    let mapContainerId = 'map' + id;
    let map = L.map(mapContainerId, {
        center: [lon, lat],
        zoom: 50
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    let heart = L.icon({
        iconUrl: '../res/MapPointer.svg',
        iconSize: [38, 38],
        iconAnchor: [19, 38],
        popupAnchor: [0, -38]
    });

    L.marker([lon, lat], { icon: heart })
        .addTo(map)
        .bindPopup(label);
}

function updatePosts() {
    startPost = 0;
    address = document.getElementById("partenza").value;
    range = document.getElementById("range").value;
    $.ajax({
        url: "../utils/manageButtons.php",
        type: "POST",
        data: {
            startId: startPost,
            address: address,
            range: range,
            action: 'morePosts'
        },
        success: function (response) {
            document.querySelector(".buttonAltriPost").remove();
            document.querySelector(".articles").innerHTML = response.articles == '' ? "<p>Nessun post trovato</p>" : response.articles + ' <button type="button" class="buttonAltriPost" onclick="morePosts()">Altri Post</button>';
            updateAllMaps(0);
            updateAllButtons(startPost);
        },
        error: function (error) {
            console.log(error);
        }
    });
}
