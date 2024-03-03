$('#categoryComboBox').on('change', function () {
    // Get the new limit
    var newLimit = $(this).val();

    // Get the current URL
    var url = new URL(window.location.href);

    // Remove sorting and ordering parameters from the URL
    url.searchParams.delete('page');
    url.searchParams.delete('sort');
    url.searchParams.delete('order');

    // Set the new limit as a query parameter
    url.searchParams.set('category', newLimit);

    // Reload the page with the new URL
    window.location.href = url.href;
});
