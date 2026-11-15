<?php
// Confirmation page before deleting a contact.
require __DIR__ . '/../layout/header.php';
?>
<h1>Delete contact</h1>

<p>Are you sure you want to delete this contact? This cannot be undone.</p>

<p>
    <strong><?= e($contact['name']) ?></strong><br>
    <?= e($contact['email']) ?>
</p>

<form method="post" action="index.php?action=delete">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= e($contact['id']) ?>">

    <div class="form-actions">
        <button type="submit">Yes, delete</button>
        <a class="cancel" href="index.php">Cancel</a>
    </div>
</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>
