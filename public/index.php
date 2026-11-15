<?php
// Front controller: starts the session and runs the requested action.

// Error details are logged, never shown to the visitor.
// Set display_errors to '1' only while developing on your own machine.
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

header_remove('X-Powered-By');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: same-origin');
header('Content-Security-Policy: default-src \'self\'');

ini_set('session.use_strict_mode', '1');
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
    'secure' => !empty($_SERVER['HTTPS']),
]);
session_start();

require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../controllers/ContactController.php';
require_once __DIR__ . '/../controllers/AuthController.php';

$action = $_GET['action'] ?? 'index';

// Every URL uses ?action=... and only the actions listed here can run.
switch ($action) {
    case 'index':
        (new ContactController())->index();
        break;
    case 'create':
        (new ContactController())->create();
        break;
    case 'store':
        (new ContactController())->store();
        break;
    case 'edit':
        (new ContactController())->edit();
        break;
    case 'update':
        (new ContactController())->update();
        break;
    case 'delete':
        (new ContactController())->delete();
        break;
    case 'login':
        (new AuthController())->login();
        break;
    case 'logout':
        (new AuthController())->logout();
        break;
    default:
        not_found();
}
