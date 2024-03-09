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

<div id="feedbackToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
        <strong id="toastInfo" class="mr-auto">Feedback</strong>
    </div>
    <div id="feedbackToastbody" class="toast-body">
        <p id="feedbackToastText"></p>
    </div>
</div>

<div class="container">
    <form method="post" id="sendFormData">
        <h3 class="text-center">Add Nobel Prize Winner</h3>
        
        <div id="person-details">
            <h4>Person Information</h4>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name">
                    <p id="nameError" class="text-danger"></p>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="surname">Surname</label>
                        <input type="text" class="form-control" id="surname" name="surname">
                        <p id="surnameError" class="text-danger"></p>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="sex">Sex</label>
                        <input type="text" class="form-control" id="sex" name="sex">
                        <p id="sexError" class="text-danger"></p>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="organisation">Organisation</label>
                        <input type="text" class="form-control" id="organisation" name="organisation">
                        <p id="organisationError" class="text-danger"></p>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="birth">Birth</label>
                        <input type="number" class="form-control" id="birth" name="birth">
                        <p id="birthError" class="text-danger"></p>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="death">Death</label>
                        <input type="number" class="form-control" id="death" name="death">
                        <p id="deathError" class="text-danger"></p>
                    </div>
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" class="form-control" id="country" name="country">
                        <p id="countryError" class="text-danger"></p>
                </div>
            </div>
        </div>
        <div id="prize-information">
            <h4>Prize Information</h4>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="year">Year</label>
                    <input type="text" class="form-control" id="year" name="year">
                    <p id="yearError" class="text-danger"></p>

                </div>
                <div class="col-md-6 form-group">
                    <label for="category">Category</label>
                    <select class="form-control" id="category" name="category">
                        <option value="Physics">Physics</option>
                        <option value="Chemistry">Chemistry</option>
                        <option value="Medicine">Medicine</option>
                        <option value="Literature">Literature</option>
                        <option value="Peace">Peace</option>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label for="contribution_en">Contribution (EN)</label>
                    <textarea class="form-control" id="contribution_en" name="contribution_en" style="resize: none;"></textarea>
                    <p id="contribution_enError" class="text-danger"></p>

                </div>
                <div class="col-md-6 form-group">
                    <label for="contribution_sk">Contribution (SK)</label>
                    <textarea class="form-control" id="contribution_sk" name="contribution_sk" style="resize: none;"></textarea>
                    <p id="contribution_skError" class="text-danger"></p>
                </div>
            </div>
        </div>
        
        <div id="prize-details" class="d-none">
            <h4>Prize Details</h4>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="language_en">Language (EN)</label>
                    <input type="text" class="form-control" id="language_en" name="language_en">
                    <p id="language_enError" class="text-danger"></p>
                </div>
                <div class="col-md-6 form-group">
                    <label for="language_sk">Language (SK)</label>
                    <input type="text" class="form-control" id="language_sk" name="language_sk">
                    <p id="language_skError" class="text-danger"></p>
                </div>
                <div class="col-md-6 form-group">
                    <label for="genre_en">Genre (EN)</label>
                    <input type="text" class="form-control" id="genre_en" name="genre_en">
                    <p id="genre_enError" class="text-danger"></p>
                </div>
                <div class="col-md-6 form-group">
                    <label for="genre_sk">Genre (SK)</label>
                    <input type="text" class="form-control" id="genre_sk" name="genre_sk">
                    <p id="genre_skError" class="text-danger"></p>
                </div>
            </div>
        </div>
        <div class="row">
                <div class="col-12 text-center mb-4">
                    <button name="submit" id="pushChanges" type="submit" class="btn btn-primary btn-lg mt-3">Save changes</button>
                </div>
        </div>
</form>
</div>
<?php
    include_once "footer.php";
?>
</body>
<script src="../js/editor.js"></script>
</html>