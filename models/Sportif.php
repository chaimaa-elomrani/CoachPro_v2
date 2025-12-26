<?php

class Sportif extends User {
    
    private $photo ;
    private $bio ;
    private $age ;

    public function __construct($id = null, $name, $email, $password, $role, $age){
        parent::__construct($id, $name , $email ,$password , $role);
        $this->photo = null ; //doing null because photo is optional
        $this->bio = null ;
        $this->age = $age ;
    }

    public function getAge(){
        return $this->age ;
    }

    public function setAge($age){
        $this->age =$age ; 
    }


    public function createSportif(){
        $db = Database::getInstance()->getConnection();
        // first we need to check if the user already exists with the role sportif and they don't have a profile yet 
        $this->create(); // create base user
        $stmt = $db->prepare("INSERT INTO sportifs (user_id , age)
        VALUES (:user_id , :age)");
        return $stmt->execute([
            ':user_id' => $this->getId(), 
            ':age' => $this->age
        ]);
    }

    
}