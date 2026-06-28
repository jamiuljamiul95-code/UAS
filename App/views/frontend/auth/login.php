<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login — Mizu Design</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Poppins', sans-serif; background: #111827;
           display: flex; align-items: center; justify-content: center; min-height: 100vh; }
    .card { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px; padding: 40px; width: 100%; max-width: 420px; }
    h1 { color: #fff; font-size: 22px; margin-bottom: 6px; }
    p.sub { color: #9ca3af; font-size: 13px; margin-bottom: 28px; }
    label { display: block; color: #d1d5db; font-size: 13px; margin-bottom: 6px; }
    input { width: 100%; padding: 12px 14px; border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.15); background: rgba(255,255,255,0.07);
            color: #fff; font-family: 'Poppins', sans-serif; font-size: 14px; margin-bottom: 18px; }
    input:focus { outline: none; border-color: #2563EB; }
    .btn { width: 100%; padding: 12px; background: #2563EB; color: #fff;
           border: none; border-radius: 8px; font-size: 14px; font-weight: 500;
           font-family: 'Poppins', sans-serif; cursor: pointer; }
    .btn:hover { background: #1d4ed8; }
    .error { background: rgba(220,38,38,0.15); border: 1px solid rgba(220,38,38,0.4);
             color: #fca5a5; padding: 10px 14px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; }
    .link { text-align: center; margin-top: 18px; font-size: 13px; color: #9ca3af; }
    .link a { color: #7C3AED; text-decoration: none; }
  </style>
</head>
<body>
  <div class="card">
    <h1>Mizu Design</h1>
    <p class="sub">Masuk ke akunmu</p>

    <?php if (!empty($error)): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="/login">
      <label>Email</label>
      <input type="email" name="email" placeholder="kamu@email.com" required>
      <label>Password</label>
      <input type="password" name="password" placeholder="••••••••" required>
      <button type="submit" class="btn">Masuk</button>
    </form>

    <div class="link">Belum punya akun? <a href="/register">Daftar sekarang</a></div>
  </div>
</body>
</html>