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
            case 'completed':
                return `
                    <div class="py-1 text-gray-700 whitespace-nowrap">
                        <div class="px-2 inline-flex items-center rounded-full bg-green-700">
                            <span class="text-[0.7rem] text-center font-normal text-blue-50">${status}</span>
                        </div>
                    </div>
                `
            case 'overdue':
                return `
                    <div class="py-1 text-gray-700 whitespace-nowrap">
                        <div class="px-2 inline-flex items-center rounded-full bg-red-500">
                            <span class="text-[0.7rem] text-center font-normal text-blue-50">${status}</span>
                        </div>
                    </div>
                `
            default:
                return `
                    <div class="py-1 text-gray-700 whitespace-nowrap">
                        <div class="inline-flex items-center rounded-full bg-gray-200">
                            <span class="text-[0.7rem] text-center font-normal text-blue-50 px-2">${status}</span>
                        </div>
                    </div>
                `
        }
    },

    'priority': (priority: boolean, id: number, hasContactPerson?: boolean) => `
        <div class="flex justify-center py-1 text-sm text-gray-500 whitespace-nowrap">
            <input type="checkbox" name="priority" id="set-priority" class="cursor-pointer" ${priority ? 'checked' : ''} ${! <boolean>hasContactPerson ? 'disabled' : ''} data-id=${id}>
        </div>
    `,

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
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                </svg>
            </button>
        </div>
    `,
}

export default elements;