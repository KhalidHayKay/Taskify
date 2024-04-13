const modalBg = document.querySelector('#modal-bg');
const modal = document.querySelector('#modal');
const modalInput = modal?.querySelector('input[name=category]') as HTMLInputElement;

const openModal = () => {
    modalBg?.classList.remove('hidden');
    modal?.classList.remove('hidden');
    modalInput.focus();
}

const closeModal = () => {
    modalBg?.classList.add('hidden');
    modal?.classList.add('hidden');
    modalInput.value = '';
}


export {
    openModal,
    closeModal
}