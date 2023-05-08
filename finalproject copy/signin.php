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
     
<h2>Sign In</h2>
    <form method="POST" action="">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username"><br><br>
      <label for="password">Password:</label>
      <input type="password" id="password" name="password"><br><br>
      <input type="submit" name="submit" value="Log in">
    </form><br><br> </div>

<?php


require_once 'login.php';

	 try
	  {$pdo = new PDO($attr, $user, $pass, $opts);}
	  catch (\PDOException $e)
	  {throw new \PDOException($e->getMessage(), (int)$e->getCode());}    

$message = "";

//checks if the form has been submitted
    if (isset($_POST['submit'])) { 
	if ( empty($_POST['username']) || empty($_POST['password']) ) {
		$message= "<p>Please fill out all of the form fields!</p>";} 
        else {
// Sanitize user's input
	  	$usernamegiven = sanitizeString($_POST["username"]); 
	  	$passwdgiven = sanitizeString($_POST["password"]);

// Prepared statement looking for that username in db
		$stmt = $pdo->prepare('SELECT * FROM User WHERE user_name=?');	
		$stmt->execute([$usernamegiven]);
		$row = $stmt->fetch();
		$un = $row['user_name'];
		$pw = $row['password'];

// Check if the password entered matches the hashed one from db
	if (password_verify($passwdgiven, $pw)) {		
// Found a matching user, store their info in session 
            session_start();
			$_SESSION['user_name'] = $un;
// Redirect to homepage or show link to account page, etc. 
			echo htmlspecialchars("Welcome, $un!");
            die("<p><a href='upload.php'>Click here to upload a new image.</a></p>");} 
            else {
			$message = "<p>Invalid username/password combination, try again.</p>";
		}
	}
}
     
ini_set('session.gc_maxlifetime',60*60*24);

function sanitizeString($var)
{
  $var = stripslashes($var);
  $var = strip_tags($var);
  $var = htmlentities($var);
  return $var;
}

?>
     
<?php echo $message; 
     $pdo=null; ?> </div>


</div>

     
    </body>
    </html> 