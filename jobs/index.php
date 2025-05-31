<?php
date_default_timezone_set('UTC');
require_once '../rest/db.php';
$conn->query("SET time_zone = '+00:00'");




function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $units = [
        'y' => 'year',
        'm' => 'month',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second'
    ];

    $output = [];

    foreach ($units as $key => $label) {
        if ($diff->$key) {
            $output[] = $diff->$key . ' ' . $label . ($diff->$key > 1 ? 's' : '');
        }
    }

    if (!$full) {
        $output = array_slice($output, 0, 1);
    }

    return $output ? implode(', ', $output) . ' ago' : 'Just now';
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobs</title>
    <link rel="stylesheet" href="../fontawesome/css/all.css">
    <link rel="stylesheet" href="jobs.css">

    <!-- <style>
       
    </style> -->
</head>

<body>
    <div class="job-maincontainer">
        <nav class="nav">
            <div class="jlogo">
                <p>si</p>
            </div>

            <form class="skil-form" method="GET" action="">
                <input type="text" name="skill" placeholder="Title or skill..." class="search-input"
                    value="<?= isset($_GET['skill']) ? htmlspecialchars($_GET['skill']) : '' ?>">
                <button type="submit" class="search-button"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>

            <form class="skil-form" method="GET" action="">
                <input type="text" name="location" placeholder="City or State..." class="search-input"
                    value="<?= isset($_GET['location']) ? htmlspecialchars($_GET['location']) : '' ?>">
                <button type="submit" class="search-button"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>

            <a href="../home.php" class="fa-solid fa-arrow-left bbac"></a>
        </nav>

        <div class="jlard">
            <div class="jlft">
                <div class="jlfttit">
                    <h2>Top jobs pick for you</h2>
                    <p>Based on your location and skills, you can search for jobs that match your qualifications and
                        preferences.</p>
                </div>
                <br>

                <?php
                $skill = isset($_GET['skill']) ? $conn->real_escape_string($_GET['skill']) : '';
                $location = isset($_GET['location']) ? $conn->real_escape_string($_GET['location']) : '';

                $sql = "SELECT * FROM jobs WHERE 1";

                if (!empty($skill)) {
                    $sql .= " AND (title LIKE '%$skill%' OR description LIKE '%$skill%' OR requirements LIKE '%$skill%')";
                }

                if (!empty($location)) {
                    $sql .= " AND region LIKE '%$location%'";
                }

                $sql .= " ORDER BY created_at DESC";

                $result = $conn->query($sql);




                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {


                        $title = htmlspecialchars($row['title']);
                        $jobid = htmlspecialchars($row['id']);
                        $company = htmlspecialchars($row['company_name']);
                        $region = htmlspecialchars($row['region']);
                        $is_remote = $row['is_remote'] ? "Remote" : "On-site";
                        $image = !empty($row['company_image']) ? $row['company_image'] : 'pos.jpg';

                        echo '
        <form action="view-job.php" method="GET" class="jobpost">
            <input hidden  name="id" value="' . $jobid . '">
            <button type="submit">
            <div class="job-card">
        
            <h3 data-i18n="web_dev">' . $title . '</h3>
            <div class="job-meta">
                <span data-i18n="tech_company">🏢 ' . $company . '</span>
                <span data-i18n="month">💵 ' . $row["salary"] . '/month</span>
                <span>⏰ ' . ($row["is_remote"] ? "Remote" : "On site") . '</span>
            </div>
            <div class="general-btn" data-i18n="apply_now">Apply Now</div>
        </div>
                
            </button> 
        </form>';
                    }
                } else {
                    echo "<p>No jobs found.</p>";

                }

                ?>
            </div>
        </div>
    </div>

    <form action="jobpform.php" method="POST">
        <input hidden type="text" name="adbj" value="post jobs">
        <button name="adaj" type="submit" class="pjo" title="Add Jobs">+</button>
    </form>

    <!-- <div class="job-badge" data-i18n="urgent_hire">🔥 '.time_elapsed_string($row["created_at"]).'</div> -->
</body>

</html>