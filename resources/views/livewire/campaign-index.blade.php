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
        
        <div class="grid xl:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
            {{-- BEGIN: Campaign list --}}
            @foreach ($campaigns as $campaign)
                <div class="card mb-5">
                    <div class="card-body">
                        <div class="card-text h-full">
                            <header class="border-b px-4 pt-4 pb-3 flex justify-between border-primary-500">
                                <div class="flex-wrap items-center">
                                    <h5 class="mb-0 text-primary-500" style="display: flex; align-items: center;">
                                        <iconify-icon class="text-xl inline-block ltr:mr-2 rtl:ml-2 text-primary-500"
                                            icon="heroicons:megaphone"></iconify-icon>
                                        {{ $campaign->name }} &nbsp; 
                                        @if($campaign->is_active)
                                            <span class="badge bg-success-500 text-white">Active</span>
                                        @else
                                            <span class="badge bg-slate-500 text-white">Inactive</span>
                                        @endif
                                    </h5>
                                </div>

                                <div class="flex space-x-3 rtl:space-x-reverse float-right">
                                    @can('update', $campaign)
                                        <button wire:click="openEditCampaign({{ $campaign->id }})" class="action-btn" type="button">
                                            <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                                        </button>
                                    @endcan
                                    @can('delete', $campaign)
                                        <button wire:click="openDeleteCampaign({{ $campaign->id }})" class="action-btn" type="button">
                                            <iconify-icon icon="heroicons:trash"></iconify-icon>
                                        </button>
                                    @endcan
                                </div>
                            </header>
                            <div class="py-3 px-5">
                                @if($campaign->description)
                                    <p class="card-text text-sm text-slate-600 mb-2">{{ Str::limit($campaign->description, 100) }}</p>
                                @endif
                                
                                @if($campaign->target_audience)
                                    <h6 class="card-subtitle text-sm mb-1">
                                        <span class="text-slate-700 font-medium">Target:</span> {{ $campaign->target_audience }}
                                    </h6>
                                @endif
                                
                                @if($campaign->handler)
                                    <h6 class="card-subtitle text-sm mb-1">
                                        <span class="text-slate-700 font-medium">Handler:</span> {{ $campaign->handler }}
                                    </h6>
                                @endif
                                
                                @if($campaign->budget)
                                    <h6 class="card-subtitle text-sm mb-1">
                                        <span class="text-slate-700 font-medium">Budget:</span> ${{ number_format($campaign->budget, 2) }}
                                    </h6>
                                @endif
                                
                                @if($campaign->start_date || $campaign->end_date)
                                    <h6 class="card-subtitle text-sm mb-1">
                                        <span class="text-slate-700 font-medium">Period:</span> 
                                        {{ $campaign->start_date ? $campaign->start_date->format('M d, Y') : 'Not set' }} - 
                                        {{ $campaign->end_date ? $campaign->end_date->format('M d, Y') : 'Open' }}
                                    </h6>
                                @endif
                                
                                <div class="mt-3 flex space-x-2">
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
