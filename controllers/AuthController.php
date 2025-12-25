<?php
require_once 'Utilisateur.php';

class Coach extends Utilisateur{
    private $discipline;
    private $experience;
    private $description;

    public function __construct($nom, $prenom, $email, $password, $discipline, $experience, $description){
        parent::__construct(null, $nom, $prenom, $email);
        $this->setPasswordHash($password);
        $this->discipline = $discipline;
        $this->experience = $experience;
        $this->description = $description;
    }

    public function save(){
        $db = Database::getInstance()->getConnection();
        $this->saveBaseUser('coach');

        $stmt = $db->prepare("
            INSERT INTO coaches (id, discipline, experience, description)
            VALUES (:id, :discipline, :experience, :description)
        ");

        $stmt->execute([
            ':id' => $this->id,
            ':discipline' => $this->discipline,
            ':experience' => $this->experience,
            ':description' => $this->description
        ]);
    }
}


// *********************************************************************************************    

<?php
require_once __DIR__ . '/../config/database.php';

class Seance{
    private $id;
    private $coach_id;
    private $date;
    private $heure;
    private $duree;
    private $statut;

    public function __construct($id = null, $coach_id, $date, $heure, $duree, $statut = 'disponible'){
        $this->id = $id;
        $this->coach_id = $coach_id;
        $this->date = $date;
        $this->heure = $heure;
        $this->duree = $duree;
        $this->statut = $statut;
    }

    // Getters
    public function getId(){
        return $this->id;
    }

    public function getCoachId(){
        return $this->coach_id;
    }

    public function getDate(){
        return $this->date;
    }

    public function getHeure(){
        return $this->heure;
    }

    public function getDuree(){
        return $this->duree;
    }

    public function getStatut(){
        return $this->statut;
    }

    // Setters
    public function setDate($date){
        $this->date = $date;
    }

    public function setHeure($heure){
        $this->heure = $heure;
    }

    public function setDuree($duree){
        $this->duree = $duree;
    }

    public function setStatut($statut){
        $this->statut = $statut;
    }

    // Create new session
    public function creer(){
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            INSERT INTO seances (coach_id, date, heure, duree, statut)
            VALUES (:coach_id, :date, :heure, :duree, :statut)
        ");

        $stmt->execute([
            ':coach_id' => $this->coach_id,
            ':date' => $this->date,
            ':heure' => $this->heure,
            ':duree' => $this->duree,
            ':statut' => $this->statut
        ]);

        $this->id = $db->lastInsertId();
        return $this->id;
    }

    // Update existing session
    public function modifier(){
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            UPDATE seances 
            SET date = :date, heure = :heure, duree = :duree, statut = :statut
            WHERE id = :id AND coach_id = :coach_id
        ");

        return $stmt->execute([
            ':id' => $this->id,
            ':coach_id' => $this->coach_id,
            ':date' => $this->date,
            ':heure' => $this->heure,
            ':duree' => $this->duree,
            ':statut' => $this->statut
        ]);
    }

    // Delete session
    public function supprimer(){
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            DELETE FROM seances 
            WHERE id = :id AND coach_id = :coach_id
        ");

        return $stmt->execute([
            ':id' => $this->id,
            ':coach_id' => $this->coach_id
        ]);
    }

    // Static method: Get all sessions for a coach
    public static function getByCoach($coach_id){
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT * FROM seances 
            WHERE coach_id = :coach_id 
            ORDER BY date DESC, heure DESC
        ");

        $stmt->execute([':coach_id' => $coach_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Static method: Get session by ID
    public static function getById($id, $coach_id){
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT * FROM seances 
            WHERE id = :id AND coach_id = :coach_id
        ");

        $stmt->execute([
            ':id' => $id,
            ':coach_id' => $coach_id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Static method: Count sessions by status
    public static function countByStatus($coach_id, $statut){
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT COUNT(*) as count FROM seances 
            WHERE coach_id = :coach_id AND statut = :statut
        ");

        $stmt->execute([
            ':coach_id' => $coach_id,
            ':statut' => $statut
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
}




<?php
require_once 'Utilisateur.php';

class Sportif extends Utilisateur{

    public function __construct($nom, $prenom, $email, $password){
        parent::__construct(null, $nom, $prenom, $email);
        $this->setPasswordHash($password);
    }

    public function save()
    {
        $db = Database::getInstance()->getConnection();
        $this->saveBaseUser('sportif');

        $stmt = $db->prepare("INSERT INTO sportifs (id) VALUES (:id)");
        $stmt->execute([':id' => $this->id]);
    }
}



<?php
require_once __DIR__ . '/../config/database.php';

abstract class Utilisateur {
    protected $id;
    protected $nom;
    protected $prenom;
    protected $email;
    protected $passwordHash;

    public function __construct($id, $nom, $prenom, $email, $passwordHash = null){
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
    }

    public function __toString(){
        return $this->nom . ' ' . $this->prenom;
    }

    // Getters
    public function getId(){
        return $this->id;
    }
    public function getNom(){
        return $this->nom;
    }
    public function getPrenom(){
        return $this->prenom;
    }
    public function getEmail(){
        return $this->email;
    }

    protected function setPasswordHash($password){
        $this->passwordHash = password_hash($password, PASSWORD_BCRYPT);
    }

    // Shared DB methods
    protected function saveBaseUser($role){
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            INSERT INTO users (nom, prenom, email, password, role)
            VALUES (:nom, :prenom, :email, :password, :role)
        ");

        $stmt->execute([
            ':nom' => $this->nom,
            ':prenom' => $this->prenom,
            ':email' => $this->email,
            ':password' => $this->passwordHash,
            ':role' => $role
        ]);

        $this->id = $db->lastInsertId();
        return $this->id;
    }

    /* ---------- AUTH ---------- */

    public static function findByEmail($email){
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function signin($email, $password) {
        $data = self::findByEmail($email);

        if (!$data || !password_verify($password, $data['password'])) {
            throw new Exception("Invalid email or password");
        }

        return $data; // session will use this
    }
}




