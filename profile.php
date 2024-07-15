<?php
include('header.php');
?>

<div class="container py-5">
    <p class="text-center fs-3">Hello <span class="user-name"></span></p><br />
    <form name="profile-form" class="border p-4" action="#" method="POST">
        <h3 class="text-center fs-4 text-info">Update Profile</h3><br />
        <div class="row mb-3">
            <div class="col">
                <label class="form-label" for="username">User Name</label>
                <input disabled type="text" placeholder="Usename" name="username" id="username" class="form-control">
            </div>
            <div class="col">
                <label class="form-label" for="email">Email Address</label>
                <input disabled type="text" placeholder="Email" name="email" id="email" class="form-control">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col form-floating">
                <input type="date" placeholder="Date Of Birth" name="dob" id="dob" class="form-control">
                <label class="3" for="dob">Date Of Birth</label>
            </div>
            <div class="col form-floating">
                <input type="text" placeholder="Age" name="age" id="age" class="border form-control">
                <label class="" for="age">Age</label>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col form-floating">
                <input type="text" placeholder="Contact No" name="contact" id="contact" class="border form-control">
                <label class="ml-3" for="contact">Contact No</label>
            </div>
        </div>
        <div class="d-flex justify-content-around align-items-center">
            <button type="submit" name="update-profile" class="btn btn-primary update-btn">Update Profile</button>
        </div>
    </form>
</div>


<?php 
include "footer.php"
?>