<?php
// Shared helper functions for escaping, flash messages, login and CSRF.

// Escapes text before it is printed in HTML.
function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Sends the browser to another URL and stops the script.
function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

// Stores a message that will be shown once on the next page.
function flash($message)
{
    $_SESSION['flash'] = $message;
}

// Returns the stored message and removes it.
function get_flash()
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $message = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $message;
}

// Reads one POST value as text. Array input is treated as empty.
function post($key, $trim = true)
{
    $value = $_POST[$key] ?? '';

    if (!is_string($value)) {
        return '';
    }

    return $trim ? trim($value) : $value;
}

// Tells whether an admin is logged in.
function is_logged_in()
{
    return isset($_SESSION['admin_id']);
}

// Blocks the page for guests and logs out idle sessions.
function require_login()
{
    if (!is_logged_in()) {
        flash('Please log in to manage contacts.');
        redirect('index.php?action=login');
    }

    if (isset($_SESSION['last_active']) && time() - $_SESSION['last_active'] > 1800) {
        $_SESSION = [];
        flash('Your session expired. Please log in again.');
        redirect('index.php?action=login');
    }

    $_SESSION['last_active'] = time();
}

// Returns the CSRF token, creating it on first use.
function csrf_token()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

// Builds the hidden CSRF input for a form.
function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

// Rejects a POST request that does not carry the session token.
function verify_csrf()
{
    $sent  = $_POST['csrf_token'] ?? '';
    $known = $_SESSION['csrf_token'] ?? '';

    if (!is_string($sent) || $known === '' || !hash_equals($known, $sent)) {
        http_response_code(400);
        exit('Invalid request. Please go back and try again.');
    }
}

// Reads a numeric id from GET or POST. Returns 0 for anything invalid.
function request_id()
{
    $value = $_GET['id'] ?? $_POST['id'] ?? null;

    return is_string($value) ? (int)$value : 0;
}

// Sends the 404 page.
function not_found()
{
    http_response_code(404);
    require __DIR__ . '/../views/errors/404.php';
    exit;
}
