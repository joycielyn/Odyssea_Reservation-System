<?php
session_start();
include 'ui/connectdb.php';
$isLoggedIn = isset($_SESSION['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ODYSSEA Reservation System</title>

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Bootstrap / AdminLTE -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Animate.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <style>
    body { font-family: 'Poppins', sans-serif; }
    .navbar { background: rgba(0, 0, 0, 0.8); }
    .landing-wrapper { position: relative; background: url('ui/malinawsiya.jpg') center center/cover no-repeat; min-height: 100vh; display: flex; align-items: center; color: #fff; text-align: center; }
    .landing-overlay { position: absolute; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.6); }
    .landing-content { position: relative; z-index: 2; }
    .btn-custom { background: #ff9800; border: none; color: #fff; font-weight: 600; padding: 10px 20px; border-radius: 30px; }
    .btn-custom:hover { background: #e68900; color: #fff; }
    .rooms-container { margin-top: 60px; }
    .card { border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); overflow: hidden; transition: transform 0.2s; }
    .card:hover { transform: translateY(-5px); }
    .main-footer { background: #111; padding: 15px; }
    footer strong { color: #ff9800; }
  </style>
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a href="index.php" class="navbar-brand font-weight-bold">ODYSSEA</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
          <li class="nav-item"><a href="gallery.php" class="nav-link">Gallery</a></li>
          <li class="nav-item"><a href="ui/about.php" class="nav-link">About</a></li>
          <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
          <li class="nav-item"><a href="faq.php" class="nav-link">FAQ</a></li>
          <?php if($isLoggedIn): ?>
            <li class="nav-item"><a href="login.php" class="nav-link">Login</a></li>
          <?php else: ?>
            <li class="nav-item"><a href="login.php" class="nav-link">Login</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <div class="landing-wrapper">
    <div class="landing-overlay"></div>
    <div class="container">
      <div class="landing-content">
        <h1 class="display-4 animate__animated animate__fadeInDown">ODYSSEA Beach Resort & Hotel</h1>
        <p class="lead animate__animated animate__fadeInUp">Unwind • Relax • Experience Luxury</p>
        <div class="mt-4">
          <a href="gallery.php" class="btn btn-custom mx-2 animate__animated animate__zoomIn animate__delay-1s">Explore the Beauty</a>
          <a href="ui/check_availability.php" class="btn btn-outline-light mx-2 animate__animated animate__zoomIn animate__delay-1s">Check Availability</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Rooms Section -->
  <section id="rooms" class="rooms-container container py-5">
    <h2 class="text-center mb-5">Our Rooms</h2>
    <div class="row">
      <?php
        $stmt = $pdo->query("SELECT * FROM tbl_rooms ORDER BY roomnum ASC");
        if ($stmt->rowCount() > 0) {
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              $imagePath = "ui/room_images/" . $row['room_image'];
              $imageTag = (file_exists($imagePath) && !empty($row['room_image']))
                ? "<img src='$imagePath' class='card-img-top' style='height:200px;object-fit:cover;'>"
                : "<img src='ui/room_images/default.jpg' class='card-img-top' style='height:200px;object-fit:cover;'>";

              $adults = $row['adults'] ?? 5;
              $children = $row['children'] ?? 5;

              $status = $row['status'];
              $statusClass = $status == "Available" ? "text-success" : ($status == "Under Maintenance" ? "text-warning" : "text-danger");

              echo "
              <div class='col-md-4 mb-4'>
                <div class='card'>
                  $imageTag
                  <div class='card-body'>
                    <h5 class='card-title'>{$row['roomtype']}</h5>
                    <p class='card-text'>Room No: {$row['roomnum']}<br>
                      Max Adults: $adults | Children: $children<br>
                      <strong>Status:</strong> <span class='$statusClass'>$status</span>
                    </p>
                    <p class='card-text h5 text-primary'>₱{$row['price']} / night</p>";

              if ($status == 'Available') {
                  echo "<button class='btn btn-warning book-now' 
                        data-roomtype='{$row['roomtype']}' 
                        data-roomnum='{$row['roomnum']}' 
                        data-adults='$adults' 
                        data-children='$children' 
                        data-price='{$row['price']}'>Book Now</button>";
              } else {
                  echo "<button class='btn btn-outline-secondary' disabled>$status</button>";
              }

              echo "</div></div></div>";
          }
        } else {
          echo "<p class='text-center w-100'>No available rooms at the moment.</p>";
        }
      ?>
    </div>
  </section>

  <!-- Activities Section -->
<section id="activities" class="container py-5">
  <h2 class="text-center mb-5">Activities</h2>
  <div class="row">
    <?php
    $stmt = $pdo->query("SELECT * FROM tbl_activities WHERE status='Available' ORDER BY activity_name ASC");
    if($stmt->rowCount() > 0){
      while($act = $stmt->fetch(PDO::FETCH_ASSOC)){
          $imgPath = "ui/activity_images/" . $act['activity_image'];
          $imgTag = (file_exists($imgPath) && !empty($act['activity_image']))
              ? "<img src='$imgPath' class='card-img-top' style='height:200px;object-fit:cover;'>"
              : "<img src='ui/activity_images/default.jpg' class='card-img-top' style='height:200px;object-fit:cover;'>";

          echo "
          <div class='col-md-4 mb-4'>
            <div class='card'>
              $imgTag
              <div class='card-body'>
                <h5 class='card-title'>{$act['activity_name']}</h5>
                <p class='card-text'>{$act['description']}</p>
              </div>
            </div>
          </div>";
      }
    } else {
      echo "<p class='text-center w-100'>No activities available at the moment.</p>";
    }
    ?>
  </div>
</section>



<!-- Main Footer -->
<footer class="main-footer bg-dark text-white w-100 mt-5">
  <div class="container-fluid px-5 py-5">
    <div class="row gy-4">

      <!-- About Us -->
      <div class="col-md-3">
        <h5 class="fw-bold mb-2">About Us</h5>
        <p style="font-size:0.9rem; line-height:1.4; color:#dcdcdc; margin:0;">
          Odyssea Beach Resort offers a relaxing stay with modern amenities,
          quality service, and a serene beachfront atmosphere. Our mission
          is to provide guests with comfort, convenience, and unforgettable
          vacation experiences.
        </p>
      </div>

      <!-- Social Media -->
      <div class="col-md-3">
        <h5 class="fw-bold mb-2">Follow Us</h5>
        <ul class="list-unstyled" style="font-size:0.9rem;">
          <li class="d-flex align-items-center gap-2 mb-2">
            <i class="fab fa-facebook"></i>
            <a href="https://www.facebook.com/Odysseabeachresort/" target="_blank" style="color:#dcdcdc; text-decoration:none;">Facebook</a>
          </li>
          <li class="d-flex align-items-center gap-2">
            <i class="fas fa-envelope"></i>
            <a href="mailto:odyssea_beachresort@yahoo.com" style="color:#dcdcdc; text-decoration:none;">Email Us</a>
          </li>
        </ul>
      </div>

      <!-- Contact -->
      <div class="col-md-3">
        <h5 class="fw-bold mb-2">Contact Us</h5>
        <ul class="list-unstyled" style="font-size:0.9rem; line-height:1.6;">
          <li class="d-flex align-items-center gap-2"><i class="fas fa-phone-alt"></i> +63 912 345 6789</li>
          <li class="d-flex align-items-center gap-2"><i class="fas fa-envelope"></i> odyssea_beachresort@yahoo.com</li>
          <li class="d-flex align-items-center gap-2"><i class="fas fa-clock"></i> Mon - Thu: 4PM - 12AM</li>
          <li class="d-flex align-items-center gap-2"><i class="fas fa-clock"></i> Fri - Sun: 8AM - 4PM</li>
        </ul>
      </div>

      <!-- Location / Map -->
      <div class="col-md-3">
        <h5 class="fw-bold mb-2"><i class="fas fa-map-marker-alt"></i> Our Location</h5>
        <div class="ratio ratio-4x3 rounded">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7725.326403495863!2d120.2691542!3d14.8419474!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3396714e31f0791b%3A0x7f833ca612e568d3!2sOdyssea%20Beach%20Resort!5e0!3m2!1sen!2sph!4v1704401461915!5m2!1sen!2sph" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>
        </div>
      </div>

    </div>

    <hr style="border-color: rgba(255,255,255,0.2); margin:20px 0;">

    <div class="text-center small">
      <strong>Copyright © J.JIMENEZ KNS</strong> | ODYSSEA RESERVATION SYSTEM 2025
    </div>
  </div>
</footer>



<!-- Facebook SDK -->
<div id="fb-root"></div>
<script async defer crossorigin="anonymous"
  src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v19.0">
</script>

<script>
  const backToTop = document.getElementById("backToTop");
  window.addEventListener("scroll", () => {
    backToTop.style.display = window.scrollY > 300 ? "flex" : "none";
  });
  backToTop.addEventListener("click", () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
document.querySelectorAll('.book-now').forEach(btn => {
    btn.addEventListener('click', function() {
        let roomtype = this.dataset.roomtype;
        let roomnum = this.dataset.roomnum;
        let adults = this.dataset.adults;
        let children = this.dataset.children;
        let price = this.dataset.price;

        // PHP sets a JS variable whether logged in
        const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;

        if (!isLoggedIn) {
            Swal.fire({
                title: 'Login Required',
                text: 'You need to login or sign up first to book this room.',
                icon: 'warning',
                confirmButtonText: 'Proceed'
            }).then(() => {
                // Encode URL properly
                const redirectURL = encodeURIComponent(
                    `resform.php?roomtype=${roomtype}&roomnum=${roomnum}&adults=${adults}&children=${children}&price=${price}`
                );
                window.location.href = `login.php?redirect=${redirectURL}`;
            });
        } else {
            // Directly go to reservation form
            window.location.href = `resform.php?roomtype=${roomtype}&roomnum=${roomnum}&adults=${adults}&children=${children}&price=${price}`;
        }
    });
});
</script>

</body>
</html>
