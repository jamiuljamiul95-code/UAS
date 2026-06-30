<?php
$activeMenu = 'products';
require ROOT . '/app/views/admin/partials/admin-header.php';
?>

<div class="admin-topbar">
  <h1>Tambah Produk</h1>
  <a href="<?= BASE_URL ?>/admin/products" class="btn btn-sm btn-outline-secondary">← Kembali</a>
</div>

<div class="admin-card">
  <?php if (!empty($error)): ?>
    <div class="alert-admin-error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form action="<?= BASE_URL ?>/admin/products" method="POST" enctype="multipart/form-data" class="admin-form">

    <div class="row g-3 mb-3">
      <div class="col-md-8">
        <label>Judul Produk</label>
        <input type="text" name="title" class="form-control" placeholder="Contoh: Template Canva Feed Instagram Aesthetic" required>
      </div>
      <div class="col-md-4">
        <label>Kategori</label>
        <select name="category_id" class="form-select" required>
          <option value="">— Pilih Kategori —</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="mb-3">
      <label>Deskripsi</label>
      <textarea name="description" class="form-control" rows="4" placeholder="Jelaskan produk ini, format file, jumlah halaman/varian, dll."></textarea>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-md-4">
        <label>Harga (Rp)</label>
        <input type="number" name="price" class="form-control" placeholder="50000" min="0" required>
      </div>
      <div class="col-md-4">
        <label>Diskon (%)</label>
        <input type="number" name="discount" class="form-control" placeholder="0" min="0" max="100" value="0">
      </div>
      <div class="col-md-4">
        <label>Status</label>
        <select name="status" class="form-select">
          <option value="draft">Draft (belum tampil di toko)</option>
          <option value="published">Published (langsung tampil)</option>
        </select>
      </div>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <label>Thumbnail</label>
        <div class="upload-box">
          <input type="file" name="thumbnail" accept=".jpg,.jpeg,.png,.webp" class="form-control form-control-sm">
          <small>Gambar utama di kartu produk. JPG/PNG/WEBP, maks 3MB.</small>
        </div>
      </div>
      <div class="col-md-4">
        <label>Preview Image</label>
        <div class="upload-box">
          <input type="file" name="preview_image" accept=".jpg,.jpeg,.png,.webp" class="form-control form-control-sm">
          <small>Gambar besar di halaman detail produk. Opsional.</small>
        </div>
      </div>
      <div class="col-md-4">
        <label>File Digital</label>
        <div class="upload-box">
          <input type="file" name="file_path" accept=".zip,.rar,.pdf,.psd,.ai" class="form-control form-control-sm">
          <small>File yang akan didownload pembeli. ZIP/RAR/PDF/PSD/AI, maks 200MB.</small>
        </div>
      </div>
    </div>

    <button type="submit" class="btn-admin-primary">Simpan Produk</button>
  </form>
</div>

<?php require ROOT . '/app/views/admin/partials/admin-footer.php'; ?>
