<?php

require_once 'UserModel.php';

/**
 * This page create a new object from User class with data from index.html (post)
 * and call two methods, SaveDatabase for include fields on database 
 * and SendPostData to transmit the data for actualsales endpoint
 */


$postdata = file_get_contents("php://input");
$user = new UserModel(json_decode($postdata, 'true'));
$user->saveDatabase();
$user->sendPostData();


