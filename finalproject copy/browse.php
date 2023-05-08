<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Signs of the Times</title></head>
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
	  {
	    $pdo = new PDO($attr, $user, $pass, $opts);
	  }
	  catch (\PDOException $e)
	  {
	    throw new \PDOException($e->getMessage(), (int)$e->getCode());
          
	  }   ?>
     
<h1>Browse Images</h1>
    <form method="POST" action="browse.php">
    <label for="category">Select Category:</label> 
     <?php
        $category_query = "SELECT category_name FROM Category ORDER BY category_name ASC";
        $res = $pdo->query($category_query);
        echo "<select name='category' id= 'category'>";
        echo "<option value ='all'". ((!isset($_POST['category']) || empty($_POST['category']) || $_POST['category'] == "all") ? " selected" : "") .">All</option>";
        while ($row = $res->fetch()) {
            $selected = (isset($_POST['category']) && $_POST['category'] == $row['category_name'] ? " selected" : "");
            echo "<option value=\"{$row['category_name']}\"{$selected}>{$row['category_name']}</option>";
    
        }
        echo "</select>";
        ?> <br><br><br>
        
<input type="submit" name="submit" value="Show Images"><br><br><br>
</form>

     
<?php //checks form is submitted and retrieves data from db for selected category
       if (isset($_POST['submit'])) {
    $category = sanitizeString($_POST["category"]);
   
    
    $category_query = "SELECT category_id FROM Category WHERE category_name = ?";
    $category_stmt = $pdo->prepare($category_query);
    $category_stmt->execute([$category]);
    $category_row = $category_stmt->fetch();
    $category_id = $category_row['category_id'];
    
    
if ($category == "all") {
    $query = "SELECT Image.image_path, Item.*, 
              c1.category_name AS category_name_primary, 
              c2.category_name AS category_name_secondary, 
              c3.category_name AS category_name_tertiary,
              Creator.first_name, Creator.last_name,
              Protest.protest_name,Protest.organizer,Protest.date,Protest.protest_description,Photographer.photog_first_name,Photographer.photog_last_name
              FROM Image
              JOIN Item ON Image.item_id = Item.item_id 
              LEFT JOIN Category c1 ON Item.category_id = c1.category_id 
              LEFT JOIN Category c2 ON Item.category_id_secondary = c2.category_id 
              LEFT JOIN Category c3 ON Item.category_id_tertiary = c3.category_id
              LEFT JOIN Creator ON Item.creator_id=Creator.creator_id
              LEFT JOIN Protest ON Item.protest_id=Protest.protest_id
              LEFT JOIN Photographer ON Image.photographer_id=Photographer.photographer_id ORDER BY year_created";
              
  
        $stmt = $pdo->prepare($query);
        $stmt->execute();
    
}

    
    else {
        $query = "SELECT Image.image_path, Item.*, 
          c1.category_name AS category_name_primary, 
          c2.category_name AS category_name_secondary, 
          c3.category_name AS category_name_tertiary,
          Creator.first_name, Creator.last_name,
        Protest.protest_name,Protest.organizer,Protest.date,Protest.protest_description,Photographer.photog_first_name,Photographer.photog_last_name
          FROM Image
          JOIN Item ON Image.item_id = Item.item_id 
          LEFT JOIN Category c1 ON Item.category_id = c1.category_id 
          LEFT JOIN Category c2 ON Item.category_id_secondary = c2.category_id 
          LEFT JOIN Category c3 ON Item.category_id_tertiary = c3.category_id 
          LEFT JOIN Creator ON Item.creator_id=Creator.creator_id
          LEFT JOIN Protest ON Item.protest_id=Protest.protest_id
          LEFT JOIN Photographer ON Image.photographer_id=Photographer.photographer_id
          WHERE Item.category_id = ? 
          OR Item.category_id_secondary = ? 
          OR Item.category_id_tertiary = ? ORDER BY year_created";


    $stmt = $pdo->prepare($query);
    $stmt->execute([$category_id, $category_id, $category_id]);
}

while ($row = $stmt->fetch()) { 
    echo '<img src="'.$row["image_path"].'" width="200" height="200"><br>';
    echo"<br><b>Year Created: </b>".$row["year_created"];
    echo "<br><b>Text: </b>".$row["sign_text"];
    echo "<br><b>Description: </b>".$row["description"];
    echo "<br><b>Categories: </b>" . $row['category_name_primary'] . ", " . $row['category_name_secondary'] . ", " . $row['category_name_tertiary'];
    echo"<br><b>Sign Creator:</b>".$row["first_name"]." ".$row["last_name"];
    echo"<br><b>Photographer: </b>".$row["photog_first_name"]." ".$row["photog_last_name"];
    echo"<br><b>Event Name: </b>".$row["protest_name"];
    echo"<br><b>Event Organizer: </b>".$row["organizer"];
    echo"<br><b>Event Description: </b>".$row["protest_description"];
    echo"<br><b>Event Date: </b>".$row["date"]."<br><br><br><br>";
    
}}

    function sanitizeString($var)
{

  $var = strip_tags($var);
  $var = htmlentities($var);
  $var = str_replace("'", "\'", $var);
  $var = stripslashes($var);
  return $var;
}
        


    $pdo=null; 
    ?>

</div>


    
     </div>


     
     </body>
    </html> 