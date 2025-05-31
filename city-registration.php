<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>City Registration</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #eef3f9;
      margin: 0;
      padding: 0;
      color: #1a1a1a;
    }
    header {
      background: linear-gradient(to right, #0052cc, #2f80ed);
      color: white;
      padding: 2rem 1rem;
      text-align: center;
      font-size: 2rem;
      font-weight: 600;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
    .container {
      max-width: 1000px;
      margin: auto;
      padding: 2rem 1rem;
    }
    .step-guide, .booking-form, .doc-checklist {
      background: white;
      margin-bottom: 2rem;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
      transition: all 0.3s ease;
    }
    h2 {
      margin-bottom: 1rem;
      color: #0052cc;
      font-size: 1.6rem;
      border-bottom: 2px solid #ff9900;
      padding-bottom: 0.5rem;
    }
    .steps {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
    }
    .step {
      flex: 1 1 30%;
      background: #f0f7ff;
      padding: 1rem;
      border-left: 4px solid #2f80ed;
      border-radius: 8px;
      font-weight: 500;
      transition: transform 0.3s ease;
    }
    .step:hover {
      transform: translateY(-5px);
      background: #e2ecff;
    }
    .form-group {
      margin-bottom: 1.2rem;
    }
    label {
      display: block;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }
    input[type="text"], input[type="email"], input[type="date"], select {
      width: 100%;
      padding: 0.75rem;
      font-size: 1rem;
      border: 1px solid #ccc;
      border-radius: 6px;
      transition: border 0.2s;
    }
    input:focus {
      border-color: #2f80ed;
      outline: none;
    }
    .error {
      color: #d90000;
      font-size: 0.85rem;
      margin-top: 0.3rem;
    }
    button {
      background: #0052cc;
      color: white;
      border: none;
      padding: 0.75rem 1.5rem;
      font-size: 1rem;
      border-radius: 6px;
      cursor: pointer;
      transition: background 0.3s;
    }
    button:hover {
      background: #0041a3;
    }
    .tabs {
      display: flex;
      gap: 1rem;
      margin-bottom: 1rem;
      flex-wrap: wrap;
    }
    .tab {
      padding: 0.5rem 1rem;
      background: #2f80ed;
      color: white;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 500;
      transition: background 0.3s;
    }
    .tab:hover {
      background: #1e5fbf;
    }
    .tab.active {
      background: #ff9900;
      color: #1a1a1a;
    }
    #checklist {
      padding-left: 1rem;
    }
    #checklist li {
      margin-bottom: 0.5rem;
      padding: 0.5rem 0.75rem;
      background: #f8fbff;
      border-left: 4px solid #2f80ed;
      border-radius: 4px;
      animation: slideIn 0.3s ease-in;
    }
    @keyframes slideIn {
      from { opacity: 0; transform: translateX(-10px); }
      to { opacity: 1; transform: translateX(0); }
    }
    .modal {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.5);
      display: flex;
      justify-content: center;
      align-items: center;
      visibility: hidden;
      opacity: 0;
      transition: all 0.3s ease;
      z-index: 999;
    }
    .modal.active {
      visibility: visible;
      opacity: 1;
    }
    .modal-content {
      background: white;
      padding: 2rem;
      border-radius: 10px;
      text-align: center;
      max-width: 400px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.2);
    }
  </style>
</head>
<body>
  <header>City Registration Portal</header>
  <div class="container">
    <section class="step-guide">
      <h2>Step-by-Step Guide</h2>
      <div class="steps">
        <div class="step">Step 1: Fill out personal details</div>
        <div class="step">Step 2: Choose appointment slot</div>
        <div class="step">Step 3: Submit required documents</div>
      </div>
    </section>

    <section class="booking-form">
      <h2>Appointment Booking</h2>
      <form id="bookingForm">
        <div class="form-group">
          <label for="name">Full Name</label>
          <input type="text" id="name" name="name" required />
          <div class="error" id="nameError"></div>
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required />
          <div class="error" id="emailError"></div>
        </div>
        <div class="form-group">
          <label for="date">Preferred Date</label>
          <input type="date" id="date" name="date" required />
          <div class="error" id="dateError"></div>
        </div>
        <button type="submit">Book Appointment</button>
      </form>
    </section>

    <section class="doc-checklist">
      <h2>Document Checklist</h2>
      <div class="tabs">
        <div class="tab active" data-type="resident">Resident</div>
        <div class="tab" data-type="business">Business Owner</div>
        <div class="tab" data-type="student">Student</div>
      </div>
      <ul id="checklist"></ul>
    </section>
  </div>

  <div class="modal" id="successModal">
    <div class="modal-content">
      <h3>Appointment Confirmed!</h3>
      <p>Your appointment has been successfully booked.</p>
      <button onclick="document.getElementById('successModal').classList.remove('active')">OK</button>
    </div>
  </div>

  <script>
    const checklist = {
      resident: ["Proof of Identity", "Address Proof", "Photograph"],
      business: ["Business License", "Tax Documents", "Identity Proof"],
      student: ["Student ID", "Enrollment Certificate", "Guardian Consent"]
    };

    function renderChecklist(type) {
      const list = document.getElementById("checklist");
      list.innerHTML = "";
      checklist[type].forEach(item => {
        const li = document.createElement("li");
        li.textContent = item;
        list.appendChild(li);
      });
    }

    document.querySelectorAll(".tab").forEach(tab => {
      tab.addEventListener("click", () => {
        document.querySelectorAll(".tab").forEach(t => t.classList.remove("active"));
        tab.classList.add("active");
        renderChecklist(tab.dataset.type);
      });
    });

    renderChecklist("resident");

    document.getElementById("bookingForm").addEventListener("submit", e => {
      e.preventDefault();

      let valid = true;
      const name = document.getElementById("name").value.trim();
      const email = document.getElementById("email").value.trim();
      const date = document.getElementById("date").value;

      document.getElementById("nameError").textContent = name ? "" : "Name is required.";
      document.getElementById("emailError").textContent = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email) ? "" : "Enter a valid email.";
      document.getElementById("dateError").textContent = date ? "" : "Select a date.";

      if (!name || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email) || !date) {
        valid = false;
      }

      if (valid) {
        document.getElementById("successModal").classList.add("active");
        document.getElementById("bookingForm").reset();
      }
    });
  </script>
</body>
</html>
