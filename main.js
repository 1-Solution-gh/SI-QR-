// Full language list with flag country codes
const languages = [
    {code: 'en', label: 'English', flagCode: 'gb'},
    {code: 'hu', label: 'Magyar', flagCode: 'hu'},
    {code: 'de', label: 'Deutsch', flagCode: 'de'},
    {code: 'fr', label: 'Français', flagCode: 'fr'},
    {code: 'es', label: 'Español', flagCode: 'es'},
    {code: 'it', label: 'Italiano', flagCode: 'it'},
    {code: 'pt', label: 'Português', flagCode: 'pt'},
    {code: 'pl', label: 'Polski', flagCode: 'pl'},
    {code: 'ro', label: 'Română', flagCode: 'ro'},
    {code: 'uk', label: 'Українська', flagCode: 'ua'},
    {code: 'ru', label: 'Русский', flagCode: 'ru'},
    {code: 'af', label: 'Afrikaans', flagCode: 'za'},
    {code: 'am', label: 'Amharic', flagCode: 'et'},
    {code: 'ar', label: 'العربية', flagCode: 'sa'},
    {code: 'az', label: 'Azərbaycan', flagCode: 'az'},
    {code: 'be', label: 'Беларуская', flagCode: 'by'},
    {code: 'bg', label: 'Български', flagCode: 'bg'},
    {code: 'bn', label: 'বাংলা', flagCode: 'bd'},
    {code: 'bs', label: 'Bosanski', flagCode: 'ba'},
    {code: 'cs', label: 'Čeština', flagCode: 'cz'},
    {code: 'da', label: 'Dansk', flagCode: 'dk'},
    {code: 'el', label: 'Ελληνικά', flagCode: 'gr'},
    {code: 'et', label: 'Eesti', flagCode: 'ee'},
    {code: 'fi', label: 'Suomi', flagCode: 'fi'},
    {code: 'gl', label: 'Galego', flagCode: 'es'},
    {code: 'gu', label: 'ગુજરાતી', flagCode: 'in'},
    {code: 'he', label: 'עברית', flagCode: 'il'},
    {code: 'hi', label: 'हिन्दी', flagCode: 'in'},
    {code: 'hr', label: 'Hrvatski', flagCode: 'hr'},
    {code: 'id', label: 'Bahasa Indonesia', flagCode: 'id'},
    {code: 'is', label: 'Íslenska', flagCode: 'is'},
    {code: 'ja', label: '日本語', flagCode: 'jp'},
    {code: 'ka', label: 'ქართული', flagCode: 'ge'},
    {code: 'kk', label: 'Қазақша', flagCode: 'kz'},
    {code: 'km', label: 'ខ្មែរ', flagCode: 'kh'},
    {code: 'kn', label: 'ಕನ್ನಡ', flagCode: 'in'},
    {code: 'ko', label: '한국어', flagCode: 'kr'},
    {code: 'lt', label: 'Lietuvių', flagCode: 'lt'},
    {code: 'lv', label: 'Latviešu', flagCode: 'lv'},
    {code: 'mk', label: 'Македонски', flagCode: 'mk'},
    {code: 'ml', label: 'മലയാളം', flagCode: 'in'},
    {code: 'mr', label: 'मराठी', flagCode: 'in'},
    {code: 'ms', label: 'Bahasa Melayu', flagCode: 'my'},
    {code: 'ne', label: 'नेपाली', flagCode: 'np'},
    {code: 'nl', label: 'Nederlands', flagCode: 'nl'},
    {code: 'no', label: 'Norsk', flagCode: 'no'},
    {code: 'pa', label: 'ਪੰਜਾਬੀ', flagCode: 'in'},
    {code: 'sv', label: 'Svenska', flagCode: 'se'},
    {code: 'ta', label: 'தமிழ்', flagCode: 'in'},
    {code: 'te', label: 'తెలుగు', flagCode: 'in'},
    {code: 'th', label: 'ไทย', flagCode: 'th'},
    {code: 'tr', label: 'Türkçe', flagCode: 'tr'},
    {code: 'uk', label: 'Українська', flagCode: 'ua'},
    {code: 'ur', label: 'اردو', flagCode: 'pk'},
    {code: 'vi', label: 'Tiếng Việt', flagCode: 'vn'},
    {code: 'zh', label: '中文', flagCode: 'cn'}
];

const languageDropdown = document.getElementById('languageDropdown');
const nextBtn = document.getElementById('nextBtn');
const dropdownContainer = document.getElementById('dropdownContainer');
const langSearch = document.getElementById('langSearch');
const toggleLangBtn = document.getElementById('toggleLangBtn');
const authModal = document.getElementById('authModal');
const loginBtn = document.getElementById('loginBtn');
const registerBtn = document.getElementById('registerBtn');
const closeModalBtn = document.getElementById('closeModalBtn');

let selectedLang = null;

function renderDropdownItems() {
  languageDropdown.innerHTML = '';
  languages.forEach(lang => {
    const item = document.createElement('div');
    item.className = 'dropdown-item';
    item.setAttribute('data-label', lang.label.toLowerCase());
    item.innerHTML = `<img src="https://flagcdn.com/w40/${lang.flagCode}.png" class="flag-img" alt=""> <span>${lang.label}</span>`;
    item.onclick = () => {
      selectedLang = lang;
      toggleLangBtn.innerHTML = `<img src="https://flagcdn.com/w20/${lang.flagCode}.png" class="flag-img" style="margin-right: 0.5rem; vertical-align: middle;"> ${lang.label}`;
      dropdownContainer.style.display = 'none';
      nextBtn.style.display = 'inline-block';
    };
    languageDropdown.appendChild(item);
  });
}

langSearch.addEventListener('input', () => {
  const query = langSearch.value.toLowerCase();
  document.querySelectorAll('#languageDropdown .dropdown-item').forEach(item => {
    const label = item.getAttribute('data-label');
    item.style.display = label.includes(query) ? 'flex' : 'none';
  });
});

toggleLangBtn.addEventListener('click', () => {
  dropdownContainer.style.display = dropdownContainer.style.display === 'none' ? 'flex' : 'none';
  renderDropdownItems();
  langSearch.focus();
});

document.addEventListener('click', (e) => {
  if (!dropdownContainer.contains(e.target) && e.target !== toggleLangBtn) {
    dropdownContainer.style.display = 'none';
  }
});

nextBtn.addEventListener('click', () => {
  if (selectedLang) {
    localStorage.setItem('lang', selectedLang.code);
    // Show the authentication modal instead of redirecting
    authModal.style.display = 'flex';
  }
});

// Modal controls
loginBtn.addEventListener('click', () => {
  // Redirect to login page or handle login
  window.location.href = "login.php";
});

registerBtn.addEventListener('click', () => {
  // Redirect to registration page
  window.location.href = "qr-scan.php";
});

closeModalBtn.addEventListener('click', () => {
  authModal.style.display = 'none';
});

// Close modal when clicking outside
window.addEventListener('click', (e) => {
  if (e.target === authModal) {
    authModal.style.display = 'none';
  }
});