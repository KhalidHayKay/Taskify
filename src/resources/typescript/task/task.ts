require('flatpickr/dist/themes/dark.css');
import '../../scss/task.scss';
import Modal from '../modal';
import { del, get, post, put } from '../ajax';
import elements from './elements';
import DataTable from 'datatables.net';
import flatpickr from "flatpickr";

const table = new DataTable('#tasks-table', {
    serverSide: true,
    ajax: '/tasks/load',
    orderMulti: false,
    columns: [
        { 
            data: row => elements.name(row.name), 
            name: 'name' 
        },
        { 
            data: row => elements.category(row.category), 
            name: 'category'
        },
        { 
            data: row => elements.status(row.status), 
            name: 'status'
        },
        { 
            orderable: false,
            data: row => elements.priority(row.isPriority, row.id), 
            name: 'priority'
        },
        { 
            data: row => elements.dateTime(row.createdAt), 
            name: 'createdAt'
        },
        { 
            data: row => elements.dateTime(row.dueDate), 
            name: 'dueDate'
        },
        {
            orderable: false,
            data: row => elements.buttons(row.id),
            name: 'actionButtons'
        }
    ],
});

const modalElement = document.querySelector('#modal') as HTMLElement;
const modal = new Modal(modalElement);
const modalInput = modal.getInputFields();

let isEditMode = false;

// DateTime picker
flatpickr("#due-date", {
    enableTime: true,
    dateFormat: "d/m/Y h:i K",
    minDate: new Date(),
});

document.querySelector('#open-modal-btn')?.addEventListener('click', () => modal.open());

modalElement.querySelector('#priority')?.addEventListener('click', e => {
    const priority = e.target as HTMLInputElement;

    if (priority.checked) {
        priority.checked = true;
    }
})

const inputs = {
    'name': (modalInput as Array<HTMLSelectElement | HTMLInputElement>)[0],
    'category': <HTMLSelectElement>(modalInput as Array<HTMLSelectElement | HTMLInputElement>)[1],
    'dueDate': (modalInput as Array<HTMLSelectElement | HTMLInputElement>)[2],
    'note': (modalInput as Array<HTMLSelectElement | HTMLInputElement>)[3],
    'priority': <HTMLInputElement>(modalInput as Array<HTMLSelectElement | HTMLInputElement>)[4],
}

modal.submitButton?.addEventListener('click', e => {
    e.preventDefault();
    if (! isEditMode) {
        post('/tasks', {
            name: inputs.name.value,
            category: inputs.category.value,
            due_date: inputs.dueDate.value,
            note: inputs.note.value,
            priority: inputs.priority.checked,
        }, modal.element).then(res => {
            if (res.ok) {
                table.draw();
                modal.close();
            }
        });
    } else {
        put(`/tasks/${sessionStorage.getItem('editId')}`, {
            name: inputs.name.value,
            category: inputs.category.value,
            due_date: inputs.dueDate.value,
            note: inputs.note.value,
            priority: inputs.priority.checked,
        }, modal.element).then(res => {
            if (res.ok) {
                table.draw();
                modal.close();
                isEditMode = false;
            }
        });
    }
});

document.querySelector('#tasks-table')?.addEventListener('click', (e) => {
    const dispatcher = e.target as HTMLElement;
    const delBtn = dispatcher.closest('button[data-name=delete]') as HTMLElement;
    const editBtn = dispatcher.closest('button[data-name=edit]') as HTMLElement;
    const setPriorityBtn = dispatcher.closest('#set-priority') as HTMLInputElement;
    console.log(setPriorityBtn)

    if (delBtn) {
        del(`/tasks/${delBtn.dataset.id}`).then(() => table.draw());
    } else if (editBtn) {
        isEditMode = true;
        get(`/tasks/${editBtn.dataset.id}`).then(res => res.json()).then(task => {
            inputs.name.value = task.name;
            inputs.note.value = task.note;
            inputs.dueDate.value = task.dueDate;
            inputs.priority.checked = task.isPriority;
            inputs.category.querySelectorAll('option').forEach(option => {
                option.removeAttribute('selected');
                if (option.textContent === task.category) {
                    option.selected = true;
                }
            })

            sessionStorage.setItem('editId', task.id);
            modal.open();
        })
    } else if (setPriorityBtn) {
        put(`/tasks/priority/set/${setPriorityBtn.dataset.id}`, {'priority': setPriorityBtn.checked});
    }
})

modal.cancelButton?.addEventListener('click', () => isEditMode = false)