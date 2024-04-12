const modalBg = document.querySelector('#modal-bg');
const modal = document.querySelector('#modal');

const openModal = () => {
    modalBg?.classList.remove('hidden');
    modal?.classList.remove('hidden');
}

const closeModal = () => {
    modalBg?.classList.add('hidden');
    modal?.classList.add('hidden');
}


export {
    openModal,
    closeModal
}