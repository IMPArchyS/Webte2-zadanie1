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

    echo "<main id='mainContainer'>";

    createPersonButtons();
    echo "</main>";
    include_once "footer.php";
?>

<div id="feedbackToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
        <strong id="toastInfo" class="mr-auto">Feedback</strong>
    </div>
    <div id="feedbackToastbody" class="toast-body">
        <p id="feedbackToastText"></p>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editPersonModal" tabindex="-1" role="dialog" aria-labelledby="editPersonModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPersonModalLabel">Person</h5>
            </div>
            <div class="modal-body">
            <form method="post" id="sendFormData">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="surname">Surname</label>
                        <input type="text" class="form-control" id="surname" name="surname">
                    </div>
                    <div class="form-group">
                        <label for="sex">Sex</label>
                        <input type="text" class="form-control" id="sex" name="sex">
                    </div>
                    <div class="form-group">
                        <label for="organisation">Organisation</label>
                        <input type="text" class="form-control" id="organisation" name="organisation">
                    </div>
                    <div class="form-group">
                        <label for="birth">Birth</label>
                        <input type="text" class="form-control" id="birth" name="birth">
                    </div>
                    <div class="form-group">
                        <label for="death">Death</label>
                        <input type="text" class="form-control" id="death" name="death">
                    </div>
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" class="form-control" id="country" name="country">
                    </div>
                </div>
                <div class="modal-footer">
                <button name="submit" id="pushChanges" type="submit" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="modal-close">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
<script src="../js/editor.js"></script>
</html>