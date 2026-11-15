<?php
// Database lookup for the single admin account.

require_once __DIR__ . '/Model.php';

class Admin extends Model
{
    public function findByUsername($username)
    {
        $sql = 'SELECT id, username, password_hash FROM admins WHERE username = ?';
        $statement = $this->db->prepare($sql);
        $statement->execute([$username]);

        return $statement->fetch();
    }
}
