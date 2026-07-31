<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "retechhub_db"
);

if(!$conn){
    die("Database Connection Failed");
}

?>