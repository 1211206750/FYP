    <?php

    $token = $_GET["token"];

    $token_hash = hash("sha256", $token);

    $mysqli = require __DIR__ . "/database.php";

    $sql = "SELECT * FROM form WHERE reset_token_hash = ?";

    $stmt = $mysqli->prepare($sql);

    $stmt->bind_param("s", $token_hash);

    $stmt->execute();

    $result = $stmt->get_result();

    $user = $result->fetch_assoc();

    if ($user === null) {
        die("Token not found");
    }

    if (strtotime($user["reset_token_expires_at"]) <= time()) {
        die("Token has expired");
    }

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $password = $_POST["password"];
        $password_confirmation = $_POST["password_confirmation"];

        if (strlen($password) < 8 || !preg_match("/[a-z]/i", $password) || !preg_match("/[0-9]/", $password) || $password !== $password_confirmation) {
            echo '<script>alert("Error: Password must meet the requirements and match the confirmation.");</script>';
        } else {
            // Update password without using password hash (not recommended)
            $sql = "UPDATE form
                    SET password_plain = ?,
                        reset_token_hash = NULL,
                        reset_token_expires_at = NULL
                    WHERE id = ?";

            $stmt = $mysqli->prepare($sql);

            $stmt->bind_param("si", $password, $user["id"]);

            $stmt->execute();

            echo '<script>
            alert("Success: Password updated. You can now login.");
            window.location.href = "login.php";
        </script>';
        }
    }

    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Reset Password</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
        <style>
                
                h1 {
            margin-top: 0;
            margin-bottom: 20px; /* Add margin at the bottom for spacing */
            text-align: center; /* Center the text */
        }
                body {
                    display: flex;
                    align-items: flex-start;
                    /* Align items to the start of the flex container */
                    justify-content: center;
                    min-height: 100vh;
                    margin: 0;
                    background-color: #f0f0f0;
                    /* Optional background color for the entire page */
                }

                .edit-user {
                    background-color: #fff;
                    padding: 30px;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    text-align: center;
                    /* Center text within .edit-user */
                    margin-top: 50px;
                    /* Add margin at the top to create space */
                }

                form {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                }

                form label {
                    margin-bottom: 10px;
                }

                form input {
                    width: 100%;
                    padding: 8px;
                    margin-bottom: 20px;
                    box-sizing: border-box;
                }

                input[type="submit"] {
                    width: auto;
                }

                /* Additional styles for the h1 element */
                h1 {
                    margin-top: 0;
                    /* Remove default margin at the top of the h1 element */
                }
            </style>
    </head>

    <body>

        <div class="edit-user">

            <form method="post" action="">

                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                <label for="password">New Password</label>
                <input type="password" id="password" name="password">

                <label for="password_confirmation">Repeat Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation">

                <button type="submit">Send</button>
            </form>

        </div>

    </body>

    </html>
