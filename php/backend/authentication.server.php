<?php

function userRegistration($userName,$email,$password){
    include "./database.php";
    if (empty($userName) || empty($email) || empty($password)){
        return [
            'data' => json_encode(["success"=>false,"message"=>"All Fields are required"])
        ];
    }

    $userId=uniqid();

    $SELECT = "SELECT email From users Where email = ? Limit 1";
    $INSERT = "INSERT Into users (user_id,username , email , password )values(?,?,?,?)";

    $stmt = $mysqlClient->prepare($SELECT);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($email);
    $stmt->store_result();
    $rnum = $stmt->num_rows;

    if ($rnum != 0) {
      return [
        'data' => json_encode(["success"=>false,"message"=>"Someone already register using this emai"])
      ];
    } 

    $stmt->close();
    $stmt = $mysqlClient->prepare($INSERT);
    $stmt->bind_param("ssss", $userId, $userName, $email, $password);
    $stmt->execute();

    $collection = $mongoClient->selectCollection("guvi", "users");

    $document = [
      "userId" => $userId,
      "age" => NULL,
      "dob" => Null,
      "contact" => NULL
    ];

    $insertResult = $collection->insertOne($document);

    if ($insertResult->getInsertedCount() === 1) {
      return [
        'data' => json_encode(["success" => true, "message" => "Account Registered. Please Login to continue"])
      ];
    } 
    
    return [
      'data' => json_encode(["success" => false, "message" => "Failed to register the account"])
    ];
}

function login($userName, $password){
    if(!empty($password) && !is_numeric($userName)) {
        include "./database.php";
        $query ="select * from users where username ='$userName' limit 1";
        $result = mysqli_query($mysqlClient, $query);

        if($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);

            if($user_data['password'] != $password){
              return [];
            }

            $sessionId = uniqid();
            $userId = $user_data["user_id"];
            
            $sessionData = [
              'user_id' => $userId,
              'login_time' => date('Y-m-d H:i:s')
            ];

            $sessionExpiryTime = 3 * (3600*24*30);
            
            $redisClient->setex($sessionId, $sessionExpiryTime, json_encode($sessionData));

            return [
              'data' => json_encode([
                'success' => true, 
                'message' => "Successfully Logged in",
                'data'=>[
                  'sessionId' => $sessionId
                ]
              ])
            ];
        }
        return [
            'data' => json_encode(["success" => false, "message" => "Account not found / Username or password is invalid."])
        ];
    }
    else {
        return [
            'data' => json_encode(["success" => false, "message" => "Required Fields are missing."])
        ];
    }
}

// echo json_encode(login("jai11","jai123"));

function getUserSession($sessionId){
    if ($sessionId) {
        include "./database.php";
        
        $retrievedData = $redisClient->get($sessionId);

        if ($retrievedData) {
          return json_decode($retrievedData);
        }
        
        return "Session not found or expired";
    }

   return "Session ID missing from your device";
}

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (isset($_POST["requestType"])) {
        $requestType = $_POST["requestType"];

        if ($requestType === "register") {
          $userName = $_POST['userName'];
          $email = $_POST['email'];
          $password = $_POST['password'];

          $result = userRegistration($userName, $email, $password);

          header('Content-Type: application/json; charset=utf-8');
          echo json_encode($result);
          die();
        }

        if ($requestType == "login") {
          $userName = $_POST["userName"];
          $password = $_POST["password"];

          $result = login($userName, $password);

          header('Content-Type: application/json; charset=utf-8');
          echo json_encode($result);
          die();
        }

        if($requestType==="get-profile" || $requestType==="update-profile"){
            require "./profile.server.php";
            
            if ($requestType === "get-profile") {
                $sessionId = $_POST['sessionId'];

                $userSessionData = getUserSession($sessionId);

                if(!property_exists($userSessionData, 'user_id')){
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode(["data" => json_encode([
                      "status"=>401,
                      "success"=>false,
                      "message"=>$userSessionData
                    ])]);
                    die();
                }
                
                $userId = $userSessionData->user_id;

                $result = getUserProfileInfo($userId) ?? "";
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(["data"=>json_encode($result)]);
                die();
            }

            if ($requestType == "update-profile") {
                $sessionId = $_POST['sessionId'];

                $userSessionData = getUserSession($sessionId);

                $userId = $userSessionData->user_id;

                if (!$userId) {
                    return ["data" => ["success" => false, "message" => "User not found."]];
                }

                $payload=[
                  "userId" => $userId,
                  "dob" => $_POST["dob"],
                  "age" => $_POST["age"],
                  "contact" => $_POST["contact"]
                ];

                $result = updateUserProfile($payload) ?? "";
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(["data"=>json_encode($result)]);
                die();
            }
        }
      }

      throw new Error("Your Request is invalid");
    }
}
catch(Exception $e) {
    return [
        'data' => json_encode(["success" => false, "message" => "" . $e->getMessage()])
    ];
}