<div>
    <button wire:click="toggleAddMeeting"
        class="lg:h-[32px] lg:w-[32px] lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center">
        <iconify-icon class="text-slate-800 dark:text-white text-xl" icon="ix:live-schedule"></iconify-icon>
    </button>

    @if ($addMeetingSection)
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
                                Add Meeting
                            </h3>

                            <button wire:click="toggleAddMeeting" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                            11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="from-group">
                                <p class="text-lg"><b>Select client</b></p>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">

                                    @if (!$owner)
                                        <div class="input-area">
                                            <label for="lastName" class="form-label">
                                                @if ($owner)
                                                    {{ $meetingType }}
                                                @else
                                                    Client Type
                                                @endif
                                            </label>

                                            <select
                                                class="form-control w-full mt-2 @error('meetingType') !border-danger-500 @enderror"
                                                wire:model="meetingType">
                                                <option value="Customer"
                                                    class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                    Customer</option>
                                                <option value="Corporate"
                                                    class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                    Corporate</option>
                                            </select>
                                        </div>
                                    @endif
                                    <div class="input-area">
                                        <label for="lastName" class="form-label">
                                            @if ($owner)
                                                <div class="flex justify-between">
                                                    <span>
                                                        Selected client
                                                    </span>
                                                    <button wire:click='resetClient'
                                                        class="text-sm text-danger-500">x</button>
                                                </div>
                                            @else
                                                Search client <iconify-icon wire:loading wire:target="searchClient"
                                                    class="loading-icon text-lg"
                                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                                            @endif
                                        </label>
                                        @if ($owner)
                                            <div class="no-wrap">
                                                <b>{{ $selectedClientName }}</b>
                                            </div>
                                        @else
                                            <input placeholder="Search..." type="text" class="form-control"
                                                wire:model="searchClient">
                                        @endif

                                    </div>
                                </div>
                                @error('meetingType')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="text-sm">
                                    @if ($clientNames)
                                        @foreach ($clientNames as $client)
                                            @if ($meetingType !== 'Customer')
                                                <p><iconify-icon icon="material-symbols:person"></iconify-icon>
                                                    {{ $client->name }} | {{ $client->email ?? 'N/A' }} | <Span
                                                        wire:click="selectClient({{ $client->id }})"
                                                        class="cursor-pointer text-primary-500">Select
                                                        Client</Span></p>
                                            @else
                                                <p><iconify-icon icon="material-symbols:person"></iconify-icon>
                                                    {{ $client->first_name }} {{ $client->last_name }} |
                                                    {{ $client->email ?? 'N/A' }} | <Span
                                                        wire:click="selectClient({{ $client->id }})"
                                                        class="cursor-pointer text-primary-500">Select
                                                        Client</Span></p>
                                            @endif
                                        @endforeach

                                    @endif
                                </div>

                                <hr class="mt-5">
                                <p class="text-lg mt-3"><b>Meeting</b></p>
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Title</label>
                                    <input id="lastName" type="text"
                                        class="form-control @error('followupTitle') !border-danger-500 @enderror"
                                        wire:model.defer="followupTitle">
                                </div>
                                @error('followupTitle')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Line of business</label>
                                    <select name="basicSelect"
                                        class="form-control w-full mt-2 @error('FollowupLineOfBussiness') !border-danger-500 @enderror"
                                        wire:model="FollowupLineOfBussiness">
                                        @foreach ($LINES_OF_BUSINESS as $LOB)
                                            <option value="{{ $LOB }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ ucwords(str_replace('_', ' ', $LOB)) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="from-group">
                                    <div class="input-area">
                                        <label for="followupCallDateTime" class="form-label">Call Date Time</label>
                                        <input id="followupCallDateTime" type="datetime-local"
                                            class="form-control @error('followupCallDateTime') !border-danger-500 @enderror"
                                            wire:model.defer="followupCallDateTime">
                                    </div>
                                    @error('followupCallDateTime')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Description</label>
                                    <input id="lastName" type="text"
                                        class="form-control @error('followupDesc') !border-danger-500 @enderror"
                                        wire:model.defer="followupDesc">
                                </div>
                                @error('followupDesc')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addMeeting" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Accept
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
