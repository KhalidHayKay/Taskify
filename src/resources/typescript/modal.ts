import { clearValidationErrors } from "./ajax";

class Modal
{
    private readonly modalBackground?: HTMLElement = undefined;

    public constructor(
        public readonly element: HTMLElement, 
    ) 
    {
        if (this.element.parentElement) {
            this.modalBackground = this.element.parentElement;
        }

        element.querySelector('#cancel-btn')?.addEventListener('click', () => {
            this.close();
            this.clearInputs();
            clearValidationErrors(element);
        })
    }

    public open(): void
    {
        this.modalBackground?.classList.remove('hidden');
        this.modalBackground?.classList.add('flex');
        this.element?.classList.remove('hidden');
        this.focusInputs();
    }

    public close(): void
    {
        this.modalBackground?.classList.remove('flex');
        this.modalBackground?.classList.add('hidden');
        this.element?.classList.add('hidden');
        this.clearInputs();
    }

    private clearInputs(): void
    {
        const inputElement = this.getInputFields();

        if (inputElement) { 
            if (! Array.isArray(inputElement)) {
                inputElement.value = '';
            } else {
                inputElement.forEach(input => {
                    if (input.type === 'checkbox') {
                        (<HTMLInputElement> input).checked = false;
                        input.value = 'false';
                    } else {
                        input.value = '';
                    }
                });
            }
        }
    }

    private focusInputs(): void
    {
        const inputElement = this.getInputFields();

        if (inputElement) { 
            if (! Array.isArray(inputElement)) {
                inputElement.focus()
            } else {
                inputElement[0].focus();
            }
        }
    }

    public getInputFields(): HTMLInputElement | Array<HTMLInputElement | HTMLSelectElement> | null
    {
        const inputs = Array.from(this.element.querySelectorAll<HTMLInputElement | HTMLSelectElement>('input, select'));

        if (inputs.length < 1) {
            return null
        } else if (inputs.length === 1) {
            return inputs[0] as HTMLInputElement;
        } else {
            return inputs;
        }
    }

    public get submitButton(): HTMLButtonElement | null
    {
        return this.element.querySelector('#submit-btn');
    }

    public get cancelButton(): HTMLButtonElement | null
    {
        return this.element.querySelector('#cancel-btn');
    }
    
}

export default Modal;