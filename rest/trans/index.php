<?php

session_start();
$_SESSION["useridx"] = 8;
$row;

$userloged = $_SESSION["useridx"];
require_once "../db.php" ;

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
    "includedTypes" => [ "bus_station",
        "train_station",
        "transit_station",
        "subway_station",
        "light_rail_station",
        "airport",
        "taxi_stand",
        "parking"],
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

<?php
// [Keep all your PHP code exactly the same until the <style> tag]
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Restaurants | Discover</title>
    <link rel="stylesheet" href="assets/fontawesome/css/all.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #e63946;
            --primary-light: #f1a8a8;
            --primary-dark: #c1121f;
            --secondary: #457b9d;
            --accent: #a8dadc;
            --dark: #1d3557;
            --light: #f1faee;
            --gray: #6c757d;
            --gray-light: #e9ecef;
            --white: #ffffff;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.08);
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 10px 15px rgba(0, 0, 0, 0.1);
            --radius-sm: 4px;
            --radius: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* Premium Navigation */
        .nav-back {
            position: fixed;
            top: 24px;
            left: 24px;
            z-index: 100;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--white);
            border-radius: 50%;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .nav-back:hover {
            transform: translateX(-4px);
            box-shadow: var(--shadow);
        }

        .nav-back i {
            color: var(--primary);
            font-size: 1.25rem;
        }

        /* Hero Section */
        .hero {
            padding: 120px 24px 60px;
            background: linear-gradient(135deg, var(--dark) 0%, var(--secondary) 100%);
            color: var(--white);
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        .hero::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxyZWN0IHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjAzKSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD9IjEwMCUiIGZpbGw9InVybCgjcGF0dGVybikiLz48L3N2Zz4=');
            opacity: 0.15;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 16px;
            position: relative;
            z-index: 2;
        }

        .hero-subtitle {
            font-size: 1.125rem;
            max-width: 600px;
            margin: 0 auto 40px;
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }

        /* Search Component */
        .search-container {
            max-width: 600px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .search-input {
            width: 100%;
            padding: 16px 24px;
            font-size: 1rem;
            border-radius: 50px;
            border: none;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: var(--shadow);
            transition: var(--transition);
            padding-right: 60px;
        }

        .search-input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.3);
            background: var(--white);
        }

        .search-button {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 44px;
            height: 44px;
            background: var(--primary);
            color: var(--white);
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .search-button:hover {
            background: var(--primary-dark);
            transform: translateY(-50%) scale(1.05);
        }

        /* Restaurant Grid */
        .restaurants-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
            padding: 40px 24px;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Restaurant Card - Premium Design */
        .restaurant-card {
            background: var(--white);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            position: relative;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .restaurant-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-md);
        }

        .card-media {
            position: relative;
            width: 100%;
            height: 180px;
            overflow: hidden;
        }

        .restaurant-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .restaurant-card:hover .restaurant-image {
            transform: scale(1.05);
        }

        .card-badge {
            position: absolute;
            top: 16px;
            right: 16px;
            background: var(--primary);
            color: var(--white);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 50px;
            z-index: 2;
        }

        .card-content {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--dark);
            font-family: 'Inter', sans-serif;
        }

        .card-meta {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
        }

        .card-rating {
            display: flex;
            align-items: center;
            margin-right: 16px;
        }

        .stars {
            color: var(--primary);
            margin-right: 6px;
            display: flex;
        }

        .rating-value {
            font-weight: 600;
            font-size: 0.875rem;
        }

        .card-distance {
            font-size: 0.875rem;
            color: var(--gray);
            display: flex;
            align-items: center;
        }

        .card-distance i {
            margin-right: 4px;
            font-size: 0.75rem;
        }

        .card-actions {
            margin-top: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-button {
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 8px 16px;
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
        }

        .card-button:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .card-button i {
            margin-right: 6px;
            font-size: 0.75rem;
        }

        .card-link {
            color: var(--secondary);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
        }

        .card-link:hover {
            color: var(--dark);
            text-decoration: underline;
        }

        .card-link i {
            margin-right: 6px;
            font-size: 0.75rem;
        }

        /* Empty State */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 80px 24px;
            color: var(--gray);
        }

        .empty-icon {
            font-size: 3rem;
            margin-bottom: 16px;
            color: var(--gray-light);
        }

        .empty-title {
            font-size: 1.5rem;
            margin-bottom: 8px;
            color: var(--dark);
            font-family: 'Playfair Display', serif;
        }

        /* Floating Action Button */
        .fab {
            position: fixed;
            bottom: 24px;
            right: 24px;
            width: 56px;
            height: 56px;
            background: var(--primary);
            color: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-md);
            z-index: 90;
            transition: var(--transition);
            border: none;
            cursor: pointer;
        }

        .fab:hover {
            background: var(--primary-dark);
            transform: translateY(-4px) scale(1.05);
            box-shadow: var(--shadow);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .restaurant-card {
            animation: fadeIn 0.6s cubic-bezier(0.22, 1, 0.36, 1) forwards;
            opacity: 0;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .hero {
                padding: 100px 16px 48px;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .restaurants-grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
                gap: 16px;
                padding: 32px 16px;
            }
            
            .card-media {
                height: 160px;
            }
        }

        @media (max-width: 480px) {
            .hero {
                padding: 80px 16px 40px;
            }
            
            .hero-title {
                font-size: 1.75rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
            
            .restaurants-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <a href="../../home.php" class="nav-back" aria-label="Go back">
        <i class="fas fa-chevron-left"></i>
    </a>
    
    <section class="hero">
        <h1 class="hero-title">Locate Transports Neaby</h1>
        <p class="hero-subtitle">ACurated selection of transportation near you</p>
        
        <div class="search-container">
            <form action="" method="GET">
                <input type="text" name="serchh" id="sercch" class="search-input" placeholder="Search restaurants...">
                <button type="submit" class="search-button" aria-label="Search">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </section>
    
    <div class="restaurants-grid">
        <?php
        if (json_last_error() === JSON_ERROR_NONE) {
            if (!isset($result['error'])) {
                if (empty($result['places'])) {
                    echo '<div class="empty-state">
                            <i class="fas fa-utensils empty-icon"></i>
                            <h3 class="empty-title">No restaurants found</h3>
                            <p>Adjust your search criteria or location settings</p>
                          </div>';
                } else {
                    foreach ($result['places'] as $index => $place) {
                        $name = strtolower($place['displayName']['text']);

                        if ($searchTerm && strpos($name, $searchTerm) === false) {
                            continue;
                        }
                        
                        $deliveryText = (isset($place['delivery']) && $place['delivery']) ? 'Delivery' : 'Dine-in';
                        
                        echo "<form action='restprocess.php' method='POST' class='restaurant-card' style='animation-delay: " . ($index * 0.1) . "s'>";
                        
                        echo "<input type='hidden' name='lat' value='" . (isset($place['location']['latitude']) ? htmlspecialchars($place['location']['latitude']) : '') . "' />";
                        echo "<input type='hidden' name='long' value='" . (isset($place['location']['longitude']) ? htmlspecialchars($place['location']['longitude']) : '') . "' />";
                        echo "<input type='hidden' name='location' value='" . (isset($place['displayName']['text']) ? htmlspecialchars($place['displayName']['text']) : '') . "' />";
                        echo "<input type='hidden' name='delivery' value='" . (isset($place['delivery']) ? htmlspecialchars($place['delivery']) : "0") . "' />";
                        echo "<input type='hidden' name='website' value='" . (isset($place['websiteUri']) ? htmlspecialchars($place['websiteUri']) : "not set") . "' />";
                        echo "<input type='hidden' name='rating' value='" . (isset($place['rating']) ? htmlspecialchars($place['rating']) : "No rating") . "' />";

                        echo "<div class='card-media'>";
                        
                        $photoUrl = (isset($place['photos']) && isset($place['photos'][0]['name']))
                            ? "https://places.googleapis.com/v1/" . htmlspecialchars($place['photos'][0]['name']) . "/media?key={$apiKey}&max_width_px=600"
                            : "aoa.jpg";
                        
                        echo "<img class='restaurant-image' src='" . $photoUrl .
                            "' onerror=\"this.onerror=null;this.src='aoa.jpg';\" alt='" .
                            (isset($place['displayName']['text']) ? htmlspecialchars($place['displayName']['text']) : "Restaurant") .
                            "'>";
                        
                        echo "<span class='card-badge'>" . $deliveryText . "</span>";
                        echo "</div>";
                        
                        echo "<div class='card-content'>";
                        echo "<h3 class='card-title'>" . (isset($place['displayName']['text']) ? htmlspecialchars($place['displayName']['text']) : "Restaurant") . "</h3>";
                        
                        echo "<div class='card-meta'>";
                        echo "<div class='card-rating'>";
                        echo "<div class='stars'>";
                        // Display star ratings
                        $rating = isset($place['rating']) ? floatval($place['rating']) : 0;
                        $fullStars = floor($rating);
                        $hasHalfStar = ($rating - $fullStars) >= 0.5;
                        
                        for ($i = 0; $i < 5; $i++) {
                            if ($i < $fullStars) {
                                echo '<i class="fas fa-star"></i>';
                            } elseif ($i == $fullStars && $hasHalfStar) {
                                echo '<i class="fas fa-star-half-alt"></i>';
                            } else {
                                echo '<i class="far fa-star"></i>';
                            }
                        }
                        echo "</div>";
                        echo "<span class='rating-value'>" . (isset($place['rating']) ? number_format($place['rating'], 1) : "N/A") . "</span>";
                        echo "</div>";
                        
                        echo "<div class='card-distance'>";
                        echo "<i class='fas fa-map-marker-alt'></i> 0.5mi";
                        echo "</div>";
                        echo "</div>";
                        
                        echo "<div class='card-actions'>";
                        echo "<button type='submit' class='card-button'>";
                        echo "<i class='fas fa-bookmark'></i> Save";
                        echo "</button>";
                        
                        if (isset($place['websiteUri'])) {
                            echo "<a href='" . htmlspecialchars($place['websiteUri']) . "' target='_blank' rel='noopener noreferrer' class='card-link'>";
                            echo "<i class='fas fa-external-link-alt'></i> Website";
                            echo "</a>";
                        }
                        echo "</div>";
                        echo "</div>";
                        echo "</form>";
                    }
                }
            } else {
                echo '<div class="empty-state">
                        <i class="fas fa-exclamation-triangle empty-icon"></i>
                        <h3 class="empty-title">Connection Error</h3>
                        <p>' . htmlspecialchars($result['error']['message']) . '</p>
                      </div>';
            }
        } else {
            echo '<div class="empty-state">
                    <i class="fas fa-exclamation-circle empty-icon"></i>
                    <h3 class="empty-title">Data Unavailable</h3>
                    <p>Please try again later</p>
                  </div>';
        }
        ?>
    </div>
    
    <button class="fab" aria-label="Search" onclick="document.getElementById('sercch').focus()">
        <i class="fas fa-search"></i>
    </button>
</body>

</html>