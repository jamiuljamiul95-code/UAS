<?php
$activeMenu = 'products';
require ROOT . '/app/views/admin/partials/admin-header.php';
?>

<div class="admin-topbar">
  <h1>Kelola Produk</h1>
  <a href="<?= BASE_URL ?>/admin/products/create" class="btn-admin-primary">+ Tambah Produk</a>
</div>

<div class="admin-card">
  <table class="admin-table">
    <thead>
      <tr>
        <th>Thumbnail</th>
        <th>Judul</th>
        <th>Kategori</th>
        <th>Harga</th>
        <th>Status</th>
        <th>Terjual</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($products)): ?>
        <?php foreach ($products as $p): ?>
          <tr>
            <td>
              <img class="thumb" src="<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($p['thumbnail'] ?: 'placeholder.jpg') ?>" alt="">
            </td>
            <td><?= htmlspecialchars($p['title']) ?></td>
            <td><?= htmlspecialchars($p['category_name']) ?></td>
            <td>Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
            <td>
              <span class="status-badge <?= $p['status'] === 'published' ? 'status-published' : 'status-draft' ?>">
                <?= $p['status'] === 'published' ? 'Published' : 'Draft' ?>
              </span>
            </td>
            <td><?= (int)$p['sales'] ?></td>
            <td class="table-action">
              <a href="<?= BASE_URL ?>/admin/products/edit?id=<?= $p['id'] ?>" class="btn-action-edit">Edit</a>
              <form action="<?= BASE_URL ?>/admin/products/delete" method="POST" style="display:inline" onsubmit="return confirm('Hapus produk ini?')">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                <button type="submit" class="btn-action-delete">Hapus</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr class="empty-row"><td colspan="7">Belum ada produk. Klik "+ Tambah Produk" untuk menambahkan.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require ROOT . '/app/views/admin/partials/admin-footer.php'; ?>
