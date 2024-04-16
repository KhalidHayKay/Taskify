class Modal
{
    private modalBackground?: HTMLElement = undefined;

    public constructor(
        public readonly modalElement: HTMLElement, 
        public readonly modalInputElement: HTMLInputElement | null = null
    ) 
    {
        if (this.modalElement.parentElement) {
            this.modalBackground = this.modalElement.parentElement;
        }
    }

    public open(): void
    {
        this.modalBackground?.classList.remove('hidden');
        this.modalElement?.classList.remove('hidden');
        if (this.modalInputElement) {
            this.modalInputElement.focus();
        }
    }

    public close(): void
    {
        this.modalBackground?.classList.add('hidden');
        this.modalElement?.classList.add('hidden');
        if (this.modalInputElement) {
            this.modalInputElement.value = '';
        }
    }
}

export default Modal;