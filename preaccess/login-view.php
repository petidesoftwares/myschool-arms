<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../custom-css/base-customstyle.css">
    <link rel="stylesheet" href="../arms.css/arms-1.css">
    <!-- <link rel="stylesheet" href="../custom-css/login.css"> -->
    <title>Admin Login</title>
</head>
<body>
    <div class="col-12" id="main-header">
        <div>
        <div class="col-2"> <img src="../images/ndu_bg_logo.png" alt="logo" id="logo"/> </div>
        <div class="col-9 header"> 
            <div id="h1">ACADEMIC RECORD MANAGMENT SYSTEM</div>
            <div id="h3">MY DEPARTMENT</div>
        </div>
        <hr id="header-seperator"/>
    </div>
    <div>
        <div class="col-2"></div>
        <div class="col-7" id="login-form-pane">
            <form action="../backend/login.php" method="post">
                <input type="email" name="login_email" id="login_email" placeholder="Enter email address">
                <input type="password" name="login_password" id="login_password" placeholder="Enter password">
                <input type="submit" name="submit" id="submit" value="Login">
            </form>
        </div>
        <div class="col-2"></div>
    </div>
</body>
</html>