<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar — Mizu Design</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth.css">
</head>
<body>
  <div class="auth-card">
    <h1>Mizu Design</h1>
    <p class="sub">Buat akun baru</p>

    <?php if (!empty($error)): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/register">
      <label>Nama Lengkap</label>
      <input type="text" name="name" placeholder="Nama kamu" required>

      <label>Email</label>
      <input type="email" name="email" placeholder="kamu@email.com" required>

      <label>Password</label>
      <input type="password" name="password" placeholder="Minimal 8 karakter" required>

      <label>Konfirmasi Password</label>
      <input type="password" name="confirm_password" placeholder="Ulangi password" required>

      <button type="submit" class="btn">Daftar</button>
    </form>

    <div class="link">Sudah punya akun? <a href="<?= BASE_URL ?>/login">Masuk di sini</a></div>
  </div>
</body>
</html>