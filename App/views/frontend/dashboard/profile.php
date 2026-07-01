<?php require ROOT . '/app/views/frontend/partials/header.php'; ?>

<div class="container py-5">
  <div class="row g-4">
    <div class="col-md-3">
      <?php require ROOT . '/app/views/frontend/dashboard/partials/sidebar.php'; ?>
    </div>

    <div class="col-md-9">
      <h4 class="fw-bold mb-4">Profil Saya</h4>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
      <?php endif; ?>

      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h6 class="fw-semibold mb-3">Data Diri</h6>

          <div class="d-flex align-items-center gap-3 mb-3">
            <img src="<?= $user['photo'] ? BASE_URL . '/assets/images/avatars/' . htmlspecialchars($user['photo']) : 'https://ui-avatars.com/api/?name=' . urlencode($user['name']) ?>"
                 alt="Foto profil" class="rounded-circle" width="64" height="64" style="object-fit:cover">
          </div>

          <form action="<?= BASE_URL ?>/dashboard/profile/update" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
              <label class="form-label">Nama</label>
              <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Foto Profil</label>
              <input type="file" name="photo" class="form-control" accept=".jpg,.jpeg,.png,.webp">
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          </form>
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="fw-semibold mb-3">Ganti Password</h6>
          <form action="<?= BASE_URL ?>/dashboard/password/update" method="POST">
            <div class="mb-3">
              <label class="form-label">Password Lama</label>
              <input type="password" name="old_password" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password Baru</label>
              <input type="password" name="new_password" class="form-control" minlength="8" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Konfirmasi Password Baru</label>
              <input type="password" name="confirm_password" class="form-control" minlength="8" required>
            </div>
            <button type="submit" class="btn btn-outline-primary">Ganti Password</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>