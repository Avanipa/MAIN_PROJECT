<?php

include './mail.php';

function send_email_status($email, $name_row, $more_details, $applied_date, $rejection_reason=0){

    $recipient = $email;

    if($rejection_reason==0){
      $message = "
        <html>
          <head>
          <title></title>
          </head>
          <body> 
            <div style='background-color: lightblue; border-radius: 10px; padding: 30px 70px 50px 70px;'>
              <center>
                <h4 style='color: black;'>Please find the below scheme details that you have applied.</h4>
                <table style='color:black'>
                  <tr style='padding: 5px'>
                    <td style='padding: 5px;'><b>Scheme Name</b></td>
                    <td style='padding: 5px;'><a href='localhost/emp/schemes'>".$more_details[0]."</a></td>  
                  </tr>
                  <tr style='padding: 5px'>
                    <td style='padding: 5px;'><b>Sub Scheme Name</b></td>
                    <td style='padding: 5px;'>".$more_details[1]."</td>  
                  </tr>
                  <tr style='padding: 5px'>
                    <td style='padding: 5px;'><b>Date</b></td>
                    <td style='padding: 5px;'>".$applied_date."</td>  
                  </tr>
                  <tr style='padding: 5px'>
                    <td style='padding: 5px;'><b>Status</b></td>
                    <td style='padding: 5px;'><button style='background-color: lightgreen; border-radius: 5px; padding: 5px; outline: none; border: none;'>Approved by Staff</button></td>  
                  </tr>
                </table><br><br>
                Please Note: This is a system generated mail, please don't reply.
              </center>
            </div>
          </body>
        </html>
      ";
      $subject = 'Employment Exchange | Application for scheme has been approved by Staff';
    }else{
      $message = "
        <html>
          <head>
          <title></title>
          </head>
          <body> 
            <div style='background-color: lightblue; border-radius: 10px; padding: 30px 70px 50px 70px;'>
              <center>
                <h4 style='color: black;'>Please find the below scheme details that you have applied.</h4>
                <table style='color:black'>
                  <tr style='padding: 5px'>
                    <td style='padding: 5px;'><b>Scheme Name</b></td>
                    <td style='padding: 5px;'><a href='localhost/emp/schemes'>".$more_details[0]."</a></td>  
                  </tr>
                  <tr style='padding: 5px'>
                    <td style='padding: 5px;'><b>Sub Scheme Name</b></td>
                    <td style='padding: 5px;'>".$more_details[1]."</td>  
                  </tr>
                  <tr style='padding: 5px'>
                    <td style='padding: 5px;'><b>Date</b></td>
                    <td style='padding: 5px;'>".$applied_date."</td>  
                  </tr>
                  <tr style='padding: 5px'>
                    <td style='padding: 5px;'><b>Rejection Reason</b></td>
                    <td style='padding: 5px;'>".$rejection_reason."</td>  
                  </tr>
                  <tr style='padding: 5px'>
                    <td style='padding: 5px;'><b>Status</b></td>
                    <td style='padding: 5px;'><button style='background-color: #F32424; color:white; border-radius: 5px; padding: 5px; outline: none; border: none;'>Rejected by Staff</button></td>  
                  </tr>
                </table><br><br>
                Please Note: This is a system generated mail, please don't reply.
              </center>
            </div>
          </body>
        </html>
      ";
      $subject = 'Employment Exchange | Application for scheme has been rejected by Staff';
    }
    
    send_mail($recipient, $subject, $message);
    
}

// Mail function for sending confirmation mails
function send_email_args($email_row, $name_row, $scheme_row, $applied_date){

$recipient = $email_row['emailid'];
// $message = 'Hi ' . $name_row['uname'] . ',' . '<br><br> Please find the below scheme details applied by you.<br><br>Scheme Name: '.$scheme_row['name'].'<br>Sub Scheme: '.$scheme_row['subscheme'];

$message = "
  <html>
    <head>
    <title></title>
    </head>
    <body> 
      <div style='background-color: lightblue; border-radius: 10px; padding: 30px 70px 50px 70px;'>
        <center>
          <h4 style='color: black;'>Please find the below scheme details that you have applied.</h4>
          <table style='color:black'>
            <tr style='padding: 5px'>
              <td style='padding: 5px;'><b>Scheme Name</b></td>
              <td style='padding: 5px;'><a href='localhost/emp/schemes'>".$scheme_row['subscheme']."</a></td>  
            </tr>
            <tr style='padding: 5px'>
              <td style='padding: 5px;'><b>Sub Scheme Name</b></td>
              <td style='padding: 5px;'>".$scheme_row['name']."</td>  
            </tr>
            <tr style='padding: 5px'>
              <td style='padding: 5px;'><b>Category</b></td>
              <td style='padding: 5px;'>".$scheme_row['category']."</td>  
            </tr>
            <tr style='padding: 5px'>
              <td style='padding: 5px;'><b>Documents Needed</b></td>
              <td style='padding: 5px;'>".$scheme_row['docs_needed']."</td>  
            </tr>
            <tr style='padding: 5px'>
              <td style='padding: 5px;'><b>Criteria</b></td>
              <td style='padding: 5px;'>".$scheme_row['criteria']."</td>  
            </tr>
            <tr style='padding: 5px'>
              <td style='padding: 5px;'><b>Date</b></td>
              <td style='padding: 5px;'>".$applied_date."</td>  
            </tr>
            <tr style='padding: 5px'>
              <td style='padding: 5px;'><b>Status</b></td>
              <td style='padding: 5px;'><button style='background-color: yellow; border-radius: 5px; padding: 5px; outline: none; border: none;'>Pending with Staff</button></td>  
            </tr>
          </table><br><br>
          Please Note: This is a system generated mail, please don't reply.
        </center>
      </div>
    </body>
  </html>
";

$subject = 'Employment Exchange | Application for Scheme';

send_mail($recipient, $subject, $message);

}


function send_email_salary($email_row, $name_row, $salary_row){

  $recipient = $email_row['emailid'];
  // $message = 'Hi ' . $name_row['uname'] . ',' . '<br><br> Please find the below scheme details applied by you.<br><br>Scheme Name: '.$scheme_row['name'].'<br>Sub Scheme: '.$scheme_row['subscheme'];
  
  $message = "
    <html>
      <head>
      <title></title>
      </head>
      <body> 
        <div style='background-color: lightblue; border-radius: 10px; padding: 30px 70px 50px 70px;'>
          <center>
            <h4 style='color: black;'>Salary Details</h4>
            <table style='color:black'>
              <tr style='padding: 5px'>
                <td style='padding: 5px;'><b>Scheme Name</b></td>
                <td style='padding: 5px;'><a href='localhost/emp/schemes'>".$salary_row['month']."</a></td>  
              </tr>
              <tr style='padding: 5px'>
                <td style='padding: 5px;'><b>Sub Scheme Name</b></td>
                <td style='padding: 5px;'>".$salary_row['year']."</td>  
              </tr>
              <tr style='padding: 5px'>
                <td style='padding: 5px;'><b>Category</b></td>
                <td style='padding: 5px;'>".$salary_row['salary']."</td>  
              </tr>
          
            </table><br><br>
            Please Note: This is a system generated mail, please don't reply.
          </center>
        </div>
      </body>
    </html>
  ";
  
  $subject = 'Employment Exchange | Salary Details';
  
  send_mail($recipient, $subject, $message);
}



?>