document.addEventListener('DOMContentLoaded', () => {
  const slides = document.querySelectorAll('.slide');
  const dots = document.querySelectorAll('.dot');
  const thumbs = document.querySelectorAll('.thumb');
  const prevBtn = document.querySelector('.prev');
  const nextBtn = document.querySelector('.next');
  let currentIndex = 0;

  function showSlide(index) {
    if (slides.length === 0) return;

    if (index < 0) index = slides.length - 1;
    if (index >= slides.length) index = 0;
    currentIndex = index;

    slides.forEach((slide, i) => slide.classList.toggle('active', i === index));
    dots.forEach((dot, i) => dot.classList.toggle('active', i === index));
    thumbs.forEach((thumb, i) => thumb.classList.toggle('active', i === index));
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', () => {
      showSlide(currentIndex - 1);
    });
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', () => {
      showSlide(currentIndex + 1);
    });
  }

  dots.forEach((dot, i) => {
    dot.addEventListener('click', () => {
      showSlide(i);
    });
  });

  thumbs.forEach((thumb, i) => {
    thumb.addEventListener('click', () => {
      showSlide(i);
    });
  });

  showSlide(currentIndex);

  if (slides.length > 0) {
    setInterval(() => {
      showSlide(currentIndex + 1);
    }, 5000);
  }

  // Funzione messaggi animati
  window.showMessage = function(msg, isError = false) {
    const messageBox = document.createElement('div');
    messageBox.textContent = msg;
    Object.assign(messageBox.style, {
      position: 'fixed',
      top: '10px',
      left: '50%',
      transform: 'translateX(-50%)',
      padding: '15px 25px',
      borderRadius: '8px',
      backgroundColor: isError ? '#e74c3c' : '#27ae60',
      color: '#fff',
      fontSize: '1.1rem',
      boxShadow: '0 2px 10px rgba(0,0,0,0.2)',
      opacity: '0',
      transition: 'opacity 0.5s ease',
      zIndex: 10000,
    });

    document.body.appendChild(messageBox);

    setTimeout(() => {
      messageBox.style.opacity = '1';
    }, 100);

    setTimeout(() => {
      messageBox.style.opacity = '0';
      setTimeout(() => messageBox.remove(), 600);
    }, 3000);
  };

  // Animazione fade-in-up al scroll (tuo approccio)
  function handleScrollAnimation() {
    const elements = document.querySelectorAll('.fade-in-up');
    const windowBottom = window.innerHeight + window.scrollY;

    elements.forEach(el => {
      if (windowBottom > el.offsetTop + 100) {
        el.classList.add('visible');
      }
    });
  }

  window.addEventListener('scroll', handleScrollAnimation);
  window.addEventListener('load', handleScrollAnimation);
  handleScrollAnimation(); // chiamata iniziale per elementi già visibili
});
