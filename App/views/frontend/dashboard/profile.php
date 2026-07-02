<?php require ROOT . '/app/views/frontend/partials/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">

<div class="dashboard-wrapper">
  <div class="container">
    <div class="row g-4">

      <!-- Sidebar -->
      <div class="col-lg-3">
        <?php require ROOT . '/app/views/frontend/dashboard/partials/sidebar.php'; ?>
      </div>

      <!-- Konten -->
      <div class="col-lg-9">
        <div class="dashboard-content">

          <?php if (!empty($error)): ?>
            <div class="alert-error-custom">⚠️ <?= htmlspecialchars($error) ?></div>
          <?php endif; ?>
          <?php if (!empty($success)): ?>
            <div class="alert-success-custom">✅ <?= htmlspecialchars($success) ?></div>
          <?php endif; ?>

          <!-- Form Data Diri -->
          <div class="dashboard-title">
            <i class="ti ti-user-circle"></i> Profil Saya
          </div>

          <form action="<?= BASE_URL ?>/dashboard/profile/update" method="POST" enctype="multipart/form-data">
            <div class="form-section">
              <h6><i class="ti ti-id-badge"></i> Data Diri</h6>
              <div class="row g-3">
                <div class="col-md-6">
                  <label>Nama Lengkap</label>
                  <input type="text" name="name" class="form-control"
                         value="<?= htmlspecialchars($user['name']) ?>" required>
                </div>
                <div class="col-md-6">
                  <label>Email</label>
                  <input type="email" name="email" class="form-control"
                         value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <div class="col-md-6">
                  <label>Foto Profil</label>
                  <input type="file" name="photo" accept=".jpg,.jpeg,.png,.webp" class="form-control">
                  <small class="text-secondary">JPG/PNG/WEBP, maks 2MB. Kosongkan jika tidak ingin mengubah.</small>
                </div>
              </div>
              <div class="mt-3">
                <button type="submit" class="btn-save">Simpan Perubahan</button>
              </div>
            </div>
          </form>

          <!-- Form Ganti Password -->
          <form action="<?= BASE_URL ?>/dashboard/password/update" method="POST">
            <div class="form-section mb-0">
              <h6><i class="ti ti-lock"></i> Ganti Password</h6>
              <div class="row g-3">
                <div class="col-md-4">
                  <label>Password Lama</label>
                  <input type="password" name="current_password" class="form-control"
                         placeholder="••••••••" required>
                </div>
                <div class="col-md-4">
                  <label>Password Baru</label>
                  <input type="password" name="new_password" class="form-control"
                         placeholder="Min. 8 karakter" required>
                </div>
                <div class="col-md-4">
                  <label>Konfirmasi Password Baru</label>
                  <input type="password" name="confirm_password" class="form-control"
                         placeholder="Ulangi password baru" required>
                </div>
              </div>
              <div class="mt-3">
                <button type="submit" class="btn-password">Ganti Password</button>
              </div>
            </div>
          </form>

        </div>
      </div>

    </div>
  </div>
</div>

<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>