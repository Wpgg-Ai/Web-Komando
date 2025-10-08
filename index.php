<?php
session_start();
include 'koneksi.php';

$login_error = '';
$register_error = '';
$register_success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // LOGIN HANDLER
    if (isset($_POST['login'])) {
        $email    = trim($_POST['email']);
        $password = $_POST['password'];

        $result = $conn->query("SELECT * FROM users WHERE email='$email'");
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['name']    = $row['name'];
                $_SESSION['email']   = $row['email'];
                header("Location: dashboard.php");
                exit;
            } else {
                $login_error = "Password salah!";
            }
        } else {
            $login_error = "Email tidak ditemukan!";
        }
    }

    // REGISTER HANDLER
    if (isset($_POST['register'])) {
        $name     = trim($_POST['name']);
        $email    = trim($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $cek = $conn->query("SELECT id FROM users WHERE email='$email'");
        if ($cek && $cek->num_rows > 0) {
            $register_error = "Email sudah terdaftar!";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $password);
            if ($stmt->execute()) {
                $register_success = "Registrasi berhasil! Silakan login.";
            } else {
                $register_error = "Terjadi kesalahan saat registrasi.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    />
    <link rel="icon" type="image/png" href="assets/images/logo-light.png" />
    <link rel="stylesheet" href="assets/css/login.css" />
    <title>Login Page - Komando</title>
  </head>

  <body>
    <div class="container" id="container">
      <!-- SIGN UP FORM -->
      <div class="form-container sign-up">
        <form method="post">
          <input type="hidden" name="register" value="1">
          <h1>Create Account</h1>
          <span>Sign-up with email for registration</span>
          
          <?php if ($register_error): ?>
            <div style="background:#ffe5e5;color:#c00;padding:8px;border-radius:6px;margin-bottom:10px;font-size:14px;text-align:center;">
              <?= htmlspecialchars($register_error) ?>
            </div>
          <?php elseif ($register_success): ?>
            <div style="background:#e6ffed;color:#0a0;padding:8px;border-radius:6px;margin-bottom:10px;font-size:14px;text-align:center;">
              <?= htmlspecialchars($register_success) ?>
            </div>
          <?php endif; ?>

          <input type="text" name="name" placeholder="Name" required />
          <input type="email" name="email" placeholder="Email" required />
          <input type="password" name="password" placeholder="Password" required />
          <button type="submit" name="register">Sign Up</button>
        </form>
      </div>

      <!-- SIGN IN FORM -->
      <div class="form-container sign-in">
        <form method="post">
          <h1>Sign In</h1>
          <span>Sign-in with email and password</span>

          <?php if ($login_error): ?>
            <div style="background:#ffe5e5;color:#c00;padding:8px;border-radius:6px;margin-bottom:10px;font-size:14px;text-align:center;">
              <?= htmlspecialchars($login_error) ?>
            </div>
          <?php endif; ?>

          <input type="email" name="email" placeholder="Email" required />
          <input type="password" name="password" placeholder="Password" required />
          <a href="#">Forget Your Password?</a>
          <button type="submit" name="login">Sign In</button>
        </form>
      </div>

      <!-- TOGGLE PANEL -->
      <div class="toggle-container">
        <div class="toggle">
          <div class="toggle-panel toggle-left">
            <h1>Halo, Rekan Seperjuangan!</h1>
            <p>Silahkan daftar dan jangan lupakan email dan passwordnya!</p>
            <button class="hidden" id="login">Sign In</button>
          </div>
          <div class="toggle-panel toggle-right">
            <h1>Selamat Datang Kembali!</h1>
            <p>Silahkan masukkan email dan password dengan benar!</p>
            <button class="hidden" id="register">Sign Up</button>
          </div>
        </div>
      </div>
    </div>

    <script src="assets/js/login.js"></script>
   <?php if ($register_error || $register_success || isset($_POST['register'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('container');
    container.classList.add('active'); // buka panel Sign Up langsung
  });
</script>
<?php endif; ?>


  </body>
</html>
