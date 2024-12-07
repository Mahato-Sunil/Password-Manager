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

        <form class="max-w-md mx-auto">
            <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="search" id="default-search" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Website Name, Username ..." required />
                <button type="submit" class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
            </div>
        </form>

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