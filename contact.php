<?php

require './navbar.php';

?>
<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Employment Exchange</title>
  <link rel="stylesheet" href="./assets/css/css/bootstrap.min.css">
  <link rel="shortcut icon" type="image/jpg" href="./assets/images/logo.png"/>
  <link rel="stylesheet" href="./assets/css/css/index.css">
  <script src="./assets/jquery-3.6.0.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>

<body>

<div style="height: 90vh; width: 100%;" >
  <img  class="index-1-image" src="./assets/images/index.jpg">
  
</div>
<br>
<br>
<center><h3 class="contact">Contact Us</h3></center>
<br>
<br>
    <div class="container">
      <div class="row">
        <div class="col-4" style="padding:20px; background-color: rgb(253, 253, 253); border-top-left-radius: 10px; border-bottom-left-radius: 10px;">
          <ul class="scheme_row_1">
         
          </ul>
        </div>

  
        <div class="col-8 scheme_row_2" style="padding:20px; background-color: rgba(245, 238, 202, 0.966); border-top-right-radius: 10px; border-bottom-right-radius: 10px;">
          <h3>Contact</h3>
          <p>
            <b>Employment Directorate</b><br>
            Sixth Floor, Thozhil Bhavan,<br>
            Vikasbhavan P.O,<br>
            Thiruvananthapuram 695 033,<br>
            Kerala..
        </p>          </div>
      </div>
    </div>


<?php include 'footer.php' ?>

</body>

<script>
  $(document).ready(function () {

    if ($(window).width() < 991) {
      $("#index_slide").attr('class', 'col-12');
      $("#index_boxes").attr('class', 'col-12');
    }

  });
</script>

</html>
</html>