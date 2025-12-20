<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Marketing Campaigns
            </h4>
        </div>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
            @can('create', \App\Models\Marketing\Campaign::class)
                <button wire:click="openCampaignSec"
                    class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                    Add Campaign
                </button>
            @endcan

            {{-- Add Campaign Modal --}}
            @if ($newCampaignSec)
                <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
                    style="display: block">
                    <div class="modal-dialog relative w-auto pointer-events-none">
                        <div
                            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                                rounded-md outline-none text-current">
                            <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                                <!-- Modal header -->
                                <div
                                    class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-success-500">
                                    <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                        Add New Campaign
                                    </h3>
                                    <button type="button" wire:click="closeCampaignSec"
                                        class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                            dark:hover:bg-slate-600 dark:hover:text-white"
                                        data-bs-dismiss="modal">
                                        <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewbox="0 0 20 20"
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
                                <div class="card-text h-full">
                                    <div class="px-4 pt-4 pb-3">
                                        <div class="input-area mb-3">
                                            <label for="name" class="form-label">Campaign Name</label>
                                            <input type="text"
                                                class="form-control @error('name') !border-danger-500 @enderror"
                                                wire:model.defer="name">
                                            @error('name')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="input-area mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control @error('description') !border-danger-500 @enderror" 
                                                wire:model.defer="description" placeholder="Campaign description"></textarea>
                                            @error('description')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="input-area mb-3">
                                            <label for="offers" class="form-label">Offers</label>
                                            <textarea class="form-control @error('offers') !border-danger-500 @enderror" 
                                                wire:model.defer="offers" placeholder="Campaign offers"></textarea>
                                            @error('offers')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="input-area mb-3">
                                            <label for="goal" class="form-label">Goal</label>
                                            <textarea class="form-control @error('goal') !border-danger-500 @enderror" 
                                                wire:model.defer="goal" placeholder="Campaign goal"></textarea>
                                            @error('goal')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="input-area mb-3">
                                            <label for="target_audience" class="form-label">Target Audience</label>
                                            <input type="text"
                                                class="form-control @error('target_audience') !border-danger-500 @enderror"
                                                wire:model.defer="target_audience" placeholder="Target audience">
                                            @error('target_audience')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="input-area mb-3">
                                            <label for="marketing_channels" class="form-label">Marketing Channels</label>
                                            <input type="text"
                                                class="form-control @error('marketing_channels') !border-danger-500 @enderror"
                                                wire:model.defer="marketing_channels" placeholder="Marketing channels">
                                            @error('marketing_channels')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="input-area mb-3">
                                            <label for="handler" class="form-label">Handler</label>
                                            <input type="text"
                                                class="form-control @error('handler') !border-danger-500 @enderror"
                                                wire:model.defer="handler" placeholder="Campaign handler">
                                            @error('handler')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="input-area mb-3">
                                            <label for="budget" class="form-label">Budget</label>
                                            <input type="number" step="0.01"
                                                class="form-control @error('budget') !border-danger-500 @enderror"
                                                wire:model.defer="budget" placeholder="Campaign budget">
                                            @error('budget')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="input-area mb-3">
                                            <label for="start_date" class="form-label">Start Date</label>
                                            <input type="date"
                                                class="form-control @error('start_date') !border-danger-500 @enderror"
                                                wire:model.defer="start_date">
                                            @error('start_date')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="input-area mb-3">
                                            <label for="end_date" class="form-label">End Date</label>
                                            <input type="date"
                                                class="form-control @error('end_date') !border-danger-500 @enderror"
                                                wire:model.defer="end_date">
                                            @error('end_date')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- Modal footer -->
                                    <div
                                        class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                        <button wire:click="add"
                                            class="btn inline-flex justify-center text-white bg-success-500">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Edit Campaign Modal --}}
            @if ($editCampaignSec)
                <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
                    style="display: block">
                    <div class="modal-dialog relative w-auto pointer-events-none">
                        <div
                            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                                rounded-md outline-none text-current">
                            <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                                <!-- Modal header -->
                                <div
                                    class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-warning-500">
                                    <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                        Edit Campaign
                                    </h3>
                                    <button type="button" wire:click="closeEditCampaign"
                                        class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                            dark:hover:bg-slate-600 dark:hover:text-white"
                                        data-bs-dismiss="modal">
                                        <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewbox="0 0 20 20"
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
                                <div class="card-text h-full">
                                    <div class="px-4 pt-4 pb-3">
                                        <div class="input-area mb-3">
                                            <label for="name" class="form-label">Campaign Name</label>
                                            <input type="text"
                                                class="form-control @error('name') !border-danger-500 @enderror"
                                                wire:model.defer="name">
                                            @error('name')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="input-area mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control @error('description') !border-danger-500 @enderror" 
                                                wire:model.defer="description" placeholder="Campaign description"></textarea>
                                            @error('description')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="input-area mb-3">
                                            <label for="offers" class="form-label">Offers</label>
                                            <textarea class="form-control @error('offers') !border-danger-500 @enderror" 
                                                wire:model.defer="offers" placeholder="Campaign offers"></textarea>
                                            @error('offers')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="input-area mb-3">
                                            <label for="goal" class="form-label">Goal</label>
                                            <textarea class="form-control @error('goal') !border-danger-500 @enderror" 
                                                wire:model.defer="goal" placeholder="Campaign goal"></textarea>
                                            @error('goal')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="input-area mb-3">
                                            <label for="target_audience" class="form-label">Target Audience</label>
                                            <input type="text"
                                                class="form-control @error('target_audience') !border-danger-500 @enderror"
                                                wire:model.defer="target_audience" placeholder="Target audience">
                                            @error('target_audience')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="input-area mb-3">
                                            <label for="marketing_channels" class="form-label">Marketing Channels</label>
                                            <input type="text"
                                                class="form-control @error('marketing_channels') !border-danger-500 @enderror"
                                                wire:model.defer="marketing_channels" placeholder="Marketing channels">
                                            @error('marketing_channels')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="input-area mb-3">
                                            <label for="handler" class="form-label">Handler</label>
                                            <input type="text"
                                                class="form-control @error('handler') !border-danger-500 @enderror"
                                                wire:model.defer="handler" placeholder="Campaign handler">
                                            @error('handler')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="input-area mb-3">
                                            <label for="budget" class="form-label">Budget</label>
                                            <input type="number" step="0.01"
                                                class="form-control @error('budget') !border-danger-500 @enderror"
                                                wire:model.defer="budget" placeholder="Campaign budget">
                                            @error('budget')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="input-area mb-3">
                                            <label for="start_date" class="form-label">Start Date</label>
                                            <input type="date"
                                                class="form-control @error('start_date') !border-danger-500 @enderror"
                                                wire:model.defer="start_date">
                                            @error('start_date')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="input-area mb-3">
                                            <label for="end_date" class="form-label">End Date</label>
                                            <input type="date"
                                                class="form-control @error('end_date') !border-danger-500 @enderror"
                                                wire:model.defer="end_date">
                                            @error('end_date')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- Modal footer -->
                                    <div
                                        class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                        <button wire:click="update"
                                            class="btn inline-flex justify-center text-white bg-warning-500">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <div>
        <div class="input-area mb-3">
            <div class="relative">
                <div>
                    <iconify-icon wire:loading class="loading-icon text-lg pt-2"
                        icon="line-md:loading-twotone-loop"></iconify-icon>
                    <input type="text" class="form-control !pl-9 mr-1 basis-1/4" placeholder="Search"
                        wire:model="search">
                </div>
            </div>
        </div>
        
        <div class="grid xl:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-5">
            {{-- BEGIN: Campaign list --}}
            @foreach ($campaigns as $campaign)
                <div class="card mb-5">
                    <div class="card-body">
                        <div class="card-text h-full">
                            <header class="border-b px-4 pt-4 pb-3 border-primary-500">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-2">
                                            <iconify-icon class="text-xl text-primary-500 flex-shrink-0"
                                                icon="heroicons:megaphone"></iconify-icon>
                                            <h5 class="mb-0 text-primary-500 font-semibold truncate">
                                                {{ $campaign->name }}
                                            </h5>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            @if($campaign->is_active)
                                                <span class="badge bg-success-500 text-white">Active</span>
                                            @else
                                                <span class="badge bg-slate-500 text-white">Inactive</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        @can('importLeads', $campaign)
                                            <button wire:click="openImportLeads({{ $campaign->id }})" 
                                                wire:loading.attr="disabled"
                                                class="btn btn-sm btn-primary light" 
                                                type="button" 
                                                title="Import Leads">
                                                <span wire:loading.remove wire:target="openImportLeads">
                                                    <iconify-icon icon="heroicons:arrow-up-tray" class="text-lg"></iconify-icon>
                                                </span>
                                                <span wire:loading wire:target="openImportLeads">
                                                    <iconify-icon class="text-lg animate-spin" icon="line-md:loading-twotone-loop"></iconify-icon>
                                                </span>
                                            </button>
                                        @endcan
                                        @can('update', $campaign)
                                            <button wire:click="openEditCampaign({{ $campaign->id }})" 
                                                class="btn btn-sm btn-warning light" 
                                                type="button"
                                                title="Edit Campaign">
                                                <iconify-icon icon="heroicons:pencil-square" class="text-lg"></iconify-icon>
                                            </button>
                                        @endcan
                                        @can('delete', $campaign)
                                            <button wire:click="openDeleteCampaign({{ $campaign->id }})" 
                                                class="btn btn-sm btn-danger light" 
                                                type="button"
                                                title="Delete Campaign">
                                                <iconify-icon icon="heroicons:trash" class="text-lg"></iconify-icon>
                                            </button>
                                        @endcan
                                    </div>
                                </div>
                            </header>
                            <div class="py-4 px-5">
                                @if($campaign->description)
                                    <p class="card-text text-sm text-slate-600 dark:text-slate-300 mb-3">{{ Str::limit($campaign->description, 100) }}</p>
                                @endif
                                
                                <div class="space-y-2 mb-4">
                                    @if($campaign->target_audience)
                                        <div class="flex items-start gap-2">
                                            <span class="text-slate-700 dark:text-slate-300 font-medium text-sm min-w-[80px]">Target:</span>
                                            <span class="text-slate-600 dark:text-slate-400 text-sm">{{ $campaign->target_audience }}</span>
                                        </div>
                                    @endif
                                    
                                    @if($campaign->handler)
                                        <div class="flex items-start gap-2">
                                            <span class="text-slate-700 dark:text-slate-300 font-medium text-sm min-w-[80px]">Handler:</span>
                                            <span class="text-slate-600 dark:text-slate-400 text-sm">{{ $campaign->handler }}</span>
                                        </div>
                                    @endif
                                    
                                    @if($campaign->budget)
                                        <div class="flex items-start gap-2">
                                            <span class="text-slate-700 dark:text-slate-300 font-medium text-sm min-w-[80px]">Budget:</span>
                                            <span class="text-slate-600 dark:text-slate-400 text-sm">${{ number_format($campaign->budget, 2) }}</span>
                                        </div>
                                    @endif
                                    
                                    @if($campaign->start_date || $campaign->end_date)
                                        <div class="flex items-start gap-2">
                                            <span class="text-slate-700 dark:text-slate-300 font-medium text-sm min-w-[80px]">Period:</span>
                                            <span class="text-slate-600 dark:text-slate-400 text-sm">
                                                {{ $campaign->start_date ? $campaign->start_date->format('M d, Y') : 'Not set' }} - 
                                                {{ $campaign->end_date ? $campaign->end_date->format('M d, Y') : 'Open' }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex flex-wrap gap-2 pt-3 border-t border-slate-200 dark:border-slate-700">
                                    <span class="badge bg-info-500 text-white text-xs">
                                        Customers: {{ $campaign->customers()->count() }}
                                    </span>
                                    <span class="badge bg-info-500 text-white text-xs">
                                        Corporates: {{ $campaign->corporates()->count() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            {{-- END: Campaign list --}}
        </div>
        {{ $campaigns->links('vendor.livewire.bootstrap') }}
    </div>
</div>

{{-- Delete Campaign Modal --}}
@if ($deleteThisCampaign)
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
        id="dangerModal" tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog"
        style="display: block;">
        <div class="modal-dialog relative w-auto pointer-events-none">
            <div
                class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                    <!-- Modal header -->
                    <div
                        class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-danger-500">
                        <h3 class="text-base font-medium text-white dark:text-white capitalize">
                            Delete Campaign
                        </h3>
                        <button type="button" wire:click="closeDeleteCampaign"
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
                        <h6 class="text-base text-slate-900 dark:text-white leading-6">
                            Are you sure you want to delete this campaign?
                        </h6>
                        <p class="text-base text-slate-600 dark:text-slate-400 leading-6">
                            This action cannot be undone. The campaign will be permanently deleted from the system.
                            @if(Campaign::find($deleteThisCampaign) && (Campaign::find($deleteThisCampaign)->customers()->count() > 0 || Campaign::find($deleteThisCampaign)->corporates()->count() > 0))
                                <br><strong class="text-danger-500">Warning: This campaign has customers or corporates attached and cannot be deleted.</strong>
                            @endif
                        </p>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="delete"
                            class="btn inline-flex justify-center text-white bg-danger-500">
                            Yes, Delete
                        </button>
                        <button wire:click="closeDeleteCampaign"
                            class="btn inline-flex justify-center text-slate-900 bg-slate-200">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Import Leads Modal --}}
@if ($importLeadsSec)
    <div class="modal fade fixed top-0 left-0 w-full h-full outline-none overflow-x-hidden overflow-y-auto z-50"
        style="display: block; background-color: rgba(0, 0, 0, 0.5);" 
        wire:key="import-leads-modal-{{ $importCampaignId }}"
        wire:click.self="closeImportLeads">
        <div class="modal-dialog relative w-auto pointer-events-none flex items-center min-h-screen max-w-2xl mx-auto px-4">
            <div
                class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                    rounded-md outline-none text-current dark:bg-slate-800">
                <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                    <!-- Modal header -->
                    <div
                        class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-primary-500">
                        <h3 class="text-base font-medium text-white dark:text-white capitalize">
                            Import Leads
                        </h3>
                        <button type="button" wire:click="closeImportLeads"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewbox="0 0 20 20"
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
                    <div class="card-text h-full">
                        <div class="px-4 pt-4 pb-3">
                            <div class="mb-4 p-3 bg-info-50 dark:bg-slate-800 rounded-lg">
                                <p class="text-sm text-slate-700 dark:text-slate-300 mb-2">
                                    <strong>Excel Format:</strong>
                                </p>
                                <ul class="text-xs text-slate-600 dark:text-slate-400 list-disc list-inside space-y-1">
                                    <li><strong>Column A:</strong> Platform/Channel</li>
                                    <li><strong>Column B:</strong> Interest Type (تأمين_صحى, تأمين_على_العربية, تأمين_على_بيتك, اخر)</li>
                                    <li><strong>Column C:</strong> Email</li>
                                    <li><strong>Column D:</strong> Full Name</li>
                                    <li><strong>Column E:</strong> Phone Number</li>
                                    <li><strong>Column F:</strong> Job Title</li>
                                </ul>
                            </div>

                            <div class="input-area mb-3">
                                <label for="importLeadsFile" class="form-label">Excel File</label>
                                <input type="file" 
                                    class="form-control @error('importLeadsFile') !border-danger-500 @enderror"
                                    wire:model="importLeadsFile"
                                    accept=".xlsx,.xls">
                                @error('importLeadsFile')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                @if ($importLeadsFile)
                                    <span class="text-sm text-slate-600 dark:text-slate-400 mt-1 block">
                                        Selected: {{ $importLeadsFile->getClientOriginalName() }}
                                    </span>
                                @endif
                            </div>

                            @if(auth()->user()->is_admin)
                                <div class="input-area mb-3">
                                    <label for="importUserId" class="form-label">Assign to User (Optional)</label>
                                    <select class="form-control @error('importUserId') !border-danger-500 @enderror"
                                        wire:model.defer="importUserId">
                                        <option value="">-- Assign to logged-in user --</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('importUserId')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                    <small class="text-slate-500 text-xs mt-1 block">
                                        If not selected, leads will be assigned to you.
                                    </small>
                                </div>
                            @endif

                            <div class="mb-3 p-3 bg-warning-50 dark:bg-slate-800 rounded-lg">
                                <p class="text-xs text-slate-600 dark:text-slate-400">
                                    <strong>Note:</strong> Rows with duplicate phone numbers and first names will be skipped automatically.
                                </p>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="importLeads" wire:loading.attr="disabled"
                                class="btn inline-flex justify-center text-white bg-primary-500" 
                                @if(!$importLeadsFile) disabled @endif>
                                <span wire:loading.remove wire:target="importLeads">Import Leads</span>
                                <span wire:loading wire:target="importLeads">
                                    <iconify-icon class="text-xl animate-spin" icon="line-md:loading-twotone-loop"></iconify-icon>
                                    Importing...
                                </span>
                            </button>
                            <button wire:click="closeImportLeads"
                                class="btn inline-flex justify-center text-slate-900 bg-slate-200">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
