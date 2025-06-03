<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Live Event Calendar + RSVP</title>
  <style>
    :root {
      --primary: #7c3aed;
      --accent: #f97316;
      --bg: #f9fafb;
      --text: #111827;
      --card-bg: #ffffff;
      --rsvp-bg: #e0f2fe;
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: var(--bg);
      color: var(--text);
      transition: background 0.3s ease-in-out;
    }

    header {
      background: linear-gradient(90deg, var(--primary), var(--accent));
      color: white;
      text-align: center;
      padding: 1.5rem 1rem;
      font-size: 2rem;
      font-weight: 600;
      letter-spacing: 0.05rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .calendar {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 1.5rem;
      padding: 2rem;
    }

    .event-card {
      background: var(--card-bg);
      padding: 1.5rem;
      border-radius: 1.5rem;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
      transition: transform 0.4s ease, box-shadow 0.4s ease;
      position: relative;
      overflow: hidden;
    }

    .event-card::after {
      content: "";
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle at center, rgba(124,58,237,0.05), transparent);
      z-index: 0;
      transition: opacity 0.3s ease-in-out;
    }

    .event-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
    }

    .event-title {
      font-size: 1.4rem;
      font-weight: 600;
      color: var(--primary);
      margin-bottom: 0.5rem;
      position: relative;
      z-index: 1;
    }

    .event-date {
      font-size: 0.95rem;
      color: #6b7280;
      margin-bottom: 1rem;
      z-index: 1;
      position: relative;
    }

    .rsvp-btn {
      padding: 0.6rem 1.2rem;
      background: var(--accent);
      color: white;
      border: none;
      border-radius: 0.5rem;
      font-weight: 500;
      cursor: pointer;
      transition: background 0.3s ease;
      z-index: 1;
      position: relative;
    }

    .rsvp-btn:hover {
      background: #ea580c;
    }

    .rsvp-form {
      margin-top: 1rem;
      background: var(--rsvp-bg);
      padding: 1rem;
      border-radius: 1rem;
      display: none;
      animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .rsvp-form input {
      width: 100%;
      padding: 0.6rem;
      margin-bottom: 0.8rem;
      border: 1px solid #d1d5db;
      border-radius: 0.5rem;
      font-size: 0.95rem;
    }

    .rsvp-form button {
      width: 100%;
      padding: 0.6rem;
      background: var(--primary);
      color: white;
      border: none;
      border-radius: 0.5rem;
      font-weight: 500;
      transition: background 0.3s ease;
    }

    .rsvp-form button:hover {
      background: #5b21b6;
    }

    @media (max-width: 600px) {
      header {
        font-size: 1.4rem;
        padding: 1rem;
      }
      .calendar {
        padding: 1rem;
        gap: 1rem;
      }
      .event-card {
        padding: 1rem;
      }
      .event-title {
        font-size: 1.2rem;
      }
    }
  </style>
</head>
<body>
  <header>Upcoming Events</header>
  <main class="calendar" id="event-calendar"></main>
  <script>
    const eventsEndpoint = 'https://api.example.com/events';

    async function fetchEvents() {
      try {
        const res = await fetch(eventsEndpoint);
        const events = await res.json();
        renderEvents(events);
      } catch (error) {
        document.getElementById('event-calendar').innerHTML = '<p>Error loading events.</p>';
      }
    }

    function renderEvents(events) {
      const calendar = document.getElementById('event-calendar');
      calendar.innerHTML = '';
      events.forEach(event => {
        const card = document.createElement('div');
        card.className = 'event-card';
        card.innerHTML = `
          <div class="event-title">${event.title}</div>
          <div class="event-date">${new Date(event.date).toLocaleDateString()}</div>
          <button class="rsvp-btn">RSVP</button>
          <div class="rsvp-form">
            <input type="text" placeholder="Your Name" class="name-input">
            <input type="email" placeholder="Your Email" class="email-input">
            <button class="submit-btn">Submit</button>
          </div>
        `;
        calendar.appendChild(card);
      });
      attachEventListeners();
    }

    function attachEventListeners() {
      document.querySelectorAll('.rsvp-btn').forEach(button => {
        button.addEventListener('click', () => {
          const form = button.nextElementSibling;
          form.style.display = form.style.display === 'block' ? 'none' : 'block';
        });
      });

      document.querySelectorAll('.submit-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
          const form = btn.closest('.rsvp-form');
          const name = form.querySelector('.name-input').value;
          const email = form.querySelector('.email-input').value;
          const title = btn.closest('.event-card').querySelector('.event-title').innerText;

          if (name && email) {
            try {
              await fetch('https://api.example.com/rsvp', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, email, event: title })
              });
              alert(`Thanks for RSVPing, ${name}!`);
              form.style.display = 'none';
            } catch (e) {
              alert('Submission failed. Try again.');
            }
          } else {
            alert('Please fill in all fields.');
          }
        });
      });
    }

    fetchEvents();
  </script>
</body>
</html>
