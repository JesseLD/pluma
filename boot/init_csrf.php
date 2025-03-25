<?php

/**
 * This file handles CSRF protection for all incoming POST requests.
 * It should be required early in the application lifecycle,
 * ideally in bootstrap/app.php or public/index.php.
 *
 * The CSRF::validate() method checks if the submitted token
 * matches the one stored in the session.
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    \Core\CSRF::validate();
}
