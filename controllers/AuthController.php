<?php
require_once '../models/User.php';
require_once '../models/Coach.php';
require_once '../models/Sportif.php';
require_once '../config/database.php';

class AuthController
{


    private function emailExists($email){
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([
            ':email' =>$email
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }



    public function getUserByEmail($email){
        $db = Database::getInstance()->getConnection();
        $stmt =$db->prepare("SELECT * FROM  users WHERE email = :email");
        $stmt->execute([
            ':email' => $email
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }



    public function register(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role']  ?? 'sportif' ;

            if(empty($name) || empty($email) || empty($password) || empty($role)){
                $_SESSION['error'] = 'Empty fiels are not allowed.';
                header('Location: ../views/register.php');
                exit(); 
            }

            if($this->emailExists($email)){
                $_SESSION['error'] = 'Email already used';
                header('Location: ../views/register.php');
                exit();
            }

            try{
                if($role === 'coach'){
                    $discipline = $_POST['discipline'] ?? '';
                    $experience = $_POST['experience'] ?? '';
                    $sport_id = $_POST['sport_id'] ?? '';

                    $user = new Coach(null , $name, $email , $password , $role, $discipline, $experience, $sport_id);
                    $user->createCoach();

                }else if($role === 'sportif'){
                    //we are doing null in the id because it will be auto generated
                    $user = new Sportif(null , $name, $email , $password , $role);
                    $user->createSportif();
                }else{
                    $_SESSION['error'] = 'Invalid role selected.';
                    header('Location: ../views/chooseRole.php');
                    exit();
                }   

                $_SESSION['success'] = 'Registration successful. Please log in.';
                header('Location: ../views/login.php');
                exit();
            }catch(Exception $e){
                $_SESSION['error'] = 'Registration failed: ' . $e->getMessage();
                header('Location: ../views/register.php');
                exit();
            }
        }
    }

    

    public function login(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if(empty($email) || empty($password)){
                $_SESSION['error'] = 'Empty fields are not allowed.';
                header('Location: ../views/login.php');
                exit();
            }

            $user = $this->getUserByEmail($email);
            if($user && password_verify($password , $user['password'])){
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                if($user['role'] === 'coach'){
                    header('Location: ../views/coach/dashboard.php');
                }else if ($user['role'] === 'sportif'){
                    header('Location: ../views/sportif/dashboard.php');
                }else{
                    header('Location: ../views/login.php');
                }
                exit();
            }else{
                $_SESSION['error'] = 'Invalid email or password.';
                header('Location: ../views/login.php');
                exit();
            }
        }
    }

}