<?php

class Reservations
{

    private $id;
    private $user_id;
    private $session_id;
    private $statut;

    public function __construct($id = null, $user_id, $session_id, $statut)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->session_id = $session_id;
        $this->statut = $statut;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function getSessionId()
    {
        return $this->session_id;
    }

    public function setSessionId($session_id)
    {
        $this->session_id = $session_id;
    }

    public function getStatut()
    {
        return $this->statut;
    }

    public function setStatut($statut)
    {
        $this->statut = $statut;
    }


    public function createReservation($sportif_id, $session_id, $statut = 'pending')
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO reservations (user_id , session_id , statut)
        VALUES (:user_id ,  :session_id , :statut)");
        return $stmt->execute([
            ':user_id' => $sportif_id,
            ':session_id' => $session_id,
            ':statut' => $statut
        ]);
    }



    public function cancelReservation($reservation_id)
    {
        $db = Database::getInstance()->getConnection();
        try {
            $db->beginTransaction();
            $getStmt = $db->prepare('SELECT session_id FROM reservations WHERE id = :id');
            $reservation = $getStmt->fetch(PDO::FETCH_ASSOC);
        

        if (!$reservation) {
            throw new Exception("Reservation not found");
        }

        $deleted = $db->prepare('DELETE FROM reservations WHERE id = :id');
        $deleted->execute([
            ':session_id' => $reservation['session_id'],
        ]);  
        $updateStmt = $db->prepare("UPDATE sessions SET statut = 'disponible' WHERE id = :session_id");
        $updateStmt->execute([':session_id' => $reservation['session_id']]);
            
            // Commit transaction
            $db->commit();
            return true;  

    }catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
    // public function updateResevation($reservation_id)
    // {
    //     $db = Database::getInstance()->getConnection();
    //     try {
    //         $db->beginTransaction();
    //         $stmt = $db->prepare('UPDATE reservations SET user_id= :user_id , session_id =:session_id , statut =:statut WHERE id = :id');
    //         $stmt->execute([
    //             ':user_id' => $this->user_id,
    //             ':session_id' => $this->session_id,
    //             ':statut' => $this->statut,

    //         ]);


    //         $updateStmt = $db->prepare("UPDATE sessions SET statut = :statut WHERE id = :session_id");
    //         $updateStmt->execute([
    //             ':session_id' => $this->session_id
    //         ]);
    //         $db->commit();
    //         return true;
    //     } catch (Exception $e) {
    //         $db->rollBack();
    //         throw $e;
    //     }
    // }

   
   public function confirmReservation($reservation_id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare('UPDATE reservations SET statut = :statut WHERE id = :id');
        return $stmt->execute([
            ':statut' => 'confirmed',
            ':id' => $reservation_id
        ]);
    }
    public function getAllReservations()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM reservations");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReservationsByUser($user_id) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT r.*, s.date, s.time, s.duration, u.name as coach_name, c.discipline
            FROM reservations r
            JOIN sessions s ON r.session_id = s.id
            JOIN users u ON s.coach_id = u.id
            JOIN coaches c ON u.id = c.user_id
            WHERE r.user_id = :user_id
            ORDER BY s.date DESC, s.time DESC
        ");
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}