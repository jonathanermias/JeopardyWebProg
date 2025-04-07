<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$message = "";
$errors = array();
$username = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? trim($_POST['username']) : "";
    $password = isset($_POST['password']) ? trim($_POST['password']) : "";

    // Basic validation
    if (empty($username)) {
        $errors['username'] = "Username is required.";
    }
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    }

    if (empty($errors)) {
        $filename = 'users.txt';
        if (file_exists($filename)) {
            $users = file($filename, FILE_IGNORE_NEW_LINES);
            foreach ($users as $user) {
                if (strpos($user, "Username: " . $username) !== false) {
                    // Extract the hashed password from the line
                    preg_match('/Password: (.*)/', $user, $matches);
                    if (isset($matches[1]) && password_verify($password, $matches[1])) {
                        $_SESSION['username'] = $username;
                        header("Location: gameselect.html");
                        exit();
                    }
                }
            }
            $errors['login'] = "Invalid username or password.";
        } else {
            $errors['login'] = "No users registered yet.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login to Jeopardy!</title>
    <link rel="icon" href="./images/favicon.png" type="image/png">
    <style>
:root {
  --jeopardy-blue: #060ce9;
  --jeopardy-gold: #FFD700;
  --jeopardy-white: #ffffff;
  --error-color: #e74c3c;
  --success-color: #2ecc71;
  --font-family: Arial, sans-serif;
}

/* Basic page styling */
body {
  background-color: var(--jeopardy-blue);
  font-family: var(--font-family);
  margin: 0;
  padding: 20px;
  color: var(--jeopardy-white);
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
}

/* Form container styling */
form {
  width: 100%;
  max-width: 500px;
  padding: 20px;
  background: var(--jeopardy-white);
  color: var(--jeopardy-blue);
  border: 1px solid #ccc;
  border-radius: 5px;
}

/* Form header */
form h2 {
  text-align: center;
  color: var(--jeopardy-blue);
  margin-bottom: 20px;
}

/* Form rows */
.row {
  margin-bottom: 15px;
}

/* Labels */
label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

/* Input fields */
input[type="text"],
input[type="password"] {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 3px;
  font-size: 16px;
  color: var(--jeopardy-blue);
}

input[type="text"]:focus,
input[type="password"]:focus {
  border-color: var(--jeopardy-gold);
}

/* Submit button */
input[type="submit"] {
  display: block;
  width: 100%;
  padding: 10px;
  background: var(--jeopardy-gold);
  border: none;
  border-radius: 3px;
  font-size: 16px;
  color: var(--jeopardy-blue);
  cursor: pointer;
  margin-top: 10px;
}

input[type="submit"]:hover {
  background: #e6c200;
}

/* Success and error messages */
.success {
  background-color: var(--success-color);
  color: var(--jeopardy-white);
  padding: 10px;
  text-align: center;
  border-radius: 3px;
  margin-bottom: 15px;
}

.error {
  color: var(--error-color);
  font-size: 14px;
  margin-top: 5px;
}

/* Register link */
.register-link {
  text-align: center;
  margin-top: 15px;
}

.register-link a {
  color: var(--jeopardy-blue);
  text-decoration: none;
}

.register-link a:hover {
  text-decoration: underline;
}
    </style>
</head>
<body>
    <?php if (!empty($message)): ?>
        <div class="success"><?php echo $message; ?></div>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="loginForm">
        <h2>Login to Jeopardy!</h2>
        
        <?php if (isset($errors['login'])): ?>
            <div class="error"><?php echo $errors['login']; ?></div>
        <?php endif; ?>
        
        <div class="row">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>">
            <?php if (isset($errors['username'])): ?>
                <div class="error"><?php echo $errors['username']; ?></div>
            <?php endif; ?>
        </div>
        
        <div class="row">
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
            <?php if (isset($errors['password'])): ?>
                <div class="error"><?php echo $errors['password']; ?></div>
            <?php endif; ?>
        </div>
        
        <div class="row">
            <input type="submit" value="Login">
        </div>
        
        <div class="register-link">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
    </form>
</body>
</html>
