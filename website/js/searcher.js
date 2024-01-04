document.addEventListener('DOMContentLoaded', function () {
    // Get the input and list elements
    const searchInput = document.getElementById('search');
    const itemList = document.getElementById('elencoFollowers_seguiti');
    const items = itemList.getElementsByTagName('li');

    searchInput.addEventListener('input', function () {
      const searchTerm = searchInput.value.toLowerCase();

      for (let i = 0; i < items.length; i++) {
        const itemText = items[i].innerText.toLowerCase();
        if (itemText.includes(searchTerm)) {
          items[i].style.display = 'block';
        } else {
          items[i].style.display = 'none';
        }
      }
    });
  });