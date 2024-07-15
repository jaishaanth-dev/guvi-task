<?php
require "header.php";
?>

<div class="box">
    <img src="user.jpeg" class="user">

    <h1>Register Here</h1>

    <form name="register-form" action="#" method="POST">

        <p>Username</p>
        <input type="text" name="username" placeholder="Enter Username" required="">

        <p>Email</p>
        <input type="Email" name="email" placeholder="Enter email id" required="">

        <p>Password</p>
        <input type="password" name="password" placeholder="Enter Password" required="">

        <input type="submit" name="register" value="Register">

        <br><br>
        <a href="login.php">existing user, login !?</a>
    </form>

</div>


<?php
require "footer.php";