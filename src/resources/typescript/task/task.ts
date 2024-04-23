require("flatpickr/dist/themes/dark.css");
import '../../scss/task.scss';
import Modal from '../modal';
import { del, get, post, put } from '../ajax';
import { buttons, category, dateTime, name, status } from './elements';
import DataTable from 'datatables.net';
import flatpickr from "flatpickr";

const table = new DataTable('#tasks-table', {
    serverSide: true,
    ajax: '/tasks/load',
    orderMulti: false,
    columns: [
        { data: row => name(row.name, row.description) },
        { data: row => category(row.category) },
        { data: row => status(row.status) },
        { data: row => dateTime(row.createdAt) },
        { data: row => dateTime(row.dueDate) },
        {
            orderable: false,
            data: row => buttons(row.id),
        }
    ]
});

const modalElement = document.querySelector('#modal') as HTMLElement;
const modal = new Modal(modalElement);
const modalInput = modal.getInputFields();

let isEditMode = false;

// DateTime picker
flatpickr("#due-date", {
    enableTime: true,
    dateFormat: "d-m-Y h:i K",
    minDate: new Date(),
});

document.querySelector('#open-modal-btn')?.addEventListener('click', () => modal.open());

modal.submitButton?.addEventListener('click', e => {
    e.preventDefault();
    if (! isEditMode) {
        post('/tasks', {
            name: (modalInput as Array<HTMLSelectElement | HTMLInputElement>)[0].value,
            description: (modalInput as Array<HTMLSelectElement | HTMLInputElement>)[1].value,
            category: (modalInput as Array<HTMLSelectElement | HTMLInputElement>)[2].value,
            due_date: (modalInput as Array<HTMLSelectElement | HTMLInputElement>)[3].value,
        }, modal.element).then(res => {
            if (res.ok) {
                table.draw();
                modal.close();
            }
        });
    } else {
        put(`/tasks/${sessionStorage.getItem('editId')}`, {
            name: (modalInput as Array<HTMLSelectElement | HTMLInputElement>)[0].value,
            description: (modalInput as Array<HTMLSelectElement | HTMLInputElement>)[1].value,
            category: (modalInput as Array<HTMLSelectElement | HTMLInputElement>)[2].value,
            due_date: (modalInput as Array<HTMLSelectElement | HTMLInputElement>)[3].value,
        }, modal.element).then(res => {
            if (res.ok) {
                table.draw();
                modal.close();
            }
        });
    }
});

document.querySelector('#tasks-table')?.addEventListener('click', (e) => {
    const dispatcher = e.target as HTMLElement;
    const delBtn = dispatcher.closest('button[data-name=delete]') as HTMLElement;
    const editBtn = dispatcher.closest('button[data-name=edit]') as HTMLElement;

    if (delBtn) {
        del(`/tasks/${delBtn.dataset.id}`).then(() => table.draw());
    } else if (editBtn) {
        isEditMode = true;
        get(`/tasks/${editBtn.dataset.id}`).then(res => res.json()).then(task => {
            (modalInput as Array<HTMLSelectElement | HTMLInputElement>)[0].value = task.name;
            (modalInput as Array<HTMLSelectElement | HTMLInputElement>)[1].value = task.description;
            // ((modalInput as Array<HTMLSelectElement | HTMLInputElement>)[2] as HTMLSelectElement).selectedIndex = task.category;
            (modalInput as Array<HTMLSelectElement | HTMLInputElement>)[3].value = task.dueDate;

            sessionStorage.setItem('editId', task.id);
            modal.open();
        })
    }
})