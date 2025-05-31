<?php
require_once '../rest/db.php'; // Include your database connection file

$job_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$result = $conn->query("SELECT * FROM jobs WHERE id = $job_id");

if (!$result || $result->num_rows === 0) {
    echo "Job not found.";
    exit;
}

$job = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>

<head>
    <title><?= htmlspecialchars($job['title']) ?> - Job Details</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../fontawesome/css/all.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        .job-details {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.7;
            color: #333;
        }

        .job-details img {
            max-width: 120px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .job-details h1 {
            font-size: 2rem;
            margin: 0 0 10px;
            color: #222;
        }

        .job-details .meta {
            font-size: 0.95rem;
            color: #777;
            margin-bottom: 30px;
        }

        .job-details h3 {
            font-size: 1.3rem;
            margin-top: 30px;
            margin-bottom: 10px;
            color: #333;
            border-bottom: 2px solid #eee;
            padding-bottom: 5px;
        }

        .job-details p {
            margin-bottom: 20px;
            white-space: pre-line;
        }

        .bbac {
            color: #000000;
            position: fixed;
            top: 2%;
            left: 10px;
            transform: translate(10px, 2%);
            width: 25px;
            height: 25px;
            background-color: #f4f2ee;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .cta {
            margin-top: 40px;
            padding: 20px;
            background-color: #f5f7fa;
            border-left: 4px solid #4f46e5;
            border-radius: 8px;
            font-size: 1rem;
        }

        .cta a {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 600;
        }

        .cta a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <a href="index.php" class="fa-solid fa-arrow-left bbac"></a>

    <div class="job-details">
        <img src="<?= htmlspecialchars($job['company_image']) ?>" alt="Company Logo">
        <h1><?= htmlspecialchars($job['title']) ?></h1>
        <p class="meta"><?= htmlspecialchars($job['company_name']) ?> — <?= htmlspecialchars($job['region']) ?>
            <?= $job['is_remote'] ? '(Remote)' : '(On site)' ?>
        </p>

        <h3>Description</h3>
        <p><?= nl2br(htmlspecialchars($job['description'])) ?></p>

        <h3>Responsibilities</h3>
        <p><?= nl2br(htmlspecialchars($job['responsibilities'])) ?></p>

        <h3>Requirements</h3>
        <p><?= nl2br(htmlspecialchars($job['requirements'])) ?></p>
        <h3>Salary</h3>
        <p><?= htmlspecialchars($job['salary']) ?></p>

        <div class="cta">
            <p><strong>Send your CV to:</strong>
                <a href="mailto:<?= htmlspecialchars($job['email']) ?>">
                    <?= htmlspecialchars($job['email']) ?>
                </a>
            </p>
        </div>

    </div>

</body>

</html>