<span style="font-family: verdana, geneva, sans-serif;"><!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8" />
      <title>Forgot Password</title>
      <link rel="stylesheet" href="style.css" />
      <!-- Font Awesome Cdn Link -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
      <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0; /* Optional background color for the entire page */
        }

        .edit-user {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center; /* Center text within .edit-user */
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        form label {
            margin-bottom: 10px; /* Add margin below each label for spacing */
        }

        form input {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px; /* Add margin below each input for spacing */
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: auto; /* Adjust width for the submit button */
        }
    </style>
    </head>
    <body>

        <h1>Forgot Password</h1>

        <form method="post" action="send-password-reset.php">

            <label for="email">email</label>
            <input type="email" name="email" id="email">

            <button type="submit">Send</button>

        </form>

    </body>
    </html>