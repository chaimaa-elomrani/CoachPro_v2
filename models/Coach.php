<?php

class Coach extends User {
    
    private $photo ;
    private $bio ;
    private $discipline ;
    private $experience;
    private $sport_id ;

    public function __construct($id = null, $name, $email, $password, $role, $discipline, $experience, $sport_id){
        parent::__construct($id, $name , $email ,$password , $role);
        $this->photo = null ; //doing null because photo is optional
        $this->bio = null ;
        $this->discipline = $discipline ;
        $this->experience = $experience ;
        $this->sport_id = $sport_id ;
    }

    public function getDiscipline(){
        return $this->discipline ;
    }

    public function setDiscipline($discipline){
        $this->discipline =$discipline ; 
    }

    public function getExperience(){
        return $this->experience ;
    }
    public function setExperience($experience){
        $this->experience = $experience ;
    }

    public function getSportId(){
        return $this->sport_id ;
    }

    public function setSportId($sport_id){
        $this->sport_id = $sport_id ;
    }


    public function createCoach(){
        $db = Database::getInstance()->getConnection();
        // first we need to check if the user already exists with the role coach and they don't have a profile yet 
        $this->create(); // create base user
        $stmt = $db->prepare("INSERT INTO coaches (user_id , discipline , experience , sport_id)
        VALUES (:user_id , :discipline , :experience , :sport_id)");
        return $stmt->execute([
            ':user_id' => $this->getId(), 
            ':discipline' => $this->discipline,
            ':experience' => $this->experience,
            ':sport_id' => $this->sport_id
        ]);

    }

    
}
