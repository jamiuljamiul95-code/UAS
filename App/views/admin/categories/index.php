
<?php
$activeMenu = 'categories';
require ROOT . '/app/views/admin/partials/admin-header.php';
?>

<div class="admin-topbar">
  <h1>Kelola Kategori</h1>
  <button type="button" class="btn-admin-primary" data-bs-toggle="collapse" data-bs-target="#formTambah">
    + Tambah Kategori
  </button>
</div>

<div class="collapse mb-4" id="formTambah">
  <div class="admin-card">
    <h6 class="fw-semibold mb-3">Tambah Kategori Baru</h6>
    <form action="<?= BASE_URL ?>/admin/categories" method="POST" class="admin-form row g-3">
      <div class="col-md-6">
        <label>Nama Kategori</label>
        <input type="text" name="name" class="form-control" placeholder="Contoh: Template Notion" required>
      </div>
      <div class="col-md-6">
        <label>Icon (kelas Tabler Icons, opsional)</label>
        <input type="text" name="icon" class="form-control" placeholder="Contoh: ti-template, ti-folder">
      </div>
      <div class="col-12">
        <button type="submit" class="btn-admin-primary">Simpan Kategori</button>
      </div>
    </form>
  </div>
</div>

<div class="admin-card">
  <table class="admin-table">
    <thead>
      <tr>
        <th>Nama</th>
        <th>Slug</th>
        <th>Icon</th>
        <th>Dibuat</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($categories)): ?>
        <?php foreach ($categories as $cat): ?>
          <tr>
            <td class="fw-medium"><?= htmlspecialchars($cat['name']) ?></td>
            <td class="text-secondary"><?= htmlspecialchars($cat['slug']) ?></td>
            <td><code><?= htmlspecialchars($cat['icon'] ?: '-') ?></code></td>
            <td class="text-secondary"><?= date('d M Y', strtotime($cat['created_at'])) ?></td>
            <td class="table-action">
              <button type="button" class="btn-action-edit" data-bs-toggle="collapse" data-bs-target="#edit-<?= $cat['id'] ?>">Edit</button>
              <form action="<?= BASE_URL ?>/admin/categories/delete" method="POST" style="display:inline" onsubmit="return confirm('Hapus kategori ini? Produk di dalamnya juga akan ikut terhapus.')">
                <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                <button type="submit" class="btn-action-delete">Hapus</button>
              </form>
            </td>
          </tr>
          <tr class="collapse" id="edit-<?= $cat['id'] ?>">
            <td colspan="5" style="background:rgba(0,0,0,0.015)">
              <form action="<?= BASE_URL ?>/admin/categories/update" method="POST" class="admin-form row g-3 py-2">
                <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                <div class="col-md-5">
                  <label>Nama Kategori</label>
                  <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($cat['name']) ?>" required>
                </div>
                <div class="col-md-5">
                  <label>Icon</label>
                  <input type="text" name="icon" class="form-control" value="<?= htmlspecialchars($cat['icon']) ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                  <button type="submit" class="btn-admin-primary w-100">Update</button>
                </div>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr class="empty-row"><td colspan="5">Belum ada kategori. Klik "+ Tambah Kategori" untuk menambahkan.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require ROOT . '/app/views/admin/partials/admin-footer.php'; ?>