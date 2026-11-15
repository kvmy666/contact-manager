<?php
// Admin login form.
require __DIR__ . '/../layout/header.php';
?>
<h1>Admin login</h1>

<?php if ($error !== ''): ?>
    <p class="error"><?= e($error) ?></p>
<?php endif; ?>

<form method="post" action="index.php?action=login">
    <?= csrf_field() ?>

    <label for="username">Username</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>

    <div class="form-actions">
        <button type="submit">Log in</button>
    </div>
</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>
