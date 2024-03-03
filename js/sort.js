$(function () {
    var sortOrderSurname = 1; // 1 for ascending, -1 for descending
    var sortOrderYear = 1; // 1 for ascending, -1 for descending
    var sortOrderCategory = 1; // 1 for ascending, -1 for descending

    // Listen for click event on surnameSort
    $('#surnameSort').on('click', function (e) {
        e.preventDefault();
        // Toggle the sort order
        sortOrderSurname = -sortOrderSurname;

        // Get the table body element
        var tableBody = $('#nobelStuff tbody');

        // Get all the table rows except the table head row
        var rows = tableBody.find('tr');

        // Sort the rows by surname
        var sortedRows = rows.sort(function (a, b) {
            var surnameA = $(a).find('.surname').text().toUpperCase();
            var surnameB = $(b).find('.surname').text().toUpperCase();
            return surnameA.localeCompare(surnameB) * sortOrderSurname;
        });

        // Append the sorted rows back to the table body
        tableBody.append(sortedRows);
    });

    // Listen for click event on yearSort
    $('#yearSort').on('click', function (e) {
        e.preventDefault();
        // Toggle the sort order
        sortOrderYear = -sortOrderYear;

        // Get the table body element
        var tableBody = $('#nobelStuff tbody');

        // Get all the table rows except the table head row
        var rows = tableBody.find('tr');

        // Sort the rows by year
        var sortedRows = rows.sort(function (a, b) {
            var yearA = parseInt($(a).children().eq(1).text(), 10);
            var yearB = parseInt($(b).children().eq(1).text(), 10);
            return (yearA - yearB) * sortOrderYear;
        });

        // Append the sorted rows back to the table body
        tableBody.append(sortedRows);
    });

    // Listen for click event on categorySort
    $('#categorySort').on('click', function (e) {
        e.preventDefault();
        // Toggle the sort order
        sortOrderCategory = -sortOrderCategory;

        // Get the table body element
        var tableBody = $('#nobelStuff tbody');

        // Get all the table rows except the table head row
        var rows = tableBody.find('tr');

        // Sort the rows by category
        var sortedRows = rows.sort(function (a, b) {
            var categoryA = $(a).children().eq(2).text().toUpperCase();
            var categoryB = $(b).children().eq(2).text().toUpperCase();
            return categoryA.localeCompare(categoryB) * sortOrderCategory;
        });

        // Append the sorted rows back to the table body
        tableBody.append(sortedRows);
    });
});
