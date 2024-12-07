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
    <ul class="nav nav-tabs pwd-vault-ui">
        <li class="nav-item">
            <a class="nav-link" aria-current="page" href="Homepage.php"> Home </a>
        </li>

        <li class="nav-item ">
            <a class="nav-link active" href="password_vault.php">Password Vault </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="set_password.php">Password Update </a>
        </li>

        <!-- search button  -->

        <div class="flex px-4 py-3 rounded-md border-2 border-blue-500 overflow-hidden max-w-md mx-auto font-[sans-serif]">
            <input type="email" placeholder="Search Something..."
                class="w-full outline-none bg-transparent text-gray-600 text-sm" />
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192.904 192.904" width="16px" class="fill-gray-600">
                <path
                    d="m190.707 180.101-47.078-47.077c11.702-14.072 18.752-32.142 18.752-51.831C162.381 36.423 125.959 0 81.191 0 36.422 0 0 36.423 0 81.193c0 44.767 36.422 81.187 81.191 81.187 19.688 0 37.759-7.049 51.831-18.751l47.079 47.078a7.474 7.474 0 0 0 5.303 2.197 7.498 7.498 0 0 0 5.303-12.803zM15 81.193C15 44.694 44.693 15 81.191 15c36.497 0 66.189 29.694 66.189 66.193 0 36.496-29.692 66.187-66.189 66.187C44.693 147.38 15 117.689 15 81.193z">
                </path>
            </svg>
        </div>

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
                    <th scope="col"> Username </th>
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
                        <td> <?php echo $d['Username'];  ?> </td>
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