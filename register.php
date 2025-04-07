<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = "";
$errors = array();
$name = $username = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? trim($_POST['name']) : "";
    $username = isset($_POST['username']) ? trim($_POST['username']) : "";
    $password = isset($_POST['password']) ? trim($_POST['password']) : "";

    // Basic validation
    if (empty($name)) {
        $errors['name'] = "Full Name is required.";
    }
    if (empty($username)) {
        $errors['username'] = "Username is required.";
    }
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $data = "Full Name: " . $name . " | Username: " . $username . " | Password: " . $hashedPassword . "\n";
        $filename = 'users.txt';

        if (file_put_contents($filename, $data, FILE_APPEND | LOCK_EX)) {
            $message = "Registration Successful!";
            header("Location: login.php");
            exit();
        } else {
            $errors[] = "There was an error saving your data.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register for Jeopardy!</title>
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
  background: #e6c200; /* A slightly darker gold */
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

    </style>
</head>
<body>
    <?php if (!empty($message)): ?>
        <div class="success"><?php echo $message; ?></div>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="registerForm">
        <h2>Application Form</h2>
        
        <div class="row">
            <label for="name">Full Name</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>">
            <?php if (isset($errors['name'])): ?>
                <div class="error"><?php echo $errors['name']; ?></div>
            <?php endif; ?>
        </div>
        
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
            <input type="submit" value="Submit">
        </div>
    </form>
</body>
</html>