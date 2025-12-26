<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Session.php';

class SessionController {
    // public function getAllSessions(){
    //     $db = Database::getInstance()->getConnection();

    // }


    public function createSession(){
        if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'coach'){
            $_SESSION['error'] = "only coaches can create sessions.";
            header('Location: ../views/login.php');
            exit();
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $coach_id = $_SESSION['user_id'];
            $date = $_POST['date'] ?? '';
            $time = $_POST['time'] ?? '';
            $duration = $_POST['duration'] ?? '';
          
            if(empty($date) || empty($time) || empty($duration)){
                $_SESSION['error']= "All fields are required.";
                header('Location: ../views/coach/dashboard.php');
                exit();
            }
        }

        $today = date('Y-m-d');
        if($date < $today){
            $_SESSION['error'] = "The date must be in the future.";
            header('Location: ../views/coach/dashboard.php');
            exit();
        }


        try{
            $session = new Session(null, $coach_id,$date , $time, $duration);
            if($session->createSession($date , $time, $duration)){
                $_SESSION['success'] = "Session created successfully.";
                header('Location: ../views/coach/dashboard.php');
            }else{
                $_SESSION['error'] = "Failed to create session.";
                header('Location: ../views/coach/dashboard.php');
            }

            exit();
        }catch(Exception $e){
            $_SESSION['error'] = "An error occurred: " . $e->getMessage();
            header('Location: ../views/coach/dashboard.php');
            exit();
        }
    }

    public function deleteSession($session_id){
        if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'coach'){
            $_SESSION['error'] = "only coaches can delete sessions.";
            header('Location: ../views/login.php');
            exit();
        }

        try{
            $session = new Session();
            if($session->deleteSession($session_id)){
                $_SESSION['success'] = "Session deleted successfully.";
                header('Location: ../views/coach/dashboard.php');
            }else{
                $_SESSION['error'] = "Failed to delete session.";
                header('Location: ../views/coach/dashboard.php');
            }
            exit();
        }catch(Exception $e){
            $_SESSION['error'] = "An error occurred: " . $e->getMessage();
            header('Location: ../views/coach/dashboard.php');
            exit();
        }
    }


    public function getCoachSessions($coach_id){
        if($coach_id === null){
            $coach_id = $_SESSION['user_id'] ?? null;
        }
        try{
            $session = new Session();
            return $session->getSessionsByCoach($coach_id);
        }catch(Exception $e){
            $_SESSION['error'] = "An error occurred: " . $e->getMessage();
            header('Location: ../views/coach/dashboard.php');
            exit();
        }
    }


    public function getAllSessions(){
        try{
            $session = new Session();
            return $session->getAllSessions();
        }catch(Exception $e){
            $_SESSION['error'] = "An error occurred: " . $e->getMessage();
            header('Location: ../views/sportif/dashboard.php');
            exit();
        }
    }

    public function  countSessions(){
        try{
            $session = new Session();
            return $session->countSessions();
        }catch(Exception $e){
            $_SESSION['error'] = "An error occurred: " . $e->getMessage();
            header('Location: ../views/coach/dashboard.php');
            exit();
        }
    }

    public function getReservatedSessions($coach_id){
        try{
            $session = new Session();
            return $session->getReservatedSessions($coach_id);
        }catch(Exception $e){
            $_SESSION['error'] = "An error occurred: " . $e->getMessage();
            header('Location: ../views/coach/dashboard.php');
            exit();
        }
    }

}
