<?php

// require './navbar.php';

//echo $userType;

?>

<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Employment Exchange</title>
  <link rel="stylesheet" href="./assets/css/css/bootstrap.min.css">
  <link rel="shortcut icon" type="image/jpg" href="./assets/images/logo.png"/>
  <link rel="stylesheet" href="./assets/css/css/index.css">
  <!-- <link rel="stylesheet" href="./assets/images/emblomm.jpg"> -->
  <script src="./assets/jquery-3.6.0.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>

<body>
 <!--                   front imagesss          -->

    <div class="index-top-image-div" style="background: url('./assets/images/index.jpg'); background-attachment: fixed;">
      <?php require './navbar.php'; ?>

      <div class="container">
        <?php
        if(isset($_SESSION['usertype'])){
            if($_SESSION['usertype'] == 2){
        ?>
              <center><h4>STAFF</h4></center>
        <?php
            }elseif($_SESSION['usertype']==3){
        ?>
              <center><h4>OFFICER</h4></center> 
        <?php
            }
          }
        ?>
      </div>

    </div>


    
    <div id="desktop_index" class="container-fluid">
      <div class="row">
        <div class="col-7" id="index_slide">
          <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
          
            <div class="carousel-inner">
              <?php
                  $sql = "select * from tbl_slide";
                  $result = mysqli_query($conn, $sql);
                  $count = 0;
                  while($row = mysqli_fetch_array($result)){
                      if($count == 0){
                          echo "<div class='carousel-item active'>";
                              echo "<img src='./superuser/assets/slides/" .$row['slide']. " ' class='index_image shadow-lg' alt='...'>";
                          echo"</div>";
                      }
                      else{
                          echo "<div class='carousel-item'>";
                              echo "<img src='./superuser/assets/slides/" .$row['slide']. " ' class='index_image shadow-lg' alt='...'>";
                          echo "</div>";
                      }
                      $count = $count+1;
                  }
              ?> 
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
        </div>

        <!-- Right Side Box Navigation -->

        <div class="col-5" id="index_boxes" style="margin-top: 55px;">
          <div class="container-fluid">
            <div class="row">
              <div class="col-6">
                <div class="box shadow-lg">
                  <img src="./assets/icons/services.png" alt="">
                  <h5>Online Services</h5>
                </div>
              </div>
              <div class="col-6">
                <div class="box shadow-lg">
                  <img src="./assets/icons/schemes.png" alt="">
                  <h5>Schemes</h5>
                </div>
              </div>
            </div>
            <br><br>
            <div class="row">
              <div class="col-6">
                <div class="box shadow-lg">
                  <img src="./assets/icons/downloads.png" alt="">
                  <h5>Downloads</h5>
                </div>
              </div>
              <div class="col-6">
                <div class="box shadow-lg">
                  <img src="./assets/icons/feedback.png" alt="">
                  <h5>Public Griveances</h5>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
   
<div>


<div style="display: flex; justify-content: space-around; margin-top: 40px;">

  <div class="card" style="width: 18rem;">
    <img src="./assets/images/index1.jpg" class="card-img-top" alt="">
    <div class="card-body">
      <h5 class="card-title text-center">Schemes</h5>
      <p class="card-text text-center">In the present day scenario,placements in the Government sector is decreasing.Self Employment promotion is the need of the hour.Necessary information is given to entrepreneurs visiting the employment exchange.</p>
    
    </div>
  </div>
  <div class="card" style="width: 18rem;">
    <img src="./assets/images/index2.jpg" class="card-img-top" alt="">
    <div class="card-body">
      <h5 class="card-title text-center">Services</h5>
      <p class="card-text text-center">The employment services department has a primary objective of providing an interface between the employers and the jobseekers.It sponsors the candidates of required qualification,experience and skill set for various job withon shortest possible time.</p>
    
    </div>
  </div>
  <div class="card" style="width: 18rem;">
    <img src="./assets/images/index3.jpg" class="card-img-top" alt="">
    <div class="card-body">
      <h5 class="card-title text-center">Mission</h5>
      <p class="card-text text-center">Provide gainful employment to all jobseekers in the state either through paid employment or self employment and to provide vocational/educational guidence and other service to the utmost satification of the stake holders.</p>
      
    </div>
  </div>
  <div class="card" style="width: 18rem;">
    <img src="./assets/images/index4.jpg" class="card-img-top" alt="">
    <div class="card-body">
      <h5 class="card-title text-center">Vision</h5>
      <p class="card-text text-center">Toprovide platform of interface between stakeholders for responsive transparent and efficient employment service in order to meet skill needs of a dynamic society.</p>
      
    </div>
  </div>

</div>





    
    <!-- Mobile Index page -->

    <div id="mobile_index">
      <!-- mobile slide -->
      <div class="mobile_slide">
        <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
        
          <div class="carousel-inner">
            <?php
                $sql = "select * from tbl_slide";
                $result = mysqli_query($conn, $sql);
                $count = 0;
                while($row = mysqli_fetch_array($result)){
                    if($count == 0){
                        echo "<div class='carousel-item active'>";
                            echo "<img src='./superuser/assets/slides/" .$row['slide']. " ' class='mobile_index_image shadow-lg' alt='...'>";
                        echo"</div>";
                    }
                    else{
                        echo "<div class='carousel-item'>";
                            echo "<img src='./superuser/assets/slides/" .$row['slide']. " ' class='mobile_index_image shadow-lg' alt='...'>";
                        echo "</div>";
                    }
                    $count = $count+1;
                }
              ?> 
          </div>
        </div>
      </div>

      <!-- mobile boxes -->
      <br>
      <div class="container-fluid">
        <div class="row">
          <div class="col-6">
            <div class="box shadow-lg">
              <img src="./assets/icons/services.png" alt="">
              <h5>Online Services</h5>
            </div>
          </div>
          <div class="col-6">
            <div class="box shadow-lg">
              <img src="./assets/icons/schemes.png" alt="">
              <h5>Schemes</h5>
            </div>
          </div>
        </div>
        <br><br>
        <div class="row">
          <div class="col-6">
            <div class="box shadow-lg">
              <img src="./assets/icons/downloads.png" alt="">
              <h5>Downloads</h5>
            </div>
          </div>
          <div class="col-6">
            <div class="box shadow-lg">
              <img src="./assets/icons/feedback.png" alt="">
              <h5>Public Griveances</h5>
            </div>
          </div>
        </div>
      </div>

    </div>

    <?php include 'footer.php' ?>
  </div>

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