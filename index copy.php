<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Signs of the Times</title>
</head>
    
<body>
    
<?php
require_once 'login.php';
try{
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>

<div class="grid-container">

<?php 
include 'header.php';
include 'nav.php';
include 'navRight.php';
include 'footer.php';
?>

<div class="middle" id="home">
    
<div class="image-container">
  <?php 
  // Selects 10 random images from the database
  $query = "SELECT image_path FROM Image ORDER BY RAND() LIMIT 10;";
  $stmt = $pdo->prepare($query);
  $stmt->execute();

  // Displays each image in a separate slide
  while ($row = $stmt->fetch()) {
    echo '<div class="mySlides fade">';
    echo '<img src="' . $row["image_path"] . '" alt="Image">';
    echo '</div>';
  }
  $pdo = null;
  ?>

  <!-- Next and previous buttons -->
  <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
  <a class="next" onclick="plusSlides(1)">&#10095;</a>
</div>

 
    
    </div>

                  
    
</div>
                        





    
<script>
    
 let slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  if (n > slides.length) {
    slideIndex = 1;
  }
  if (n < 1) {
    slideIndex = slides.length;
  }
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex - 1].style.display = "block";
  dots[slideIndex - 1].className += " active";
}
</script>
    

</body>
</html>
