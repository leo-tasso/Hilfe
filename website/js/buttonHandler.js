function toggleSalva(idPost) {
    $.ajax({
        url: "../utils/manageButtons.php",
        type: "POST",
        data: {
            id: idPost
        },
        success: function(response) {
            let button = document.getElementById("buttonSalva"+idPost);
            if(response.status == "saved"){
            button.innerHTML =  '<img class="iconButton" src="../Icons/Heart.svg" alt="">Salvato';
            }
            if(response.status == "unsaved"){
                button.innerHTML =  '<img class="iconButton" src="../Icons/HeartEmpty.svg" alt="">Salva';
                }
        },
        error: function(error) {
            console.log(error);
        }
    });
}
