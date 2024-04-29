<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Users Management
            </h4>
        </div>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
            @can('create', App\Models\Users\User::class)
                <button wire:click="openAddCompany"
                    class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                    Add Company
                </button>
            @endcan

        </div>
    </div>
    <div class="card">
        <header class="card-header cust-card-header noborder">
            <iconify-icon wire:loading wire:target="search" class="loading-icon text-lg" icon="line-md:loading-twotone-loop"></iconify-icon>
            <input type="text" class="form-control !pl-9 mr-1 basis-1/4" placeholder="Search" wire:model="search">
        </header>

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

                                    <th scope="col" class=" table-th ">
                                        Username
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Type
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Phone
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Email
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Activated
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Actions
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                @foreach ($users as $user)
                                    <tr>

                                        <td class="table-td">
                                            {{ $user->first_name }} {{ $user->last_name }}
                                        </td>

                                        <td class="table-td">
                                            {{ $user->username }}
                                        </td>

                                        <td class="table-td ">
                                            {{ ucwords(str_replace('_',' ',$user->type)) }}
                                        </td>

                                        <td class="table-td">
                                            {{ $user->phone }}
                                        </td>

                                        <td class="table-td">
                                            {{ $user->email }}
                                        </td>

                                        <td class="table-td ">
                                            <div class="flex justify-center">
                                                <button class="toolTip onTop action-btn m-1 " data-tippy-content="Edit"
                                                    wire:click="editRow({{ $user->id }})" type="button">
                                                    <iconify-icon icon="iconamoon:edit-bold"></iconify-icon>
                                                </button>
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                        @if ($users->isEmpty())
                            {{-- START: empty filter result --}}
                            <div class="card m-5 p-5">
                                <div class="card-body rounded-md bg-white dark:bg-slate-800">
                                    <div class="items-center text-center p-5">
                                        <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon></h2>
                                        <h2 class="card-title text-slate-900 dark:text-white mb-3">No users with the
                                            applied
                                            filters</h2>
                                        <p class="card-text">Try changing the filters or search terms for this view.
                                        </p>
                                        <a href="{{ url('/users') }}"
                                            class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">View
                                            all users</a>
                                    </div>
                                </div>
                            </div>
                            {{-- END: empty filter result --}}
                        @endif
                    </div>
                    {{ $users->links('vendor.livewire.bootstrap') }}
                </div>
            </div>
        </div>
    </div>

    @can('create', App\Models\Users\User::class)
    @if ($newUserSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Create new user
                            </h3>
                            <button wire:click="closeNewUserSec" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">



                            <div class="from-group">
                                <label for="paymentType" class="form-label">Payment type</label>
                                <select name="paymentType" id="basicSelect" class="form-control w-full mt-2" wire:model="paymentType">
                                    <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                        Select type</option>
                                    @foreach ($PYMT_TYPES as $paymentType)
                                        <option value="{{ $paymentType }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ ucwords(str_replace('_', ' ', $paymentType)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="from-group">
                                <label for="paymentAmount" class="form-label">Amount</label>
                                <input type="number" name="paymentAmount" class="form-control mt-2 w-full @error('paymentAmount') !border-danger-500 @enderror" wire:model.defer="paymentAmount">
                                @error('paymentAmount')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="from-group">
                                <label for="paymentDue" class="form-label">Due</label>
                                <input type="date" name="paymentDue" class="form-control mt-2 w-full @error('paymentDue') !border-danger-500 @enderror" wire:model.defer="paymentDue">
                                @error('paymentDue')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="from-group">
                                <label for="paymentNote" class="form-label">Note</label>
                                <textarea name="paymentNote" class="form-control mt-2 w-full @error('paymentNote') !border-danger-500 @enderror" wire:model.defer="paymentNote"></textarea>
                                @error('paymentNote')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addClientPayment" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="addClientPayment">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="addClientPayment" icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @endcan
</div>
