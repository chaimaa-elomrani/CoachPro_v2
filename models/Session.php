<?php
require_once __DIR__ . '/../config/database.php';

class Session {
    private $id ;
    private $coach_id ;
    private $date ;
    private $time ;
    private $duration ;
    

    public function __construct($id = null, $coach_id, $date, $time, $duration){
        $this->id = $id ;
        $this->coach_id = $coach_id ;
        $this->date = $date ;
        $this->time = $time ;
        $this->duration = $duration ;
    
    }

    public function getId(){
        return $this->id ;
    }
    public function getCoachId(){
        return $this->coach_id ;
    }
    public function getDate(){
        return $this->date ;
    }
    public function getTime(){
        return $this->time ;
    }
    public function getDuration(){
        return $this->duration ;
    }


       public function  createSession($date, $time, $duration){
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO sessions (coach_id , date , time , duration , statut)
        VALUES (:coach_id , :date , :time , :duration , :statut)");
        return $stmt->execute([ 
            ':coach_id' => $this->getId(),
            ':date' => $date,
            ':time' => $time,
            ':duration' => $duration,
            ':statut' => 'disponible'
        ]);
    }

    public function deleteSession($session_id){
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM sessions WHERE id = :session_id");
        return $stmt->execute([
            ':session_id' => $session_id
        ]);
    }


    public function getAllSessions(){
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM sessions");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSessionsByCoach($coach_id){
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM sessions WHERE coach_id = :coach_id");
        $stmt->execute([
            ':coach_id' => $coach_id
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countSessions(){
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) as total_sessions FROM sessions");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
        public function getReservatedSessions($coach_id){
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT COUNT(DISTINCT r.session_id ) as total FROM reservations r JOIN sessions s ON r.session_id = s.id WHERE s.coach_id = :coach_id and r.statut = 'confirmed'");
        $stmt->execute([
            ':coach_id' => $coach_id
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
        }

}