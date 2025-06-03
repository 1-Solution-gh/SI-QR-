console.log("Script is loading!");
document.addEventListener('DOMContentLoaded', function() {
  console.log("DOM is loaded!");
  
  const menuGrid = document.getElementById('menuGrid');
  console.log("Menu grid element:", menuGrid);

    const menuData = [
      {
        href: '', icon: 'fa-home', title: 'Housing', message: 'Find your perfect home.'
      },
      {
        href: '', icon: 'fa-calendar-alt', title: 'Events', message: 'Discover upcoming events.'
      },
      {
        href: '', icon: 'fa-users', title: 'Community', message: 'Connect with others nearby.'
      },
      {
        href: '', icon: 'fa-concierge-bell', title: 'Restaurant', message: 'Explore Restaurant in Town.'
      },
      {
        href: '', icon: 'fa-solid fa-briefcase', title: 'Jobs & Gigs', message: 'Browse all Jobs in the city.'
      },
      {
        href: '', icon: 'fas fa-car', title: 'Transportation', message: 'Check and Understand Transport in the city.'
      },
      {
        href: '', icon: 'fa-solid fa-hospital', title: 'Emergency/Hospital', message: 'Get Emergency Helps here.'
      },
      {
        href: '', icon: 'fa-solid fa-cart-plus', title: 'Shopping', message: 'See Shopping mall and market.'
      },
      {
        href: '', icon: 'fa-solid fa-school', title: 'Schools', message: 'Find schools and University.'
      },
      {
        href: '', icon: 'fas fa-calendar', title: 'Waste Managment', message: 'View Waste Managment Calendar.'
      }
    ];
  
  
    menuData.forEach(({ href, icon, title, message }) => {
      const a = document.createElement('a');
      a.href = href;
      a.className = 'menu-item';
      a.innerHTML = `
        <i class="fas ${icon}"></i>
        <h3>${title}</h3>
        <p>${message}</p>
      `;
      menuGrid.appendChild(a);
    });
  });