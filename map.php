<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Map View with Filters</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    :root {
      --primary: #0057b8;
      --accent: #ff7a59;
      --dark: #1a1a1a;
      --bg: #f0f2f5;
      --card: #ffffff;
      --shadow: rgba(0, 0, 0, 0.1);
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: var(--bg);
      color: var(--dark);
    }

    header {
      background: linear-gradient(90deg, var(--primary), var(--accent));
      padding: 1.5rem;
      color: white;
      text-align: center;
      box-shadow: 0 4px 8px var(--shadow);
    }

    h1 {
      margin: 0;
      font-size: 1.75rem;
      letter-spacing: 1px;
    }

    .container {
      display: flex;
      flex-direction: column;
    }

    .filters {
      display: flex;
      justify-content: space-around;
      gap: 1rem;
      padding: 1rem;
      background-color: var(--card);
      box-shadow: 0 2px 6px var(--shadow);
      flex-wrap: wrap;
    }

    .filters button {
      padding: 0.5rem 1rem;
      border: 2px solid transparent;
      border-radius: 20px;
      font-size: 0.9rem;
      background-color: var(--primary);
      color: white;
      cursor: pointer;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .filters button:hover {
      background-color: var(--accent);
    }

    .filters button.active {
      background-color: white;
      color: var(--primary);
      border-color: var(--primary);
      font-weight: bold;
    }

    #map {
      width: 100%;
      height: calc(100vh - 240px);
      margin-top: 1rem;
    }

    @media (min-width: 768px) {
      .container {
        flex-direction: row;
      }

      .filters {
        flex-direction: column;
        width: 220px;
        min-height: calc(100vh - 72px);
        border-right: 1px solid #ddd;
      }

      #map {
        height: calc(100vh - 72px);
        width: 100%;
        margin-top: 0;
      }
    }
  </style>
</head>
<body>
  <header>
    <h1><i class="fas fa-globe"></i> Explore Local Services</h1>
  </header>

  <div class="container">
    <div class="filters">
      <button onclick="toggleCategory('health')"><i class="fas fa-notes-medical"></i> Health</button>
      <button onclick="toggleCategory('housing')"><i class="fas fa-home"></i> Housing</button>
      <button onclick="toggleCategory('school')"><i class="fas fa-school"></i> Schools</button>
      <button onclick="toggleCategory('event')"><i class="fas fa-calendar-alt"></i> Events</button>
    </div>
    <div id="map"></div>
  </div>

  <script>
    let map;
    let userMarker;
    let categories = new Set();
    const markers = [];

    const iconMap = {
      health: 'https://cdn-icons-png.flaticon.com/512/2965/2965567.png',
      housing: 'https://cdn-icons-png.flaticon.com/512/1946/1946436.png',
      school: 'https://cdn-icons-png.flaticon.com/512/2965/2965366.png',
      event: 'https://cdn-icons-png.flaticon.com/512/747/747310.png',
    };

    function toggleCategory(category) {
      const btn = document.querySelector(`button[onclick*="${category}"]`);
      if (categories.has(category)) {
        categories.delete(category);
        btn.classList.remove('active');
      } else {
        categories.add(category);
        btn.classList.add('active');
      }
      updateMarkers();
    }

    function fetchLiveData(userPos) {
      fetch(`/api/data?lat=${userPos.lat}&lng=${userPos.lng}`)
        .then(res => res.json())
        .then(data => {
          updateMapMarkers(data);
        })
        .catch(console.error);
    }

    function updateMapMarkers(places) {
      markers.forEach(m => m.setMap(null));
      markers.length = 0;
      places.forEach(place => {
        if (!categories.size || categories.has(place.category)) {
          const marker = new google.maps.Marker({
            position: { lat: place.lat, lng: place.lng },
            map,
            title: place.title,
            icon: {
              url: iconMap[place.category] || null,
              scaledSize: new google.maps.Size(30, 30)
            }
          });
          markers.push(marker);
        }
      });
    }

    function initMap() {
      navigator.geolocation.getCurrentPosition(pos => {
        const userPos = {
          lat: pos.coords.latitude,
          lng: pos.coords.longitude,
        };

        map = new google.maps.Map(document.getElementById("map"), {
          zoom: 14,
          center: userPos,
        });

        userMarker = new google.maps.Marker({
          position: userPos,
          map,
          title: "You are here",
          icon: {
            path: google.maps.SymbolPath.CIRCLE,
            scale: 8,
            fillColor: "#0077ff",
            fillOpacity: 0.8,
            strokeWeight: 2,
            strokeColor: "white",
          },
        });

        fetchLiveData(userPos);
      });
    }
  </script>
  <script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap"></script>
</body>
</html>
