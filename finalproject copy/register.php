<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>WebDev Final Project</title></head>
 <body>
     
<div class="grid-container">

<?php include 'header.php';
include 'nav.php';
include 'navRight.php';  
include 'footer.php';?>
    
<div class="middle">
<div class="form-container">
     
     
<h2>Create account</h2>
    <form method="POST" action="">
      <label for="new_username">Username:</label>
      <input type="text" id="new_username" name="new_username"><br><br>
      <label for="new_password">Password:</label>
      <input type="password" id="new_password" name="new_password"><br><br>
      <label for="confirm_password">Confirm password:</label>
      <input type="password" id="confirm_password" name="confirm_password"><br><br>
      <input type="submit" name="submit" value="Sign up">
    </form> </div> 
    

<?php


require_once 'login.php';
    try
	  {$pdo = new PDO($attr, $user, $pass, $opts);}
	  catch (\PDOException $e)
	  {throw new \PDOException($e->getMessage(), (int)$e->getCode());}    

$message = "";
  
//checks for form completion and assigns new username/pw to variables 
    if (isset($_POST['submit'])) { 
    if ( empty($_POST['new_username']) || empty($_POST['new_password'])|| empty($_POST['confirm_password']) ) {
		echo"<p>Please fill out all of the form fields!</p>";} 
    else{  
    $new_username = sanitizeString($_POST['new_username']);
    $new_password = sanitizeString($_POST['new_password']);
    $confirm_password =sanitizeString($_POST['confirm_password']);}}
                                  

//checks for matching passwords and stores new data in User table
    if($new_password!==$confirm_password)
    {echo "<p>Error:passwords do not match</p>";exit;}
    else{$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);}

    $stmt = $pdo->prepare("INSERT INTO User (user_name, password) VALUES ('$new_username', '$hashed_password')");


  if($stmt->execute()) {
    $message= "<p>User created successfully.<a href='signin.php'> Click here to sign in</a></p>";} 
    else {
    $message= "<p>Error: Could not create user.</p>";}
    
     
function sanitizeString($var)
{
  $var = stripslashes($var);
  $var = strip_tags($var);
  $var = htmlentities($var);
  return $var;
}

echo $message ?>  </div>
    

     
</div>

     
    </body>
    </html> 