<?php
// Lists all contacts in a table.
require __DIR__ . '/../layout/header.php';
?>
<h1>Contacts</h1>

<?php if (!is_logged_in()): ?>
    <p class="note">Log in to add, edit or delete contacts.</p>
<?php endif; ?>

<?php if (is_logged_in()): ?>
    <p><a class="button" href="index.php?action=create">Add contact</a></p>
<?php endif; ?>

<?php if (empty($contacts)): ?>
    <p>No contacts yet.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Added</th>
                <?php if (is_logged_in()): ?>
                    <th>Actions</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contacts as $c): ?>
                <tr>
                    <td><?= e($c['name']) ?></td>
                    <td><?= e($c['email']) ?></td>
                    <td><?= e($c['phone']) ?></td>
                    <td><?= e(date('d M Y', strtotime($c['created_at']))) ?></td>
                    <?php if (is_logged_in()): ?>
                        <td class="actions">
                            <a href="index.php?action=edit&amp;id=<?= e($c['id']) ?>">Edit</a>
                            <a href="index.php?action=delete&amp;id=<?= e($c['id']) ?>">Delete</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require __DIR__ . '/../layout/footer.php'; ?>
