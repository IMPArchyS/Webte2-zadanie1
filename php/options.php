<?php

    function createComboBoxes($mysqli, $itemsPerPage, $selectedYear, $selectedCategory) : void {
        echo "<form method='GET'>";
        // items per page
        echo "<div class='form-group my-2'>";
        echo "<label class='my-1 impFontW font-weight-bold fs-5' for='itemsPerPage'>Počet položiek:</label>";
        echo "<select name='pagination' id='itemsPerPage' class='form-control text-light impSelect'>";
        echo "<option value='10'" . ($itemsPerPage == 10 ? " selected" : "") . ">10</option>";
        echo "<option value='20'" . ($itemsPerPage == 20 ? " selected" : "") . ">20</option>";
        echo "<option value='30'" . ($itemsPerPage == 30 ? " selected" : "") . ">30</option>";
        echo "<option value='all'" . ($itemsPerPage > 30 ? " selected" : "") . ">Všetky</option>";
        echo "</select>";
        echo "</div>";

        // years
        echo "<div class='form-group my-2'>";
        echo "<label class='my-1 impFontW font-weight-bold fs-5' for='yearComboBox'>Rok:</label>";
        echo "<select name='year' id='yearComboBox' class='form-control text-light impSelect'>";
        echo "<option value=''". ($selectedYear == '' ? " selected" : "") .">Všetky</option>";
        // Fetch and display options for years
        $sql_years = "SELECT DISTINCT year FROM prizes";
        $result_years = $mysqli->query($sql_years);
        $years = [];
        while ($row_years = $result_years->fetch_assoc()) {
            $years[] = $row_years["year"];
        }
        sort($years);

        foreach ($years as $year) {
            echo "<option class'text-light' value='$year'" . ($selectedYear == $year ? " selected" : "") . ">$year</option>";
        }
        echo "</select>";
        echo "</div>";

        // categories
        echo "<div class='form-group my-2'>";
        echo "<label class='my-1 impFontW font-weight-bold fs-5' for='categoryComboBox'>Kategória:</label>";
        echo "<select name='category' id='categoryComboBox' class='form-control text-light impSelect'>";
        echo "<option value=''". ($selectedCategory == '' ? " selected" : "") .">Všetky</option>";
        // Fetch and display options for categories
        $sql_categories = "SELECT DISTINCT category FROM prizes";
        $result_categories = $mysqli->query($sql_categories);
        while ($row_categories = $result_categories->fetch_assoc()) {
            $category = $row_categories["category"];
            echo "<option class'text-light' value='$category'" . ($selectedCategory == $category ? " selected" : "") . ">$category</option>";
        }
        echo "</select>";
        echo "</div>";

        echo "</form>";
    }

    function createTableHeader($sort, $order, $page, $itemsPerPage, $selectedYear, $selectedCategory) : void {
        echo "<thead class=''><tr id='tableHead'>";

        if ($selectedYear == "" && $selectedCategory == "") {
            echo '<th><a href="?page=' . $page . '&sort=surname&order=' . ($sort == 'surname' && $order == 'asc' ? 'desc' : 'asc') . '&itemsPerPage=' . $itemsPerPage . '">Priezvisko</a></th>';
            echo '<th><a href="?page=' . $page . '&sort=year&order=' . ($sort == 'year' && $order == 'asc' ? 'desc' : 'asc') . '&itemsPerPage=' . $itemsPerPage . '">Rok</a></th>';
            echo '<th><a href="?page=' . $page . '&sort=category&order=' . ($sort == 'category' && $order == 'asc' ? 'desc' : 'asc') . '&itemsPerPage=' . $itemsPerPage . '">Kategória</a></th>';    
        } elseif ($selectedYear == "") {
            echo '<th><a href="?page=' . $page . '&sort=surname&order=' . ($sort == 'surname' && $order == 'asc' ? 'desc' : 'asc') . '&itemsPerPage=' . $itemsPerPage . '&category=' . $selectedCategory . '">Priezvisko</a></th>';
            echo '<th><a href="?page=' . $page . '&sort=year&order=' . ($sort == 'year' && $order == 'asc' ? 'desc' : 'asc') . '&itemsPerPage=' . $itemsPerPage . '&category=' . $selectedCategory . '">Rok</a></th>';
        }
        elseif ($selectedCategory == "") {
            echo '<th><a href="?page=' . $page . '&sort=surname&order=' . ($sort == 'surname' && $order == 'asc' ? 'desc' : 'asc') . '&itemsPerPage=' . $itemsPerPage . '&year=' . $selectedYear . '">Priezvisko</a></th>';
            echo '<th><a href="?page=' . $page . '&sort=category&order=' . ($sort == 'category' && $order == 'asc' ? 'desc' : 'asc') . '&itemsPerPage=' . $itemsPerPage . '&year=' . $selectedYear . '">Kategória</a></th>';    
        }
        else 
        echo '<th><a href="?page=' . $page . '&sort=surname&order=' . ($sort == 'surname' && $order == 'asc' ? 'desc' : 'asc') . '&itemsPerPage=' . $itemsPerPage . '&year=' . $selectedYear . '&category=' . $selectedCategory . '">Priezvisko</a></th>';


        echo '<th>Organizácia</th>';
        echo '<th>Krajina</th>';
        echo "</tr></thead>";
    }

    function createLogoutButton() : void {
        echo '<li><button id="user-logout" class="impButton btn btn-primary">Odhlásiť</button> </li>';
    }
