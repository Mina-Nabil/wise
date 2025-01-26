<div>
    <div style="max-width:800px;">
        <div class="flex justify-between flex-wrap items-center">
            <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
                <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                    Line Of Business Fields
                </h4>
            </div>
        </div>
        <div class="card">

            <div class="card-body px-6 pb-6">
                <div class=" -mx-6">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden ">
                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                <thead
                                    class=" border-t border-slate-100 dark:border-slate-800 bg-slate-200 dark:bg-slate-700">
                                    <tr>

                                        <th scope="col" class=" table-th ">
                                            Name
                                        </th>


                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                    @foreach ($LINE_OF_BUSINESSES as $lob)
                                        <tr class="@if ($showLineFields && $showLineFields === $lob) bg-slate-900 dark:bg-slate-900 text-slate-100 @else hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer @endif"
                                            wire:click='showLineFields("{{ $lob }}")'>
                                            <td
                                                class="flex justify-between @if ($showLineFields && $showLineFields === $lob) p-3 px-5 text-slate-100 @else table-td @endif ">
                                                <span class="text-lg">{{ ucwords(str_replace('_', ' ', $lob)) }}</span>

                                                @if ($showLineFields)
                                                    @if ($showLineFields === $lob)
                                                        <button wire:click='openAddField("{{ $lob }}")'
                                                            class="btn inline-flex justify-center btn-light shadow-base2 btn-sm">Add
                                                            {{ ucwords(str_replace('_', ' ', $lob)) }} Fields</button>
                                                    @endif
                                                @endif
                                            </td>


                                        </tr>
                                        @if ($showLineFields)
                                            @if ($showLineFields === $lob)
                                                @forelse ($fields as $field)
                                                    <tr style="margin-bottom: 5px;">
                                                        <td class="table-td ">
                                                            <div class="flex justify-between">
                                                                <div>
                                                                    <iconify-icon icon="mingcute:arrow-right-fill"
                                                                        class="pl-4" width="15"
                                                                        height="15"></iconify-icon>
                                                                    <b><span
                                                                            class="text-lg  px-2">{{ $field->field }}</span></b>
                                                                </div>
                                                                <div class="flex space-x-3 rtl:space-x-reverse">
                                                                    <button
                                                                        wire:click='openEditField({{ $field->id }})'
                                                                        class="action-btn" type="button">
                                                                        <iconify-icon
                                                                            icon="heroicons:pencil-square"></iconify-icon>
                                                                    </button>
                                                                    <button
                                                                        wire:click='confirmDelete({{ $field->id }})'
                                                                        class="action-btn" type="button">
                                                                        <iconify-icon
                                                                            icon="heroicons:trash"></iconify-icon>
                                                                    </button>
                                                                </div>

                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td class="table-td text-center">
                                                            <p>No Fields Found in this line of business</p>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            @endif
                                        @endif
                                    @endforeach

                                </tbody>
                            </table>

                            @if (empty($LINE_OF_BUSINESSES))
                                {{-- START: empty filter result --}}
                                <div class="card m-5 p-5">
                                    <div class="card-body rounded-md bg-white dark:bg-slate-800">
                                        <div class="items-center text-center p-5">
                                            <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon></h2>
                                            <h2 class="card-title text-slate-900 dark:text-white mb-3">No line of
                                                business Found.</h2>
                                        </div>
                                    </div>
                                </div>
                                {{-- END: empty filter result --}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($isOpenAddField)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Add Field to {{ ucwords(str_replace('_', ' ', $isOpenAddField)) }}
                            </h3>
                            <button wire:click="closeAddField" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="input-area mb-3">
                                <label for="fieldName" class="form-label">Field</label>
                                <input name="fieldName"
                                    class="form-control  @error('fieldName') !border-danger-500 @enderror"
                                    id="default-picker" type="text" wire:model.defer="fieldName" autocomplete="off">
                                @error('fieldName')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Modal footer -->
                            <div
                                class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                <button wire:click="addField" data-bs-dismiss="modal"
                                    class="btn inline-flex justify-center text-white bg-black-500">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($isOpenEditField)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Edit Field
                            </h3>
                            <button wire:click="closeEditField" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="input-area mb-3">
                                <label for="fieldName" class="form-label">Field</label>
                                <input name="fieldName"
                                    class="form-control @error('fieldName') !border-danger-500 @enderror"
                                    id="default-picker" type="text" wire:model.defer="fieldName" autocomplete="off">
                                @error('fieldName')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Modal footer -->
                            <div
                                class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                <button wire:click="editField" data-bs-dismiss="modal"
                                    class="btn inline-flex justify-center text-white bg-black-500">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($isConformDelete)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                        rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-danger-500">
                            <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                Delete Field
                            </h3>
                            <button wire:click="closeDelete" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                    dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                            11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <h6 class="text-base text-slate-900 dark:text-white leading-6">
                                Are you sure ! Do you want to delete field ?
                            </h6>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="deleteField" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-danger-500">Yes,
                                Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
