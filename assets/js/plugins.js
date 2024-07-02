// handle the visibility of the search container
const search_plugin = document.getElementById("search-plugins");
search_plugin.addEventListener("input", function (event) {
    if (search_plugin.value.length > 0)
        document.getElementById("search-container").style.display = 'block ';
    else
        document.getElementById("search-container").style.display = 'none ';
});
// handlethe fillter container 
const filter_container = document.querySelector('.filter-container');
const filter_btn = document.getElementById('filter-plugins');

filter_btn.addEventListener('click', function () {
    if (filter_container.style.display === 'flex') {
        filter_container.style.height = '0';
        filter_container.style.display = 'none';
    } else {
        filter_container.style.display = 'flex';
        filter_container.style.height = 'auto';
    }
});