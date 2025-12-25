<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $id ; 
    private $name ; 
    private $email ;
    private $password ;
    private $role ; 

    public function __construct($id = null, $name, $email , $password , $role){
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->role = $role;
    }

    public function getId(){
        return $this->id ; 
    }

    public function getName(){
        return $this->name ;
    }
    public function getEmail(){
        return $this->email ;
    }
    public function getPassword(){
        return $this->password ;
    }
    public function getRole(){
        return $this->role ;
    }

    public function setName($name){
        $this->name =$name;
    }
    public function setEmail($email){
        $this->email = $email ;
    }
    

    public function create(){
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO users (name , email , password , role)
        VALUES (:name ,  :email , :password , :role)");
        return $stmt->execute([
            ':name' => $this->name, 
            ':email' => $this->email,
            ':password' => $this->password,
            ':role' => $this->role
        ]);
    }

    public function update(){
        $db = Database::getInstance()->getConnection();
        $sql = $db->prepare('UPDATE users SET name= :name , email =:email , password =:password , role = :role WHERE id = :id');
        return $sql->execute([
            ':name' => $this->name, 
            ':email' => $this->email,
            ':password' => $this->password,
            ':role' => $this->role,
            ':id' => $this->id
        ]);
    }

    public function delete(){
        $db = Database::getInstance()->getConnection();
        $sql = $db->prepare('DELETE FROM users WHERE id = :id');
        return $sql->execute([
            ':id' => $this->id
        ]);
    }

    public function showAll(){
        $db = Database::getInstance()->getConnection();
        $sql = $db->query('SELECT * FROM users');
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }


}