document.addEventListener('DOMContentLoaded', function () {
    const rangeInput = document.getElementById('range');
    const rangeValue = document.getElementById('rangeValue');

    rangeInput.addEventListener('input', function () {
        rangeValue.textContent = rangeInput.value == 100 ? "Illimit." : rangeInput.value + "km";
    });
});