<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="Assets/CSS/login-page-ui.css">
  <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Sharp" rel="stylesheet">
  <title>Login Page</title>
</head>

<body>
  <div class="container">

    <!-- code for the login button  -->
    <div class="login-box" id="user">
      <!-- image source  -->
      <img src="Assets/Image/admin.svg" style="width: 12rem; border-top:3px solid blue; border-right:1.5px solid red; border-left:1.5px solid green; padding: 0.5rem; border-radius: 50%;" alt="Login Page Demo" class="login-img">

      <strong id="msg"></strong>
      <form name="login" method="post" action="Template/validate_login.php">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder=" Password" required>
        <input type="submit" name="submit" value="Login">
      </form>
    </div>
  </div>
</body>

</html>