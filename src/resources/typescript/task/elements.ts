const elements = {
    'name': (name: string) => `
        <div class="py-1 text-[0.95rem] text-gray-700 whitespace-nowrap w-[200px]">
            <div class="flex flex-col gap-x-2">
                <h2>${name}</h2>
            </div>
        </div>
    `,

    'category': (category: string) => `
        <div class="py-1 text-[0.95rem] text-gray-700 whitespace-nowrap">
            <div>
                <h2>${category}</h2>
            </div>
        </div>
    `,

    'status': (status: string) => {
        switch (status) {
            case 'scheduled':
                return `
                    <div class="py-1 text-gray-700 whitespace-nowrap">
                        <div class="px-2 inline-flex items-center rounded-full bg-blue-700">
                            <span class="text-[0.7rem] text-center font-normal text-blue-50">${status}</span>
                        </div>
                    </div>
                `
            default:
                return `
                    <div class="px-2 py-1 text-gray-700 whitespace-nowrap">
                        <div class="inline-flex items-center rounded-full bg-gray-200">
                            <span class="text-[0.7rem] text-center font-normal text-blue-50 px-2">${status}</span>
                        </div>
                    </div>
                `
        }
    },

    'dateTime': (date: string) => `<div class=" py-1 text-sm text-gray-500 whitespace-nowrap">${date}</div>`,

    'buttons': (id: string) => `
        <div class="flex items-center gap-x-10">
            <button class="text-gray-500 transition-colors duration-200 hover:text-red-500 focus:outline-none" data-name="delete" data-id=${id}>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
            </button>
            <button class="text-gray-500 transition-colors duration-200 hover:text-yellow-500 focus:outline-none" data-name="edit" data-id=${id}>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
            </button>
            <button class="text-gray-500 transition-colors duration-200 hover:text-green-500 focus:outline-none" data-name="view" data-id=${id}>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                    <path d="M6 3a3 3 0 0 0-3 3v1.5a.75.75 0 0 0 1.5 0V6A1.5 1.5 0 0 1 6 4.5h1.5a.75.75 0 0 0 0-1.5H6ZM16.5 3a.75.75 0 0 0 0 1.5H18A1.5 1.5 0 0 1 19.5 6v1.5a.75.75 0 0 0 1.5 0V6a3 3 0 0 0-3-3h-1.5ZM12 8.25a3.75 3.75 0 1 0 0 7.5 3.75 3.75 0 0 0 0-7.5ZM4.5 16.5a.75.75 0 0 0-1.5 0V18a3 3 0 0 0 3 3h1.5a.75.75 0 0 0 0-1.5H6A1.5 1.5 0 0 1 4.5 18v-1.5ZM21 16.5a.75.75 0 0 0-1.5 0V18a1.5 1.5 0 0 1-1.5 1.5h-1.5a.75.75 0 0 0 0 1.5H18a3 3 0 0 0 3-3v-1.5Z" />
                </svg>
            </button>
        </div>
    `,
}

export default elements;