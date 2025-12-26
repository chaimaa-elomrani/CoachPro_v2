<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Reservations.php';

class ReservationController {
    public function createReservation(){
        if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'sportif'){
            $_SESSION['error'] = "only sportifs can create reservations.";
            header('Location: ../views/login.php');
            exit();
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $sportif_id = $_SESSION['user_id'];
            $session_id = $_POST['session_id'] ?? '';
          
            if(empty($session_id)){
                $_SESSION['error']= "Session ID is required.";
                header('Location: ../views/sportif/dashboard.php');
                exit();
            }
        }

        try{
            $reservation = new Reservations(null, $sportif_id, $session_id);
            if($reservation->createReservation($sportif_id , $session_id)){
                $_SESSION['success'] = "Reservation created successfully.";
                header('Location: ../views/sportif/dashboard.php');
            }else{
                $_SESSION['error'] = "Failed to create reservation.";
                header('Location: ../views/sportif/dashboard.php');
            }

            exit();
        }catch(Exception $e){
            $_SESSION['error'] = "An error occurred: " . $e->getMessage();
            header('Location: ../views/sportif/dashboard.php');
            exit();
        }
    }

    public function cancelReservation($reservation_id){
        if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'sportif'){
            $_SESSION['error'] = "only sportifs can cancel reservations.";
            header('Location: ../views/login.php');
            exit();
        }

        try{
            $reservation = new Reservations();
            if($reservation->cancelReservation($reservation_id)){
                $_SESSION['success'] = "Reservation cancelled successfully.";
                header('Location: ../views/sportif/dashboard.php');
            }else{
                $_SESSION['error'] = "Failed to cancel reservation.";
                header('Location: ../views/sportif/dashboard.php');
            }

            exit();
        }catch(Exception $e){
            $_SESSION['error'] = "An error occurred: " . $e->getMessage();
            header('Location: ../views/sportif/dashboard.php');
            exit();
        }
    }


    public function confirmReservation($reservation_id){
        if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'coach'){
            $_SESSION['error'] = "only coaches can confirm reservations.";
            header('Location: ../views/login.php');
            exit();
        }

        try{
            $reservation = new Reservations();
            if($reservation->confirmReservation($reservation_id)){
                $_SESSION['success'] = "Reservation confirmed successfully.";
                header('Location: ../views/coach/dashboard.php');
            }else{
                $_SESSION['error'] = "Failed to confirm reservation.";
                header('Location: ../views/coach/dashboard.php');
            }

            exit();
        }catch(Exception $e){
            $_SESSION['error'] = "An error occurred: " . $e->getMessage();
            header('Location: ../views/coach/dashboard.php');
            exit();
        }
    }


    public function getAllReservations(){
        try{
            $reservation = new Reservations();
            return $reservation->getAllReservations();
        }catch(Exception $e){
            $_SESSION['error'] = "An error occurred: " . $e->getMessage();
            header('Location: ../views/login.php');
            exit();
        }
    }


    public function getUserReservations($user_id = null){
        if($user_id === null){
            $user_id = $_SESSION['user_id'] ?? null;
        }
        try{
            $reservation = new Reservations();
            return $reservation->getReservationsByUser($user_id);
        }catch(Exception $e){
            $_SESSION['error'] = "An error occurred: " . $e->getMessage();
            header('Location: ../views/login.php');
            exit();
        }
    }
}
