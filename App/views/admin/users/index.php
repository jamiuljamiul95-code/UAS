<?php
$activeMenu = 'users';
require ROOT . '/app/views/admin/partials/admin-header.php';
?>

<div class="admin-topbar">
  <h1>Kelola User</h1>
</div>

<div class="admin-card">
  <table class="admin-table">
    <thead>
      <tr>
        <th>Nama</th>
        <th>Email</th>
        <th>Role</th>
        <th>Status</th>
        <th>Bergabung</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($users)): ?>
        <?php foreach ($users as $u): ?>
          <tr>
            <td class="fw-medium"><?= htmlspecialchars($u['name']) ?></td>
            <td class="text-secondary"><?= htmlspecialchars($u['email']) ?></td>
            <td>
              <span class="status-badge <?= $u['role'] === 'admin' ? 'status-published' : 'status-draft' ?>">
                <?= $u['role'] === 'admin' ? 'Admin' : 'Customer' ?>
              </span>
            </td>
            <td>
              <span class="status-badge <?= $u['status'] === 'active' ? 'status-published' : 'status-draft' ?>">
                <?= $u['status'] === 'active' ? 'Aktif' : 'Nonaktif' ?>
              </span>
            </td>
            <td class="text-secondary"><?= date('d M Y', strtotime($u['created_at'])) ?></td>
            <td class="table-action">
              <?php if ((int)$u['id'] !== (int)$_SESSION['user_id']): ?>
                <form action="<?= BASE_URL ?>/admin/users/toggle-status" method="POST" style="display:inline">
                  <input type="hidden" name="id" value="<?= $u['id'] ?>">
                  <button type="submit" class="btn-action-edit">
                    <?= $u['status'] === 'active' ? 'Nonaktifkan' : 'Aktifkan' ?>
                  </button>
                </form>
                <form action="<?= BASE_URL ?>/admin/users/delete" method="POST" style="display:inline" onsubmit="return confirm('Hapus user ini secara permanen?')">
                  <input type="hidden" name="id" value="<?= $u['id'] ?>">
                  <button type="submit" class="btn-action-delete">Hapus</button>
                </form>
              <?php else: ?>
                <span class="text-secondary small">— Akun kamu —</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr class="empty-row"><td colspan="6">Belum ada user terdaftar.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require ROOT . '/app/views/admin/partials/admin-footer.php'; ?>