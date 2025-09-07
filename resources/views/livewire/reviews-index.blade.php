<div>
    <!-- Header Section -->
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                <b>Reviews</b> Management
            </h4>
        </div>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
            @if(Auth::user()->is_admin)
                <button wire:click="exportReviews"
                    class="btn inline-flex justify-center btn-outline-dark rounded-[25px]">
                    <span wire:loading.remove wire:target="exportReviews">Export</span>
                    <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading
                        wire:target="exportReviews" icon="line-md:loading-twotone-loop"></iconify-icon>
                </button>
                @endif

            <!-- Filters Dropdown -->
            <div class="dropdown relative">
                <button class="btn inline-flex justify-center btn-dark items-center cursor-default relative !pr-14"
                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Add filter
                    <span class="cursor-pointer absolute ltr:border-l rtl:border-r border-slate-100 h-full ltr:right-0 rtl:left-0 px-2 flex items-center justify-center leading-none">
                        <iconify-icon class="leading-none text-xl" icon="ic:round-keyboard-arrow-down"></iconify-icon>
                    </span>
                </button>
                <ul class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                    <li wire:click="toggleReviewableType">
                        <span class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Reviewable Type</span>
                    </li>
                    <li wire:click="toggleCreatedDate">
                        <span class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Creation Date (From-To)</span>
                    </li>
                    <li wire:click="toggleReviewDate">
                        <span class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Review Date (From-To)</span>
                    </li>
                    <li wire:click="toggleEmployeeRating">
                        <span class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Employee Rating (From-To)</span>
                    </li>
                    <li wire:click="toggleCompanyRating">
                        <span class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Company Rating (From-To)</span>
                    </li>
                    <li wire:click="toggleReviewStatus">
                        <span class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Review Status</span>
                    </li>
                    <li wire:click="toggleEmployeeComment">
                        <span class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Has Employee Comment</span>
                    </li>
                    <li wire:click="toggleCompanyComment">
                        <span class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Has Company Comment</span>
                    </li>
                    <li wire:click="toggleManagerReview">
                        <span class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Needs Manager Review</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card">
        <!-- Search Header -->
        <header class="card-header cust-card-header noborder">
            <iconify-icon wire:loading wire:target="search" class="loading-icon text-lg" icon="line-md:loading-twotone-loop"></iconify-icon>
            <input type="text" class="form-control !pl-9 mr-1 basis-1/4" placeholder="Search reviews..." wire:model="search">
        </header>

        <!-- Active Filters Display -->
        <header class="card-header cust-card-header noborder" style="display: block;">
            @if ($reviewable_type)
                <button class="btn inline-flex justify-center btn-dark btn-sm">
                    <span wire:click="toggleReviewableType">
                        Type: {{ class_basename($reviewable_type) }} &nbsp;&nbsp;
                    </span>
                    <span wire:click="clearReviewableType">
                        <iconify-icon icon="material-symbols:close" width="1.2em" height="1.2em"></iconify-icon>
                    </span>
                </button>
            @endif

            @if (!is_null($is_reviewed))
                <button class="btn inline-flex justify-center btn-dark btn-sm">
                    <span wire:click="toggleReviewStatus">
                        Review Status: {{ $is_reviewed ? 'Reviewed' : 'Not Reviewed' }} &nbsp;&nbsp;
                    </span>
                    <span wire:click="clearReviewStatus">
                        <iconify-icon icon="material-symbols:close" width="1.2em" height="1.2em"></iconify-icon>
                    </span>
                </button>
            @endif

            @if ($needs_manager_review)
                <button class="btn inline-flex justify-center btn-warning btn-sm">
                    <span wire:click="toggleManagerReview">
                        Needs Manager Review: Yes &nbsp;&nbsp;
                    </span>
                    <span wire:click="clearManagerReview">
                        <iconify-icon icon="material-symbols:close" width="1.2em" height="1.2em"></iconify-icon>
                    </span>
                </button>
            @endif
        </header>

        <!-- Reviews Table -->
        <div class="card-body px-6 pb-6 overflow-x-auto">
            <div class="-mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="bg-slate-200 dark:bg-slate-700">
                                <tr>
                                    <th scope="col" class="table-th">ID</th>
                                    <th scope="col" class="table-th">Title</th>
                                    <th scope="col" class="table-th">Type</th>
                                    <th scope="col" class="table-th">Assignee</th>
                                    <th scope="col" class="table-th">Status</th>
                                    <th scope="col" class="table-th">Ratings</th>
                                    <th scope="col" class="table-th">Manager Review</th>
                                    <th scope="col" class="table-th">Created</th>
                                    <th scope="col" class="table-th">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @foreach ($reviews as $review)
                                    <tr class="even:bg-slate-50 dark:even:bg-slate-700">
                                        <td class="table-td">{{ $review->id }}</td>
                                        
                                        <td class="table-td">
                                            <div class="flex-1 text-start">
                                                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                    {{ $review->title }}
                                                </h4>
                                                <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                                    {{ Str::limit($review->desc, 50) }}
                                                </div>
                                            </div>
                                        </td>

                                        <td class="table-td">
                                            <span class="badge bg-slate-500 text-slate-500 bg-opacity-30 rounded-3xl">
                                                {{ class_basename($review->reviewable_type) }}
                                            </span>
                                        </td>

                                        <td class="table-td">
                                            {{ $review->assignee?->full_name ?? 'Unassigned' }}
                                        </td>

                                        <td class="table-td">
                                            <div class="flex flex-col gap-1">
                                                @if ($review->is_reviewed)
                                                    <span class="badge bg-success-500 text-success-500 bg-opacity-30 rounded-3xl">
                                                        Reviewed
                                                    </span>
                                                    @if ($review->reviewed_at)
                                                        <span class="text-xs text-slate-500">
                                                            {{ $review->reviewed_at->format('d/m/Y') }}
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-warning-500 text-warning-500 bg-opacity-30 rounded-3xl">
                                                        Pending
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="table-td">
                                            @if ($review->is_reviewed)
                                                <div class="flex flex-col gap-1">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-xs text-slate-600">Emp:</span>
                                                        <span class="text-sm font-medium {{ $review->employee_rating < 8 ? 'text-red-600' : 'text-green-600' }}">
                                                            {{ $review->employee_rating }}/10
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-xs text-slate-600">Co:</span>
                                                        <span class="text-sm font-medium {{ $review->company_rating < 8 ? 'text-red-600' : 'text-green-600' }}">
                                                            {{ $review->company_rating }}/10
                                                        </span>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-xs text-slate-400">Not rated</span>
                                            @endif
                                        </td>

                                        <td class="table-td">
                                            @php
                                                $needsManagerReview = $review->is_reviewed && 
                                                                    !$review->is_manager_reviewed && 
                                                                    ($review->employee_rating < 8 || $review->company_rating < 8);
                                            @endphp
                                            
                                            @if ($needsManagerReview)
                                                <span class="badge bg-warning-500 text-warning-500 bg-opacity-30 rounded-3xl">
                                                    Required
                                                </span>
                                            @elseif ($review->is_manager_reviewed)
                                                <span class="badge bg-success-500 text-success-500 bg-opacity-30 rounded-3xl">
                                                    Completed
                                                </span>
                                            @else
                                                <span class="badge bg-slate-500 text-slate-500 bg-opacity-30 rounded-3xl">
                                                    N/A
                                                </span>
                                            @endif
                                        </td>

                                        <td class="table-td">
                                            <span class="block text-xs text-slate-600">
                                                {{ $review->created_at->format('d/m/Y') }}
                                            </span>
                                            <span class="block text-xs text-slate-400">
                                                {{ $review->created_at->format('H:i') }}
                                            </span>
                                        </td>

                                        <td class="table-td">
                                            <div class="dropdown relative">
                                                <button class="btn inline-flex justify-center btn-outline-secondary dropdown-toggle btn-sm" 
                                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="heroicons-outline:dots-horizontal"></iconify-icon>
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                                    
                                                    <!-- Go To SoldPolicy -->
                                                    @if ($review->reviewable_type === 'App\Models\Business\SoldPolicy')
                                                        <li>
                                                            <a href="{{ route('sold.policy.show', $review->reviewable_id) }}" 
                                                               target="_blank"
                                                               class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="heroicons:eye-20-solid"></iconify-icon>
                                                                Go To Policy
                                                            </a>
                                                        </li>
                                                    @endif

                                                    <!-- Set Ratings & Comments -->
                                                    @can('receiveClientComment', $review)
                                                        @if (!$review->is_reviewed)
                                                            <li>
                                                                <button wire:click="openRatingsModal({{ $review->id }})" 
                                                                        class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white w-full text-left">
                                                                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="heroicons:star-20-solid"></iconify-icon>
                                                                    Set Ratings & Comments
                                                                </button>
                                                            </li>
                                                        @endif
                                                    @endcan

                                                    <!-- Manager Review -->
                                                    @can('markAsReviewed', $review)
                                                        @php
                                                            $needsManagerReview = $review->is_reviewed && 
                                                                                !$review->is_manager_reviewed && 
                                                                                ($review->employee_rating < 8 || $review->company_rating < 8);
                                                        @endphp
                                                        @if ($needsManagerReview)
                                                            <li>
                                                                <button wire:click="openManagerModal({{ $review->id }})" 
                                                                        class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white w-full text-left">
                                                                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="heroicons:shield-check-20-solid"></iconify-icon>
                                                                    Manager Review
                                                                </button>
                                                            </li>
                                                        @endif
                                                    @endcan

                                                    <!-- No actions available -->
                                                    @if (!($review->reviewable_type === 'App\Models\Business\SoldPolicy') && 
                                                         !Auth::user()->can('receiveClientComment', $review) && 
                                                         !Auth::user()->can('markAsReviewed', $review))
                                                        <li>
                                                            <span class="text-slate-400 block font-Inter font-normal px-4 py-2">
                                                                No actions available
                                                            </span>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if ($reviews->isEmpty())
                            <div class="card m-5 p-5">
                                <div class="card-body rounded-md bg-white dark:bg-slate-800">
                                    <div class="items-center text-center p-5">
                                        <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon></h2>
                                        <h2 class="card-title text-slate-900 dark:text-white mb-3">
                                            No reviews found with the applied filters
                                        </h2>
                                        <p class="card-text">
                                            Try changing the filters or search terms for this view.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    {{ $reviews->links('vendor.livewire.bootstrap') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Simple Filter Modals -->
    @if ($createdDateSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white">Creation Date</h3>
                            <button wire:click="toggleCreatedDate" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="from-group">
                                <label for="Ecreated_from" class="form-label">Created from</label>
                                <input name="Ecreated_from" type="date" class="form-control mt-2 w-full" wire:model.defer="Ecreated_from">
                            </div>
                            <div class="from-group">
                                <label for="Ecreated_to" class="form-label">Created to</label>
                                <input name="Ecreated_to" type="date" class="form-control mt-2 w-full" wire:model.defer="Ecreated_to">
                            </div>
                        </div>
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setCreatedDates" class="btn inline-flex justify-center text-white bg-black-500">
                                Apply
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Ratings & Comments Modal -->
    @if ($showRatingsModal)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white">Set Ratings & Comments</h3>
                            <button wire:click="closeRatingsModal" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6 space-y-4">
                            @if($selectedReview)
                                <div class="mb-4 p-3 bg-slate-100 rounded-lg">
                                    <h4 class="font-medium text-slate-700">{{ $selectedReview->title }}</h4>
                                    <p class="text-sm text-slate-600">{{ $selectedReview->desc }}</p>
                                </div>
                            @endif
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div class="from-group">
                                    <label for="form_employee_rating" class="form-label">Employee Rating (0-10)</label>
                                    <input type="number" step="0.1" min="0" max="10" 
                                           class="form-control mt-2 w-full" 
                                           wire:model.defer="form_employee_rating"
                                           placeholder="e.g. 8.5">
                                </div>
                                <div class="from-group">
                                    <label for="form_company_rating" class="form-label">Company Rating (0-10)</label>
                                    <input type="number" step="0.1" min="0" max="10" 
                                           class="form-control mt-2 w-full" 
                                           wire:model.defer="form_company_rating"
                                           placeholder="e.g. 9.0">
                                </div>
                            </div>
                            
                            <div class="from-group">
                                <label for="form_employee_comment" class="form-label">Employee Comment</label>
                                <textarea class="form-control mt-2 w-full" rows="3" 
                                          wire:model.defer="form_employee_comment"
                                          placeholder="Enter feedback about the employee..."></textarea>
                            </div>
                            
                            <div class="from-group">
                                <label for="form_company_comment" class="form-label">Company Comment</label>
                                <textarea class="form-control mt-2 w-full" rows="3" 
                                          wire:model.defer="form_company_comment"
                                          placeholder="Enter feedback about the company..."></textarea>
                            </div>
                        </div>
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="closeRatingsModal" class="btn inline-flex justify-center btn-outline-secondary">
                                Cancel
                            </button>
                            <button wire:click="setRatingsAndComments" class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setRatingsAndComments">Save Ratings</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading
                                    wire:target="setRatingsAndComments" icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Manager Review Modal -->
    @if ($showManagerModal)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-warning-500">
                            <h3 class="text-xl font-medium text-white">Manager Review</h3>
                            <button wire:click="closeManagerModal" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6 space-y-4">
                            @if($selectedReview)
                                <div class="mb-4 p-3 bg-orange-100 rounded-lg">
                                    <h4 class="font-medium text-slate-700">{{ $selectedReview->title }}</h4>
                                    <p class="text-sm text-slate-600">{{ $selectedReview->desc }}</p>
                                    <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
                                        <div>
                                            <span class="font-medium">Employee Rating:</span> 
                                            <span class="{{ $selectedReview->employee_rating < 8 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $selectedReview->employee_rating }}/10
                                            </span>
                                        </div>
                                        <div>
                                            <span class="font-medium">Company Rating:</span> 
                                            <span class="{{ $selectedReview->company_rating < 8 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $selectedReview->company_rating }}/10
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div class="from-group">
                                    <label for="manager_employee_rating" class="form-label">Manager Employee Rating (0-10)</label>
                                    <input type="number" step="0.1" min="0" max="10" 
                                           class="form-control mt-2 w-full" 
                                           wire:model.defer="manager_employee_rating"
                                           placeholder="e.g. 9.0">
                                </div>
                                <div class="from-group">
                                    <label for="manager_company_rating" class="form-label">Manager Company Rating (0-10)</label>
                                    <input type="number" step="0.1" min="0" max="10" 
                                           class="form-control mt-2 w-full" 
                                           wire:model.defer="manager_company_rating"
                                           placeholder="e.g. 9.5">
                                </div>
                            </div>
                            
                            <div class="from-group">
                                <label for="manager_employee_comment" class="form-label">Manager Employee Comment</label>
                                <textarea class="form-control mt-2 w-full" rows="3" 
                                          wire:model.defer="manager_employee_comment"
                                          placeholder="Manager's feedback about employee performance..."></textarea>
                            </div>
                            
                            <div class="from-group">
                                <label for="manager_company_comment" class="form-label">Manager Company Comment</label>
                                <textarea class="form-control mt-2 w-full" rows="3" 
                                          wire:model.defer="manager_company_comment"
                                          placeholder="Manager's feedback about company processes..."></textarea>
                            </div>
                        </div>
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="closeManagerModal" class="btn inline-flex justify-center btn-outline-secondary">
                                Cancel
                            </button>
                            <button wire:click="markAsManagerReviewed" class="btn inline-flex justify-center text-white bg-warning-500">
                                <span wire:loading.remove wire:target="markAsManagerReviewed">Complete Manager Review</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading
                                    wire:target="markAsManagerReviewed" icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
