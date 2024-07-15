<?php include "header.php"; ?>

<div class="box">

    <h1>Login Here</h1>

    <form name="login-form" action="#" method="POST">

        <p>Username</p>
        <input type="text" name="username" placeholder="Enter Username" required="">

        <p>Password</p>
        <input type="password" name="password" placeholder="Enter Password" required="">

        <button type="submit" class="btn btn-primary btn-lg">Login</button>

        <br><br>
        <a href="register.php">New users, Go register !?</a>
    </form>

</div>

<?php include "footer.php";