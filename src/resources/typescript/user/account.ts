import { formatISO } from 'date-fns';
import '../../scss/user.scss';

const forms = document.querySelectorAll('form[id]');
const anchorLinks = document.querySelectorAll("a[href^='#']");

anchorLinks?.forEach(anchor => {
    anchor.addEventListener('click', e => {
        e.preventDefault();
        const dispatcher = (e.target as HTMLElement).closest('a');
        
        const href = dispatcher?.getAttribute('href') ?? '';
        const targetSection = document.querySelector(href);
        if (targetSection) {
            targetSection.scrollIntoView({
                behavior: 'smooth'
            });
        };

        anchorLinks.forEach(a => a.classList.remove('active'));
        dispatcher?.classList.add('active');


    })
})

window.addEventListener('scroll', () => {
    let scrollPosition = window.scrollY;

    forms.forEach(section => {
        const sectionTop = (section as HTMLElement).offsetTop - 250;
        const sectionHeight = section.clientHeight;

        if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
            anchorLinks.forEach(a => a.classList.remove('active'));

            const href = '#' + section.getAttribute('id');
            document.querySelector(`a[href="${href}"]`)?.classList.add('active');
        }
    });
});

forms.forEach(form => {
    form.addEventListener('submit', e => e.preventDefault());


});