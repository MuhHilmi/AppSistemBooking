import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// import './bootstrap';
import 'bootstrap';

const button = document.getElementById('scrollTop');

window.addEventListener('scroll', () => {
    if (window.scrollY > 300) {
        button.classList.remove('hidden');
    } else {
        button.classList.add('hidden');
    }
});

button?.addEventListener('click', () => {
    window.scrollTo({
        top: 0,

        behavior: 'smooth',
    });
});
