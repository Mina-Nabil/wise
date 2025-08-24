<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Follow Ups
            </h4>
        </div>
        {{-- <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
            <button wire:click="toggleAddLead" class="btn inline-flex justify-center btn-outline-dark rounded-[25px]">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                Add Lead
            </button>
            <button wire:click="toggleAddCorporate" class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                Add Corporate
            </button>
        </div> --}}
    </div>

    <div class="card">
        <header class="card-header cust-card-header noborder">
            <iconify-icon wire:loading class="loading-icon text-lg" icon="line-md:loading-twotone-loop"></iconify-icon>
            <input type="text" class="form-control !pl-9 mr-1 basis-1/4" placeholder="Search" wire:model="search">
        </header>

        <div class="card-body px-6 pb-6">
            <div class=" -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div>
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class=" border-t border-slate-100 dark:border-slate-800 bg-slate-200 dark:bg-slate-700">
                                <tr>

                                    <th scope="col" class=" table-th ">
                                        Title
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Type
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Name
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Status
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Call Time
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Description
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Note
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Created by
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                @foreach ($followups as $followup)
                                    <tr>

                                        <td wire:click="redirectToShowPage({{ $followup->id }})" class="table-td hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer">
                                            <b>{{ Str::limit($followup->title, 40, '...') }}</b>
                                        </td>

                                        <td class="table-td ">
                                            {{ $followup->called_type }}
                                        </td>

                                        <td wire:click="redirectToCalledPage({{ $followup->id }})" class="table-td hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer">
                                            {{ $followup->called->name }}
                                        </td>

                                        <td class="table-td ">
                                            @if ($followup->status === 'new')
                                                <span class="badge bg-info-500 h-auto bg-opacity-50">
                                                    <iconify-icon icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $followup->status)) }}
                                                </span>
                                            @elseif(str_contains($followup->status, 'canceled'))
                                                <span class="badge bg-danger-500 h-auto bg-opacity-50">
                                                    <iconify-icon icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $followup->status)) }}
                                                </span>
                                            @elseif($followup->status === 'called')
                                                <span class="badge bg-success-500 h-auto bg-opacity-50">
                                                    <iconify-icon icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $followup->status)) }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-500 h-auto bg-opacity-50">
                                                    <iconify-icon icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $followup->status)) }}
                                                </span>
                                            @endif

                                        </td>



                                        <td class="table-td ">
                                            @if ($followup->status === 'new')
                                                @if ($followup->call_time && \Carbon\Carbon::parse($followup->call_time)->isToday())
                                                    <span class="h-[6px] w-[6px] bg-info-500 rounded-full inline-block ring-4 ring-opacity-30 ring-info-500" style="vertical-align: middle;"></span>&nbsp;
                                                @elseif ($followup->call_time && \Carbon\Carbon::parse($followup->call_time)->isPast())
                                                    <span class="h-[6px] w-[6px] bg-danger-500 rounded-full inline-block ring-4 ring-opacity-30 ring-danger-500" style="vertical-align: middle;"></span>&nbsp;
                                                @endif
                                            @endif
                                            {{ $followup->call_time }}
                                        </td>

                                        <td class="table-td ">
                                            {{ Str::limit($followup->desc, 40, '...') }}
                                        </td>

                                        <td class="table-td ">
                                            <button class="toolTip caller-note{{ $followup->id }} btn inline-flex justify-center btn-outline-dark mr-3">Caller
                                                Note</button>

                                        </td>

                                        <td class="table-td flex">
                                            {{ $followup->creator->first_name }}
                                            <div class="ml-auto">
                                                <div class="relative">
                                                    <div class="dropdown relative">
                                                        <button class="text-xl text-center block w-full " type="button" id="tableDropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <iconify-icon icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                        </button>
                                                        <ul class=" dropdown-menu min-w-[120px] absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">

                                                            @if ($followup->status === 'new')
                                                                <li>
                                                                    <button wire:click="editThisFollowup({{ $followup->id }})" class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                                        Edit</button>
                                                                </li>
                                                                <li>
                                                                    <button wire:click="toggleCallerNote('called',{{ $followup->id }})" class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                                        Set as called</button>
                                                                </li>
                                                                <li>
                                                                    <button wire:click="toggleCallerNote('cancelled',{{ $followup->id }})" class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                                        Set as cancelled</button>
                                                                </li>
                                                            @endif
                                                            <li>
                                                                <button wire:click="deleteThisFollowup({{ $followup->id }})" class="text-slate-600 dark:text-white block font-Inter text-left font-normal w-full px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                                    Delete</button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                                @section('child_scripts')
                                    <script>
                                        $(function() {
                                            @foreach ($followups as $followup)
                                                tippy(".caller-note{{ $followup->id }}", {
                                                    content: "{{ $followup->caller_note }}",
                                                    placement: "bottom"
                                                });
                                            @endforeach
                                        });
                                    </script>
                                @endsection
                            </tbody>
                        </table>

                        @if ($followups->isEmpty())
                            {{-- START: empty filter result --}}
                            <div class="card m-5 p-5">
                                <div class="card-body rounded-md bg-white dark:bg-slate-800">
                                    <div class="items-center text-center p-5">
                                        <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon></h2>
                                        <h2 class="card-title text-slate-900 dark:text-white mb-3">No Follow ups with
                                            the
                                            applied
                                            filters</h2>
                                        <p class="card-text">Try changing the filters or search terms for this view.
                                        </p>
                                        <a href="{{ url('/followups') }}" class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">View
                                            all follow ups</a>
                                    </div>
                                </div>
                            </div>
                            {{-- END: empty filter result --}}
                        @endif

                    </div>



                    {{ $followups->links('vendor.livewire.bootstrap') }}

                </div>
            </div>
        </div>
    </div>

    @if ($followupId)
        {{-- add address section --}}
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Edit Follow up
                            </h3>
                            <button wire:click="closeEditFollowup" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
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
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Title</label>
                                    <input id="lastName" type="text" class="form-control @error('followupTitle') !border-danger-500 @enderror" wire:model.defer="followupTitle">
                                </div>
                                @error('followupTitle')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Call Date</label>
                                        <input id="lastName" type="date" class="form-control @error('followupCallDate') !border-danger-500 @enderror" wire:model.defer="followupCallDate">
                                    </div>
                                    @error('followupCallDate')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                    <div class="input-area">
                                        <label for="firstName" class="form-label"> Time</label>
                                        <input id="lastName" type="time" class="form-control @error('followupCallTime') !border-danger-500 @enderror" wire:model.defer="followupCallTime">
                                    </div>
                                    @error('followupCallTime')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Description</label>
                                    <input id="lastName" type="text" class="form-control @error('followupDesc') !border-danger-500 @enderror" wire:model.defer="followupDesc">
                                </div>
                                @error('followupDesc')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="campaignId" class="form-label">Campaign</label>
                                    <select name="campaignId" id="campaignId"
                                        class="form-control w-full mt-2 @error('campaignId') !border-danger-500 @enderror"
                                        wire:model.defer="campaignId">
                                        <option value="">Keep Current Campaign</option>
                                        @foreach ($campaigns as $campaign)
                                            <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('campaignId')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editFollowup" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($callerNoteSec)
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                        <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                            Caller Note
                        </h3>
                        <button wire:click="toggleCallerNote" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
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
                            <div class="input-area">
                                <label for="firstName" class="form-label">Leave a note...</label>
                                <input id="lastName" type="text" class="form-control @error('followupTitle') !border-danger-500 @enderror" wire:model.defer="note">
                            </div>
                            @error('note')
                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror

                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="submitCallerNote" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
