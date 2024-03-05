<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <title>Register</title>
</head>
<body>
    <?php 
        include "regHeader.php";
    ?>
    <div class="container">
        <h1>Register</h1>
        <form method="POST" action="register.php" id="loginForm">
            <div class="form-group">
                <label for="firstname">First Name</label>
                <input type="text" class="form-control" id="firstname" name="firstname" required value="<?php echo isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" class="form-control" id="lastname" name="lastname" required value="<?php echo isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <p id="userOccupied" class="text-danger d-none">Email is already in use</p>
        <p>Already registered? <a href="login.php">Login here</a></p>
        
    </div>
    <?php 
        include "footer.php";
        ?>
</body>
</html>


<?php 

require_once "functions.php";

function createUser($firstName, $lastName, $email, $hashedPassword) {
    // Database connection details
    $conn = createMySqlConnection();

    // Prepare the SQL statement
    $stmt = $conn->prepare('INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)');

    // Bind the parameters to the SQL statement
    $stmt->bind_param('ssss', $firstName, $lastName, $email, $hashedPassword);

    // Execute the SQL statement
    $stmt->execute();
}

function getUserByEmail($email) {
    // Database connection details
    $conn = createMySqlConnection();

    // Prepare the SQL statement
    $stmt = $conn->prepare('SELECT * FROM users WHERE email = ?');

    // Bind the email to the SQL statement
    $stmt->bind_param('s', $email);

    // Execute the SQL statement
    $stmt->execute();

    // Get the result of the query
    $result = $stmt->get_result();

    // Fetch the user data
    $user = $result->fetch_assoc();

    return $user;
}

session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    //header("location: php/restricted.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST["firstname"];
    $lastName = $_POST["lastname"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Fetch the user from the database
    $user = getUserByEmail($email);

    if ($user) {
        // The user exists, show an error message
        echo "
        <script>
        $(function () {
            $('#loginForm input').addClass('impError');
            $('#userOccupied').removeClass('d-none');
        });
        </script>
        ";
        $error = "User already exists with this email";
    } else {
        // The user does not exist, create the user and log them in
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        createUser($firstName, $lastName, $email, $hashedPassword);
        
        session_start();
        $_SESSION["user_id"] = $firstName;
        $_SESSION["loggedin"] = true;
        
        // Redirect to a success page
        echo "<p>SUCCESS</p>";
        //header("Location: ../index.php");
        exit;
    }
}
?>