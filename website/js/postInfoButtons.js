function loadPhoto(input) {
    const file = input.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            document.getElementById('postPhoto').src = e.target.result;
        };

        reader.readAsDataURL(file);
    }
}
