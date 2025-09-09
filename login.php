<?php
session_start();
include 'ui/connectdb.php';

$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '';
$roomtype = isset($_GET['roomtype']) ? $_GET['roomtype'] : '';
$roomnum = isset($_GET['roomnum']) ? $_GET['roomnum'] : '';

// ===================== LOGIN & SIGNUP LOGIC =====================
// (same code mo sa database dito, hindi ko binago)
if (isset($_POST['btn_login'])) {
    $useremail = $_POST['txtemail'];
    $password = $_POST['txtpassword'];
    $select = $pdo->prepare("SELECT * FROM tbl_user WHERE useremail = :useremail");
    $select->bindParam(':useremail', $useremail);
    $select->execute();
    $row = $select->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        if ($password == $row['userpassword']) {
            $_SESSION['id'] = $row['userid'];
            $_SESSION['name'] = $row['username'];
            $_SESSION['useremail'] = $row['useremail'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['address'] = $row['address'] ?? '';
            $_SESSION['contnum'] = $row['contnum'] ?? '';
            $_SESSION['status'] = "Welcome back, " . htmlspecialchars($row['username']) . "!";
            $_SESSION['status_code'] = "success";
            if (strtolower($row['role']) === 'admin') {
                $redirectUrl = !empty($redirect) ? $redirect : "ui/dashboard.php";
                if (!empty($roomtype) && !empty($roomnum)) {
                    $redirectUrl .= "?roomtype=" . urlencode($roomtype) . "&roomnum=" . urlencode($roomnum);
                }
                $_SESSION['redirect'] = $redirectUrl;
            }
        } else {
            $_SESSION['status'] = "Incorrect email or password.";
            $_SESSION['status_code'] = "error";
        }
    } else {
        $_SESSION['status'] = "No account found with that email. Please sign up first!";
        $_SESSION['status_code'] = "warning";
    }
}

if (isset($_POST['btnsignup'])) {
    $username = $_POST['txtname'];
    $useremail = $_POST['txtemail'];
    $userpassword = $_POST['txtpassword'];
    $address = $_POST['txtaddress'];
    $contnum = $_POST['txtcontnum'];
    $role = "User";
    $selectEmail = $pdo->prepare("SELECT useremail FROM tbl_user WHERE useremail = :useremail");
    $selectEmail->bindParam(':useremail', $useremail);
    $selectEmail->execute();
    if ($selectEmail->rowCount() > 0) {
        $_SESSION['status'] = "Email already exists. Please use another email.";
        $_SESSION['status_code'] = "warning";
    } else {
        $insert = $pdo->prepare("INSERT INTO tbl_user (username, useremail, userpassword, role, address, contnum) 
                                 VALUES (:username, :useremail, :userpassword, :role, :address, :contnum)");
        $insert->bindParam(':username', $username);
        $insert->bindParam(':useremail', $useremail);
        $insert->bindParam(':userpassword', $userpassword);
        $insert->bindParam(':role', $role);
        $insert->bindParam(':address', $address);
        $insert->bindParam(':contnum', $contnum);
        if ($insert->execute()) {
            $lastId = $pdo->lastInsertId();
            $_SESSION['id'] = $lastId;
            $_SESSION['name'] = $username;
            $_SESSION['useremail'] = $useremail;
            $_SESSION['role'] = $role;
            $_SESSION['address'] = $address;
            $_SESSION['contnum'] = $contnum;
            $_SESSION['status'] = "Account created successfully! Welcome, " . htmlspecialchars($username) . ".";
            $_SESSION['status_code'] = "success";
            $redirect = "resform.php";
            if (!empty($roomtype) && !empty($roomnum)) {
                $redirect .= "?roomtype=" . urlencode($roomtype) . "&roomnum=" . urlencode($roomnum);
            }
            $_SESSION['redirect'] = $redirect;
        } else {
            $errorInfo = $insert->errorInfo();
            $_SESSION['status'] = "Error creating account: " . $errorInfo[2];
            $_SESSION['status_code'] = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Odyssea | Login / Sign Up</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<link rel="stylesheet" href="dist/css/adminlte.min.css">
<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f4f6f9;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}
.card {
    background: #fff;
    border-radius: 10px;
    width: 360px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    overflow: hidden;
}
.card-header {
    display: flex;
    justify-content: space-around;
    background: #1abc9c;
    color: #fff;
    cursor: pointer;
}
.card-header div {
    padding: 15px 0;
    width: 50%;
    text-align: center;
    font-weight: bold;
}
.card-header .active {
    background: #16a085;
}
.card-body {
    padding: 20px;
}
.input-group {
    margin-bottom: 15px;
}
.input-group i {
    margin-right: 10px;
    color: #1abc9c;
}
.input-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
.button {
    margin-top: 10px;
}
.button input {
    width: 100%;
    padding: 10px;
    background: #1abc9c;
    border: none;
    color: #fff;
    border-radius: 5px;
    cursor: pointer;
}
.button input:hover {
    background: #16a085;
}
.toggle-text {
    text-align: center;
    margin-top: 10px;
    font-size: 0.9rem;
}
.toggle-text label {
    color: #1abc9c;
    cursor: pointer;
}
</style>
</head>
<body>

<div class="card">
    <div class="card-header">
        <div id="login-tab" class="active">Login</div>
        <div id="signup-tab">Sign Up</div>
    </div>
    <div class="card-body">
        <!-- LOGIN FORM -->
        <form id="login-form" method="post" autocomplete="off">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="txtemail" placeholder="Email" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="txtpassword" placeholder="Password" required>
            </div>
            <div class="button">
                <input type="submit" value="Login" name="btn_login">
            </div>
            <div class="toggle-text">Don't have an account? <label for="signup-tab" id="goto-signup">Sign Up</label></div>
        </form>

        <!-- SIGNUP FORM -->
        <form id="signup-form" method="post" autocomplete="off" style="display:none;">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="txtname" placeholder="Full Name" required>
            </div>
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="txtemail" placeholder="Email" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="txtpassword" placeholder="Password" required minlength="6">
            </div>
            <div class="input-group">
                <i class="fas fa-map-marker-alt"></i>
                <input type="text" name="txtaddress" placeholder="Address" required>
            </div>
            <div class="input-group">
                <i class="fas fa-phone"></i>
                <input type="text" name="txtcontnum" placeholder="Contact Number" required>
            </div>
            <input type="hidden" name="txtrole" value="User">
            <div class="button">
                <input type="submit" value="Sign Up" name="btnsignup">
            </div>
            <div class="toggle-text">Already have an account? <label for="login-tab" id="goto-login">Login</label></div>
        </form>
    </div>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<script>
// Tab toggle
const loginTab = document.getElementById('login-tab');
const signupTab = document.getElementById('signup-tab');
const loginForm = document.getElementById('login-form');
const signupForm = document.getElementById('signup-form');
const gotoSignup = document.getElementById('goto-signup');
const gotoLogin = document.getElementById('goto-login');

loginTab.onclick = () => {
    loginTab.classList.add('active');
    signupTab.classList.remove('active');
    loginForm.style.display = 'block';
    signupForm.style.display = 'none';
}
signupTab.onclick = () => {
    signupTab.classList.add('active');
    loginTab.classList.remove('active');
    signupForm.style.display = 'block';
    loginForm.style.display = 'none';
}
gotoSignup.onclick = signupTab.onclick;
gotoLogin.onclick = loginTab.onclick;
</script>

<!-- SweetAlert2 feedback and redirect -->
<?php if (isset($_SESSION['status']) && $_SESSION['status'] != ''): ?>
<script>
Swal.fire({
    icon: '<?php echo $_SESSION['status_code']; ?>',
    title: '<?php echo $_SESSION['status']; ?>',
    showConfirmButton: false,
    timer: 2000
});
<?php if (isset($_SESSION['redirect'])): ?>
setTimeout(function(){ window.location.href = '<?php echo $_SESSION['redirect']; ?>'; },2000);
<?php unset($_SESSION['redirect']); ?>
<?php endif; ?>
</script>
<?php unset($_SESSION['status']); unset($_SESSION['status_code']); ?>
<?php endif; ?>
</body>
</html>
