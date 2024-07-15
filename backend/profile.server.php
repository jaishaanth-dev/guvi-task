<?php
function getUserProfileInfo($userId="") {
    if(empty($userId)){
        return ["status"=>401, "success"=>false,"message"=>"Session Is missing"];
    }
    
    include "./database.php";

    $query ="select * from users where user_id ='$userId' limit 1";
    $result = mysqli_query($mysqlClient, $query);

    if(!$result || mysqli_num_rows($result) < 1) {
        return ["status"=>401, "success"=>false,"message"=>"User Info Not found"];
    }
    
    $user_data = mysqli_fetch_assoc($result);

    $email = $user_data["email"];
    $username = $user_data["username"];

    $userDocument=$mongoClient->guvi->users->findOne(['userId' => $userId]);

    if(!$userDocument){
        return ["status"=>401, "success"=>false,"message"=>"User Info Not found"];
    }
    
    $age=$userDocument["age"];
    $dob=$userDocument["dob"];
    $contact=$userDocument["contact"];

    return [
        "status"=>200,
        "success"=>true,
        "message"=>"User Data retrieved suceessfully",
        "data"=>[
            "email"=>$email,
            "username"=>$username,
            "dob"=>$dob,
            'age'=>$age,
            'contact'=>$contact
        ]
    ];
}

function updateUserProfile($payload){
    $userId=$payload["userId"];
    
    if(empty($userId)){
        return ["success"=>false,"message"=>"User not found."];
    }
    
    include "./database.php";
    
    $updatedDocument = $mongoClient->guvi->users->updateOne(['userId'=>$userId],['$set'=> ["dob" => $payload["dob"],"age"=>$payload["age"],"contact"=> $payload["contact"]]]);

    if($updatedDocument->getMatchedCount() < 1){ 
        return [
            "success"=>false,
            "message"=>"User Info not found",
        ];
    }

    if($updatedDocument->getModifiedCount() > 0) {
        return [
            "success"=>true,
            "message"=>"User Data updated suceessfully",
        ];
    }
    
    return [
        "success"=>false,
        "message"=>"Failed to updated",
    ];
    
}