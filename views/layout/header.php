<?php
// Top of every page: head, site header and navigation.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Manager</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <header class="site-header">
        <a class="site-title" href="index.php">Contact Manager</a>
        <nav>
            <a href="index.php">Contacts</a>
            <?php if (is_logged_in()): ?>
                <a href="index.php?action=create">Add contact</a>
                <span class="signed-in">Signed in as <?= e($_SESSION['admin_username']) ?></span>
                <form class="inline-form" method="post" action="index.php?action=logout">
                    <?= csrf_field() ?>
                    <button type="submit">Log out</button>
                </form>
            <?php else: ?>
                <a href="index.php?action=login">Log in</a>
            <?php endif; ?>
        </nav>
    </header>

    <?php $flash = get_flash(); ?>
    <?php if ($flash): ?>
        <p class="flash"><?= e($flash) ?></p>
    <?php endif; ?>
