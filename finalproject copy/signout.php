<!DOCTYPE html>
<html>
<head>
   <meta charset="UTF-8">
    <title>WebDev Final Project</title>
    <link rel="stylesheet" type="text/css" href="style.css"> </head>
 <body>
 
<div class="grid-container">
<?php include 'header.php';?>
<?php include 'nav.php';?>  
    
<div class="middle">
    <?php
session_start();
if(isset($_SESSION['user_name']))
{destroy_session_and_data();
echo "You have been signed out. <a href='signin.php'>Click here</a> to sign in.";}

function destroy_session_and_data()
{$_SESSION=array();
setcookie(session_name(),'',time()-2592000,'/');
session_destroy();}
?>
</div>

<?php 
    
include 'navRight.php';
     
include 'footer.php';?>
    
     </div>

     
     </body>
    </html> 

