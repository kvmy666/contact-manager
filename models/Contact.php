<?php
// Database queries and validation rules for contacts.

require_once __DIR__ . '/Model.php';

class Contact extends Model
{
    public function all()
    {
        $sql = 'SELECT id, name, email, phone, created_at FROM contacts ORDER BY name ASC';

        return $this->db->query($sql)->fetchAll();
    }

    public function find($id)
    {
        $sql = 'SELECT id, name, email, phone, created_at FROM contacts WHERE id = ?';
        $statement = $this->db->prepare($sql);
        $statement->execute([$id]);

        return $statement->fetch();
    }

    public function create($name, $email, $phone)
    {
        $sql = 'INSERT INTO contacts (name, email, phone) VALUES (?, ?, ?)';
        $statement = $this->db->prepare($sql);

        return $statement->execute([$name, $email, $phone]);
    }

    public function update($id, $name, $email, $phone)
    {
        $sql = 'UPDATE contacts SET name = ?, email = ?, phone = ? WHERE id = ?';
        $statement = $this->db->prepare($sql);

        return $statement->execute([$name, $email, $phone, $id]);
    }

    public function delete($id)
    {
        $sql = 'DELETE FROM contacts WHERE id = ?';
        $statement = $this->db->prepare($sql);

        return $statement->execute([$id]);
    }

    public static function validate($name, $email, $phone)
    {
        $errors = [];

        if ($name === '') {
            $errors[] = 'Name is required.';
        }

        if (mb_strlen($name, 'UTF-8') > 100) {
            $errors[] = 'Name must be 100 characters or fewer.';
        }

        if ($email === '') {
            $errors[] = 'Email is required.';
        }

        if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors[] = 'Please enter a valid email address.';
        }

        if (mb_strlen($email, 'UTF-8') > 190) {
            $errors[] = 'Email must be 190 characters or fewer.';
        }

        if ($phone === '') {
            $errors[] = 'Phone number is required.';
        }

        if (mb_strlen($phone, 'UTF-8') > 30) {
            $errors[] = 'Phone number must be 30 characters or fewer.';
        }

        if ($phone !== '' && !preg_match('/^[0-9+\-\s().]+$/', $phone)) {
            $errors[] = 'Phone number may only contain digits, spaces, +, -, ( and ).';
        }

        if ($phone !== '' && mb_strlen($phone, 'UTF-8') < 7) {
            $errors[] = 'Phone number must be at least 7 characters.';
        }

        return $errors;
    }
}
