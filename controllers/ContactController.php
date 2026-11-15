<?php
// Handles listing, adding, editing and deleting contacts.

require_once __DIR__ . '/../models/Contact.php';

class ContactController
{
    public function index()
    {
        $contacts = (new Contact())->all();

        require __DIR__ . '/../views/contacts/index.php';
    }

    public function create()
    {
        require_login();

        $errors = [];
        $old = ['name' => '', 'email' => '', 'phone' => ''];

        require __DIR__ . '/../views/contacts/create.php';
    }

    public function store()
    {
        require_login();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('index.php');
        }

        verify_csrf();

        $name  = post('name');
        $email = post('email');
        $phone = post('phone');

        $errors = Contact::validate($name, $email, $phone);

        if (!empty($errors)) {
            $old = compact('name', 'email', 'phone');
            require __DIR__ . '/../views/contacts/create.php';

            return;
        }

        (new Contact())->create($name, $email, $phone);

        flash('Contact added.');
        redirect('index.php');
    }

    public function edit()
    {
        require_login();

        $id = request_id();
        $contact = (new Contact())->find($id);

        if (!$contact) {
            not_found();
        }

        $errors = [];

        require __DIR__ . '/../views/contacts/edit.php';
    }

    public function update()
    {
        require_login();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('index.php');
        }

        verify_csrf();

        $id = request_id();
        $contact = (new Contact())->find($id);

        if (!$contact) {
            not_found();
        }

        $name  = post('name');
        $email = post('email');
        $phone = post('phone');

        $errors = Contact::validate($name, $email, $phone);

        if (!empty($errors)) {
            $old = compact('name', 'email', 'phone');
            require __DIR__ . '/../views/contacts/edit.php';

            return;
        }

        (new Contact())->update($id, $name, $email, $phone);

        flash('Contact updated.');
        redirect('index.php');
    }

    public function delete()
    {
        require_login();

        $id = request_id();
        $contact = (new Contact())->find($id);

        if (!$contact) {
            not_found();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();

            (new Contact())->delete($id);

            flash('Contact deleted.');
            redirect('index.php');
        }

        require __DIR__ . '/../views/contacts/delete.php';
    }
}
