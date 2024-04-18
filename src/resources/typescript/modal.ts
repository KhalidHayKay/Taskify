class Modal
{
    private readonly modalBackground?: HTMLElement = undefined;
    public readonly InputElement: HTMLInputElement | Array<HTMLInputElement | HTMLSelectElement>;

    public constructor(
        public readonly element: HTMLElement, 
    ) 
    {
        if (this.element.parentElement) {
            this.modalBackground = this.element.parentElement;
        }

        const inputs = Array.from(element.querySelectorAll<HTMLInputElement | HTMLSelectElement>('input, select'));

        if (inputs.length === 1) {
            this.InputElement = inputs[0] as HTMLInputElement;
        } else {
            this.InputElement = inputs;
        }
    }

    public open(): void
    {
        this.modalBackground?.classList.remove('hidden');
        this.element?.classList.remove('hidden');
        this.focusInputs();
    }

    public close(): void
    {
        this.modalBackground?.classList.add('hidden');
        this.element?.classList.add('hidden');
        this.clearInputs();
    }

    private clearInputs(): void
    {
        if (this.InputElement) { 
            if (! Array.isArray(this.InputElement)) {
                this.InputElement.value = '';
            } else {
                this.InputElement.forEach(input => {
                    input.value = '';
                });
            }
        }
    }

    private focusInputs(): void
    {
        if (this.InputElement) { 
            if (! Array.isArray(this.InputElement)) {
                this.InputElement.focus()
            } else {
                this.InputElement[0].focus();
            }
        }
    }
}

export default Modal;