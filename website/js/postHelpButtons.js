function increment() {
    var numeroInput = document.getElementById('numero');
    var currentVal = parseInt(numeroInput.value);
    if (!isNaN(currentVal)) {
        numeroInput.value = currentVal + 1;
    }
}

function decrement() {
    var numeroInput = document.getElementById('numero');
    var currentVal = parseInt(numeroInput.value);
    if (!isNaN(currentVal) && currentVal > 1) {
        numeroInput.value = currentVal - 1;
    }
}
function addMaterial() {
    let newId = getLastNumber() + 1;
    var newElement = document.createElement("div");
    newElement.innerHTML = '<label for= "oggetto' + newId + '" hidden> Oggetto </label> <input type="text" required  class="oggetto" id="oggetto' + newId + '" name="oggetto[' + newId + ']" placeholder="Oggetto" /> <label for="quantita" hidden>Quantità</label> <input type="number" required class="quantita" id="quantita' + newId + '" name="quantita[' + newId + ']" min="0" max="99" value="1" onchange="checkVariation(this)"/>';
    newElement.classList.add("materiale");
    var container = document.querySelector('.colonna2');
    var aggiungiButton = document.querySelector('.aggiungi');
    container.insertBefore(newElement, aggiungiButton);
}
function checkVariation(input) {
    var container = input.parentElement;
    if (parseInt(input.value) === 0) {
        container.remove();
    }
}
function getLastNumber() {
    var elements = document.querySelectorAll('[id^="oggetto"]');
    if (elements.length > 0) {
        var lastElement = elements[elements.length - 1];
        var lastElementId = lastElement.id;
        var lastNumber = parseInt(lastElementId.replace('oggetto', ''), 10);
        if (!isNaN(lastNumber)) {
            return lastNumber;
        }
        else {
            return 0
        }
    }
    return 0;
}