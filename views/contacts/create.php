<?php
// Form for adding a new contact.
require __DIR__ . '/../layout/header.php';
?>
<h1>Add contact</h1>

<?php if (!empty($errors)): ?>
    <ul class="errors">
        <?php foreach ($errors as $error): ?>
            <li><?= e($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post" action="index.php?action=store">
    <?= csrf_field() ?>

    <label for="name">Name</label>
    <input type="text" id="name" name="name" value="<?= e($old['name']) ?>" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?= e($old['email']) ?>" required>

    <label for="phone">Phone</label>
    <input type="tel" id="phone" name="phone" value="<?= e($old['phone']) ?>" required>

    <div class="form-actions">
        <button type="submit">Save contact</button>
        <a class="cancel" href="index.php">Cancel</a>
    </div>
</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>
