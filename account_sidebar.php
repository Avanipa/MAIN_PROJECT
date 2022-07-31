<?php
    $USERTYPE = $_SESSION['usertype'];
?>
<div class="navigation">
<ul>
<li>
     <a href="./index">
<span class="icon"><img style="width: 50px; height:50px;" src="./assets/images/logo.png" alt=""></span>
    <span class="title">My Account Page</span>
</a>
</li>
 <li>
     <a href="./index">
<span class="icon"><i class="bi bi-house-door"></i></span>
     <span class="title">Home</span>
</a>
</li>
<li>
     <a href="./account">
<span class="icon"><i class="bi bi-house-door"></i></span>
    <span class="title">Dashboard</span>
</a>
</li>
           
<?php
     if($USERTYPE==2 || $USERTYPE==3){
?>
<li>
    <a href="./apply_leave">
<span class="icon"><i class="bi bi-house-door"></i></span>
    <span class="title">Leave</span>
</a>
</li>      
<li>
    <a href ="./leave_history">
<span class="icon"><i class="bi bi-house-door"></i></span>
    <span class="title">Leave History</span>
</a>
</li> 
<li>
    <a href ="./view_salary">
<span class="icon"><i class="bi bi-house-door"></i></span>
    <span class="title">View Salary</span>
</a>
</li> 


<?php
 }
?>

<?php
    if($USERTYPE==2){
?>
    <li>
        <a href ="./attendence">
            <span class="icon"><i class="bi bi-house-door"></i></span>
            <span class="title">Attendence</span>
        </a>
    </li> 
<?php
    }
?>

<?php
    if($USERTYPE==0){
?>
<li>
    <a href="./email_verify">
<span class="icon"><i class="bi bi-house-door"></i></span>
    <span class="title">Verify Email</span>
</a>
</li>
<li>
    <a href="./mydetails">
<span class="icon"><i class="bi bi-house-door"></i></span>
    <span class="title">My Details</span>
</a>
</li>
<li>
    <a href="./mydocuments">
<span class="icon"><i class="bi bi-house-door"></i></span>
    <span class="title">My Documents</span>
</a>
</li>
<li>
    <a href="./document_value_insert">
<span class="icon"><i class="bi bi-house-door"></i></span>
    <span class="title">Fill document Details</span>
</a>
</li>
<li>
    <a href="./applied_schemes">
<span class="icon"><i class="bi bi-house-door"></i></span>
    <span class="title">Applied Schemes</span>
</a>
</li>
<li>
   <a href="./applied_services">
<span class="icon"><i class="bi bi-house-door"></i></span>
   <span class="title">Applied Services</span>
</a>
</li>
<li>
    <a href="./certificate_generate_pdf">
<span class="icon"><i class="bi bi-house-door"></i></span>
    <span class="title">Certificate</span>
</a>
</li>
            
<?php
    }elseif($USERTYPE==1){
?>
<li>
    <a href="./inserted_jobs">
<span class="icon"><i class="bi bi-house-door"></i></span>
    <span class="title">Inserted Jobs</span>
</a>
</li>

<?php
    }
?>
<li>
    <a href="./logout">
<span class="icon"><i class="bi bi-house-door"></i></span>
<span class="title">Logout</span>
    </a>
</li>
</ul>
</div>
<div class="main">
    <div class="top-bar">
        <div class="toggle">
            <i class="bi bi-list"></i>
        </div>
        <!-- User Image -->
<div class="userimage">
    <?php
        if($USERTYPE==0){
            $id = $_SESSION['regid'];
            $pic = "select display_picture from tbl_user_details where user_reg_id='$id'";
            $pic_result = mysqli_query($conn, $pic);
            $pic_row = mysqli_fetch_assoc($pic_result);
            if($pic_row['display_picture']=='NULL'){
                echo "<img src='./assets/icons/profile.png'>";
            }else{
                $path = "superuser/assets/documents/".$id."/dp/";
            echo "<img src='$path" .$pic_row['display_picture']. " ' class='account_image'>";
                }
            }else{
            echo "<img src='./assets/icons/profile.png'>";
                }
            ?>
        </div>
    </div>

<script>
    // menubar toggle
    let toggle = document.querySelector('.toggle');
    let navigation = document.querySelector('.navigation');
    let main = document.querySelector('.main');
    toggle.onclick = function(){
        navigation.classList.toggle('active');
        main.classList.toggle('active');
    }

    // navigation on hover stay
    let list = document.querySelectorAll('.navigation li');
    function activeLink(){
        list.forEach((item) => 
        item.classList.remove('hovered'));
        this.classList.add('hovered');
    }
    list.forEach((item) => 
    item.addEventListener('mouseover', activeLink));
</script>