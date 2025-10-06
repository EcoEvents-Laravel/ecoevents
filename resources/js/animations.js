// path: resources/js/animations.js

// Fonction pour faire apparaître les éléments au défilement
function revealOnScroll() {
  const elementsToReveal = document.querySelectorAll('.blog-card');

  // On crée un "observateur" qui regarde si les éléments entrent dans la fenêtre
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      // Si l'élément est visible à l'écran...
      if (entry.isIntersecting) {
        // ...on lui ajoute la classe pour le faire apparaître
        entry.target.classList.add('is-visible');
        // Et on arrête de l'observer pour ne pas répéter l'animation
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.1 // L'animation se déclenche quand 10% de la carte est visible
  });

  // On dit à l'observateur de surveiller chaque carte
  elementsToReveal.forEach(element => {
    observer.observe(element);
  });
}

// On attend que la page soit entièrement chargée pour lancer notre script
document.addEventListener('DOMContentLoaded', revealOnScroll);