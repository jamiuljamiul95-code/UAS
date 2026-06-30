<?php
$activeMenu = 'products';
require ROOT . '/app/views/admin/partials/admin-header.php';
?>

<div class="admin-topbar">
  <h1>Edit Produk</h1>
  <a href="<?= BASE_URL ?>/admin/products" class="btn btn-sm btn-outline-secondary">← Kembali</a>
</div>

<div class="admin-card">
  <?php if (!empty($error)): ?>
    <div class="alert-admin-error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form action="<?= BASE_URL ?>/admin/products/update" method="POST" enctype="multipart/form-data" class="admin-form">
    <input type="hidden" name="id" value="<?= $product['id'] ?>">

    <div class="row g-3 mb-3">
      <div class="col-md-8">
        <label>Judul Produk</label>
        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($product['title']) ?>" required>
      </div>
      <div class="col-md-4">
        <label>Kategori</label>
        <select name="category_id" class="form-select" required>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($cat['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="mb-3">
      <label>Deskripsi</label>
      <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($product['description']) ?></textarea>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-md-4">
        <label>Harga (Rp)</label>
        <input type="number" name="price" class="form-control" value="<?= $product['price'] ?>" min="0" required>
      </div>
      <div class="col-md-4">
        <label>Diskon (%)</label>
        <input type="number" name="discount" class="form-control" value="<?= $product['discount'] ?>" min="0" max="100">
      </div>
      <div class="col-md-4">
        <label>Status</label>
        <select name="status" class="form-select">
          <option value="draft" <?= $product['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
          <option value="published" <?= $product['status'] === 'published' ? 'selected' : '' ?>>Published</option>
        </select>
      </div>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <label>Thumbnail</label>
        <?php if ($product['thumbnail']): ?>
          <img src="<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($product['thumbnail']) ?>" class="thumb mb-2" style="width:60px;height:60px">
        <?php endif; ?>
        <div class="upload-box">
          <input type="file" name="thumbnail" accept=".jpg,.jpeg,.png,.webp" class="form-control form-control-sm">
          <small>Kosongkan jika tidak ingin mengganti.</small>
        </div>
      </div>
      <div class="col-md-4">
        <label>Preview Image</label>
        <?php if ($product['preview_image']): ?>
          <img src="<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($product['preview_image']) ?>" class="thumb mb-2" style="width:60px;height:60px">
        <?php endif; ?>
        <div class="upload-box">
          <input type="file" name="preview_image" accept=".jpg,.jpeg,.png,.webp" class="form-control form-control-sm">
          <small>Kosongkan jika tidak ingin mengganti.</small>
        </div>
      </div>
      <div class="col-md-4">
        <label>File Digital</label>
        <?php if ($product['file_path']): ?>
          <p class="small text-success mb-2">✓ File sudah ada</p>
        <?php endif; ?>
        <div class="upload-box">
          <input type="file" name="file_path" accept=".zip,.rar,.pdf,.psd,.ai" class="form-control form-control-sm">
          <small>Kosongkan jika tidak ingin mengganti.</small>
        </div>
      </div>
    </div>

    <button type="submit" class="btn-admin-primary">Update Produk</button>
  </form>
</div>

<?php require ROOT . '/app/views/admin/partials/admin-footer.php'; ?>
