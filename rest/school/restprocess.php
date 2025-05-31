<?php
if(isset($_POST['lat'])  && isset($_POST['long']) ){
  $location = $_POST['location'] ?? 'not set';
    $lat = $_POST['lat'] ?? '0';
$lng = $_POST['long'] ?? '0';
$delivery = $_POST['delivery'] ?? '0';
$website = $_POST['website'] ?? 'not set';
$rating = $_POST['rating'] ?? 'No rating';
}else{
    header("location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Restaurant Details</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #fff;
      color: #fff;
    }

 

    .wrapper {
     width: 100vw;
     height: 100vh;
     display: flex;
     background-color: #fff;
    }

    
    .info-half {
      flex: 1;
      background-color: #fff;
    }

    .map-half{
        width: 50%;
        height: 100%;
    }

    .map-half iframe {
      width: 100%;
      height: 100%;
      border: none;
    }

    .info-half {
      padding: 20px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      justify-content: center;
    }

    .card {
      background-color: #2a2a2a;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 0 12px rgba(0, 255, 195, 0.3);
      display: flex;
      align-items: center;
      gap: 15px;
      width: 340px;
      height: 300px;
      overflow: hidden;
    }

    .card i {
      color:rgb(0, 94, 255);
      font-size: 2.5em;
      min-width: 30px;
      text-align: center;
    }

    .card-content {
      display: flex;
      flex-direction: column;
    }

    .card-title {
      font-weight: bold;
      font-size: 2em;
      margin-bottom: 5px;
    }

    .card-value a {
      color: #00ffc3;
      text-decoration: none;
    }

    .card-value a:hover {
      text-decoration: underline;
    }

    @media (max-width: 1071px) {
      .wrapper {
        flex-direction: column;
        height: 100%;
        width: 100vw;
      }

      
      .info-half {
        height: 100%;
        display: grid;
      grid-template-columns: 1fr;
      place-content: center;
      }

      .map-half  {
      width: 100%;
      height: 90vh;
      border: none;
    }
    }

     .plsname {
      font-size: 2em;
      color:rgb(0, 0, 0);
      text-align: center;
      margin-top: 20px;
      z-index: 3;
    }
  </style>
</head>
<body>
<a href="index.php"><i style="position: fixed;
            top: 20px;
            left: 20px;
            font-size: 3rem;
            color: black;
            cursor: pointer;
            z-index: 10;
            background-color: green;
            padding: 10px;
            rotate: 45deg;
            width: 60px;
            height: 60px;
            border-radius: 70px;
            color: silver;" class="fa-solid fa-cross backarr"></i></a>
<h1 class="plsname"><?=$_POST['location'] ?></h1>
<br>

<div class="wrapper">
  <!-- Map Section -->
  <div class="map-half">
    <iframe
      src="https://www.google.com/maps?q=<?= urlencode($lat) ?>,<?= urlencode($lng) ?>&hl=en&z=15&output=embed"
      allowfullscreen>
    </iframe>
  </div>

  <!-- Info Cards Section -->
  <div class="info-half">
    <!-- Delivery Card -->
    <div class="card">
      <i class="fas fa-truck"></i>
      <div class="card-content">
        <div class="card-title">Delivery</div>
        <div class="card-value"><?= $delivery === '1' ? 'Available' : 'Not Available' ?></div>
      </div>
    </div>

     <!-- pet Policy -->
     <div class="card">
      <i class="fas fa-dog"></i>
      <div class="card-content">
        <div class="card-title">Pet Policy</div>
        <div class="card-value"><?= $delivery === '1' ? 'Dogs are allowed' : 'No dogs allowed' ?></div>
      </div>
    </div>

    <!-- Website Card -->
    <div class="card">
      <i class="fas fa-globe"></i>
      <div class="card-content">
        <div class="card-title">Website</div>
        <div class="card-value">
          <?php if ($website !== 'not set'): ?>
            <a href="<?= htmlspecialchars($website) ?>" target="_blank"><?= htmlspecialchars($website) ?></a>
          <?php else: ?>
            Not Available
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Rating Card -->
    <div class="card">
      <i class="fas fa-star"></i>
      <div class="card-content">
        <div class="card-title">Rating</div>
        <div class="card-value"><?= htmlspecialchars($rating) ?></div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
