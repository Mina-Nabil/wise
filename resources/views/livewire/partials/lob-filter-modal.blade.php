@if ($lobSection)
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
        tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
        style="display: block;">
        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
            <div
                class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                    <div
                        class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                        <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                            Line of Business
                        </h3>
                        <button wire:click="toggleLob" type="button"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd">
                                </path>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            @foreach ($Eline_of_business_ids as $lob)
                                <span
                                    class="badge bg-slate-900 text-white capitalize rounded-3xl">{{ ucwords(str_replace('_', ' ', $lob)) }}</span>
                            @endforeach
                        </div>
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="bg-slate-200 dark:bg-slate-700">
                                <tr>
                                    <th scope="col" class=" table-th ">
                                        Line of Business
                                    </th>
                                    <th scope="col" class=" table-th ">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @foreach ($LINES_OF_BUSINESS as $LOB)
                                    @if (!in_array($LOB, $Eline_of_business_ids))
                                        <tr class="even:bg-slate-50 dark:even:bg-slate-700">
                                            <td class="table-td">{{ ucwords(str_replace('_', ' ', $LOB)) }}</td>
                                            <td class="table-td "><button
                                                    wire:click="pushLob('{{ $LOB }}')"
                                                    class="btn inline-flex justify-center btn-success light">Add</button>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div
                        class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="setLob" data-bs-dismiss="modal"
                            class="btn inline-flex justify-center text-white bg-black-500">
                            <span wire:loading.remove wire:target="setLob">Submit</span>
                            <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                wire:loading wire:target="setLob"
                                icon="line-md:loading-twotone-loop"></iconify-icon>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
