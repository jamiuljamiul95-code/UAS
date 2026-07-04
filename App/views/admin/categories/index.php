<?php
$activeMenu = 'categories';
require ROOT . '/app/views/admin/partials/admin-header.php';

// Kelompokkan kategori: parent → children
$parents  = [];
$children = [];
foreach ($categories as $cat) {
    if ($cat['parent_id'] === null) {
        $parents[$cat['id']] = $cat;
        $parents[$cat['id']]['children'] = [];
    } else {
        $children[] = $cat;
    }
}
foreach ($children as $child) {
    if (isset($parents[$child['parent_id']])) {
        $parents[$child['parent_id']]['children'][] = $child;
    }
}
?>

<style>
.cat-parent-row td { background: #f8f7ff; font-weight: 600; }
.cat-parent-row td:first-child { border-left: 4px solid #7C3AED; }
.cat-child-row td:first-child { border-left: 4px solid #e5e7eb; padding-left: 28px; }
.cat-child-row td { background: #fff; color: #4b5563; font-size: 13px; }
.badge-parent {
  background: rgba(124,58,237,0.1); color: #7C3AED;
  font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 20px;
}
.badge-child {
  background: rgba(0,0,0,0.05); color: #6b7280;
  font-size: 11px; font-weight: 500; padding: 2px 8px; border-radius: 20px;
}
.cat-prefix { color: #d1d5db; margin-right: 4px; }
</style>

<div class="admin-topbar">
  <h1>Kelola Kategori</h1>
  <button type="button" class="btn-admin-primary" data-bs-toggle="collapse" data-bs-target="#formTambah">
    + Tambah Kategori
  </button>
</div>

<!-- Form Tambah -->
<div class="collapse mb-4" id="formTambah">
  <div class="admin-card">
    <h6 class="fw-semibold mb-3">Tambah Kategori Baru</h6>
    <form action="<?= BASE_URL ?>/admin/categories" method="POST" class="admin-form row g-3">
      <div class="col-md-4">
        <label>Nama Kategori</label>
        <input type="text" name="name" class="form-control" placeholder="Contoh: Template Notion" required>
      </div>
      <div class="col-md-3">
        <label>Icon (Tabler Icons)</label>
        <input type="text" name="icon" class="form-control" placeholder="ti-template">
      </div>
      <div class="col-md-3">
        <label>Parent Kategori</label>
        <select name="parent_id" class="form-select">
          <option value="">— Jadikan Kategori Utama —</option>
          <?php foreach ($parents as $p): ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn-admin-primary w-100">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Tabel Kategori Terstruktur -->
<div class="admin-card">
  <table class="admin-table">
    <thead>
      <tr>
        <th>Nama</th>
        <th>Tipe</th>
        <th>Slug</th>
        <th>Icon</th>
        <th>Sub-kategori</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($parents as $parent): ?>
        <!-- Baris Parent -->
        <tr class="cat-parent-row">
          <td>
            <i class="ti <?= htmlspecialchars($parent['icon'] ?: 'ti-folder') ?>"
               style="color:#7C3AED;margin-right:8px"></i>
            <?= htmlspecialchars($parent['name']) ?>
          </td>
          <td><span class="badge-parent">Utama</span></td>
          <td><code><?= htmlspecialchars($parent['slug']) ?></code></td>
          <td><code><?= htmlspecialchars($parent['icon'] ?: '-') ?></code></td>
          <td>
            <span style="color:#7C3AED;font-weight:600">
              <?= count($parent['children']) ?> sub-kategori
            </span>
          </td>
          <td class="table-action">
            <button class="btn-action-edit" data-bs-toggle="collapse"
                    data-bs-target="#edit-parent-<?= $parent['id'] ?>">Edit</button>
            <form action="<?= BASE_URL ?>/admin/categories/delete" method="POST"
                  style="display:inline"
                  onsubmit="return confirm('Hapus kategori utama ini? Semua sub-kategorinya juga akan terhapus!')">
              <input type="hidden" name="id" value="<?= $parent['id'] ?>">
              <button type="submit" class="btn-action-delete">Hapus</button>
            </form>
          </td>
        </tr>
        <!-- Form Edit Parent (inline collapse) -->
        <tr class="collapse" id="edit-parent-<?= $parent['id'] ?>">
          <td colspan="6" style="background:#f8f7ff;padding:16px">
            <form action="<?= BASE_URL ?>/admin/categories/update" method="POST"
                  class="admin-form row g-3">
              <input type="hidden" name="id" value="<?= $parent['id'] ?>">
              <input type="hidden" name="parent_id" value="">
              <div class="col-md-5">
                <label>Nama</label>
                <input type="text" name="name" class="form-control"
                       value="<?= htmlspecialchars($parent['name']) ?>" required>
              </div>
              <div class="col-md-4">
                <label>Icon</label>
                <input type="text" name="icon" class="form-control"
                       value="<?= htmlspecialchars($parent['icon']) ?>">
              </div>
              <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn-admin-primary w-100">Update</button>
              </div>
            </form>
          </td>
        </tr>

        <!-- Baris Children -->
        <?php foreach ($parent['children'] as $child): ?>
          <tr class="cat-child-row">
            <td>
              <span class="cat-prefix">├</span>
              <i class="ti <?= htmlspecialchars($child['icon'] ?: 'ti-folder') ?>"
                 style="color:#9ca3af;margin-right:6px;font-size:13px"></i>
              <?= htmlspecialchars($child['name']) ?>
            </td>
            <td><span class="badge-child">Sub</span></td>
            <td><code style="font-size:11px"><?= htmlspecialchars($child['slug']) ?></code></td>
            <td><code style="font-size:11px"><?= htmlspecialchars($child['icon'] ?: '-') ?></code></td>
            <td><span class="text-secondary small">—</span></td>
            <td class="table-action">
              <button class="btn-action-edit" data-bs-toggle="collapse"
                      data-bs-target="#edit-child-<?= $child['id'] ?>">Edit</button>
              <form action="<?= BASE_URL ?>/admin/categories/delete" method="POST"
                    style="display:inline"
                    onsubmit="return confirm('Hapus sub-kategori ini?')">
                <input type="hidden" name="id" value="<?= $child['id'] ?>">
                <button type="submit" class="btn-action-delete">Hapus</button>
              </form>
            </td>
          </tr>
          <!-- Form Edit Child -->
          <tr class="collapse" id="edit-child-<?= $child['id'] ?>">
            <td colspan="6" style="background:#fafafa;padding:16px">
              <form action="<?= BASE_URL ?>/admin/categories/update" method="POST"
                    class="admin-form row g-3">
                <input type="hidden" name="id" value="<?= $child['id'] ?>">
                <div class="col-md-4">
                  <label>Nama</label>
                  <input type="text" name="name" class="form-control"
                         value="<?= htmlspecialchars($child['name']) ?>" required>
                </div>
                <div class="col-md-3">
                  <label>Icon</label>
                  <input type="text" name="icon" class="form-control"
                         value="<?= htmlspecialchars($child['icon']) ?>">
                </div>
                <div class="col-md-3">
                  <label>Pindah ke Parent</label>
                  <select name="parent_id" class="form-select">
                    <?php foreach ($parents as $p): ?>
                      <option value="<?= $p['id'] ?>"
                        <?= $p['id'] == $child['parent_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['name']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                  <button type="submit" class="btn-admin-primary w-100">Update</button>
                </div>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>

      <?php endforeach; ?>

      <?php if (empty($parents)): ?>
        <tr class="empty-row"><td colspan="6">Belum ada kategori.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require ROOT . '/app/views/admin/partials/admin-footer.php'; ?>