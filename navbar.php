<?php

require_once('./include/connect.php');
include './encdec.php';
session_start();

$status = "";
$userType = "";
$stat = 0;
$id = "";
$username = "";
if(isset($_SESSION['regid'])){
    $id = $_SESSION['regid'];
}

 ///alert message ///

$sql="SELECT * FROM tbl_alert";
$result =mysqli_query($conn,$sql);
while($row = mysqli_fetch_assoc($result)){
    $stat = $row['status'];
    $alertmsg= $row ['message'];
}
//echo $alertmsg;

if($id){
    $login = "SELECT * FROM tbl_login where login_id=$id";
    $login_result = mysqli_query($conn, $login);
    while($row = mysqli_fetch_assoc($login_result)){
        $status = $row['usertype'];
    }
    $sql = "SELECT * FROM tbl_registration where regid=$id";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)){
        $username = $row['uname'];
    }
}

if($status==0){
 $userType = "User";
}else if($status==1){
    $userType = "Company";
}else if($status==2){
    $userType = "Staff";
}else if($status==3){
    $userType = "Officer";
}

?>
<?php
    if($stat==1){
?>
    <div class="alert-danger custom-alert">
        <marquee behavior="" direction=""><?php echo $alertmsg; ?></marquee>
    </div>
<?php
    }
?>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./assets/css/css/bootstrap.min.css">
    <link rel="shortcut icon" type="image/jpg" href="./assets/images/logo.png"/>
    <link rel="stylesheet" href="./assets/css/css/index.css">
    
    <script src="./assets/jquery-3.6.0.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">

      <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

<?php
    if($_SESSION){
        if($_SESSION['usertype'] == 3){
            echo "<div class='main-nav shadow-lg' id='main-nav' style='background-color: rgba(204, 102, 255, 0.5);'>";
        }elseif($_SESSION['usertype'] == 2){
            echo "<div class='main-nav shadow-lg' id='main-nav' style='background-color: rgba(0, 102, 204, 0.5);'>";
        }
    }else{
        echo "<div class='main-nav shadow-lg' id='main-nav' style='background-color: rgba(63, 224, 208, 0.5);'>";
    }
?>
    <label id="menubar"><i id="menubarI" style="float: left; font-size: 22px;" class="bi bi-list menubar"></i></label>
    <div class="row lap_nav">
        <div class="col-1">
            <a href="./index"><img class="logo" src="./assets/images/employment.png" alt=""></a>
        </div>
        <div class="col-11">
            <ul class="nav-list">
                <?php
                    if($userType=='User'){
                        $id = $_SESSION['regid'];
                        $pic = "select display_picture from tbl_user_details where user_reg_id='$id'";
                        $pic_result = mysqli_query($conn, $pic);
                        $pic_row = mysqli_fetch_assoc($pic_result);
                        if($pic_row['display_picture']!='NULL'){
                            $path = "superuser/assets/documents/".$id."/dp/";
                            echo "<a href='./account'>";
                                echo "<img src='$path" .$pic_row['display_picture']. " ' style='width: 40px; height: 40px;border-radius: 50%; float: right;'>";
                            echo "</a>";
                        }
                    }
                ?>
                <?php
                    if($userType==null){
                ?>
                    <a href="./login"><li class='navbar_font'>Sign In</li></a>
                <?php
                    }
                ?>
                <?php
                    if($userType!=null){
                ?>
                    <a href="./logout"><li class='navbar_font'>Logout</li></a>
                <?php
                    }
                ?>
                <a href="./contact"><li class='navbar_font'>Contact Us</li></a>
                <a href="./about"><li class='navbar_font'>About</li></a>
                <?php
                    if($userType=='User'){
                ?>
                        <a href="./jobs"><li class='navbar_font'>Jobs</li></a>
                <?php
                    }
                    if($userType=='Staff' || $userType=='Officer'){

                ?>
                        <a href="./approvals"><li class='navbar_font'>Approvals</li></a>
                <?php
                    }elseif($userType=='User' || $userType==null){

                ?>
                    <li class="nav-item " id="schemes">
                    <a class="text_black navbar_font" href="#" id="navbarDropdown" aria-expanded="false">Schemes</a>
                    <ul class="dropdown-menu nav-dropdown" id="schemes_dropdown" aria-labelledby="navbarDropdown">
                        <?php
                            $sql = "select distinct name from tbl_cscheme";
                            $result = mysqli_query($conn, $sql);
                            while($row=mysqli_fetch_assoc($result)){

                        ?>
                            <a href="./schemes?tag=<?php echo encrypt($row['name']); ?>">
                        <?php
                                    echo "<li class='navbar_font'>" .$row['name']. "</li>";
                                echo "</a>";
                            }
                        ?>

                    </ul>
                    </li>
                    <li class="nav-item " id="services">
                        <a class="text_black navbar_font" href="#" id="navbarDropdown" aria-expanded="false">Services</a>
                        <ul class="dropdown-menu nav-dropdown" id="services_dropdown" aria-labelledby="navbarDropdown">
                            <?php
                                $sql = "select distinct name from tbl_services";
                                $result = mysqli_query($conn, $sql);
                                while($row=mysqli_fetch_assoc($result)){

                            ?>
                                <a href="./services?tag=<?php echo encrypt($row['name']); ?>">
                            <?php
                                        echo "<li class='navbar_font'>" .$row['name']. "</li>";
                                    echo "</a>";
                                }
                            ?>
                        </ul>
                    </li>
                <?php

                    }elseif($userType=='Company'){
                ?>
                    <a href="./insertjobs"><li class='navbar_font'>New Jobs</li></a>
                <?php
                    }
                ?>
                <a href="./index"><li class='navbar_font'>Home</li></a>
                <?php
                    if($status==0 || $status==1){
                ?>
                    <a href="./account"><li style="color: green">Welcome&numsp;<?php echo ucfirst($username) ?></li></a>
                <?php
                    }else if($status==2 || $status==3){
                ?>
                    <a href="./account"><li style="color: purple">Welcome&numsp;<?php echo ucfirst($username) ?></li></a>
                <?php
                    }
                ?>
            </ul>
        </div>
    </div>
</div>

<div class="mobile_navigation">
    <div class="row">
        <div class="col-1">
            <a href="./index"><img class="mobile_logo" src="./assets/images/employment.png" alt=""></a>
        </div>
   

        <!-- <div class="col-5">
            <h5 class="mobile_nav_name">Employment Exchange</h5>
        </div> -->
        <div class="col-6">
            <h5 class="usertype">
                <!-- <?php
                    if($status==0 || $status==1){
                ?>
                    <a href="./account"><li style="color: green">Welcome<?php echo $username ?></li></a>
                <?php
                    }else if($status==2 || $status==3){
                ?>
                    <a href="#"><li style="color: purple">Welcome<?php echo $username ?></li></a>
                <?php
                    }
                ?> -->
            </h5>
        </div>
    </div>
</div>

<div class="mobile_nav">
    <ul>
        <a href="./index"><li>Home</li></a>
        <a href="./about"><li>About</li></a>
        <a href="./contact"><li>Contact Us</li></a>
        <a href="#" id="mobile_services"><li>Services<i id="mobile-nav-sub1" style="float: right;" class="bi bi-plus-lg"></i></li></a>
        <ul class="mobile-nav-sub1">
            <?php
                $sql = "select distinct name from tbl_services";
                $result = mysqli_query($conn, $sql);
                while($row=mysqli_fetch_assoc($result)){
        ?>
                    <a href="./services?tag=<?php echo encrypt($row['name']); ?>"
        <?php
                    echo "<li style='margin-left: 10px;'>" .$row['name']. "</li>";
                    echo "</a>";
                }
            ?>
        </ul>
        <a id="mobile_schemes" href="#"><li>Schemes<i id="mobile-nav-sub2" style="float: right;" class="bi bi-plus-lg"></i></li></a>
        <ul class="mobile-nav-sub2">
            <?php
                $sql = "select distinct name from tbl_cscheme";
                $result = mysqli_query($conn, $sql);
                while($row=mysqli_fetch_assoc($result)){
        ?>
                    <a href="./schemes?tag=<?php echo encrypt($row['name']); ?>"
        <?php
                    echo "<li style='margin-left: 10px;'>" .$row['name']. "</li>";
                    echo "</a>";
                }
            ?>
        </ul>
        <?php
            if($userType!=null){
            ?>
                <a href="./logout"><li>Logout</li></a>
            <?php
                }
            ?>
            <?php
                if($userType==null){
            ?>
                <a href="./login"><li>Sign In</li></a>
            <?php
                }
        ?>
    </ul>
</div>


<script>
    $(document).ready(function () {
        $("#services").hover(function () {
            // $('#services_dropdown').toggle();
            $("#services_dropdown").toggleClass("nav-dropdown-active");
        });

        $("#schemes").hover(function () {
            // $('#services_dropdown').toggle();
            $("#schemes_dropdown").toggleClass("nav-dropdown-active");
        });

        $("#mobile_services").click(function(){
            $("#mobile_services").next().toggle(400);
            $("#mobile-nav-sub1").toggleClass("bi bi-x-lg");
        });

        $("#mobile_schemes").click(function(){
            $("#mobile_schemes").next().toggle(400);
            $("#mobile-nav-sub2").toggleClass("bi bi-x-lg");
        });

        $("#menubar").click(function(){
            $(".mobile_nav").toggle(500);
            $("#menubarI").toggleClass("bi bi-x-lg");            

        });
    });

</script>