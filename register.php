<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>JavaScript Form validation</title>
    <link rel="stylesheet" href="style.css">
    <script>
    </script>
</head>
<body>

    <form name="contactForm">
        <h2>Application Form</h2>
        <div class="row">
            <label>Full Name</label>
            <input type="text" name="name">
            <div class="error" id="nameErr"></div>
        </div>
        <div class="row">
            <label>Username</label>
            <input type="text" name="username">
            <div class="error" id="usrnmErr"></div>
        </div>

        <div class="row">
            <label>Password</label>
            <input type="password" name="password">
            <div class="error" id="passErr"></div>
        </div>

        <div class="row">
            <input type="submit" value="Submit">
        </div>
    </form>
</body>
</html>