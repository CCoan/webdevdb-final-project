<!DOCTYPE html>
<html>
<head>
   <meta charset="UTF-8">
    <title>WebDev Final Project</title>
    <link rel="stylesheet" type="text/css" href="style.css"> </head>
 <body>
 
<div class="grid-container">
<?php 
include 'header.php';
include 'nav.php';
include 'navRight.php';   
include 'footer.php';?>
    
<div class="middle">

    <?php

require_once 'login.php';

	 try
	  {$pdo = new PDO($attr, $user, $pass, $opts);}
	  catch (\PDOException $e)
	  {throw new \PDOException($e->getMessage(), (int)$e->getCode());}   
     
        session_start();
    
                 if (!isset($_SESSION['user_name'])) {
                    header("Location: signin.php");
                    exit;
                }
    
    if(isset($_SESSION['user_name']))
    {$user_name=htmlspecialchars($_SESSION['user_name']);
     
 // retrieve user_id from User table
    $user_query = "SELECT user_id FROM User WHERE user_name = ?";
    $user_stmt = $pdo->prepare($user_query);
    $user_stmt->execute([$user_name]);
    $user_row = $user_stmt->fetch();
    $user_id = $user_row['user_id'];
 
} 
    
  

    if($_FILES){  
        $name=$_FILES['filename']['name'];
        $name=strtolower(preg_replace("[^A-Za-z0-9.]","",$name));//from page 160


      $filepath=$_FILES['filename']['tmp_name'];
      $fileSize=filesize($filepath);
      $fileinfo=finfo_open(FILEINFO_MIME_TYPE);
      $filetype=finfo_file($fileinfo,$filepath);
    
          $allowedTypes=[
          'image/png'=>'png',
          'image/jpeg'=>'jpg',
      ];
      
      
if ($fileSize === 0) {
    $error_message = "The file is empty.";
} 
        else if ($fileSize > 3145728) {
    $error_message = "The file is too large.";
}
     if(!in_array($filetype,array_keys($allowedTypes))){
          $error_message="File not allowed.";
      }
    
    
if (isset($error_message)) {
    echo "<p>Error: " . $error_message . "</p>";
exit();}
      
else{
move_uploaded_file($_FILES['filename']['tmp_name'],'images/'.$name);
 echo 'File uploaded successfully: <img src="images/'.$name.'" width=\'200\' height=\'200\'><br>';


   }}
     
       
    if (isset($_POST['submit'])) { 
        $year_created = sanitizeString($_POST['year']);
        $sign_text = sanitizeString($_POST['text']);
        $description =sanitizeString($_POST['description']);
        $category=sanitizeString($_POST['category']);
        $category2=sanitizeString($_POST['category2']);
        $category3=sanitizeString($_POST['category3']);
        $creator_name = sanitizeString($_POST['creator']);
        $photog_name=sanitizeString($_POST['photographer']);
        $protest_name=sanitizeString($_POST['protest']);
        $organizer=sanitizeString($_POST['organizer']);
        $borough=sanitizeString($_POST['borough']);
        $date=sanitizeString($_POST['date']);
        $protest_description=sanitizeString($_POST['protest_description']);

// check if the creator is Unknown, Anonymous, matches existing creator or needs to be added to the database
    if ($creator_name === 'unknown') {
    $creator_id = 1;}

    elseif ($creator_name === 'anonymous') {
    $creator_id = 2;}
        
    else {
    $creator_first_name = sanitizeString($_POST['creator_first_name']);
    $creator_last_name = sanitizeString($_POST['creator_last_name']);
        
    // Check if creator already exists in database
    $query = "SELECT creator_id FROM Creator WHERE first_name = ? AND last_name = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$creator_first_name,$creator_last_name]);
    $result = $statement->fetch();
    
    if ($result) {
        // Creator already exists in database, assign existing creator_id to submission
        $creator_id = $result['creator_id'];}
        
    else{
    
    $insert_query = "INSERT INTO Creator (creator_id, first_name, last_name) VALUES (NULL,?, ?)";
    $stmt = $pdo->prepare($insert_query);
    $stmt->execute([$creator_first_name, $creator_last_name]);
    
    $creator_id = $pdo->lastInsertId();

}}
        
// check if the photographer is Unknown, Anonymous, matches existing creator or needs to be added to the database
    if ($photog_name === 'unknown') {
    $photog_id = 1;}

    elseif ($photog_name === 'anonymous') {
    $photog_id = 2;}
        
 
    else {
    $photog_first_name = sanitizeString($_POST['photog_first_name']);
    $photog_last_name = sanitizeString($_POST['photog_last_name']);
        
    // Check if photographer already exists in database
    $query = "SELECT photographer_id FROM Photographer WHERE photog_first_name = ? AND photog_last_name = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$photog_first_name,$photog_last_name]);
    $result = $statement->fetch();
    
    if ($result) {
        // Photographer already exists in database, assign existing creator_id to submission
        $photog_id = $result['photographer_id'];}
        
    else{
    
    $insert_query = "INSERT INTO Photographer (photographer_id, photog_first_name, photog_last_name) VALUES (NULL,?, ?)";
    $stmt = $pdo->prepare($insert_query);
    $stmt->execute([$photog_first_name, $photog_last_name]);
        
    $photog_id = $pdo->lastInsertId();
    
}}
        


  
        
    
    // query to retrieve the Protest ID for the protest name
    $protest_id_query = "SELECT protest_id FROM Protest WHERE protest_name = ?";
    $protest_id_stmt = $pdo->prepare($protest_id_query);
    $protest_id_stmt->execute([$protest_name]);
    $protest_row = $protest_id_stmt->fetch();

   
         if ($protest_row) {
        // Protest already exists in database, assign existing protest_id to submission
        $protest_id = $protest_row['protest_id'];
   }
        
          else{
    
    $protest_insert_query = "INSERT INTO Protest (protest_id,protest_name,borough, date,organizer,protest_description) VALUES (NULL,?,?,?,?,?)";
    $stmt = $pdo->prepare($protest_insert_query);
    $stmt->execute([$protest_name,$borough,$date,$organizer,$protest_description]);
        
    $protest_id = $pdo->lastInsertId();}     


  
// query to retrieve the category IDs for the category names
    $category_id_query = "SELECT category_id FROM Category WHERE category_name = ?";
    $category_id_stmt = $pdo->prepare($category_id_query);
    $category_id_stmt->execute([$category]);
    $category_row = $category_id_stmt->fetch();
    $category_id = $category_row['category_id'];
        
    $category_id_query2 = "SELECT category_id FROM Category WHERE category_name = ?";
    $category_id_stmt2 = $pdo->prepare($category_id_query2);
    $category_id_stmt2->execute([$category2]);
    $category_row2 = $category_id_stmt2->fetch();
    $category_id_2 = $category_row2['category_id'];
 
    $category_id_query3 = "SELECT category_id FROM Category WHERE category_name = ?";
    $category_id_stmt3 = $pdo->prepare($category_id_query3);
    $category_id_stmt3->execute([$category3]);
    $category_row3 = $category_id_stmt3->fetch();
    $category_id_3 = $category_row3['category_id'];
        
        
//query to insert data into Item table
    $item_query="INSERT INTO Item(year_created,sign_text,description, creator_id,category_id,category_id_secondary,category_id_tertiary, protest_id,item_id)VALUES(?,?,?,?,?,?,?,?,NULL)"; 
    $stmt=$pdo->prepare($item_query);
    $stmt->execute([$year_created,$sign_text,$description,$creator_id,$category_id,$category_id_2,$category_id_3,$protest_id]);
       
//definining item_id and image_path    
    $item_id = $pdo->lastInsertId();
    $image_path="images/$name";
    
 //query to insert both into Image table, along with user_id
    $image_query="INSERT INTO Image(image_id,image_path,date_uploaded,user_id,item_id,photographer_id) VALUES(NULL,?,DATE(NOW()),?,?,?)";
    $stmt=$pdo->prepare($image_query);
    $stmt->execute([$image_path,$user_id,$item_id,$photog_id]);
   } 
         
   
        
function sanitizeString($var)
{

  $var = strip_tags($var);
  $var = htmlentities($var);
  $var = str_replace("'", "\'", $var);
  $var = stripslashes($var);
  return $var;
}
        
$pdo=null; ?>   


</div>



    
     </div>

     
     </body>
    </html> 