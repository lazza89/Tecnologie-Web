<?php
namespace DB;

class DBConnection{
    private const HOST_DB = "127.0.0.1";
    private const DATABASE_NAME = "ibenetaz";
    private const USERNAME = "ibenetaz";
    private const PASSWORD = "zeiwai3vuchou7Th";

    private $connection;

    public function openDBConnection(){
        $this->connection = mysqli_connect(DBConnection::HOST_DB, DBConnection::USERNAME, DBConnection::PASSWORD, DBConnection::DATABASE_NAME);
        if(mysqli_errno($this->connection)){
            return false;
        }else{
            mysqli_set_charset($this->connection,"utf8");
            return true;
        }
    }

    public function closeDBConnection(){
        mysqli_close($this->connection);
    }
    
    public function createNewUser($mail, $username, $pw, $name, $surname, $city){
        $createNewUserQuery = "INSERT INTO `user` (`id`, `email`,`username`, `password`, `name`, `surname`, `city`, `isAdmin`) values (NULL, \"$mail\", \"$username\", \"$pw\", \"$name\", \"$surname\", \"$city\", 0)";
        
        $queryResult = mysqli_query($this->connection, $createNewUserQuery) or die (mysqli_error($this->connection)); 
        if(mysqli_affected_rows($this->connection) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function checkUsernameOnDB($username){
        $checkUsernameQuery = "SELECT `username` from `user` WHERE `username` = \"$username\"";
        $queryResult = mysqli_query($this->connection, $checkUsernameQuery) or die (mysqli_error($this->connection)); 
        if(mysqli_num_rows($queryResult) > 0 ){
            return true;
        }else{
            return false;
        }
    }

    public function checkLoginCredentials($username, $password){
        $checkLoginCredentials = "SELECT `id`, `email`, `username`, `password`, `name`, `surname`, `city`, `isAdmin` from `user` WHERE `username` = \"$username\" and `password` = \"$password\" ";
        $queryResult = mysqli_query($this->connection, $checkLoginCredentials) or die (mysqli_error($this->connection)); 

        if(mysqli_num_rows($queryResult) > 0){
            $row = mysqli_fetch_assoc($queryResult);
            return $row;
        }else{
            return "";
        }
    }

    public function insertComment( $userId, $comment, $stars){
        $insertComment = "INSERT INTO `comment` (`id`, `userId`, `comment`, `stars`, `date`) values (NULL, \"$userId\", \"$comment\", \"$stars\", NOW());";
        mysqli_query($this->connection, $insertComment) or die (mysqli_error($this->connection)); 

        if(mysqli_affected_rows($this->connection) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getComments(){
        $loadComments = "SELECT * from `comment` ORDER BY `date` DESC LIMIT 10";
        $queryResult = mysqli_query($this->connection, $loadComments) or die (mysqli_error($this->connection)); 

        if(mysqli_num_rows($queryResult) > 0){
            return $queryResult;
        }else{
            return "";
        }
    }

    public function updateUser($id, $mail, $username, $pw, $name, $surname, $city){
        $modifyUserQuery = "UPDATE `user` SET `email` = \"$mail\", `username` = \"$username\", `password` = \"$pw\", `name` = \"$name\", `surname` = \"$surname\", `city` = \"$city\" WHERE `id` = \"$id\"";
        mysqli_query($this->connection, $modifyUserQuery) or die (mysqli_error($this->connection)); 
        if(mysqli_affected_rows($this->connection) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getUserDetailsById($id){
        $getUsernameById = "SELECT `email`, `username`, `name`, `surname`, `city` FROM `user` WHERE `id` = \"$id\" ";
        $queryResult = mysqli_query($this->connection, $getUsernameById) or die (mysqli_error($this->connection)); 

        if(mysqli_num_rows($queryResult) > 0){
            $row = mysqli_fetch_assoc($queryResult);
            return $row;
        }else{
            return "";
        }
    }

    public function getUsernameById($id){
        $getUsernameById = "SELECT `username` FROM `user` WHERE `id` = \"$id\" ";
        $queryResult = mysqli_query($this->connection, $getUsernameById) or die (mysqli_error($this->connection)); 

        if(mysqli_num_rows($queryResult) > 0){
            return $queryResult;
        }else{
            return "";
        }
    }

    public function deleteUser($username){
        $deleteUserOnTable = "DELETE FROM `user` WHERE `username` = \"$username\"";
        
        mysqli_query($this->connection, $deleteUserOnTable) or die (mysqli_error($this->connection)); 
        if(mysqli_affected_rows($this->connection) > 0){
            return true;
        }else{
            return false;
        }

    }

    public function deleteComment($commentID){
        $deleteComment = "DELETE FROM `comment` WHERE `id` = \"$commentID\"";
        
        $queryResult = mysqli_query($this->connection, $deleteComment) or die (mysqli_error($this->connection)); 
        if(mysqli_affected_rows($this->connection) > 0){
            return true;
        }else{
            return false;
        }
    }

}


?>