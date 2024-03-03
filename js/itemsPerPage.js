$('#itemsPerPage').on('change', function () {
    // Get the new limit
    var newLimit = $(this).val();

    // Get the current URL
    var url = new URL(window.location.href);

    // Set the new limit as a query parameter
    url.searchParams.set('itemsPerPage', newLimit);

    // Reload the page with the new URL
    window.location.href = url.href;
});
