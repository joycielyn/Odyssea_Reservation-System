<?php 
session_start();
include 'connectdb.php';
?>


<div style="padding: 60px 20px; max-width: 900px; margin: auto; font-family: Arial, sans-serif;">
  <h2 style="font-size: 2.5rem; font-weight: bold; margin-bottom: 20px; text-align:center; color:#333;">
    About Us
  </h2>
  <p style="font-size: 1.1rem; line-height: 1.8; color:#555; text-align:justify;">
    The Odyssea Reservation System is designed to provide a modern, 
    user-friendly platform for booking rooms with ease.  
    It offers real-time room availability, secure guest information management,  
    and a smooth reservation experience for both administrators and guests.
  </p>

  <p style="font-size: 1.1rem; line-height: 1.8; color:#555; text-align:justify;">
    With features such as booking management, reports and analytics, 
    and secure payment verification, Odysea ensures that guests enjoy 
    a hassle-free stay while administrators maintain full control over reservations.
  </p>

  <div style="text-align:center; margin-top:40px;">
    <a href="index.php" style="background:#3498db; color:white; padding:12px 28px; 
       border-radius:50px; text-decoration:none; font-weight:bold; transition:0.3s;">
       Back to Home
    </a>
  </div>
</div>
<?php include_once "footer.php"; ?>
