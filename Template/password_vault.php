<?php
include 'retrieve_password.php';
?>

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
            <a class="nav-link" aria-current="page" href="Homepage.php"> Home </a>
        </li>

        <li class="nav-item ">
            <a class="nav-link active" href="password_vault.php">Password Vault </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="set_password.php">Password Update </a>
        </li>
    </ul>
    <div class="container-fluid">
        <div class="heading">
            <p> My Password Vault </p>
        </div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Website Name</th>
                    <th scope="col">Website Password</th>
                    <th scope="col">Website Note</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($userdata as $index => $d) : ?>
                    <tr data-id="<?php echo $d['Id']; ?>">
                        <th scope="row"><?php echo $d['Id']; ?> </th>
                        <td><?php echo $d['Name']; ?></td>
                        <td>
                            <span class="password-hash" style="display: none;"><?php echo $d['Hash']; ?></span>
                            <span class="password-asterisks">**********</span>
                        </td>
                        <td><?php echo $d['Note']; ?></td>
                        <td>
                            <span id='viewPwd-<?php echo $index; ?>' class="viewPwd material-symbols-outlined icon"> visibility </span>
                            &nbsp; &nbsp;
                            <span class="deletePwd material-symbols-outlined icon"> delete </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let viewBtns = document.querySelectorAll('.viewPwd');
            let deleteBtns = document.querySelectorAll('.deletePwd');

            viewBtns.forEach(viewBtn => {
                viewBtn.addEventListener('click', () => {
                    let row = viewBtn.closest('tr');
                    let passwordHashSpan = row.querySelector('.password-hash');
                    let passwordAsterisksSpan = row.querySelector('.password-asterisks');

                    if (viewBtn.textContent.trim() === "visibility") {
                        let masterPassword = prompt("Enter the master password:");

                        if (masterPassword) {
                            fetch('decrypt_password.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        masterPassword: masterPassword,
                                        hash: passwordHashSpan.textContent.trim()
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        passwordAsterisksSpan.textContent = data.decryptedPassword;
                                        viewBtn.textContent = "visibility_off";
                                    } else {
                                        alert("Decryption failed: " + data.message);
                                    }
                                })
                                .catch(error => {
                                    console.error("Error:", error);
                                    alert("An error occurred while decrypting the password.");
                                });
                        }
                    } else {
                        viewBtn.textContent = "visibility";
                        passwordAsterisksSpan.textContent = '**********';
                    }
                });
            });

            deleteBtns.forEach(deleteBtn => {
                deleteBtn.addEventListener('click', () => {
                    let row = deleteBtn.closest('tr');
                    let id = row.getAttribute('data-id');

                    if (confirm("Are you sure you want to delete this password?")) {
                        fetch('delete_password.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    id: id
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    row.remove();
                                    alert("Password deleted successfully.");
                                } else {
                                    alert("Deletion failed: " + data.message);
                                }
                            })
                            .catch(error => {
                                console.error("Error:", error);
                                alert("An error occurred while deleting the password.");
                            });
                    }
                });
            });
        });
    </script>
</body>

</html>