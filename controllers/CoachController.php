<?php
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/../models/Coach.php';

class CoachController {

    public function getAllCoaches(){
        try {
            $coachs = new Coach();
            return $coachs->getAllCoaches();
        }
        catch (Exception $e) {
            throw new Exception("Error fetching coaches: " . $e->getMessage());
        }
    }
}