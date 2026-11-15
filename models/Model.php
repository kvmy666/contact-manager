<?php
// Base class that gives every model a database connection.

require_once __DIR__ . '/../config/database.php';

class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }
}
