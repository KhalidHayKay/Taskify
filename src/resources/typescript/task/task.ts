import DataTable from 'datatables.net-dt';
import '../../scss/task.scss';
import Modal from '../modal';
import { clearValidationErrors, del, get, post, put } from '../ajax';

const modalElement = document.querySelector('#modal') as HTMLElement;
const modal = new Modal(modalElement);

let isEditMode = false;

document.querySelector('#open-modal-btn')?.addEventListener('click', () => modal.open());

// document.querySelector('#modal')?.addEventListener('click', (e) => {
//     e.preventDefault();
//     const dispacher = e.target as Node;
//     if (dispacher.textContent?.trim() === 'Save') {
//         if (! isEditMode) {
//             post('/categories', {name: modalInput.value}, modal.modalElement).then(res => {
//                 if (res.ok) {
//                     render();
//                     resetModal();
//                 }
//             });
//         } 
//         /* else {
//             const id = sessionStorage.getItem('editId');
//             put(`/categories/${id}`, {name: modalInput.value}, modal.modalElement).then(res => {
//                 if (res.ok) {
//                     render();
//                     resetModal();
//                 }
//             });
//         }
//         console.log('Do what you gotta do'); */

//     } else if (dispacher.textContent?.trim() === 'Cancel') {
//         resetModal();
//     }

//     function resetModal() {
//         modal.close();
//     }
// })
