<?php
    session_start();
    require_once "options.php";
    require_once "functions.php";
    require_once "../config.php";

    // Create connection
    $conn = fnc\createMySqlConnection($dbconfig);

    $surname = isset($_GET['surname']) ? $_GET['surname'] : '';

    // Prepare and bind
    $stmt = $conn->prepare("SELECT persons.id as personID, persons.*, countries.name as countryName, prizes.*, prize_details.* 
                            FROM persons 
                            LEFT JOIN countries ON persons.country_id = countries.id 
                            LEFT JOIN prizes ON persons.id = prizes.person_id
                            LEFT JOIN prize_details ON prizes.prize_detail_id = prize_details.id 
                            WHERE surname = ?");
    
    $stmt->bind_param("s", $surname);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Fetch the data
    $row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="../node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <title><?php echo isset($row['surname']) ? $row['surname'] : 'Nobel Winner'; ?></title>
</head>
<body>

<?php
    include_once "header.php";

    $personId = $row['personID'];

    if ($row) {
        // Display the fetched data
        echo '<div class="container impContainer">';
        echo '<h4>Person Details</h4>';
        echo '<div class="row">';
        echo '<div class="col-md-6">';
        echo '<p><strong>Name:</strong> ' . $row['name'] . '</p>';
        echo '<p><strong>Surname:</strong> ' . $row['surname'] . '</p>';
        echo '<p><strong>Sex:</strong> ' . $row['sex'] . '</p>';
        echo '<p><strong>Organisation:</strong> ' . $row['organisation'] . '</p>';
        echo '<p><strong>Contribution (English):</strong> ' . $row['contribution_en'] . '</p>';
        echo '<p><strong>Contribution (Slovak):</strong> ' . $row['contribution_sk'] . '</p>';
        echo '</div>';
        echo '<div class="col-md-6">';
        echo '<p><strong>Birth:</strong> ' . $row['birth'] . '</p>';
        echo '<p><strong>Death:</strong> ' . $row['death'] . '</p>';
        echo '<p><strong>Country:</strong> ' . $row['countryName'] . '</p>';
        echo '<p><strong>Year:</strong> ' . $row['year'] . '</p>';
        echo '<p><strong>Category:</strong> ' . $row['category'] . '</p>';
        echo '</div>';
        echo '</div>';
    }

    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        echo "<form id='sendFormData' action='post' class=''>";
        echo "<button id='user-delete-person' class='impRedButton btn btn-danger mb-3' data-person-id=" . $personId . ">Vymazať Výťaza</button>";
        echo "</form>";
        echo '<button id="user-edit-person" class="impGreenButton btn btn-primary mb-3">Upraviť Informácie</button>';

    
        echo '
                <div class="container d-none" id="editPersonContainer">
            <form method="post" id="editFormData">
                <h3 class="text-center">Edit Nobel Prize Winner</h3>
                
                <div id="person-details">
                    <h4>Osobné údaje</h4>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="name">Meno</label>
                            <input type="text" class="form-control" id="name" name="name">
                            <p id="nameError" class="text-danger"></p>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="surname">Priezvisko</label>
                                <input type="text" class="form-control" id="surname" name="surname">
                                <p id="surnameError" class="text-danger"></p>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="sex">Pohlavie</label>
                                <input type="text" class="form-control" id="sex" name="sex">
                                <p id="sexError" class="text-danger"></p>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="organisation">Organizácia</label>
                                <input type="text" class="form-control" id="organisation" name="organisation">
                                <p id="organisationError" class="text-danger"></p>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="birth">Narodenie</label>
                                <input type="number" class="form-control" id="birth" name="birth">
                                <p id="birthError" class="text-danger"></p>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="death">Úmrtie</label>
                                <input type="number" class="form-control" id="death" name="death">
                                <p id="deathError" class="text-danger"></p>
                            </div>
                            <div class="form-group">
                                <label for="country">Krajina</label>
                                <input type="text" class="form-control" id="country" name="country">
                                <p id="countryError" class="text-danger"></p>
                        </div>
                    </div>
                </div>
                <div id="prize-information">
                    <h4>Informácie o cene</h4>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="year">Rok</label>
                            <input type="text" class="form-control" id="year" name="year">
                            <p id="yearError" class="text-danger"></p>

                        </div>
                        <div class="col-md-6 form-group">
                            <label for="category">Kategória</label>
                            <select class="form-control" id="category" name="category">
                                <option value="Physics">Fyzika</option>
                                <option value="Chemistry">Chémia</option>
                                <option value="Medicine">Medicína</option>
                                <option value="Literature">Literatúra</option>
                                <option value="Peace">Mier</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="contribution_en">Príspevok (EN)</label>
                            <textarea class="form-control" id="contribution_en" name="contribution_en" style="resize: none;"></textarea>
                            <p id="contribution_enError" class="text-danger"></p>

                        </div>
                        <div class="col-md-6 form-group">
                            <label for="contribution_sk">Príspevok (SK)</label>
                            <textarea class="form-control" id="contribution_sk" name="contribution_sk" style="resize: none;"></textarea>
                            <p id="contribution_skError" class="text-danger"></p>
                        </div>
                    </div>
                </div>
                
                <div id="prize-details" class="d-none">
                    <h4>Detaily Ceny</h4>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="language_en">Jazyk (EN)</label>
                            <input type="text" class="form-control" id="language_en" name="language_en">
                            <p id="language_enError" class="text-danger"></p>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="language_sk">Jazyk (SK)</label>
                            <input type="text" class="form-control" id="language_sk" name="language_sk">
                            <p id="language_skError" class="text-danger"></p>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="genre_en">Žáner (EN)</label>
                            <input type="text" class="form-control" id="genre_en" name="genre_en">
                            <p id="genre_enError" class="text-danger"></p>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="genre_sk">Žáner (SK)</label>
                            <input type="text" class="form-control" id="genre_sk" name="genre_sk">
                            <p id="genre_skError" class="text-danger"></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                        <div class="col-12 text-center mb-4">
                            <button name="submit" id="pushChanges" type="submit" class="btn btn-primary btn-lg mt-3">Modifikovať</button>
                        </div>
                </div>
        </form>
        </div>
        ';
    }
    echo '</div>';
    include_once "footer.php";

    $stmt->close();
    $conn->close();

    echo "<script>const personId = '$personId';</script>";
?>
<div id="feedbackToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
        <strong id="toastInfo" class="mr-auto">Feedback</strong>
    </div>
    <div id="feedbackToastbody" class="toast-body">
        <p id="feedbackToastText"></p>
    </div>
</div>  
</body>
<script src="../js/person.js"></script>
<script src="../js/editPerson.js"></script>
<script>
    $("#user-logout").on("click", function () {
        window.location.href = "logout.php";
    });
</script>
</html>
