// const ajax = (
//     url: string, 
//     method?: string | null | undefined, 
//     data: {} | null | undefined = {}, 
//     domElement = null
// ) => {
//     method = method.toLowerCase()

//     let options = {
//         method,
//         headers: {
//             'Content-Type': 'application/json',
//             'X-Requested-With': 'XMLHttpRequest'
//         }
//     }

//     const csrfMethods = new Set(['post', 'put', 'delete', 'patch'])

//     if (csrfMethods.has(method)) {
//         let additionalFields = {...csrf()}

//         if (method !== 'post') {
//             options.method = 'post'

//             additionalFields._METHOD = method.toUpperCase()
//         }

//         if (data instanceof FormData) {
//             for (const additionalField in additionalFields) {
//                 data.append(additionalField, additionalFields[additionalField])
//             }

//             delete options.headers['Content-Type'];

//             options.body = data
//         } else {
//             options.body = JSON.stringify({...data, ...additionalFields})
//         }
//     } else if (method === 'get') {
//         url += '?' + (new URLSearchParams(data)).toString();
//     }

//     return fetch(url, options).then(response => {
//         if (domElement) {
//             clearValidationErrors(domElement)
//         }

//         if (! response.ok) {
//             if (response.status === 422) {
//                 response.json().then(errors => {
//                     handleValidationErrors(errors, domElement)
//                 })
//             } else if (response.status === 404) {
//                 alert(response.statusText)
//             }
//         }

//         return response
//     })
// }

// const get  = (url: string, data: {}) => ajax(url, 'get', data)
// const post = (url: string, data: {}, domElement) => ajax(url, 'post', data, domElement)
// const del  = (url: string, data: {}) => ajax(url, 'delete', data)

// function handleValidationErrors(errors, domElement) {
//     for (const name in errors) {
//         const element = domElement.querySelector(`[name="${ name }"]`)

//         element.classList.add('is-invalid')

//         const errorDiv = document.createElement('div')

//         errorDiv.classList.add('invalid-feedback')
//         errorDiv.textContent = errors[name][0]

//         element.parentNode.append(errorDiv)
//     }
// }

// function clearValidationErrors(domElement) {
//     domElement.querySelectorAll('.is-invalid').forEach(function (element) {
//         element.classList.remove('is-invalid')

//         element.parentNode.querySelectorAll('.invalid-feedback').forEach(function (e) {
//             e.remove()
//         })
//     })
// }

// const csrf = () => {
//     const nameField = document.querySelector('#csrf-name-field') as HTMLMetaElement;
//     const valueField = document.querySelector('#csrf-value-field') as HTMLMetaElement;

//     return {
//         [nameField.name]: nameField.content,
//         [valueField.name]: valueField.content,
//     };
// }

// export {
//     ajax,
//     get,
//     post,
//     del
// }
