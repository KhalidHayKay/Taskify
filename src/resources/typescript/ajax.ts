const ajax = (
    url: string, 
    method?: string, 
    data?: {},
    domElement?: HTMLElement,
) => {
    if (! method) {
        method = 'get';
    }

    let options;

    const csrfMethods = new Set(['post', 'put', 'delete', 'patch'])

    if (csrfMethods.has(method)) {
        options = {
            method: method.toUpperCase(),
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({...data, ...csrf()})
        }
    } else if (method === 'get') {
        url += '?' + (new URLSearchParams(data)).toString();
    }

    return fetch(url, options).then(response => {
        if (domElement) {
            clearValidationErrors(domElement)
        }

        if (! response.ok) {
            if (response.status === 422) {
                response.json().then(errors => {
                    handleValidationErrors(errors, domElement);
                    console.log(errors.name);
                })
            } else if (response.status === 404) {
                alert(response.statusText)
            }
        }

        return response
    })
}

const get  = (url: string) => ajax(url);
const post = (url: string, data: {}, domElement?: HTMLElement) => ajax(url, 'post', data, domElement);
const put = (url: string, data: {}, domElement?: HTMLElement) => ajax(url, 'put', data, domElement);
const del  = (url: string, data?: {}) => ajax(url, 'delete', data);

const handleValidationErrors = (errors?: [], domElement?: HTMLElement) => {
    if (errors && domElement) {
        for (const name in errors) {
            const element = domElement.querySelector(`input[name="${ name }"]`);
            const errorDiv = domElement.querySelector('#error-div') as HTMLElement;

            element?.classList.add('is-invalid');
            // errorDiv.classList.add('invalid-feedback');
            errorDiv.textContent = errors[name][0];
        }
    }
}

const clearValidationErrors = (domElement: HTMLElement) => {
    domElement.querySelectorAll('#error-div').forEach(element => {
        element.textContent = '';
    });
}

const csrf = () => {
    const nameField = document.querySelector('#csrf-name-field') as HTMLMetaElement;
    const valueField = document.querySelector('#csrf-value-field') as HTMLMetaElement;

    return {
        [nameField.name]: nameField.content,
        [valueField.name]: valueField.content,
    };
}

export {
    ajax,
    get,
    post,
    del,
    put,

    clearValidationErrors
}
