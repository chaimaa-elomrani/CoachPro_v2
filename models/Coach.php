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



}
