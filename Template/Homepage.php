<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/CSS/home-page-ui.css">
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

    <!-- main container  -->
    <div class="main-container">

        <!-- show the alert to the user  -->
        <?php
        // get the success status 
        $success = '';
        if (isset($_GET['success'])) {
            $success = $_GET['success'];

            if ($success) {
                // success 
                echo "  <div class='alert d-flex align-items-center alert-success dismissible fade show' role='alert'>
                        <span class='material-symbols-sharp'> check_circle </span> 
                        <strong> &nbsp;Congrats ! </strong> &nbsp; Password Vault updated Successfully 
                        <button type='button' class='btn-close w3-margin-left' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div> ";
            } else {
                // failure 
                echo "
                    <div class='alert d-flex align-items-center alert-danger dismissible fade show' role='alert'>
                            <span class='material-symbols-sharp'> cancel </span>  
                            <strong> &nbsp; Sorry ! </strong> &nbsp; Unable to Update the Vault. Try Again 
                            <button type='button' class='btn-close w3-margin-left' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>
            ";
            }
        }
        ?>


        <div class="container">
            <div class="form-content">
                <form name="form" method="post" action="../Template/registerData.php">
                    <label for="name">Name of the Website: <span class="danger">*</span></label>
                    <input type="text" name="name" class="w3-input" required>

                    <label for="name">Username : <span class="danger">*</span></label>
                    <input type="text" name="username" class="w3-input" required>

                    <label for="password">Password: <span class="danger">*</span></label>
                    <input type="text" name="password" class="w3-input" required>

                    <label for="password">Master Password: <span class="danger">*</span></label>
                    <input type="text" name="masterpwd" class="w3-input" required>

                    <label for="notes">Notes: [Optional]</label>
                    <textarea name="notes" class="w3-input"></textarea> <!-- Fixed required attribute -->

                    <input type="submit" class="button" name="btn">
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>