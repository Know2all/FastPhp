<?php 
    define('SERVER_NAME', 'localhost');
    define('USER_NAME', 'root');
    define('USER_PASSWORD', '');
    define('DATABASE_NAME', 'ecommerce');

    $conn = new mysqli(SERVER_NAME, USER_NAME, USER_PASSWORD, DATABASE_NAME);

    if(!$conn){
        echo "Failed To Connect";
    }
?>
