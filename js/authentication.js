function validateForm() {
  /* var username = document.forms["register"]["username"].value;
    var email = document.forms["register"]["email"].value;
    var password = document.forms["register"]["password"].value;
    
    // Regular expression for email validation
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

    // Validate username
    if (username == "") {
        alert("Username must be filled out");
        return false;
    }

    // Validate email
    if (email == "") {
        alert("Email must be filled out");
        return false;
    } else if (!emailPattern.test(email)) {
        alert("Invalid email format");
        return false;
    }

    // Validate password
    if (password == "") {
        alert("Password must be filled out");
        return false;
    } else if (password.length < 6) {
        alert("Password must be at least 6 characters long");
        return false;
    } */

  return true;
}

const SESSION_KEY = "APP_SESSION_ID";

$(document).ready(function () {
  //register
  $("form[name='register-form']").submit(function (event) {
    event.preventDefault(); // Prevent the default form submission

    if (!validateForm()) {
      return; // If validation fails, do not proceed
    }

    var formData = {
      requestType: "register",
      userName: $("input[name='username']").val(),
      email: $("input[name='email']").val(),
      password: $("input[name='password']").val(),
    };

    $.ajax({
      type: "POST",
      url: "http://localhost/www/tasks/php/backend/authentication.server.php",
      encode: true,
      data: formData,
      dataType: "json",
      header: {
        "Content-type": "application/x-www-form-urlencoded",
      },
      success: function (response) {
        alert(response.message);
        if (response.success) {
          window.location.href = "login.html";
          return;
        }
        //handle errors
      },
      error: function (xhr, status, error) {
        console.log({ xhr, status, error });
        console.error({ status, error });
      },
    });
  });

  //login
  $("form[name='login-form']").submit(function (event) {
    event.preventDefault(); // Prevent the default form submission

    var formData = {
      requestType: "login",
      userName: $("input[name='username']").val(),
      password: $("input[name='password']").val(),
    };

    $.ajax({
      type: "POST",
      url: "http://localhost/www/tasks/php/backend/authentication.server.php",
      encode: true,
      data: formData,
      dataType: "json",
      header: {
        "Content-type": "application/x-www-form-urlencoded",
      },
      success: function (response) {
        if (response.success) {
          const sessionId = response.data.sessionId;

          if (!sessionId) {
            alert("Something gone wrong.. Please Try again");
            return;
          }

          localStorage.setItem(SESSION_KEY, sessionId);

          alert("Login successful!");
          window.location.href = "profile.html";
          return;
        }

        alert(response.message);

        //handle errors
      },
      error: function (xhr, status, error) {
        console.log({ xhr, status, error });
        console.error({ status, error });
      },
    });
  });

  //update-profile
  $("form[name='profile-form']").submit(function (event) {
    event.preventDefault(); // Prevent the default form submission

    var formData = {
      requestType: "update-profile",
      sessionId: localStorage.getItem(SESSION_KEY),
      age: $("input[name='age']").val(),
      dob: $("input[name='dob']").val(),
      contact: $("input[name='contact']").val(),
    };

    $.ajax({
      type: "POST",
      url: "http://localhost/www/tasks/php/backend/authentication.server.php",
      encode: true,
      data: formData,
      dataType: "json",
      header: {
        "Content-type": "application/x-www-form-urlencoded",
      },
      success: function (response) {
        const responseJson = JSON.parse(response.data);

        console.log({ responseJson });

        alert(responseJson.message);

        if (responseJson.success) {
          /*  setTimeout(function () {
                        window.location.reload();
                    }, 3000); */
          return;
        }
        //handle errors
      },
      error: function (xhr, status, error) {
        console.log({ xhr, status, error });
        console.error(status, error);
      },
    });
  });

  if (localStorage.getItem(SESSION_KEY)) {
    getProfile();
  } else {
    if (location.pathname.includes("profile.html")) {
      window.location.href = "login.html";
    }
  }
});

function getProfile() {
  // get-profile
  $.ajax({
    type: "POST",
    name: "get-profile",
    url: "http://localhost/www/tasks/php/backend/authentication.server.php",
    encode: true,
    data: {
      requestType: "get-profile",
      sessionId: localStorage.getItem(SESSION_KEY),
    },
    dataType: "json",
    header: {
      "Content-type": "application/x-www-form-urlencoded",
    },
    success: function (response) {
      const responseJson = JSON.parse(response.data);

      if (responseJson.success) {
        const userData = responseJson.data;

        const form = "form[name='profile-form']";

        $(`${form} #username`).val(userData.username);
        $(`${form} #email`).val(userData.email);
        $(`${form} #dob`).val(userData.dob);
        $(`${form} #age`).val(userData.age);
        $(`${form} #contact`).val(userData.contact);

        return;
      }

      if (responseJson.status === 401) {
        localStorage.clear();
        alert("Please Login again");
        window.location.href = "login.html";
        return;
      }

      alert(responseJson.message);

      //handle errors
    },
    error: function (xhr, status, error) {
      console.log({ xhr, status, error });
      console.error(status, error);
    },
  });
}
