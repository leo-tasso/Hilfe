function loadPhoto(input) {
    const file = input.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            document.getElementById('profileImage').src = e.target.result;
        };

        reader.readAsDataURL(file);
    }
}

function validateForm() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('conferma').value;

    // Check if passwords match
    if (password !== confirmPassword) {
        alert('Le password non corrispondono.');
        return false; // Prevent form submission
    }

    // Check if terms are accepted
    const termsCheckbox = document.getElementById('autorizzazione');
    if (!termsCheckbox.checked) {
        alert('È necessario accettare i termini.');
        return false; // Prevent form submission
    }

    return true;
}
