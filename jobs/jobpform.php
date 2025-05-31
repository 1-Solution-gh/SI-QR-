<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['adbj'])) {


echo '
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Post a Job</title>
  <link rel="stylesheet" href="../fontawesome/css/all.css">

<style>
  * {
    box-sizing: border-box;
  }

  body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f3f4f6;
    padding: 20px;
  }

  form {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  h2 {
    text-align: center;
    font-size: 24px;
    color: #333;
  }

  label {
    display: block;
    margin-bottom: 6px;
    font-weight: bold;
    color: #333;
  }

  input[type="text"],
  select,
  textarea,
  input[type="file"] {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 15px;
  }

  textarea {
    resize: vertical;
  }

  .form-row {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    width: 100%;
  }

  .form-row > div {
    display: flex;
    flex-direction: column;
    width: 48%;
  }

  .checkbox-group {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  button {
    background-color: #007bff;
    color: white;
    padding: 14px;
    border: none;
    width: 100%;
    border-radius: 6px;
    font-weight: bold;
    font-size: 16px;
    cursor: pointer;
  }

  button:hover {
    background-color: #0056b3;
  }

  @media (max-width: 836px) {
    form {
      padding: 20px;
    }

    .form-row {
      flex-direction: column;
    }

    .form-row > div {
      width: 100%;
    }
  }

 .bbac{
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
</style>

<form action="submit-job.php" method="POST" enctype="multipart/form-data">
  <h2>Post a Job</h2>

  <a href="index.php" class="fa-solid fa-arrow-left bbac"></a>

  <!-- Company Name -->
  <div>
    <label for="company_name">Company Name</label>
    <input required type="text" name="company_name" id="company_name" placeholder="Gee Technology Inc.">
  </div>

  <!-- Job Title -->
  <div>
    <label for="title">Job Title</label>
    <input required type="text" name="title" id="title" placeholder="Frontend Developer">
  </div>

  <!-- Job Type and Region -->
  <div class="form-row">
    <div>
      <label for="job_type">Employment Type</label>
      <select required name="job_type" id="job_type">
        <option value="Full-time">Full-time</option>
        <option value="Part-time">Part-time</option>
        <option value="Contract">Contract</option>
        <option value="Freelance">Freelance</option>
      </select>
    </div>
    <div>
      <label for="region">Region</label>
      <input required type="text" name="region" id="region" placeholder="EMEA, APAC, etc.">
    </div>
  </div>

  <!-- Remote Option -->
  <div class="checkbox-group">
    <input  type="checkbox" name="remote" id="remote" value="yes">
    <label for="remote">This is a remote position</label>
  </div>

  <!-- Job Description -->
  <div>
    <label for="description">Job Description</label>
    <textarea required name="description" id="description" rows="5" placeholder="Describe the role"></textarea>
  </div>

  <!-- Responsibilities -->
  <div>
    <label for="responsibilities">Key Responsibilities</label>
    <textarea required name="responsibilities" id="responsibilities" rows="4" placeholder="List responsibilities"></textarea>
  </div>

  <!-- Requirements -->
  <div>
    <label for="requirements">Requirements</label>
    <textarea required name="requirements" id="requirements" rows="4" placeholder="List required skills and experience"></textarea>
  </div>

  <!-- Company Logo -->
  <div>
    <label for="company_image">Company Logo</label>
    <input required type="file" name="company_image" id="company_image" accept="image/*">
  </div>

   <label for="email">Contact Email:</label>
    <input type="text" name="email" id="email" required>

    <label for="salary">Salary:</label>
    <input type="text" name="salary" id="salary" placeholder="e.g. $50,000 - $70,000" required>

  <!-- Submit -->
  <button type="submit">Publish Job</button>
</form>
</body>
';









    } else {
        header("Location: ../home.php");
        exit();
    }
} else {
    header("Location: ../home.php");
    exit();
}
?>