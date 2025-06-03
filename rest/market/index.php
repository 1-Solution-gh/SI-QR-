<?php

session_start();
$_SESSION["useridx"] = 8;
$row;

$userloged = $_SESSION["useridx"];
require_once "../db.php";

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
    "includedTypes" => ["supermarket"],
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
    <title>Nearby Groceries</title>
    <link rel="stylesheet" href="assets/fontawesome/css/all.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #6366f1;
            --primary-dark: #4338ca;
            --secondary: #10b981;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #94a3b8;
            --gray-light: #e2e8f0;
            --red: #ef4444;
            --yellow: #f59e0b;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --radius-sm: 0.125rem;
            --radius: 0.25rem;
            --radius-md: 0.375rem;
            --radius-lg: 0.5rem;
            --radius-xl: 0.75rem;
            --radius-2xl: 1rem;
            --radius-full: 9999px;
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
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        .backarr {
            position: fixed;
            top: 1.5rem;
            left: 1.5rem;
            font-size: 1.5rem;
            color: white;
            cursor: pointer;
            z-index: 50;
            background-color: var(--primary);
            padding: 0.75rem;
            width: 3.5rem;
            height: 3.5rem;
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s ease;
            transform: rotate(45deg);
        }

        .backarr:hover {
            background-color: var(--primary-dark);
            transform: rotate(45deg) scale(1.05);
        }

        .restaurant-section {
            text-align: center;
            padding: 6rem 1.5rem 2rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .restaurant-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxyZWN0IHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjA1KSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNwYXR0ZXJuKSIvPjwvc3ZnPg==');
            opacity: 0.4;
        }

        .main-heading {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            position: relative;
            z-index: 1;
            line-height: 1.2;
        }

        .sub-heading {
            font-size: 1.125rem;
            font-weight: 400;
            opacity: 0.9;
            max-width: 42rem;
            margin: 0 auto 2rem;
            position: relative;
            z-index: 1;
        }

        .serch-dbox {
            max-width: 36rem;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .serch-dbox input {
            width: 100%;
            padding: 1rem 1.5rem;
            font-size: 1rem;
            border-radius: var(--radius-full);
            border: none;
            background-color: white;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s ease;
            padding-right: 3.5rem;
        }

        .serch-dbox input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.3);
        }

        .serch-dbox input::placeholder {
            color: var(--gray);
        }

        .serch-dbox button {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            cursor: pointer;
            color: var(--primary);
            transition: all 0.2s ease;
        }

        .serch-dbox button:hover {
            color: var(--primary-dark);
        }

        .serch-dbox button i {
            font-size: 1.25rem;
        }

        .restaurants-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(18rem, 1fr));
            gap: 1.5rem;
            padding: 2rem;
            max-width: 90rem;
            margin: 0 auto;
        }

        @media (min-width: 640px) {
            .restaurants-container {
                grid-template-columns: repeat(auto-fill, minmax(20rem, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .restaurants-container {
                grid-template-columns: repeat(auto-fill, minmax(22rem, 1fr));
            }
        }

        .restaurant-card {
            background: white;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .restaurant-card:hover {
            transform: translateY(-0.5rem);
            box-shadow: var(--shadow-xl);
        }

        .restaurant-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 0.25rem;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .restaurant-image {
            width: 100%;
            height: 12rem;
            object-fit: cover;
            transition: all 0.3s ease;
        }

        .restaurant-card:hover .restaurant-image {
            transform: scale(1.03);
        }

        .card-content {
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .restname {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: var(--dark);
        }

        .rating {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .rating-stars {
            color: var(--yellow);
            margin-right: 0.5rem;
            display: flex;
        }

        .rating-value {
            font-weight: 500;
            color: var(--dark);
        }

        .reslink {
            display: inline-flex;
            align-items: center;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 1rem;
            transition: color 0.2s ease;
        }

        .reslink:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .reslink i {
            margin-right: 0.5rem;
            font-size: 0.875rem;
        }

        .delivery-badge {
            display: inline-block;
            background-color: var(--secondary);
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: var(--radius-sm);
            margin-top: auto;
            align-self: flex-start;
        }

        .no-delivery {
            background-color: var(--gray-light);
            color: var(--dark);
        }

        .fbtn {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            text-align: left;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 4rem 2rem;
            color: var(--gray);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--gray-light);
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }

        .loading-spinner {
            display: inline-block;
            width: 2rem;
            height: 2rem;
            border: 0.25rem solid rgba(79, 70, 229, 0.2);
            border-radius: var(--radius-full);
            border-top-color: var(--primary);
            animation: spin 1s linear infinite;
            margin: 2rem auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Floating action button for mobile */
        .fab {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 3.5rem;
            height: 3.5rem;
            background-color: var(--primary);
            color: white;
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-xl);
            z-index: 40;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .fab:hover {
            background-color: var(--primary-dark);
            transform: scale(1.1);
        }

        .fab i {
            font-size: 1.5rem;
        }

        /* Responsive adjustments */
        @media (max-width: 640px) {
            .restaurant-section {
                padding: 5rem 1rem 1.5rem;
            }
            
            .main-heading {
                font-size: 2rem;
            }
            
            .sub-heading {
                font-size: 1rem;
            }
            
            .restaurants-container {
                padding: 1rem;
                gap: 1rem;
            }
        }
    </style>
</head>

<body>
    <a href="../../home.php" class="backarr" aria-label="Go back">
        <i class="fa-solid fa-cross"></i>
    </a>
    
    <section class="restaurant-section">
        <h1 class="main-heading">Shop Groceries Nearby</h1>
        <p class="sub-heading">Discover top-rated supermarkets near you, handpicked to suit your needs</p>
        <form action="" method="GET" class="serch-dbox">
            <input placeholder="Search for supermarkets..." type="text" name="serchh" id="sercch">
            <button type="submit" aria-label="Search"><i class="fas fa-search"></i></button>
        </form>
    </section>
    
    <div class="restaurants-container">
        <?php
        if (json_last_error() === JSON_ERROR_NONE) {
            if (!isset($result['error'])) {
                if (empty($result['places'])) {
                    echo '<div class="empty-state">
                            <i class="fas fa-store-slash"></i>
                            <h3>No supermarkets found</h3>
                            <p>Try expanding your search radius or check your location settings</p>
                          </div>';
                } else {
                    foreach ($result['places'] as $place) {
                        $name = strtolower($place['displayName']['text']);

                        if ($searchTerm && strpos($name, $searchTerm) === false) {
                            continue;
                        }

                        $deliveryClass = (isset($place['delivery']) && $place['delivery']) ? 'delivery-badge' : 'delivery-badge no-delivery';
                        $deliveryText = (isset($place['delivery']) && $place['delivery']) ? 'Delivery Available' : 'No Delivery';
                        
                        echo "<form action='restprocess.php' method='POST' class='restaurant-card'>";
                        echo "<input hidden name='lat' value='" . (isset($place['location']['latitude']) ? htmlspecialchars($place['location']['latitude']) : '') . "' />";
                        echo "<input hidden name='long' value='" . (isset($place['location']['longitude']) ? htmlspecialchars($place['location']['longitude']) : '') . "' />";
                        echo "<input hidden name='location' value='" . (isset($place['displayName']['text']) ? htmlspecialchars($place['displayName']['text']) : '') . "' />";
                        echo "<input hidden name='delivery' value='" . (isset($place['delivery']) ? htmlspecialchars($place['delivery']) : "0") . "' />";
                        echo "<input hidden name='website' value='" . (isset($place['websiteUri']) ? htmlspecialchars($place['websiteUri']) : "not set") . "' />";
                        echo "<input hidden name='rating' value='" . (isset($place['rating']) ? htmlspecialchars($place['rating']) : "No rating") . "' />";

                        echo "<button class='fbtn' type='submit'>";
                        
                        $photoUrl = (isset($place['photos']) && isset($place['photos'][0]['name']))
                            ? "https://places.googleapis.com/v1/" . htmlspecialchars($place['photos'][0]['name']) . "/media?key={$apiKey}&max_width_px=400"
                            : "aoa.jpg";
                        
                        echo "<img class='restaurant-image' src='" . $photoUrl .
                            "' onerror=\"this.onerror=null;this.src='aoa.jpg';\" alt='" .
                            (isset($place['displayName']['text']) ? htmlspecialchars($place['displayName']['text']) : "Supermarket") .
                            "'>";
                        
                        echo "<div class='card-content'>";
                        echo "<h2 class='restname'>" . (isset($place['displayName']['text']) ? htmlspecialchars($place['displayName']['text']) : "Name not set") . "</h2>";
                        
                        echo "<div class='rating'>";
                        echo "<div class='rating-stars'>";
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
                        echo "<span class='rating-value'>" . (isset($place['rating']) ? htmlspecialchars($place['rating']) : "N/A") . "</span>";
                        echo "</div>";
                        
                        if (isset($place['websiteUri'])) {
                            echo "<a href='" . htmlspecialchars($place['websiteUri']) . "' target='_blank' rel='noopener noreferrer' class='reslink'>";
                            echo "<i class='fa-solid fa-globe'></i> Visit Website";
                            echo "</a>";
                        } else {
                            echo "<p class='reslink'><i class='fa-solid fa-globe'></i> Website not available</p>";
                        }
                        
                        echo "<span class='{$deliveryClass}'>{$deliveryText}</span>";
                        echo "</div>"; // Close card-content
                        echo "</button>";
                        echo "</form>";
                    }
                }
            } else {
                echo '<div class="empty-state">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h3>API Error</h3>
                        <p>' . htmlspecialchars($result['error']['message']) . '</p>
                      </div>';
            }
        } else {
            echo '<div class="empty-state">
                    <i class="fas fa-exclamation-circle"></i>
                    <h3>Data Error</h3>
                    <p>We couldn\'t process the supermarket data. Please try again later.</p>
                  </div>';
        }
        ?>
    </div>
    
    <button class="fab" aria-label="Search" onclick="document.getElementById('sercch').focus()">
        <i class="fas fa-search"></i>
    </button>
    
    <script>
        // Simple animation for cards when they come into view
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.restaurant-card');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = 1;
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });
            
            cards.forEach((card, index) => {
                card.style.opacity = 0;
                card.style.transform = 'translateY(20px)';
                card.style.transition = `all 0.5s ease ${index * 0.1}s`;
                observer.observe(card);
            });
        });
    </script>
</body>

</html>