<?php
// Form for editing an existing contact.
require __DIR__ . '/../layout/header.php';

$values = $old ?? $contact;
?>
<h1>Edit contact</h1>

<?php if (!empty($errors)): ?>
    <ul class="errors">
        <?php foreach ($errors as $error): ?>
            <li><?= e($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post" action="index.php?action=update">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= e($contact['id']) ?>">

    <label for="name">Name</label>
    <input type="text" id="name" name="name" value="<?= e($values['name']) ?>" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?= e($values['email']) ?>" required>

    <label for="phone">Phone</label>
    <input type="tel" id="phone" name="phone" value="<?= e($values['phone']) ?>" required>

    <div class="form-actions">
        <button type="submit">Save changes</button>
        <a class="cancel" href="index.php">Cancel</a>
    </div>
</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>
