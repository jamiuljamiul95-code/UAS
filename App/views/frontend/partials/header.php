<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?? 'Mizu Design' ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/navbar.css">
</head>
<body>

<?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
  <!-- Banner kecil khusus kalau admin sedang berada di halaman customer (bukan area /admin) -->
  <div class="admin-notice-bar">
    <span>Kamu login sebagai <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong> (Admin)</span>
    <a href="<?= BASE_URL ?>/admin/dashboard">Buka Admin Panel →</a>
  </div>
<?php endif; ?>

<nav class="navbar navbar-expand-lg navbar-glass sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>/">
      <span style="color:#2563EB">Mizu</span><span style="color:#7C3AED">Design</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/shop">Shop</a></li>
        <li class="nav-item">
          <form action="<?= BASE_URL ?>/shop" method="GET" class="navbar-search-form d-flex">
            <input type="text" name="q" class="form-control form-control-sm rounded-pill" placeholder="Cari produk..." style="width:200px">
          </form>
        </li>

        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin'): ?>
          <li class="nav-item"><a class="btn btn-sm btn-nav-user rounded-pill" href="<?= BASE_URL ?>/admin/dashboard">
            <?= htmlspecialchars($_SESSION['user_name']) ?> (Admin)
          </a></li>

        <?php elseif (isset($_SESSION['user_id'])): ?>
  <!-- Tambahkan icon keranjang di sini -->
          <li class="nav-item">
    <a class="nav-icon-link position-relative" href="<?= BASE_URL ?>/cart">
      <i class="ti ti-shopping-cart"></i>
      <?php
        // Hitung jumlah item di cart dari session
        $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
        if ($cartCount > 0):
      ?>
        <span class="cart-badge"><?= $cartCount ?></span>
      <?php endif; ?>
    </a>
          </li>
          

          <li class="nav-item">
            <a class="nav-icon-link position-relative" href="<?= BASE_URL ?>/wishlist">
              <i class="ti ti-heart"></i>
              <?php
                // Hitung jumlah item wishlist dari database
                if (isset($_SESSION['user_id'])) {
                  require_once ROOT . '/config/database.php';
                  $db = Database::getInstance()->getConnection();
                  $stmt = $db->prepare("SELECT COUNT(*) FROM wishlists WHERE user_id = ?");
                  $stmt->execute([$_SESSION['user_id']]);
                  $wishCount = $stmt->fetchColumn();
                  if ($wishCount > 0):
                ?>
                  <span class="cart-badge"><?= $wishCount ?></span>
                <?php
                  endif;
                }
              ?>
            </a>
          </li>
          <?php elseif (isset($_SESSION['user_id'])): ?>
  <!-- Notifikasi customer -->
  <?php
    $notifModel  = new \App\models\Notification();
    $userNotifs  = $notifModel->forUser($_SESSION['user_id'], 5);
    $userUnread  = $notifModel->countUnread($_SESSION['user_id']);
  ?>
  <li class="nav-item">
    <div class="notif-bell nav-icon-link" onclick="toggleNotif()" style="position:relative;display:flex;align-items:center">
      <i class="ti ti-bell" style="font-size:18px"></i>
      <?php if ($userUnread > 0): ?>
        <span class="cart-badge"><?= $userUnread > 9 ? '9+' : $userUnread ?></span>
      <?php endif; ?>

      <div class="notif-dropdown" id="notifDropdown" style="top:36px">
        <div class="notif-header">
          <span>Notifikasi</span>
          <a href="<?= BASE_URL ?>/notifications/read-all">Semua dibaca</a>
        </div>
        <?php if (empty($userNotifs)): ?>
          <div class="notif-empty">Tidak ada notifikasi</div>
        <?php else: ?>
          <?php foreach ($userNotifs as $n): ?>
            <a href="<?= BASE_URL . $n['url'] ?>" class="notif-item <?= $n['is_read'] ? '' : 'unread' ?>">
              <div class="notif-icon <?= $n['type'] ?>">
                <?= match($n['type']) {
                  'payment'  => '<i class="ti ti-credit-card"></i>',
                  'order'    => '<i class="ti ti-shopping-bag"></i>',
                  default    => '<i class="ti ti-bell"></i>',
                }; ?>
              </div>
              <div>
                <div class="notif-title"><?= htmlspecialchars($n['title']) ?></div>
                <div class="notif-msg"><?= htmlspecialchars($n['message']) ?></div>
                <div class="notif-time"><?= date('d M Y, H:i', strtotime($n['created_at'])) ?></div>
              </div>
            </a>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </li>

          <li class="nav-item"><a class="btn btn-sm btn-nav-user rounded-pill" href="<?= BASE_URL ?>/dashboard">
            <?= htmlspecialchars($_SESSION['user_name']) ?>
          </a></li>

        <?php else: ?>
          <!-- Keranjang untuk guest juga -->
          <li class="nav-item">
            <a class="nav-icon-link position-relative" href="<?= BASE_URL ?>/cart">
              <i class="ti ti-shopping-cart"></i>
              <?php
                $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                if ($cartCount > 0):
              ?>
                <span class="cart-badge"><?= $cartCount ?></span>
              <?php endif; ?>
            </a>
          </li>
          <li class="nav-item"><a class="btn btn-sm btn-nav-outline rounded-pill" href="<?= BASE_URL ?>/login">Login</a></li>
          <li class="nav-item"><a class="btn btn-sm btn-nav-primary rounded-pill" href="<?= BASE_URL ?>/register">Daftar</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>