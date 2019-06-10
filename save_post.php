<?php

error_reporting( E_ALL );
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('ROOT', dirname(__FILE__));
$db_config = require ROOT.'/config/db.php';

try {
    $pdo = new PDO('mysql:host='.$db_config['host'].';dbname='.$db_config['name'].'', $db_config['user'], $db_config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}



if (isset($_POST['new_photo'])){
    $file = file_get_contents($_FILES["image"]["tmp_name"]);
    $postname = $_POST['postname'];
    $description = $_POST['desc'];

    try {
        $stmt = "INSERT INTO posts (img, postname, description) VALUES (:file, :postname, :description)";
        $query = $pdo->prepare($stmt);
        $query->bindParam(':file', $file, PDO::PARAM_LOB);
        $query->bindParam(':postname', $postname, PDO::PARAM_STR);
        $query->bindParam(':description', $description, PDO::PARAM_STR);
        $query->execute();
    } catch (PDOException $e) {
        echo 'Error : ' .$e->getMessage();
    }
    
    
    unset($_POST['new_photo']);
    header('Location: index.php');
}


?>