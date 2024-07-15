<?php

    require __DIR__ . '/../../vendor/autoload.php';

    Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../../')->load();

    // mysql
    $hostName   =  $_ENV['MYSQL_HOST'];
    $dbUserName =  $_ENV['MYSQL_USERNAME'];
    $dbName     =  $_ENV['MYSQL_DBNAME'];
    $dbPassword =  $_ENV['MYSQL_PASSWORD'];

    $mysqlClient=mysqli_connect($hostName,$dbUserName,$dbPassword,$dbName) or die("Can't able to connect mysql Database");

    // create required db structured if not exists
    $mysqlUsersTable="CREATE TABLE IF NOT EXISTS users(
                                            `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                                            `user_id` varchar(100) UNIQUE NOT NULL,
                                            `username` varchar(50) UNIQUE NOT NULL,
                                            `email` varchar(150) NOT NULL,
                                            `password` varchar(255) NOT NULL
                                        );"; 
    mysqli_query($mysqlClient,$mysqlUsersTable);
 
    echo"5";
    // mongodb
    $mongoClient = new MongoDB\Client($_ENV["MONGODB_URI"]);

    //redis
    $redisClient = new Predis\Client([
        'scheme' => 'tcp',
        'host'   => '127.0.0.1',
        'port'   => 6379,   
    ]);  