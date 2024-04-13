import '../scss/category.scss';
import { del, get, post, put } from './ajax';
import element from './categoryElement';
import { closeModal, openModal } from './modal';

let isEditMode = false;
const nameInput = document.querySelector('input[name=category]') as HTMLInputElement;

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
                    const nameElement = editBtn.parentElement?.parentElement?.parentElement?.firstElementChild?.firstElementChild?.firstElementChild?.firstElementChild;
                    // other option to get edit target name is to make fetch request with btn's data-id, which might just increase latency
                    nameInput.value = nameElement?.textContent ?? '';
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
        if (! isEditMode) {
            post('/categories/add', {name: nameInput.value})
                .then(res => res.json()).then(() => render());
        } else {
            put('/categories/update', {
                id: sessionStorage.getItem('editId'),
                name: nameInput.value,
            }).then(() => render());
        }

        isEditMode = false;
        closeModal();
    } else if (dispacher.textContent === 'Cancel') {
        isEditMode = false;
        closeModal();
    }
})

render();