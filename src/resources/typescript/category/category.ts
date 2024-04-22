import '../../scss/category.scss';
import { clearValidationErrors, del, get, post, put } from '../ajax';
import element from './elements';
import Modal from '../modal';

const modalElement = document.querySelector('#modal') as HTMLElement
const modal = new Modal(modalElement);

let isEditMode = false;

const render = () => {
    get('/categories/all').then(res => res.json()).then((res: []) => {
        console.log(res);
        const container = document.querySelector('table>tbody') as HTMLElement;
        container.innerHTML = res.map(item => element(item)).join('');
    
        Array.from(container.children).forEach(element => {
            element.addEventListener('click', e => {
                const dispatcher = e.target as HTMLElement;
                const delBtn = dispatcher.closest('button[data-name="delete"]') as HTMLElement;
                const editBtn = dispatcher.closest('button[data-name="edit"]') as HTMLElement;
                const viewBtn = dispatcher.closest('button[data-name="view"]') as HTMLElement;
                
                if (delBtn) {
                    del(`/categories/${delBtn.dataset.id}`).then(() => render())
                } else if(editBtn) {
                    isEditMode = true;
                    const id = editBtn.dataset.id ?? '';
                    get(`/categories/${id}`).then(res => res.json()).then(res => {
                        (modal.getInputFields() as HTMLInputElement).value = res.name
                        sessionStorage.setItem('editId', res.id);
                        modal.open();
                    });
                }
            })
        })
    })
}

document.querySelector('#open-modal-btn')?.addEventListener('click', () => modal.open());

modal.submitButton?.addEventListener('click', (e) => {
    e.preventDefault();
    if (! isEditMode) {
        post('/categories', {name: (modal.getInputFields() as HTMLInputElement).value}, modal.element).then(res => {
            if (res.ok) {
                render();
                modal.close();
                resetModal();
            }
        });
    } else {
        const id = sessionStorage.getItem('editId');
        put(`/categories/${id}`, {name: (modal.getInputFields() as HTMLInputElement).value}, modal.element).then(res => {
            if (res.ok) {
                render();
                modal.close();
                resetModal();
            }
        });
    }
})
    
modal.cancelButton?.addEventListener('click', () => {
    resetModal();
});

const resetModal = () => {
    isEditMode = false;
    clearValidationErrors(modal.element);
    sessionStorage.removeItem('editId');
};

render();