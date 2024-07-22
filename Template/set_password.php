 <!-- php code  -->
 <?php
    require '../Configuration/config.php';

    // code to update the password of the user
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pwd-update-btn'])) {
        $new_pwd = $_POST['new-password'];
        $new_pwd_confirm = $_POST['confirm-password'];

        if ($new_pwd !== $new_pwd_confirm) {
            echo "<script> alert('The password doesn\'t match')</script>";
            exit();
        }

        //database connection 
        $conn = new PDO("mysql:host=$host; dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Storing the data to the server
        try {
            $conn->beginTransaction();

            $new_hash = password_hash($new_pwd_confirm, PASSWORD_DEFAULT);
            $query = "UPDATE credentials SET Hash = :NEW_HASH";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(':NEW_HASH', $new_hash);

            // Execute the statement
            $stmt->execute();

            $conn->commit();

            // Redirect the user back to the login page
            echo "<script>  alert('Password Updated');</script>";
            exit();
        } catch (PDOException $e) {
            // Error message
            echo "<script>
    alert('Sorry ! Password Reset Failed');
</script>";
            echo $e->getMessage();
            $conn->rollBack();
        }
    }
    ?>

 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="../Assets/CSS/home-page-ui.css">
     <style>
    

         .container {
             background-color: #ffffff;
             padding: 20px;
             max-width: 400px;
             width: 100%;
             border: 1px solid #dddddd;
             box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
             border-radius: 5px;
         }

         h2 {
             text-align: center;
             color: #333333;
         }

         form {
             display: flex;
             flex-direction: column;
         }

         label {
             margin-bottom: 5px;
             color: #555555;
         }

         input[type="password"] {
             padding: 10px;
             margin-bottom: 15px;
             border: 1px solid #dddddd;
             border-radius: 5px;
             font-size: 16px;
         }

         input[type="submit"] {
             padding: 10px;
             background-color: #007BFF;
             color: #ffffff;
             border: none;
             border-radius: 5px;
             font-size: 16px;
             cursor: pointer;
         }

         input[type="submit"]:hover {
             background-color: #0056b3;
         }

         .error {
             color: red;
             margin-bottom: 10px;
             text-align: center;
         }
     </style>
     <title>Password Manager</title>
 </head>

 <body>

     <!-- home page  -->
     <ul class="nav nav-tabs">
         <li class="nav-item">
             <a class="nav-link active" aria-current="page" href="#"> Home </a>
         </li>

         <li class="nav-item">
             <a class="nav-link" href="password_vault.php">Password Vault </a>
         </li>

         <li class="nav-item">
             <a class="nav-link" href="set_password.php">Password Update </a>
         </li>
     </ul>

     <div class="container">
         <h2>Enter New Password </h2>
         <form method="POST" onsubmit="return validateForm()">
             <div class="error" id="error-message"></div>
             <label for="new-password">New Password</label>
             <input type="password" id="new-password" name="new-password" required>
             <label for="confirm-password">Confirm Password</label>
             <input type="password" id="confirm-password" name="confirm-password" required>
             <input type="submit" name="pwd-reset-btn" value="Reset Password">
         </form>
     </div>

     <script>
         function validateForm() {
             var newPassword = document.getElementById("new-password").value;
             var confirmPassword = document.getElementById("confirm-password").value;
             var errorMessage = document.getElementById("error-message");

             if (newPassword !== confirmPassword) {
                 errorMessage.textContent = "Passwords do not match.";
                 return false;
             }

             if (newPassword.length < 8) {
                 errorMessage.textContent = "Password must be at least 8 characters long.";
                 return false;
             }

             return true;
         }
     </script>

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
 </body>

 </html>