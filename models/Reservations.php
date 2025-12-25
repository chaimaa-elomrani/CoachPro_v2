<?php 

class Reservations extends User {
    
    private $user_id ;
    private $session_id ;
    private $statut ;

    public function __construct($id = null, $user_id, $session_id, $statut){
        parent::__construct($id);
        $this->user_id = $user_id ;
        $this->session_id = $session_id ;
        $this->statut = $statut ;
    }

    public function getUserId(){
        return $this->user_id ;
    }

    public function setUserId($user_id){
        $this->user_id =$user_id ; 
    }

    public function getSessionId(){
        return $this->session_id ;
    }

    public function setSessionId($session_id){
        $this->session_id =$session_id ; 
    }

    public function getStatut(){
        return $this->statut ;
    }

    public function setStatut($statut){
        $this->statut =$statut ; 
    }


    public function createReservation(){
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO reservations (user_id , session_id , statut)
        VALUES (:user_id ,  :session_id , :statut)");
        return $stmt->execute([
            ':user_id' => $this->user_id, 
            ':session_id' => $this->session_id,
            ':statut' => $this->statut
        ]);
    }


    public function cancelReservation($reservation_id){
        $db = Database::getInstance()->getConnection();
        $sql = $db->prepare('DELETE FROM reservations WHERE id = :id');
        return $sql->execute([
            ':id' => $reservation_id
        ]);
    }


    public function updateResevation($reservation_id){
        $db = Database::getInstance()->getConnection();
        $sql = $db->prepare('UPDATE reservations SET user_id= :user_id , session_id =:session_id , statut =:statut WHERE id = :id');
        return $sql->execute([
            ':user_id' => $this->user_id, 
            ':session_id' => $this->session_id,
            ':statut' => $this->statut,
            ':id' => $reservation_id
        ]);
    }
    

}