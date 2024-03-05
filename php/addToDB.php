<?php
function insertPersonIntoDB($name, $surname, $organisation, $sex, $birth, $death, $countryName) {
    // Check if the country exists in the database
    $countryId = getCountryIdByName($countryName);
    if ($countryId === false) {
        // Create a new country in the countries table
        $countryId = createCountry($countryName);
    }
    $conn = createMySqlConnection();

    $countryId = getCountryIdByName($countryName);
    if ($countryId === false) {
        // Create a new country in the countries table
        $countryId = createCountry($countryName);
    }

    // Check if the person already exists in the database
    if (personExists($name, $surname, $birth)) {
        return "Person already exists in the database.";
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO persons (name, surname, organisation, sex, birth, death, country_id) VALUES (?, ?, ?, ?, ?, ?, ?)");

    // Bind the parameters to the statement
    $stmt->bind_param("sssssss", $name, $surname, $organisation, $sex, $birth, $death, $countryId);

    // Execute the statement
    if ($stmt->execute()) {
        // Close the statement and the connection
        $stmt->close();
        $conn->close();

        return "Person inserted successfully.";
    } else {
        // Close the statement and the connection
        $stmt->close();
        $conn->close();

        return "Failed to insert person into the database: " . $stmt->error;
    }
}

// Function to get the country id by name
function getCountryIdByName($countryName) {
        $conn = createMySqlConnection();

        // Prepare the SQL statement
        $stmt = $conn->prepare("SELECT id FROM countries WHERE name = ?");

        // Bind the parameter to the statement
        $stmt->bind_param("s", $countryName);

        // Execute the statement
        $stmt->execute();

        // Bind the result to a variable
        $stmt->bind_result($countryId);

        // Fetch the result
        $stmt->fetch();

        // Close the statement and the connection
        $stmt->close();
        $conn->close();

        // Return the country id if found, false otherwise
        return $countryId ? $countryId : false;
}

// Function to create a new country in the countries table
function createCountry($countryName) {
    $conn = createMySqlConnection();

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO countries (name) VALUES (?)");

    // Bind the parameter to the statement
    $stmt->bind_param("s", $countryName);

    // Execute the statement
    if ($stmt->execute()) {
        // Close the statement and the connection
        $stmt->close();
        $conn->close();

        return $stmt->insert_id;
    } else {
        // Close the statement and the connection
        $stmt->close();
        $conn->close();

        return false;
    }
}

// Function to check if a person exists in the database
function personExists($name, $surname, $birth) {
    $conn = createMySqlConnection();

    // Check if the person exists in the database
    $stmt = $conn->prepare("SELECT COUNT(*) FROM persons WHERE name = ? AND surname = ? AND birth = ?");
    $stmt->bind_param("sss", $name, $surname, $birth);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    // Return true if the person exists, false otherwise
    if ($count > 0) {
        return true;
    } else {
        return false;
    }
    return false;
}