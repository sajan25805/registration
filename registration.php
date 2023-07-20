<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>
    <?php
        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $name = $_POST["name"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $confirm_password = $_POST["confirm_password"];

            // Validate name
            if (empty($name)) {
                $errors["name"] = "Name is required";
            }

            // Validate email
            if (empty($email)) {
                $errors["email"] = "Email is required";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors["email"] = "Invalid email format";
            }

            // Validate password
            if (empty($password)) {
                $errors["password"] = "Password is required";
            } elseif (strlen($password) < 6) {
                $errors["password"] = "Password should be at least 6 characters long";
            }

            // Validate confirm password
            if (empty($confirm_password)) {
                $errors["confirm_password"] = "Confirm password is required";
            } elseif ($password !== $confirm_password) {
                $errors["confirm_password"] = "Passwords do not match";
            }

            if (empty($errors)) {
                // Read existing user data from users.json
                $userData = file_get_contents("users.json");
                $users = json_decode($userData, true);

                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Create a new user array
                $newUser = [
                    "name" => $name,
                    "email" => $email,
                    "password" => $hashedPassword
                ];

                // Add the new user to the array
                $users[] = $newUser;

                // Write the updated array back to users.json
                $jsonData = json_encode($users, JSON_PRETTY_PRINT);
                if (file_put_contents("users.json", $jsonData) === false) {
                    echo "Error writing to users.json";
                } else {
                    echo "<div style='color: green;'>Registration successful!</div>";
                }
            }
        }
    ?>


    <h2>User Registration</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name">
        <br><br>
        
        <label for="email">Email:</label>
        <input type="email" name="email" id="email">
        <br><br>
        
        <label for="password">Password:</label>
        <input type="password" name="password" id="password">
        <br><br>
        
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" id="confirm_password">
        <br><br>
        
        <input type="submit" value="Register">
    </form>
    <?php if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($errors)): ?>
        <div style="color: red;">Please fix the following errors:</div>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li style="color: red;"><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>
