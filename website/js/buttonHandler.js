let address = "";
let startPost = 0;
let range = 100;

function updateEverything() {
    updateJustButtons();
    updateAllMaps(0);
}
function updateJustButtons() {
    updateAllButtons(0);
    updateAllInfoButtons(0);
    setTimeout(updateJustButtons, 2000);
}

window.onload = updateEverything;
function updateAllButtons(startButton) {
    let articles = document.querySelectorAll(".HelpPost");
    articles.forEach(article => {
        let articleParams = article.id.split(',');
        if (articleParams[0] > startButton) {
            updateButtons(articleParams[0], articleParams[1].replace(/\s/g, ''));
        }
    });
}
function updateAllInfoButtons(startButton) {
    let articles = document.querySelectorAll(".InfoPost");
    articles.forEach(article => {
        let PostId = parseInt(article.id.replace('comunicazione', ''), 10);
        if (PostId > startButton) {
            updateLikeButton(PostId);
            updateComments(PostId);
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

function updateLikeButton(idPost) {
    $.ajax({
        url: "../utils/manageButtons.php",
        type: "POST",
        data: {
            id: idPost,
            action: 'updateLike'
        },
        success: function (response) {
            let likeButton = document.getElementById("buttonMiPiace" + idPost);
            if (response.statusLike == "liking") {
                likeButton.innerHTML = '<img class="iconButton" src="../Icons/Heart.svg" alt="">Non mi piace più'
            }
            if (response.statusLike == "notLiking") {
                likeButton.innerHTML = '<img class="iconButton" src="../Icons/HeartEmpty.svg" alt="">Mi Piace'
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

function toggleLike(idPost) {
    $.ajax({
        url: "../utils/manageButtons.php",
        type: "POST",
        data: {
            id: idPost,
            action: 'like'
        },
        success: function (response) {
            updateLikeButton(idPost);
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

function toggleFollow(idUser) {
    $.ajax({
        url: "../utils/manageButtons.php",
        type: "POST",
        data: {
            id: idUser,
            action: 'follow'
        },
        success: function (response) {
            updateFollow(response);
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

function updateComments(idArticle) {
    let commentList = document.querySelector("#comunicazione" + idArticle + " .commenti");
    $.ajax({
        url: "../utils/manageButtons.php",
        type: "POST",
        data: {
            id: idArticle,
            action: 'getComments',
        },
        success: function (response) {
            commentList.innerHTML = response.comments;
        },
        error: function (error) {
            console.log(error);
        }
    });

}

function publish(idArticle) {
    $.ajax({
        url: "../utils/manageButtons.php",
        type: "POST",
        data: {
            id: idArticle,
            action: 'postComment',
            comment: document.getElementById('commenta' + idArticle).value
        },
        success: function (response) {
            updateComments(idArticle);
        },
        error: function (error) {
            console.log(error);
        }
    });
    document.getElementById('commenta' + idArticle).value = "";
}

function deleteHelpPost(idPost){
    $.ajax({
        url: "../utils/manageButtons.php",
        type: "POST",
        data: {
            id: idPost,
            action: 'deleteHelpPost',
        },
        success: function (response) {
            window.location.href = "../index.php";
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function deleteUser(idUser){
    $.ajax({
        url: "../utils/manageButtons.php",
        type: "POST",
        data: {
            id: idUser,
            action: 'deleteUser',
        },
        success: function (response) {
            logout();
            window.location.href = "../index.php";
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function deleteInfoPost(idPost){
    $.ajax({
        url: "../utils/manageButtons.php",
        type: "POST",
        data: {
            id: idPost,
            action: 'deleteInfoPost',
        },
        success: function (response) {
            window.location.href = "../index.php";
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function deleteComment(idComment) {
    $.ajax({
        url: "../utils/manageButtons.php",
        type: "POST",
        data: {
            id: idComment,
            action: 'deleteComment',
        },
        success: function (response) {
            updateAllInfoButtons(0);
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function moreInfoPosts() {
    startPost = parseInt(document.querySelector('article:last-of-type').id.replace('comunicazione', ''), 10);

    $.ajax({
        url: "../utils/manageButtons.php",
        type: "POST",
        data: {
            startId: startPost,
            action: 'moreInfoPosts'
        },
        success: function (response) {
            if (response.articles != "") {
                document.querySelector(".buttonAltriPost").remove();
                document.querySelector(".articles").innerHTML += response.articles + ' <button type="button" class="buttonAltriPost" onclick="moreInfoPosts()">Altri Post</button>';
                updateAllInfoButtons(startPost);
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

function logout() {
    $.ajax({
        url: "../utils/manageButtons.php",
        type: "POST",
        data: {
            action: 'logout'
        },
        success: function (response) {
            window.location.href = "/index.php";
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
    if(address===null||address==""){
        address = document.getElementById("partenza").placeholder;
    }
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
            let altriPostButton = document.querySelector(".buttonAltriPost");
            altriPostButton !== null ? altriPostButton.remove() : {};
            document.querySelector(".articles").innerHTML = response.articles == '' ? "<p>Nessun post trovato</p>" : response.articles + ' <button type="button" class="buttonAltriPost" onclick="morePosts()">Altri Post</button>';
            updateAllMaps(0);
            updateAllButtons(startPost);
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function toLoginPage() {
    window.location.href = "../login.php";
}

function toProfileEditPage() {
    window.location.href = "../profileEdit.php";
}

function toHomePage() {
    window.location.href = "../index.php";
}

function updateFollow(result) {
    let button = document.getElementsByClassName("segui")[0];
    if (result.status == "follows") {
        button.classList.add("seguito");
        button.value = "Seguito"
    }
    else {
        button.classList.remove("seguito");
        button.value = "Segui"
    }
    document.getElementById("followersCount").innerHTML = result.counter;
}
function publishOnEnter(event, postId) {
    if (event.key === 'Enter') {
        event.preventDefault(); // Prevent the default form submission
        document.getElementById('publishButton' + postId).click();
    }
}