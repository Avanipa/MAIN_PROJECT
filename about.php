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
<center><h3 class="contact">About Us</h3></center>
<br>
<br>

    <div class="container">
      <div class="row">
        <div class="col-4" style="padding:20px; background-color: rgba(148, 186, 236, 0.7); border-top-left-radius: 10px; border-bottom-left-radius: 10px;">
          <ul class="scheme_row_1">
         
          </ul>
        </div>

  
        <div class="col-8 scheme_row_2" style="padding:20px; background-color: rgba(252, 233, 128, 0.7); border-top-right-radius: 10px; border-bottom-right-radius: 10px;">
          <h3></h3>
          <p>
            <h3 class="contact">About US</h3>

    <div class="container">
      <div class="row">
        <div class="col-4" style="padding:20px; background-color: rgba(148, 186, 236, 0.7); border-top-left-radius: 10px; border-bottom-left-radius: 10px;">
          <ul class="scheme_row_1">
         
          </ul>
        </div>

  
        <div class="col-8 scheme_row_2" style="padding:20px; background-color: rgba(252, 233, 128, 0.7); border-top-right-radius: 10px; border-bottom-right-radius: 10px;">
          <h3></h3>
          <p>
          Businesses can post their opportunities with work trades and look over among the enlisted competitors according to their prerequisites through Employment Exchange Services. This is probably going to bring about a progressively reasonable gauge of work in the sorted-out segment. An Employment Exchange is an association that gives business help based on capability and experience. The Departments of Employment in the different States of India permit jobless taught youth living in the particular States to pre-register for looming work opening happening in various divisions of that State.
        </p>          </div>
      </div>
    </div>
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