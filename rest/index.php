<?php

session_start();
$_SESSION["useridx"] = 8;
$row;

$userloged = $_SESSION["useridx"];
require_once "db.php";

function userlogedin($conn, $userloged)
{
    $sqlquery = "select lat,lng from users where id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sqlquery)) {
        header("location:index.php");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $userloged);
    mysqli_stmt_execute($stmt);
    

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        $result = false;
        return $result;
    }

   
}

$userexist = userlogedin($conn, $userloged);



$searchTerm = isset($_GET['serchh']) ? strtolower(trim($_GET['serchh'])) : '';



$url = "https://places.googleapis.com/v1/places:searchNearby";
$result;
$apiKey = "AIzaSyB36tP9bzCFKT3A1Il8tDS57LfJ3TquBb0";

$data = [
    "includedTypes" => ["restaurant"],
    "maxResultCount" => 20,
    "locationRestriction" => [
        "circle" => [
            "center" => [
                "latitude" => $userexist["lat"],
                "longitude" => $userexist["lng"]
            ],
            "radius" => 500.0
        ]
    ]
];

$headers = [
    "Content-Type: application/json",
    "X-Goog-Api-Key: AIzaSyB36tP9bzCFKT3A1Il8tDS57LfJ3TquBb0",
    "X-Goog-FieldMask: places.displayName,places.location,places.photos,places.rating,places.websiteUri,places.allowsDogs,places.delivery"
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch);
} else {

    $result = json_decode($response, true);
}

curl_close($ch);

// var_dump($result['places']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI QR Hub - Nearby Restaurants</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #4895ef;
            --accent: #4cc9f0;
            --dark: #14213d;
            --light: #f8f9fa;
            --gray: #adb5bd;
            --success: #52b788;
            --border-radius: 12px;
            --shadow: 0 4px 20px rgba(0,0,0,0.08);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--light);
            color: var(--dark);
            margin: 0;
            padding: 0;
        }

        .back-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 100;
            box-shadow: var(--shadow);
            transition: var(--transition);
            transform: rotate(45deg);
        }

        .back-btn:hover {
            transform: rotate(0deg) scale(1.1);
        }

        .back-btn i {
            font-size: 1.5rem;
        }

        .restaurant-section {
            padding: 2rem;
            text-align: center;
            background: linear-gradient(135deg, rgba(67,97,238,0.1), rgba(76,201,240,0.1));
            margin-bottom: 2rem;
        }

        .main-heading {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(to right, var(--primary), var(--accent));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .sub-heading {
            color: var(--dark);
            opacity: 0.8;
            margin-bottom: 1.5rem;
        }

        .search-box {
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 1px solid var(--gray);
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67,97,238,0.1);
        }

        .search-box button {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--primary);
            cursor: pointer;
        }

        .restaurants-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .restaurant-card {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .restaurant-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .restaurant-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }

        .restaurant-content {
            padding: 1.5rem;
        }

        .restname {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }

        .rating {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            color: #FF9529;
            margin-bottom: 0.5rem;
        }

        .reslink {
            color: var(--primary);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .delivery-badge {
            display: inline-block;
            padding: 0.3rem 0.6rem;
            background-color: var(--success);
            color: white;
            border-radius: 20px;
            font-size: 0.8rem;
            margin-top: 0.5rem;
        }

        .fbtn {
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
        }

        @media (max-width: 768px) {
            .restaurants-container {
                grid-template-columns: 1fr;
                padding: 1rem;
            }
            
            .restaurant-section {
                padding: 1.5rem 1rem;
            }
        }
    </style>
</head>
<body>
    <a href="../home.php" class="back-btn">
        <i class="fas fa-arrow-left"></i>
    </a>

    <section class="restaurant-section">
        <h1 class="main-heading">Visit Nearby Restaurants</h1>
        <p class="sub-heading">Discover top-rated spots near you, handpicked to suit your preference</p>
        <form action="" method="GET" class="search-box">
            <input placeholder="Search for popular restaurants..." type="text" name="serchh" id="sercch">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>
    </section>

    <div class="restaurants-container">
        <?php
        if (json_last_error() === JSON_ERROR_NONE) {
            if (!isset($result['error'])) {
                foreach ($result['places'] as $place) {
                    $name = strtolower($place['displayName']['text']);

                    // Skip if search term doesn't match
                    if ($searchTerm && strpos($name, $searchTerm) === false) {
                        continue;
                    }

                    $photoUrl = (isset($place['photos']) && isset($place['photos'][0]['name']))
                        ? "https://places.googleapis.com/v1/" . htmlspecialchars($place['photos'][0]['name']) . "/media?key={$apiKey}&max_width_px=800"
                        : "assets/images/default-restaurant.jpg";
        ?>
        <form action="restprocess.php" method="POST" class="restaurant-card">
            <input type="hidden" name="lat" value="<?= isset($place['location']['latitude']) ? htmlspecialchars($place['location']['latitude']) : '' ?>">
            <input type="hidden" name="location" value="<?= isset($place['displayName']['text']) ? htmlspecialchars($place['displayName']['text']) : '' ?>">
            <input type="hidden" name="long" value="<?= isset($place['location']['longitude']) ? htmlspecialchars($place['location']['longitude']) : '' ?>">
            <input type="hidden" name="delivery" value="<?= isset($place['delivery']) ? htmlspecialchars($place['delivery']) : "0" ?>">
            <input type="hidden" name="website" value="<?= isset($place['websiteUri']) ? htmlspecialchars($place['websiteUri']) : "not set" ?>">
            <input type="hidden" name="rating" value="<?= isset($place['rating']) ? htmlspecialchars($place['rating']) : "No rating" ?>">

            <button type="submit" class="fbtn">
                <img class="restaurant-image" src="<?= $photoUrl ?>" onerror="this.onerror=null;this.src='assets/images/default-restaurant.jpg';" alt="<?= isset($place['displayName']['text']) ? htmlspecialchars($place['displayName']['text']) : 'Restaurant' ?>">
                
                <div class="restaurant-content">
                    <h2 class="restname"><?= isset($place['displayName']['text']) ? htmlspecialchars($place['displayName']['text']) : "Name not set" ?></h2>
                    
                    <?php if (isset($place['websiteUri'])): ?>
                    <p class="reslink">
                        <i class="fas fa-globe"></i> Visit Website
                    </p>
                    <?php endif; ?>
                    
                    <?php if (isset($place['rating'])): ?>
                    <p class="rating">
                        <i class="fas fa-star"></i>
                        <?= htmlspecialchars($place['rating']) ?>
                    </p>
                    <?php endif; ?>
                    
                    <?php if (isset($place['delivery']) && $place['delivery']): ?>
                    <span class="delivery-badge">Delivery Available</span>
                    <?php endif; ?>
                </div>
            </button>
        </form>
        <?php
                }
            } else {
                echo '<div class="error-message">Error from API: ' . htmlspecialchars($result['error']['message']) . '</div>';
            }
        } else {
            echo '<div class="error-message">Unable to load restaurant data. Please try again later.</div>';
        }
        ?>
    </div>
</body>
</html>