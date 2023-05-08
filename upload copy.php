<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8">
    <title>Signs of the Times</title>
</head>
<body>
    
<div class="grid-container">
<?php include 'header.php';
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
     

 
    echo "Hello, $user_name!"."<br><br>"."Please follow the instructions below to contribute to this project."."<br><br><br><br>";} 
    
  
?>
    
<h3>Upload an Image of a Protest Sign:</h3>
    <h4>(Try to choose an image that contains only one prominent sign)</h4>

<form method="POST" action="success.php" enctype="multipart/form-data"><br>

Select a JPG of PNG File: <input type='file' name='filename' required>

    
<br><br><br><br><br><br><h3>Add additional information about this sign:</h3><br><br>

    
<label for="creator">1) Creator (who made the sign?):</label><br><br>
    <select name="creator" id="creator" required >
        <option value=''>Select an option</option>
        <option value="unknown">Unknown  (you did not make this sign yourself and are not sure who did)</option>
        <option value="anonymous">Anonymous  (you don't want the creator's name publicly on this website's Browse page)</option>
        <option value="new">Add a New Creator</option>
    </select><br><br>
            <div id="new_creator" style="display: none;">
                <label for="creator_first_name">First Name:</label>
                <input type="text" id="creator_first_name" name="creator_first_name">
                <label for="creator_last_name">Last Name:</label>
                <input type="text" id="creator_last_name" name="creator_last_name">
            </div><br><br><br>
    
        
<label for="year">2) When was it made? (enter a year between 2000 and now):</label>
      <input type="number" id="year" name="year" required min="2000" max=<?php echo date("Y")?>><br><br><br>

    

<label for="photographer">3) Photographer (who took the photo of the sign?):</label><br><br>
    <select name="photographer" id="photographer" required >
        <option value=''>Select an option</option>
        <option value="unknown">Unknown  (you did not take this photo yourself and are not sure who did)</option>
        <option value="anonymous">Anonymous  (you don't want the photographer's name publicly on this website's Browse page)</option>
        <option value="new">Add a New Photographer</option>
    </select><br><br>
            <div id="new_photographer" style="display: none;">
                <label for="photog_first_name">First Name:</label>
                <input type="text" id="photog_first_name" name="photog_first_name">
                <label for="photog_last_name">Last Name:</label>
                <input type="text" id="photog_last_name" name="photog_last_name">
            </div><br><br><br>

    


<label for="category">4) Primary category (required) :</label>
     <?php //populates drop down menu with category names from db
        $category_query = "SELECT category_name FROM Category ORDER BY category_name ASC";
        $res = $pdo->query($category_query);
        echo "<select name='category' id= 'category' required>";
        echo "<option value=''>Select an option</option>";
        while ($row = $res->fetch()) {
        echo "<option value=\"{$row['category_name']}\">{$row['category_name']}</option>";
        }
        echo "</select>";
        ?> <br><br><br>
    
<label for="category2">5) Additional category (optional) :</label>
     <?php
        $category2_query = "SELECT category_name FROM Category ORDER BY category_name ASC";
        $res = $pdo->query($category2_query);
        echo "<select name='category2' id= 'category2'>";
        echo "<option value=''>Select an option</option>";
        while ($row = $res->fetch()) {
        echo "<option value=\"{$row['category_name']}\">{$row['category_name']}</option>";
        }
        echo "</select>";
        ?> <br><br><br>
    
<label for="category3">6) Additional category (optional) :</label>
     <?php
        $category3_query = "SELECT category_name FROM Category ORDER BY category_name ASC";
        $res = $pdo->query($category3_query);
        echo "<select name='category3' id= 'category3'>";
        echo "<option value=''>Select an option</option>";
        while ($row = $res->fetch()) {
        echo "<option value=\"{$row['category_name']}\">{$row['category_name']}</option>";
        }
        echo "</select>";
        ?> <br><br><br>



<label for="text">7) Text displayed on sign:</label>
    <input type="text" name="text" id="text"><br><br><br>

<label for="description">Description of sign (250 characters max):</label>
<br><br>
<textarea name="description" id="description" rows="10" cols="30" oninput="countChars()"></textarea>
<br>
<span id="charCount"></span>
<br><br><br>

<h3>Add information about a specific event the sign was created for (if you do not know the specific event please select "None"):</h3>
    
<label for="protest">8) Event Name:</label>
        <?php
        $protest_query = "SELECT protest_name, date FROM Protest ORDER BY date ASC";
        $res = $pdo->query($protest_query);
        echo "<select name='protest' id= 'protest' required>";
        echo "<option value=''>Select an option</option>";
        echo "<option value='new'>Add a New Event</option>";
        while ($row = $res->fetch()) {
  echo "<option value=\"{$row['protest_name']}\">{$row['protest_name']} {$row['date']}</option>";

        }
        
        echo "</select>";
        ?> <br><br>
    
    <div id="new_event" style="display: none;">
                <label for="protest_name">Event Name:</label>
                <input type="text" id="protest_name" name="protest_name"><br><br><br>
                <label for="organizer">9) Event Organizer:</label>
                <input type="text" id="organizer" name="organizer"><br><br><br>
                <label for="borough">10) Location:</label>
        
                <select name="borough" id="borough">
                    <option value=''>Select an option</option>
                    <option value="Bronx">Bronx</option>
                    <option value="Brooklyn">Brooklyn</option>
                    <option value="Manhattan">Manhattan</option>
                    <option value="Queens">Queens</option>
                    <option value="Staten Island">Staten Island</option>
                </select><br><br><br>
         
                <label for="date">11) Date of Event:</label>
                <input type="date" id="date" name="date"><br><br><br>
        
                <label for="description">12) Description of Event (250 characters max):</label>  <br><br>
                <textarea name="protest_description" id="protest_description" rows="10" cols ="30" oninput="countChars2()"></textarea><br>
<span id="charCount2"></span><br><br><br>

        
        </div><br><br><br>
    
      <input type="checkbox" id="agree" name="agree" required>
    <label for="agree">I have checked that the above infomation is accurate and complete to the best of my knowledge and am ready to submit.</label><br><br>
    
<input type="submit" value="Submit" name="submit">

</form>
 

    
</div>
    


</div>
    
    
<script>
    
var photogSelect = document.getElementById('photographer');
  var newPhotogDiv = document.getElementById('new_photographer');

 photogSelect.addEventListener('change', function() {
    if (photogSelect.value == 'new') {
      newPhotogDiv.style.display = 'block';
    } else {
      newPhotogDiv.style.display = 'none';
    }
  });    
    
      
  var creatorSelect = document.getElementById('creator');
  var newCreatorDiv = document.getElementById('new_creator');

  creatorSelect.addEventListener('change', function() {
    if (creatorSelect.value == 'new') {
      newCreatorDiv.style.display = 'block';
    } else {
      newCreatorDiv.style.display = 'none';
    }
  });
    
var eventSelect = document.getElementById('protest');
  var newEventDiv = document.getElementById('new_event');

 eventSelect.addEventListener('change', function() {
    if (eventSelect.value == 'new') {
      newEventDiv.style.display = 'block';
    } else {
      newEventDiv.style.display = 'none';
    }
  });
    
function countChars() {
  var element = document.getElementById("description");
  var charCount = element.value.length;
  document.getElementById("charCount").innerHTML = "Character count: " + charCount;
}

function countChars2() {
  var element = document.getElementById("protest_description");
  var charCount2 = element.value.length;
  document.getElementById("charCount2").innerHTML = "Character count: " + charCount2;
}
    
  
</script>
    
    
</body>
</html>
