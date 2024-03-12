<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="../node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <title>Editor</title>
</head>
<body>
<?php
    session_start();

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("Location: login.php");
        exit;
    }
    
    require_once "options.php";
    require_once "functions.php";
    include_once "header.php";

?>

<div class="container impContainer">
    <form method="post" id="sendFormData">
        <h3 class="text-center impFontH">Pridať Výťaza</h3>
        
        <div id="person-details">
            <h4 class="impFontH">Osobné údaje</h4>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="my-1 impFontW font-weight-bold fs-5" for="name">*Meno</label>
                    <input type="text" class="form-control impSelect text-light" id="name" name="name">
                    <p id="nameError" class="text-danger"></p>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="my-1 impFontW font-weight-bold fs-5" for="surname">*Priezvisko</label>
                        <input type="text" class="form-control impSelect text-light" id="surname" name="surname">
                        <p id="surnameError" class="text-danger"></p>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="my-1 impFontW font-weight-bold fs-5" for="sex">*Pohlavie</label>
                        <input type="text" class="form-control impSelect text-light" id="sex" name="sex">
                        <p id="sexError" class="text-danger"></p>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="my-1 impFontW font-weight-bold fs-5" for="organisation">Organizácia</label>
                        <input type="text" class="form-control impSelect text-light" id="organisation" name="organisation">
                        <p id="organisationError" class="text-danger"></p>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="my-1 impFontW font-weight-bold fs-5" for="birth">*Narodenie</label>
                        <input type="number" class="form-control impSelect text-light" id="birth" name="birth">
                        <p id="birthError" class="text-danger"></p>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="my-1 impFontW font-weight-bold fs-5" for="death">Úmrtie</label>
                        <input type="number" class="form-control impSelect text-light" id="death" name="death">
                        <p id="deathError" class="text-danger"></p>
                    </div>
                    <div class="form-group">
                        <label class="my-1 impFontW font-weight-bold fs-5" for="country">*Krajina</label>
                        <input type="text" class="form-control impSelect text-light" id="country" name="country">
                        <p id="countryError" class="text-danger"></p>
                </div>
            </div>
        </div>
        <div id="prize-information">
            <h4 class="impFontH">Informácie o cene</h4>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="my-1 impFontW font-weight-bold fs-5" for="year">Rok</label>
                    <input type="text" class="form-control impSelect text-light" id="year" name="year">
                    <p id="yearError" class="text-danger"></p>

                </div>
                <div class="col-md-6 form-group">
                    <label class="my-1 impFontW font-weight-bold fs-5" for="category">Kategória</label>
                    <select class="form-control impSelect text-light" id="category" name="category">
                        <option value="Physics">fyzika</option>
                        <option value="Chemistry">Chémia</option>
                        <option value="Medicine">Medicína</option>
                        <option value="Literature">Literatúra</option>
                        <option value="Peace">Mier</option>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label class="my-1 impFontW font-weight-bold fs-5" for="contribution_en">Príspevok (EN)</label>
                    <textarea class="form-control impSelect text-light" id="contribution_en" name="contribution_en" style="resize: none;"></textarea>
                    <p id="contribution_enError" class="text-danger"></p>

                </div>
                <div class="col-md-6 form-group">
                    <label class="my-1 impFontW font-weight-bold fs-5" for="contribution_sk">Príspevok (SK)</label>
                    <textarea class="form-control impSelect text-light" id="contribution_sk" name="contribution_sk" style="resize: none;"></textarea>
                    <p id="contribution_skError" class="text-danger"></p>
                </div>
            </div>
        </div>
        
        <div id="prize-details" class="d-none">
            <h4 class="impFontH">Detaily ceny</h4>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="my-1 impFontW font-weight-bold fs-5" for="language_en">Jazyk (EN)</label>
                    <input type="text" class="form-control impSelect text-light" id="language_en" name="language_en">
                    <p id="language_enError" class="text-danger"></p>
                </div>
                <div class="col-md-6 form-group">
                    <label class="my-1 impFontW font-weight-bold fs-5" for="language_sk">Jazyk (SK)</label>
                    <input type="text" class="form-control impSelect text-light" id="language_sk" name="language_sk">
                    <p id="language_skError" class="text-danger"></p>
                </div>
                <div class="col-md-6 form-group">
                    <label class="my-1 impFontW font-weight-bold fs-5" for="genre_en">Žáner (EN)</label>
                    <input type="text" class="form-control impSelect text-light" id="genre_en" name="genre_en">
                    <p id="genre_enError" class="text-danger"></p>
                </div>
                <div class="col-md-6 form-group">
                    <label class="my-1 impFontW font-weight-bold fs-5" for="genre_sk">Žáner (SK)</label>
                    <input type="text" class="form-control impSelect text-light" id="genre_sk" name="genre_sk">
                    <p id="genre_skError" class="text-danger"></p>
                </div>
            </div>
        </div>
        <div class="row">
                <div class="col-12 text-center mb-4">
                    <button name="submit" id="pushChanges" type="submit" class="impGreenButton btn btn-primary btn-lg mt-3">Pridať</button>
                </div>
        </div>
</form>
</div>
<div id="feedbackToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
        <strong id="toastInfo" class="mr-auto">Feedback</strong>
    </div>
    <div id="feedbackToastbody" class="toast-body">
        <p id="feedbackToastText"></p>
    </div>
</div>
<?php
    include_once "footer.php";
?>
</body>
<script src="../js/editor.js"></script>
</html>