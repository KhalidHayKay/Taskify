import '../scss/category.scss';
import { del, get, post, put } from './ajax';
import element from './categoryElement';
import { closeModal, openModal } from './modal';

let isEditMode = false;

const render = () => {
    get('/categories/all').then(res => res.json()).then((res: []) => {
        const container = document.querySelector('table>tbody') as HTMLElement;
        container.innerHTML = res.map(item => element(item)).join('');
    
        Array.from(container.children).forEach(element => {
            element.addEventListener('click', e => {
                const dispatcher = e.target as HTMLElement;
                const delBtn = dispatcher.closest('button[data-name="delete"]') as HTMLElement;
                const editBtn = dispatcher.closest('button[data-name="edit"]') as HTMLElement;
                const viewBtn = dispatcher.closest('button[data-name="view"]') as HTMLElement;
                
                if (delBtn) {
                    del('/categories/delete', {id: delBtn.dataset.id})
                        .then(() => render())
                } else if(editBtn) {
                    isEditMode = true;
                    openModal();
                    const id = editBtn.dataset.id ?? '';
                    sessionStorage.setItem('editId', id);
                }
            })
        })
    })
}

document.querySelector('#modal-btn')?.addEventListener('click', openModal);

document.querySelector('#modal')?.addEventListener('click', (e) => {
    e.preventDefault();
    const dispacher = e.target as Node;
    if (dispacher.textContent === 'Save') {
        const name = document.querySelector('input[name=category]') as HTMLInputElement;

        if (! isEditMode) {
            post('/categories/add', {name: name.value})
                .then(res => res.json()).then(() => render())
        } else {
            put('/categories/update', {
                id: sessionStorage.getItem('editId'),
                name: name.value,
            }).then(() => render())
        }

        name.value = '';
        isEditMode = false;
        closeModal();
    } else if (dispacher.textContent === 'Cancel') {
        closeModal();
    }
})

render();