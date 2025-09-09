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
  <title>Oddysea Beach Resort  | Gallery</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Animate.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="gallery.css">
  <link rel="stylesheet" href="index.css">
  <style>
    .gallery-section {
      padding: 40px 0;
      background:rgb(81, 160, 94);
      min-height: 70vh;
      max-height: 80vh;
      overflow-y: auto;
    }
    .gallery-img-portrait {
      width: 100%;
      height: 420px;
      object-fit: cover;
      object-position: center;
      border-radius: 14px;
      transition: transform 0.2s, box-shadow 0.2s;
      box-shadow: 0 2px 16px rgba(199, 145, 145, 0.11);
      cursor: pointer;
    }
    .gallery-img-portrait:hover {
      transform: scale(1.04);
      box-shadow: 0 6px 32px rgba(26,188,156,0.19);
    }
    .gallery-caption {
      margin-top: 10px;
      font-size: 1.05rem;
      color: #333;
      font-weight: 500;
      text-align: center;
    }
    /* Modal Styles */
    .modal-img-viewer-wrapper {
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 60vh;
      background: #fff;
      border-radius: 16px 16px 0 0;
      padding: 0;
    }
    .modal-img-viewer {
      width: 100%;
      max-width: 400px;
      max-height: 70vh;
      min-height: 350px;
      object-fit: contain;
      object-position: center;
      display: block;
      margin: auto;
      border-radius: 16px;
      background: #fff;
      box-shadow: 0 6px 32px rgba(26,188,156,0.18);
      padding: 0;
    }
    .modal-room-description {
      color: #222;
      background: #fff;
      padding: 18px 24px 10px 24px;
      border-radius: 0 0 14px 14px;
      margin-top: 0;
      font-size: 1.04rem;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      text-align: center;
      display: block;
      animation: fadeInDetails 0.3s;
    }
    @keyframes fadeInDetails {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    .close-modal-btn {
      position: absolute;
      top: 15px;
      right: 25px;
      font-size: 2rem;
      color: #fff;
      z-index: 10;
      background: rgba(0,0,0,0.3);
      border-radius: 50%;
      border: none;
      outline: none;
      width: 42px;
      height: 42px;
      text-align: center;
      line-height: 40px;
      transition: background 0.2s;
    }
    .close-modal-btn:hover {
      background: rgba(26,188,156,0.7);
      color: #fff;
    }
    @media (max-width: 576px) {
      .gallery-img-portrait { height: 220px; }
      .modal-img-viewer { max-width: 99vw; height: 230px; min-height: 180px; }
      .modal-room-description { font-size: 0.98rem; padding: 14px 8px 6px 8px; }
      .close-modal-btn { right: 12px; }
      .gallery-section { max-height: 60vh; }
      .modal-img-viewer-wrapper { min-height: 220px; }
    }
  </style>
</head>
<body class="hold-transition layout-top-nav">
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark" style="background:#1abc9c;">
      <div class="container">
        <a href="index.php" class="navbar-brand font-weight-bold">Oddysea Beach Resort </a>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
          <li class="nav-item"><a href="gallery.php" class="nav-link active">Gallery</a></li>
          <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
          <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
          <li class="nav-item"><a href="faq.php" class="nav-link">FAQ</a></li>
          <li class="nav-item"><a href="login.php" class="nav-link">Login</a></li>
        </ul>
      </div>
    </nav>

    <!-- Hero Section -->
    <div class="gallery-hero">
      <div class="gallery-hero-content animate__animated animate__fadeInDown">
        <h1 class="display-4 font-weight-bold">Gallery</h1>
        <p class="lead">Discover Our Highlight Rooms</p>
      </div>
    </div>

    <!-- Gallery Section (Portrait, Scrollable) -->
    <div class="gallery-section">
      <div class="container">
        <div class="row">
          <?php
          $gallery_images = [
            [
              'src' => 'img/deluxe.jpg',
              'alt' => 'Deluxe Room',
              'caption' => 'Deluxe Room',
              'desc' => 'A spacious room featuring a king-sized bed, modern amenities, a balcony with a beautiful view, and a luxurious bathroom. Perfect for couples or solo travelers seeking comfort and elegance.'
            ],
            [
              'src' => 'img/standard.jpg',
              'alt' => 'Family Suite',
              'caption' => 'Family Suite',
              'desc' => 'Designed for families, this suite offers two bedrooms, a living area, and a kitchenette. Enjoy privacy and convenience with space for relaxation after a fun day at the resort.'
            ],
            [
              'src' => 'img/queen.jpg',
              'alt' => 'Queen Suite',
              'caption' => 'Queen Suite',
              'desc' => 'Elegant suite with a plush queen bed, stylish interior, work desk, and a cozy lounge area. Ideal for business travelers or couples looking for a mix of work and leisure.'
            ],
            [
              'src' => 'img/default.jpg',
              'alt' => 'Poolside Room',
              'caption' => 'Poolside Room',
              'desc' => 'Stay just steps away from the outdoor pool. Features contemporary décor, a comfy bed, and a terrace with direct pool access — great for those who love a refreshing swim any time of day.'
            ],
            [
              'src' => 'img/single.jpg',
              'alt' => 'Single Room',
              'caption' => 'Single Room',
              'desc' => 'Perfect for solo travelers, this cozy room comes with a single bed, workspace, and a private bath.'
            ],
            [
              'src' => 'img/deluxe.jpg',
              'alt' => 'Executive Suite',
              'caption' => 'Executive Suite',
              'desc' => 'Our top-tier suite, with a separate living area, premium bedding, and VIP amenities for the ultimate stay.'
            ],
          ];
          foreach ($gallery_images as $index => $img) {
            echo '
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex align-items-stretch">
              <div class="card shadow-sm border-0 w-100">
                <img src="'.$img['src'].'" alt="'.htmlspecialchars($img['alt']).'" class="gallery-img-portrait open-img-modal" 
                  data-img="'.$img['src'].'"
                  data-alt="'.htmlspecialchars($img['alt']).'"
                  data-caption="'.htmlspecialchars($img['caption']).'"
                  data-desc="'.htmlspecialchars($img['desc']).'"
                  data-index="'.$index.'"
                  title="View">
                <div class="gallery-caption">'.$img['caption'].'</div>
              </div>
            </div>
            '; 
          }
          ?>
        </div>
      </div>
    </div>

    <!-- Image Viewer Modal -->
    <div class="modal fade" id="imgModal" tabindex="-1" aria-labelledby="imgModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-transparent border-0 p-0" style="position:relative;">
          <button type="button" class="close-modal-btn" data-dismiss="modal" aria-label="Close">
            &times;
          </button>
          <div class="modal-img-viewer-wrapper">
            <img src="" alt="" id="modalImg" class="modal-img-viewer animate__animated animate__fadeIn">
          </div>
          <div class="modal-room-description" id="modalRoomDesc"></div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="main-footer text-white text-center">
      <div>Odyssea Beach Resort Reservation System 2025 | <strong>&copy; J.JIMENEZ KNS</strong></div>
    </footer>
  </div>
  <!-- JS: jQuery, Bootstrap, Modal viewer logic -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script>
    $(function() {
      // Open modal on image click
      $(".open-img-modal").on("click", function(e) {
        e.preventDefault();
        var src = $(this).data("img");
        var alt = $(this).data("alt");
        var caption = $(this).data("caption");
        var desc = $(this).data("desc");
        $("#modalImg").attr("src", src).attr("alt", alt);
        $("#modalRoomDesc").addClass("active").html("<strong>" + caption + "</strong><br>" + desc);
        $("#imgModal").modal({
          backdrop: true,
          keyboard: true,
          focus: true
        });
        setTimeout(function() {
          $('.modal-backdrop').addClass('modal-backdrop-custom');
        }, 50);
      });

      // Remove image and reset on modal close
      $('#imgModal').on('hidden.bs.modal', function () {
        $("#modalImg").attr("src", "");
        $("#modalRoomDesc").removeClass("active").text("");
      });
    });
  </script>
</body>
</html>