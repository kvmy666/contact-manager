# Contact Manager

A small contact manager built as a hand-written MVC application in plain PHP 8 for the Advanced Computer assignment. It covers the unit topics 3–7: MySQL access through PDO and the custom MVC framework. The application has a front controller, models, views, controllers, a single administrator login and full create/read/update/delete for contacts. Every query that uses input is a prepared statement, every state-changing form is protected by a CSRF token, and all contact input is validated on the server. There is no framework, no Composer package and no JavaScript.

- Student: Abdulkarim
- Student ID: `202202900`
- Repository : https://github.com/kvmy666/contact-manager
- Submission archive: `contact-manager.zip`

## What it does

- Lists every contact with name, email, phone and the date it was added.
- Lets a signed-in administrator add, edit and delete contacts.
- Provides one administrator login; there is no public registration.
- Validates contact input on the server before it reaches the database.
- Shows one-time flash messages after login, logout, create, update and delete.
- Asks for confirmation on a separate page before a contact is deleted.
- Returns a real 404 page for unknown actions and for missing contact ids.

## Requirements

- PHP 8.0 or newer with the `pdo_mysql` and `mbstring` extensions.
- MySQL 5.7+ or MariaDB 10.4+.
- Apache, Nginx or PHP's built-in web server.
- No Composer, no npm and no build step.

## Folder structure

```text
contact-manager/
├── config/
│   ├── database.php           Opens the PDO connection, reads DB_* environment variables
│   └── helpers.php            Escaping, redirect, flash, login, CSRF, id and 404 helpers
├── controllers/
│   ├── AuthController.php     Login and logout
│   └── ContactController.php  List, create, store, edit, update and delete actions
├── models/
│   ├── Model.php              Base class that supplies the shared PDO connection
│   ├── Contact.php            Contact queries and validation rules
│   └── Admin.php              Admin lookup by username
├── views/
│   ├── layout/
│   │   ├── header.php         Document head, navigation and flash message
│   │   └── footer.php         Closing markup
│   ├── auth/
│   │   └── login.php          Administrator login form
│   ├── contacts/
│   │   ├── index.php          Contact table
│   │   ├── create.php         Add-contact form
│   │   ├── edit.php           Edit-contact form
│   │   └── delete.php         Delete confirmation page
│   └── errors/
│       └── 404.php            Not-found page
├── public/
│   ├── index.php              Front controller: headers, session, action switch
│   └── assets/
│       └── style.css          The only stylesheet
├── database/
│   └── contact_manager.sql    Schema, three sample contacts and the admin account
├── .gitignore                 Ignores log files and operating-system metadata
└── README.md                  This file
```

Only `public/` is meant to be reachable from the web. That is why the front controller and the stylesheet live there, while configuration, models, controllers, views and SQL stay outside the document root.

## How a request flows

1. The browser requests `index.php?action=...`, which is `public/index.php` on disk because `public/` is the document root. When no action is given, `$action` defaults to `index`.
2. The front controller removes the `X-Powered-By` header, sets the security headers and session cookie options, then starts the session. It loads the helpers and both controllers.
3. A `switch` statement acts as an allowlist. Only the known actions can run; anything else calls `not_found()`, which sends HTTP 404 and renders `views/errors/404.php`.
4. The controller applies access control and CSRF checks, reads input with `post()` or `request_id()`, calls `Contact::validate()`, and then calls a model method with the clean values.
5. The model runs a prepared statement through PDO and returns rows or a boolean. The controller requires the matching view, and the view escapes every dynamic value with `e()`. After a successful POST the controller sets a flash message and redirects, so refreshing the browser cannot repeat the write.

## Routes

| URL | Method | What it does | Login required |
| --- | --- | --- | --- |
| `index.php` or `index.php?action=index` | GET | Lists all contacts | No |
| `index.php?action=create` | GET | Shows the add-contact form | Yes |
| `index.php?action=store` | POST | Validates and inserts a contact, then redirects | Yes |
| `index.php?action=edit&id=N` | GET | Shows the edit form for contact `N` | Yes |
| `index.php?action=update` | POST | Validates and saves changes, then redirects | Yes |
| `index.php?action=delete&id=N` | GET | Shows the delete confirmation page | Yes |
| `index.php?action=delete` | POST | Deletes the contact after the CSRF check | Yes |
| `index.php?action=login` | GET, POST | Shows the login form and processes it | No |
| `index.php?action=logout` | POST | Destroys the session and redirects | No |
| Any other `action` value | Any | Returns the 404 page | No |

`store`, `update` and `logout` redirect a non-POST request. A GET to `delete` only shows the confirmation page; the delete itself requires a POST, sent from that page.

## Setup

### 1. Create the database

Import the SQL script with the MySQL client:

```bash
mysql -u root -p < database/contact_manager.sql
```

Alternatively, use phpMyAdmin's Import tab and select `database/contact_manager.sql`.

The script creates the `contact_manager` database if needed, **drops and recreates** the `contacts` and `admins` tables, then inserts three sample contacts and the single `admin` account. Running it again therefore erases all existing contacts and resets the administrator password.

### 2. Configure the connection

`config/database.php` reads five environment variables and falls back to local XAMPP defaults:

| Variable | Default |
| --- | --- |
| `DB_HOST` | `127.0.0.1` |
| `DB_PORT` | `3306` |
| `DB_NAME` | `contact_manager` |
| `DB_USER` | `root` |
| `DB_PASS` | empty |

Either edit those defaults in `config/database.php`, or leave the file untouched and set the variables before starting PHP. For example, in a POSIX shell:

```bash
export DB_HOST=127.0.0.1
export DB_PORT=3306
export DB_NAME=contact_manager
export DB_USER=root
export DB_PASS=
```

In Windows PowerShell the equivalent is `$env:DB_USER = "root"` and so on. Environment variables are read at connection time, so they must be present in the process that runs PHP.

Do not commit real credentials. The repository contains only the local defaults above; a real password belongs in the environment, not in the file.

### 3. Run the app

Option A, XAMPP or another Apache installation: place the project folder under `htdocs`, start Apache and MySQL, and open `http://localhost/contact-manager/public/`. Set the site's document root to the `public/` folder rather than the project root. Serving the project root directly would expose `config/`, `database/` and the rest of the application.

Option B, PHP's built-in server:

```bash
cd contact-manager
php -S localhost:8000 -t public
```

Then open `http://localhost:8000/`. The `-t public` option makes `public/` the document root, so the same protection applies.

### 4. Log in

Open the login page and use the demo account:

- Username: `admin`
- Password: `Admin@123`

This password is published in this README, so it is a demo credential only. To change it, generate a new hash:

```bash
php -r "echo password_hash('YourNewPassword', PASSWORD_DEFAULT), PHP_EOL;"
```

Update the stored hash:

```sql
UPDATE admins SET password_hash = '<the new hash>' WHERE username = 'admin';
```

Importing `database/contact_manager.sql` again restores the demo hash, so run the `UPDATE` again afterwards.

## Security checklist

| Protection | How it works | Where |
| --- | --- | --- |
| SQL injection | Every query that uses input is a prepared statement with `?` placeholders; values are passed in `execute()`. `PDO::ATTR_EMULATE_PREPARES` is `false`, so MySQL prepares the statements itself. No variable is concatenated into SQL. | `models/Contact.php`, `models/Admin.php`, `config/database.php` |
| Cross-site scripting (XSS) | Every dynamic value is printed through `e()`, which calls `htmlspecialchars` with `ENT_QUOTES \| ENT_SUBSTITUTE` and UTF-8. The only output not passed through `e()` is the hidden CSRF input built by `csrf_field()`, whose value is escaped inside the helper. | `config/helpers.php`, all `views/` |
| CSRF | A 256-bit token from `random_bytes(32)` is stored in the session and written into every POST form. `verify_csrf()` compares it with `hash_equals()` and rejects a missing, empty or mismatched token with HTTP 400. | `config/helpers.php` (`csrf_token`, `csrf_field`, `verify_csrf`), all POST handlers |
| Password storage | The seed file stores only a bcrypt hash produced by `password_hash(..., PASSWORD_DEFAULT)`. Login uses `password_verify()`. No plaintext password appears in the database or in the code. | `database/contact_manager.sql`, `controllers/AuthController.php` |
| Session security | The session cookie is `HttpOnly` and `SameSite=Lax`, and `Secure` is set automatically under HTTPS. `session.use_strict_mode` is enabled, the session id is regenerated on login, and the CSRF token is rotated at the same time. A session idle for more than 30 minutes is cleared on the next protected request, and logout clears and destroys the session. | `public/index.php`, `config/helpers.php`, `controllers/AuthController.php` |
| Access control | `require_login()` guards create, store, edit, update and delete. Guests are redirected to the login page with a flash message. Deleting additionally requires POST, so a link or a GET request can only reach the confirmation page, never perform the delete. | `controllers/ContactController.php` |
| Server-side validation | Required fields are checked, email format is checked with `filter_var`, lengths match the column sizes (name 100, email 190, phone 30), and phone numbers may only contain digits, spaces, `+`, `-`, `(`, `)` and `.` with a minimum length of 7. The same rules apply to create and update. Array input is treated as an empty string. | `models/Contact.php` (`validate`), `config/helpers.php` (`post`) |
| Routing safety | The front controller uses an explicit `switch` allowlist. No class, method or file name comes from the request, and an unknown action returns a genuine 404 response. | `public/index.php` |
| HTTP method discipline | All writes use POST. Delete and logout require POST plus a valid CSRF token. Successful writes end in a redirect, following POST/Redirect/GET. | `controllers/ContactController.php`, `controllers/AuthController.php` |
| Error handling | `display_errors` is off and errors are logged. A failed database connection logs the exception and shows only a generic message. Exception text, SQL and file paths are never sent to the visitor. | `public/index.php`, `config/database.php` |
| Security headers | Responses set `X-Content-Type-Options: nosniff`, `X-Frame-Options: DENY`, `Referrer-Policy: same-origin` and `Content-Security-Policy: default-src 'self'`. The `X-Powered-By` header is removed. | `public/index.php` |
| Configuration and deployment | Database settings can come from environment variables, no real secret is committed, and only `public/` is web-exposed. Secure cookies switch on automatically when the request is HTTPS. | `config/database.php`, `public/index.php` |

### Where these controls came from

The unit material covered PDO prepared statements, `htmlspecialchars`, `password_hash`/`password_verify` and the action allowlist, and those form the core of the implementation. CSRF tokens, session hardening, the security headers and the array-safe input helper were not taken from the lecture slides; they were added from standard PHP and web-security practice, mainly the PHP manual pages for sessions and password hashing, and OWASP guidance on CSRF and XSS. I am stating this explicitly so the marker can distinguish what was learned in the unit from what was researched independently.

## Manual verification performed

The checks below were made by hand with HTTP requests against the running application, on PHP 8.2.12 with MariaDB 10.4.32.

| Area | Check | Result |
| --- | --- | --- |
| Access control | Visiting add, edit or delete while signed out redirects to the login page | Passed |
| SQL injection | Login as `admin' OR '1'='1` is rejected | Passed |
| Authentication | Valid login succeeds; logout ends the session | Passed |
| CRUD | Create, edit and delete each update the list and the database | Passed |
| Validation | Invalid email and a too-short phone are rejected, and the submitted values are kept in the form | Passed |
| CSRF | A POST without a token returns HTTP 400 | Passed |
| XSS | A contact name containing a script payload is rendered as text, not executed | Passed |
| Array input | Array values in place of text fields are treated as empty and do not cause an error | Passed |
| Routing | Unknown actions and missing or invalid contact ids return HTTP 404 | Passed |
| Headers | The four security headers are present and `X-Powered-By` is absent | Passed |
| Errors | No PHP warnings or notices appeared during these checks | Passed |

The project has no automated test suite; these checks were made with HTTP requests against the running app. Re-run them after changing the code.

## Assignment requirements

| Brief requirement | Where it is met |
| --- | --- |
| A `contacts` table with `id`, `name`, `email`, `phone` and `created_at` | `database/contact_manager.sql` |
| Full CRUD for contacts | `controllers/ContactController.php`, `models/Contact.php`, `views/contacts/` |
| Separate `models/`, `views/` and `controllers/` folders | Project root; each folder contains the classes or templates for its layer |
| A front controller | `public/index.php`, reached through `?action=...` routes |
| One administrator login | `admins` table in the SQL script, `controllers/AuthController.php`, `views/auth/login.php` |
| Only logged-in users can add, edit or delete | `require_login()` at the start of each write action in `controllers/ContactController.php` |
| A SQL script for the database | `database/contact_manager.sql` |
| A README with a security list and an integrity statement | This file |

## Integrity statement

*Edit the bracketed details and make sure every sentence is true for you before submitting. Follow your unit's rules on AI assistance.*

I declare that this project is my own work. I can explain every file and every security control in it, including why each one is present and how it behaves. I used an AI coding assistant to produce the first draft of the code and of this README, and I then read, reviewed, rewrote and tested the final project myself. No code was copied from another student. My sources were the unit lecture material in Modules 1–4 (MVC, forms, PDO and SQL injection) and the PHP manual pages for PDO, `password_hash`, sessions and `htmlspecialchars`, together with OWASP guidance on CSRF and XSS.

```text
Name:        Abdualkrim

Student ID:  202202900


```

## Known limitations

- There is one seeded administrator account. There is no registration, password reset or user management.
- The demo password is published in this README and must be changed before any real use.
- The contact list is public by design, as required by the brief; only writes are protected.
- There is no search, no sort control and no pagination. The list is always ordered by name.
- Duplicate email addresses are allowed; the database has no unique constraint on `email`.
- There is no login rate limiting and no account lockout.
- There is no audit log of changes.
- There is no automated test suite.
- The security headers are sent by PHP for application responses. Static files such as the stylesheet are served by the web server, so their headers depend on the server configuration.

## Submission contents

- Repository: https://github.com/kvmy666/contact-manager (private)
- Archive: `contact-manager.zip`
- SQL script inside the archive: `database/contact_manager.sql`
- README inside the archive: `README.md`
