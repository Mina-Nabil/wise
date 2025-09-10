<div>
    <button wire:click="toggleAddLead" class="lg:h-[32px] lg:w-[32px] lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center">
        <iconify-icon class="text-slate-800 dark:text-white text-xl" icon="mdi:user-add"></iconify-icon>
    </button>

    @if ($addLeadSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Add Lead
                            </h3>

                            <button wire:click="toggleAddLead" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
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
                                <p class="text-lg"><b>Lead Info</b></p>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                    <div class="col-span-2">
                                        <label for="basicSelect" class="form-label">Lead Type</label>
                                        <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2" wire:model="leadType">
                                            <option value="customer" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">Customer</option>
                                            <option value="corporate" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">Corporate</option>
                                        </select>
                                    </div>
                                    <div class="col-span-2">
                                        @if ($leadType === 'customer')
                                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6">
                                                <div class="input-area">
                                                    <label for="leadFirstName" class="form-label">First Name</label>
                                                    <input id="leadFirstName" type="text" class="form-control @error('leadFirstName') !border-danger-500 @enderror" wire:model.defer="leadFirstName">
                                                </div>
                                                <div class="input-area">
                                                    <label for="firstName" class="form-label">Middle Name</label>
                                                    <input id="firstName" type="text" class="form-control @error('leadMiddleName') !border-danger-500 @enderror" wire:model.defer="leadMiddleName">
                                                </div>
                                                <div class="input-area">
                                                    <label for="firstName" class="form-label">Last Name</label>
                                                    <input id="firstName" type="text" class="form-control @error('leadLastName') !border-danger-500 @enderror" wire:model.defer="leadLastName">
                                                </div>
                                            </div>
                                            @error('leadFirstName')
                                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror

                                            @error('leadMiddleName')
                                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror

                                            @error('leadLastName')
                                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        @elseif($leadType === 'corporate')
                                            <div class="input-area">
                                                <label for="corporateName" class="form-label">Corporate Name</label>
                                                <input id="corporateName" type="text" class="form-control @error('corporateName') !border-danger-500 @enderror" wire:model.defer="corporateName">
                                            </div>
                                            @error('corporateName')
                                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="input-area col-span-2 mb-3">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input id="phone" type="text" class="form-control @error('LeadPhone') !border-danger-500 @enderror" wire:model.defer="LeadPhone">
                                    </div>
                                </div>
                                @error('LeadPhone')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                <div class="input-area">
                                    <label for="email" class="form-label">Email</label>
                                    <input id="email" type="email" class="form-control @error('LeadEmail') !border-danger-500 @enderror" wire:model.defer="LeadEmail">
                                    @error('LeadEmail')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="input-area">
                                    <label for="note" class="form-label">Note</label>
                                    <input id="note" type="email" class="form-control @error('note') !border-danger-500 @enderror" wire:model.defer="note">
                                    @error('note')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="input-area mt-3">
                                    <label for="campaignId" class="form-label">Campaign</label>
                                    <select name="campaignId" id="campaignId"
                                        class="form-control w-full mt-2 @error('campaignId') !border-danger-500 @enderror"
                                        wire:model.defer="campaignId">
                                        <option value="">None</option>
                                        @foreach ($campaigns as $campaign)
                                            <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('campaignId')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <hr class="mt-5">
                                <div class="from-group">
                                    <p class="text-lg mt-3"><b>Followup</b></p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                        <div class="input-area">
                                            <label for="followupCallDateTime" class="form-label">Call Date Time</label>
                                            <input id="followupCallDateTime" type="datetime-local" class="form-control @error('followupCallDateTime') !border-danger-500 @enderror" wire:model.defer="followupCallDateTime">
                                        </div>
                                        @error('followupCallDateTime')
                                            <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="checkbox-area black-checkbox mr-2 sm:mr-4 mt-2">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="hidden" name="checkbox"
                                                wire:model="followup_is_meeting">
                                            <span
                                                class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                <img src="{{ asset('assets/images/icon/ck-white.svg') }}" alt=""
                                                    class="h-[10px] w-[10px] block m-auto opacity-0"></span>
                                            <span
                                                class="text-black-500 dark:text-slate-400 text-sm leading-6 capitalize">is Meeting ?</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addLead" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                Accept
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
