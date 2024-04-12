import '../scss/category.scss';
import element from './categoryElement';
import { closeModal, openModal } from './modal';

document.querySelector('#modal-btn')?.addEventListener('click', openModal);

document.querySelector('#modal')?.addEventListener('click', (e) => {
    e.preventDefault();
    const dispacher = e.target as Node;
    if (dispacher.textContent === 'Save') {
        const name = document.querySelector('input[name=category]') as HTMLInputElement;

        fetch('/categories/add', {
            method: 'POST',
            body: JSON.stringify({
                ...csrf(),
                name: name.value,
            }),
            headers: {
                'Content-type': 'Application/Json',
            }
        }).then(res => res.json()).then(res => console.log(res))
        .catch(err => console.error(err))

        closeModal();

        fetch('/categories/all').then(res => res.json()).then((res: []) => {
            const container = document.querySelector('table>tbody') as HTMLElement;
            container.innerHTML = res.map(item => element(item)).join('');
        })
    } else if (dispacher.textContent === 'Cancel') {
        closeModal();
    }
})

function csrf() {
    const nameField = document.querySelector('#csrf-name-field') as HTMLMetaElement;
    const valueField = document.querySelector('#csrf-value-field') as HTMLMetaElement;

    return {
        [nameField.name]: nameField.content,
        [valueField.name]: valueField.content,
    };
}

fetch('/categories/all').then(res => res.json()).then((res: []) => {
    const container = document.querySelector('table>tbody') as HTMLElement;
    container.innerHTML = res.map(item => element(item)).join('');

    Array.from(container.children).forEach(element => {
        element.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', e => {
                e.stopPropagation();
                console.log(e.target);
            })
        })
    //     element.addEventListener('click', e => {
    //         const dispatcher = e.target as HTMLElement;
            
    //         if (dispatcher.parentElement?.dataset.name === 'delete') {
    //             console.log(dispatcher.parentElement.dataset.id);
    //         }
    //     })
    })
})