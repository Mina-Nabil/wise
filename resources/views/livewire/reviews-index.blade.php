<div>
    <!-- Header Section -->
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                <b>Reviews</b> Management
            </h4>
        </div>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
            @if (Auth::user()->is_admin)
                <button wire:click="exportReviews" class="btn inline-flex justify-center btn-outline-dark rounded-[25px]">
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
                    <span
                        class="cursor-pointer absolute ltr:border-l rtl:border-r border-slate-100 h-full ltr:right-0 rtl:left-0 px-2 flex items-center justify-center leading-none">
                        <iconify-icon class="leading-none text-xl" icon="ic:round-keyboard-arrow-down"></iconify-icon>
                    </span>
                </button>
                <ul
                    class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                    <li wire:click="toggleReviewableType">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Reviewable Type</span>
                    </li>
                    <li wire:click="toggleCreatedDate">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Creation Date (From-To)</span>
                    </li>
                    <li wire:click="toggleReviewDate">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Review Date (From-To)</span>
                    </li>
                    <li wire:click="toggleEmployeeRating">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Employee Rating (From-To)</span>
                    </li>
                    <li wire:click="toggleCompanyRating">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Company Rating (From-To)</span>
                    </li>
                    <li wire:click="toggleServiceQualityRating">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Service Quality Rating (From-To)</span>
                    </li>
                    <li wire:click="togglePricingRating">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Pricing Rating (From-To)</span>
                    </li>
                    <li wire:click="toggleProcessingTimeRating">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Processing Time Rating (From-To)</span>
                    </li>
                    <li wire:click="toggleCollectionChannelRating">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Collection Channel Rating (From-To)</span>
                    </li>
                    <li wire:click="togglePolicyConditionsRating">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Policy Conditions Rating (From-To)</span>
                    </li>
                    <li wire:click="toggleInsuranceCompanyRating">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Insurance Company Rating (From-To)</span>
                    </li>
                    <li wire:click="toggleProviderRating">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Provider Rating (From-To)</span>
                    </li>
                    <li wire:click="toggleClaimsSpecialistRating">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Claims Specialist Rating (From-To)</span>
                    </li>
                    <li wire:click="toggleWiseRating">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Wise Rating (From-To)</span>
                    </li>
                    <li wire:click="toggleReviewStatus">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Review Status</span>
                    </li>
                    <li wire:click="toggleEmployeeComment">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Has Employee Comment</span>
                    </li>
                    <li wire:click="toggleCompanyComment">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Has Company Comment</span>
                    </li>
                    <li wire:click="toggleNeedManagerReview">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Need Manager Review</span>
                    </li>
                    <li wire:click="toggleNoAnswer">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                            Call Status</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card">
        <!-- Search Header -->
        <header class="card-header cust-card-header noborder">
            <iconify-icon wire:loading wire:target="search" class="loading-icon text-lg"
                icon="line-md:loading-twotone-loop"></iconify-icon>
            <input type="text" class="form-control !pl-9 mr-1 basis-1/4" placeholder="Search reviews..."
                wire:model="search">
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

            @if ($need_manager_review)
                <button class="btn inline-flex justify-center btn-warning btn-sm">
                    <span wire:click="toggleNeedManagerReview">
                        Need Manager Review: Yes &nbsp;&nbsp;
                    </span>
                    <span wire:click="clearNeedManagerReview">
                        <iconify-icon icon="material-symbols:close" width="1.2em" height="1.2em"></iconify-icon>
                    </span>
                </button>
            @endif

            @if (isset($no_answer))
                <button class="btn inline-flex justify-center btn-dark btn-sm">
                    <span wire:click="toggleNoAnswer">
                        @php
                            $statusLabels = [
                                null => 'Not Yet Called',
                                0 => 'No Answer',
                                1 => 'Answered',
                                2 => 'Sent WhatsApp',
                                3 => 'Wrong Number',
                                4 => 'Callback'
                            ];
                        @endphp
                        Call Status: {{ $statusLabels[$no_answer] ?? 'Unknown' }} &nbsp;&nbsp;
                    </span>
                    <span wire:click="clearNoAnswer">
                        <iconify-icon icon="material-symbols:close" width="1.2em" height="1.2em"></iconify-icon>
                    </span>
                </button>
            @endif

            @if ($employee_rating_from || $employee_rating_to)
                <button class="btn inline-flex justify-center btn-dark btn-sm">
                    <span wire:click="toggleEmployeeRating">
                        Employee Rating: {{ $employee_rating_from ?? '0' }}-{{ $employee_rating_to ?? '10' }} &nbsp;&nbsp;
                    </span>
                    <span wire:click="clearEmployeeRating">
                        <iconify-icon icon="material-symbols:close" width="1.2em" height="1.2em"></iconify-icon>
                    </span>
                </button>
            @endif

            @if ($service_quality_rating_from || $service_quality_rating_to)
                <button class="btn inline-flex justify-center btn-dark btn-sm">
                    <span wire:click="toggleServiceQualityRating">
                        Service Quality Rating: {{ $service_quality_rating_from ?? '0' }}-{{ $service_quality_rating_to ?? '10' }} &nbsp;&nbsp;
                    </span>
                    <span wire:click="clearServiceQualityRating">
                        <iconify-icon icon="material-symbols:close" width="1.2em" height="1.2em"></iconify-icon>
                    </span>
                </button>
            @endif

            @if ($pricing_rating_from || $pricing_rating_to)
                <button class="btn inline-flex justify-center btn-dark btn-sm">
                    <span wire:click="togglePricingRating">
                        Pricing Rating: {{ $pricing_rating_from ?? '0' }}-{{ $pricing_rating_to ?? '10' }} &nbsp;&nbsp;
                    </span>
                    <span wire:click="clearPricingRating">
                        <iconify-icon icon="material-symbols:close" width="1.2em" height="1.2em"></iconify-icon>
                    </span>
                </button>
            @endif

            @if ($processing_time_rating_from || $processing_time_rating_to)
                <button class="btn inline-flex justify-center btn-dark btn-sm">
                    <span wire:click="toggleProcessingTimeRating">
                        Processing Time Rating: {{ $processing_time_rating_from ?? '0' }}-{{ $processing_time_rating_to ?? '10' }} &nbsp;&nbsp;
                    </span>
                    <span wire:click="clearProcessingTimeRating">
                        <iconify-icon icon="material-symbols:close" width="1.2em" height="1.2em"></iconify-icon>
                    </span>
                </button>
            @endif

            @if ($collection_channel_rating_from || $collection_channel_rating_to)
                <button class="btn inline-flex justify-center btn-dark btn-sm">
                    <span wire:click="toggleCollectionChannelRating">
                        Collection Channel Rating: {{ $collection_channel_rating_from ?? '0' }}-{{ $collection_channel_rating_to ?? '10' }} &nbsp;&nbsp;
                    </span>
                    <span wire:click="clearCollectionChannelRating">
                        <iconify-icon icon="material-symbols:close" width="1.2em" height="1.2em"></iconify-icon>
                    </span>
                </button>
            @endif

            @if ($policy_conditions_rating_from || $policy_conditions_rating_to)
                <button class="btn inline-flex justify-center btn-dark btn-sm">
                    <span wire:click="togglePolicyConditionsRating">
                        Policy Conditions Rating: {{ $policy_conditions_rating_from ?? '0' }}-{{ $policy_conditions_rating_to ?? '10' }} &nbsp;&nbsp;
                    </span>
                    <span wire:click="clearPolicyConditionsRating">
                        <iconify-icon icon="material-symbols:close" width="1.2em" height="1.2em"></iconify-icon>
                    </span>
                </button>
            @endif

            @if ($insurance_company_rating_from || $insurance_company_rating_to)
                <button class="btn inline-flex justify-center btn-dark btn-sm">
                    <span wire:click="toggleInsuranceCompanyRating">
                        Insurance Company Rating: {{ $insurance_company_rating_from ?? '0' }}-{{ $insurance_company_rating_to ?? '10' }} &nbsp;&nbsp;
                    </span>
                    <span wire:click="clearInsuranceCompanyRating">
                        <iconify-icon icon="material-symbols:close" width="1.2em" height="1.2em"></iconify-icon>
                    </span>
                </button>
            @endif

            @if ($provider_rating_from || $provider_rating_to)
                <button class="btn inline-flex justify-center btn-dark btn-sm">
                    <span wire:click="toggleProviderRating">
                        Provider Rating: {{ $provider_rating_from ?? '0' }}-{{ $provider_rating_to ?? '10' }} &nbsp;&nbsp;
                    </span>
                    <span wire:click="clearProviderRating">
                        <iconify-icon icon="material-symbols:close" width="1.2em" height="1.2em"></iconify-icon>
                    </span>
                </button>
            @endif

            @if ($claims_specialist_rating_from || $claims_specialist_rating_to)
                <button class="btn inline-flex justify-center btn-dark btn-sm">
                    <span wire:click="toggleClaimsSpecialistRating">
                        Claims Specialist Rating: {{ $claims_specialist_rating_from ?? '0' }}-{{ $claims_specialist_rating_to ?? '10' }} &nbsp;&nbsp;
                    </span>
                    <span wire:click="clearClaimsSpecialistRating">
                        <iconify-icon icon="material-symbols:close" width="1.2em" height="1.2em"></iconify-icon>
                    </span>
                </button>
            @endif

            @if ($wise_rating_from || $wise_rating_to)
                <button class="btn inline-flex justify-center btn-dark btn-sm">
                    <span wire:click="toggleWiseRating">
                        Wise Rating: {{ $wise_rating_from ?? '0' }}-{{ $wise_rating_to ?? '10' }} &nbsp;&nbsp;
                    </span>
                    <span wire:click="clearWiseRating">
                        <iconify-icon icon="material-symbols:close" width="1.2em" height="1.2em"></iconify-icon>
                    </span>
                </button>
            @endif

        </header>

        <!-- Reviews Table -->
        <div class="card-body px-6 pb-6 overflow-x-auto">
            <div class="-mx-6">
                <div class="inline-block min-w-full align-middle">

                    <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                        <thead class="bg-slate-200 dark:bg-slate-700">
                            <tr>
                                <th scope="col" class="table-th">#</th>
                                <th scope="col" class="table-th">Title</th>
                                <th scope="col" class="table-th">Type</th>
                                <th scope="col" class="table-th">Contact</th>
                                <th scope="col" class="table-th">Assignee</th>
                                <th scope="col" class="table-th">Status</th>
                                <th scope="col" class="table-th">Ratings</th>
                                <th scope="col" class="table-th">Manager?</th>
                                <th scope="col" class="table-th">Answered?</th>
                                <th scope="col" class="table-th">Created</th>
                                <th scope="col" class="table-th"></th>
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
                                        @php
                                            $client = null;
                                            $phone = null;
                                            $clientType = null;

                                            // Try to get client from direct relationship (SoldPolicy)
                                            if ($review->reviewable && $review->reviewable->client) {
                                                $client = $review->reviewable->client;
                                                $clientType = 'customer';
                                            }
                                            // Try to get client from indirect relationship (Task -> SoldPolicy -> Client)
                                            elseif (
                                                $review->reviewable &&
                                                $review->reviewable->taskable &&
                                                $review->reviewable->taskable->client
                                            ) {
                                                $client = $review->reviewable->taskable->client;
                                                $clientType = 'customer';
                                            }

                                            // Get the primary phone number using the accessor
                                            if ($client) {
                                                $phone = $client->telephone1;
                                            }
                                        @endphp

                                        <div class="flex items-center space-x-2">
                                            @if ($client)
                                                <div class="flex-1">
                                                    <a href="{{ $clientType === 'customer' ? route('customers.show', $client->id) : route('corporates.show', $client->id) }}"
                                                        class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline cursor-pointer">
                                                        {{ $client->name }}
                                                    </a>
                                                </div>
                                                @if ($phone)
                                                    <button wire:click="openContactsModal({{ $review->id }})"
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-colors flex-shrink-0"
                                                        title="View All Contacts">
                                                        <iconify-icon icon="heroicons:phone-20-solid"
                                                            class="text-sm"></iconify-icon>
                                                    </button>
                                                @endif
                                            @else
                                                <span class="text-xs text-slate-400">No client</span>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="table-td">
                                        {{ $review->assignee?->full_name ?? 'Unassigned' }}
                                    </td>

                                    <td class="table-td">
                                        <div class="flex flex-col gap-1">
                                            @if ($review->is_reviewed)
                                                <span
                                                    class="badge bg-success-500 text-success-500 bg-opacity-30 rounded-3xl">
                                                    Reviewed
                                                </span>
                                                @if ($review->reviewed_at)
                                                    <span class="text-xs text-slate-500">
                                                        {{ $review->reviewed_at->format('d/m/Y') }}
                                                    </span>
                                                @endif
                                            @else
                                                <span
                                                    class="badge bg-warning-500 text-warning-500 bg-opacity-30 rounded-3xl">
                                                    Pending
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="table-td">
                                        @if ($review->is_reviewed)
                                            @if ($review->is_claim_review)
                                                <div class="grid grid-cols-2 gap-1">
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-xs text-slate-600">Ins:</span>
                                                        <span
                                                            class="text-sm font-medium {{ $review->insurance_company_rating < 8 ? 'text-red-600' : 'text-green-600' }}">
                                                            {{ $review->insurance_company_rating }}/10
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-xs text-slate-600">Prov:</span>
                                                        <span
                                                            class="text-sm font-medium {{ $review->provider_rating < 8 ? 'text-red-600' : 'text-green-600' }}">
                                                            {{ $review->provider_rating }}/10
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-xs text-slate-600">Claim:</span>
                                                        <span
                                                            class="text-sm font-medium {{ $review->claims_specialist_rating < 8 ? 'text-red-600' : 'text-green-600' }}">
                                                            {{ $review->claims_specialist_rating }}/10
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-xs text-slate-600">Wise:</span>
                                                        <span
                                                            class="text-sm font-medium {{ $review->wise_rating < 8 ? 'text-red-600' : 'text-green-600' }}">
                                                            {{ $review->wise_rating }}/10
                                                        </span>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="flex flex-col gap-1">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-xs text-slate-600">Emp:</span>
                                                        <span
                                                            class="text-sm font-medium {{ $review->employee_rating < 8 ? 'text-red-600' : 'text-green-600' }}">
                                                            {{ $review->employee_rating }}/10
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-xs text-slate-600">Co:</span>
                                                        <span
                                                            class="text-sm font-medium {{ $review->policy_conditions_rating < 8 ? 'text-red-600' : 'text-green-600' }}">
                                                            {{ $review->policy_conditions_rating }}/10
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-xs text-slate-400">Not rated</span>
                                        @endif
                                    </td>

                                    <td class="table-td">
                                        @if ($review->is_claim_review)
                                            @if ($review->need_claim_manager_review)
                                                <span
                                                    class="badge bg-warning-500 text-warning-500 bg-opacity-30 rounded-3xl">
                                                    Claim Manager Required
                                                </span>
                                            @elseif ($review->is_claim_manager_reviewed)
                                                <span
                                                    class="badge bg-success-500 text-success-500 bg-opacity-30 rounded-3xl">
                                                    Claim Manager Completed
                                                </span>
                                            @else
                                                <span
                                                    class="badge bg-slate-500 text-slate-500 bg-opacity-30 rounded-3xl">
                                                    N/A
                                                </span>
                                            @endif
                                        @else
                                            @if ($review->need_manager_review)
                                                <span
                                                    class="badge bg-warning-500 text-warning-500 bg-opacity-30 rounded-3xl">
                                                    Required
                                                </span>
                                            @elseif ($review->is_manager_reviewed)
                                                <span
                                                    class="badge bg-success-500 text-success-500 bg-opacity-30 rounded-3xl">
                                                    Completed
                                                </span>
                                            @else
                                                <span
                                                    class="badge bg-slate-500 text-slate-500 bg-opacity-30 rounded-3xl">
                                                    N/A
                                                </span>
                                            @endif
                                        @endif
                                    </td>

                                    <td class="table-td">
                                        @if ($review->no_answer === 0)
                                            <span class="badge bg-red-500 text-red-500 bg-opacity-30 rounded-3xl">
                                                No Answer
                                            </span>
                                        @elseif ($review->no_answer === 1)
                                            <span class="badge bg-green-500 text-green-500 bg-opacity-30 rounded-3xl">
                                                Answered
                                            </span>
                                        @elseif ($review->no_answer === 2)
                                            <span class="badge bg-blue-500 text-blue-500 bg-opacity-30 rounded-3xl">
                                                Sent WhatsApp
                                            </span>
                                        @elseif ($review->no_answer === 3)
                                            <span class="badge bg-orange-500 text-orange-500 bg-opacity-30 rounded-3xl">
                                                Wrong Number
                                            </span>
                                        @elseif ($review->no_answer === 4)
                                            <span class="badge bg-purple-500 text-purple-500 bg-opacity-30 rounded-3xl">
                                                Callback
                                            </span>
                                        @else
                                            <span class="badge bg-slate-500 text-slate-500 bg-opacity-30 rounded-3xl">
                                                Not Yet Called
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
                                            <button
                                                class="btn inline-flex justify-center btn-outline-secondary dropdown-toggle btn-sm"
                                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2"
                                                    icon="heroicons-outline:dots-horizontal"></iconify-icon>
                                            </button>
                                            <ul
                                                class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">

                                                <!-- Go To Policy -->
                                                @if ($review->reviewable_type === 'sold_policy' || 
                                                     ($review->reviewable_type === 'task' && $review->reviewable && $review->reviewable->type === 'claim'))
                                                    <li>
                                                        <button wire:click="goToPolicy({{ $review->id }})"
                                                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white w-full text-left">
                                                            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2"
                                                                icon="heroicons:eye-20-solid"></iconify-icon>
                                                            Go To Policy
                                                        </button>
                                                    </li>
                                                @endif

                                                <!-- View Info -->
                                                <li>
                                                    <button wire:click="openInfoModal({{ $review->id }})"
                                                        class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white w-full text-left">
                                                        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2"
                                                            icon="heroicons:information-circle-20-solid"></iconify-icon>
                                                        View Info
                                                    </button>
                                                </li>

                                                <!-- Set Ratings & Comments -->
                                                @can('receiveClientComment', $review)
                                                        <li>
                                                            <button wire:click="openRatingsModal({{ $review->id }})"
                                                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white w-full text-left">
                                                                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2"
                                                                    icon="heroicons:star-20-solid"></iconify-icon>
                                                                {{ $review->is_reviewed ? 'Edit Ratings & Comments' : 'Set Ratings & Comments' }}
                                                            </button>
                                                        </li>
                                                @endcan

                                                <!-- Go to Claim -->
                                                @if ($review->reviewable_type === 'task' && $review->reviewable && $review->reviewable->type === 'claim')
                                                    <li>
                                                        <button wire:click="goToClaim({{ $review->id }})"
                                                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white w-full text-left">
                                                            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2"
                                                                icon="heroicons:document-text-20-solid"></iconify-icon>
                                                            Go to Claim
                                                        </button>
                                                    </li>
                                                @endif

                                                <!-- Mark as Manager Reviewed -->
                                                @can('markAsReviewed', $review)
                                                    @if ($review->need_manager_review && !$review->is_manager_reviewed && !$review->is_claim_review)
                                                        <li>
                                                            <button wire:click="openManagerModal({{ $review->id }})"
                                                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white w-full text-left">
                                                                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2"
                                                                    icon="heroicons:shield-check-20-solid"></iconify-icon>
                                                                Mark as Manager Reviewed
                                                            </button>
                                                        </li>
                                                    @endif
                                                @endcan

                                                <!-- Mark as Claim Manager Reviewed -->
                                                @can('markAsReviewed', $review)
                                                    @if ($review->need_claim_manager_review && !$review->is_claim_manager_reviewed && $review->is_claim_review)
                                                        <li>
                                                            <button
                                                                wire:click="openClaimManagerModal({{ $review->id }})"
                                                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white w-full text-left">
                                                                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2"
                                                                    icon="heroicons:shield-check-20-solid"></iconify-icon>
                                                                Mark as Claim Manager Reviewed
                                                            </button>
                                                        </li>
                                                    @endif
                                                @endcan

                                                <!-- Mark Call Status -->
                                                <li>
                                                    <button wire:click="openNoAnswerModal({{ $review->id }})"
                                                        class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white w-full text-left">
                                                        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2"
                                                            icon="heroicons:phone-20-solid"></iconify-icon>
                                                        Mark Call Status
                                                    </button>
                                                </li>

                                                <!-- Delete Review (Admin Only) -->
                                                @if (false && Auth::user()->is_admin)
                                                    <li>
                                                        <button
                                                            wire:click="$emit('showConfirmation', 'Are you sure you want to delete this review?', 'red', 'deleteReview', {{ $review->id }})"
                                                            class="text-red-600 dark:text-red-400 block font-Inter font-normal px-4 py-2 hover:bg-red-50 dark:hover:bg-red-900 dark:hover:text-white w-full text-left">
                                                            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2"
                                                                icon="heroicons:trash-20-solid"></iconify-icon>
                                                            Delete Review
                                                        </button>
                                                    </li>
                                                @endif

                                                <!-- No actions available -->
                                                {{-- @if (
                                                    !($review->reviewable_type === 'sold_policy') &&
                                                    !($review->reviewable_type === 'task' && $review->reviewable && $review->reviewable->type === 'claim') &&
                                                        !Auth::user()->can('receiveClientComment', $review) &&
                                                        !Auth::user()->is_admin)
                                                    <li>
                                                        <span
                                                            class="text-slate-400 block font-Inter font-normal px-4 py-2">
                                                            No actions available
                                                        </span>
                                                    </li>
                                                @endif --}}
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
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white">Creation Date</h3>
                            <button wire:click="toggleCreatedDate" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="from-group">
                                <label for="Ecreated_from" class="form-label">Created from</label>
                                <input name="Ecreated_from" type="date" class="form-control mt-2 w-full"
                                    wire:model.defer="Ecreated_from">
                            </div>
                            <div class="from-group">
                                <label for="Ecreated_to" class="form-label">Created to</label>
                                <input name="Ecreated_to" type="date" class="form-control mt-2 w-full"
                                    wire:model.defer="Ecreated_to">
                            </div>
                        </div>
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setCreatedDates"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Apply
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Employee Rating Filter Modal -->
    @if ($employeeRatingSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white">Employee Rating</h3>
                            <button wire:click="toggleEmployeeRating" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="from-group">
                                <label for="Eemployee_rating_from" class="form-label">Rating from (0-10)</label>
                                <input name="Eemployee_rating_from" type="number" step="0.1" min="0" max="10" class="form-control mt-2 w-full"
                                    wire:model.defer="Eemployee_rating_from">
                            </div>
                            <div class="from-group">
                                <label for="Eemployee_rating_to" class="form-label">Rating to (0-10)</label>
                                <input name="Eemployee_rating_to" type="number" step="0.1" min="0" max="10" class="form-control mt-2 w-full"
                                    wire:model.defer="Eemployee_rating_to">
                            </div>
                        </div>
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setEmployeeRating"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Apply
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Service Quality Rating Filter Modal -->
    @if ($serviceQualityRatingSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white">Service Quality Rating</h3>
                            <button wire:click="toggleServiceQualityRating" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="from-group">
                                <label for="Eservice_quality_rating_from" class="form-label">Rating from (0-10)</label>
                                <input name="Eservice_quality_rating_from" type="number" step="0.1" min="0" max="10" class="form-control mt-2 w-full"
                                    wire:model.defer="Eservice_quality_rating_from">
                            </div>
                            <div class="from-group">
                                <label for="Eservice_quality_rating_to" class="form-label">Rating to (0-10)</label>
                                <input name="Eservice_quality_rating_to" type="number" step="0.1" min="0" max="10" class="form-control mt-2 w-full"
                                    wire:model.defer="Eservice_quality_rating_to">
                            </div>
                        </div>
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setServiceQualityRating"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Apply
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Pricing Rating Filter Modal -->
    @if ($pricingRatingSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white">Pricing Rating</h3>
                            <button wire:click="togglePricingRating" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="from-group">
                                <label for="Epricing_rating_from" class="form-label">Rating from (0-10)</label>
                                <input name="Epricing_rating_from" type="number" step="0.1" min="0" max="10" class="form-control mt-2 w-full"
                                    wire:model.defer="Epricing_rating_from">
                            </div>
                            <div class="from-group">
                                <label for="Epricing_rating_to" class="form-label">Rating to (0-10)</label>
                                <input name="Epricing_rating_to" type="number" step="0.1" min="0" max="10" class="form-control mt-2 w-full"
                                    wire:model.defer="Epricing_rating_to">
                            </div>
                        </div>
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setPricingRating"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Apply
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Insurance Company Rating Filter Modal -->
    @if ($insuranceCompanyRatingSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white">Insurance Company Rating</h3>
                            <button wire:click="toggleInsuranceCompanyRating" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="from-group">
                                <label for="Einsurance_company_rating_from" class="form-label">Rating from (0-10)</label>
                                <input name="Einsurance_company_rating_from" type="number" step="0.1" min="0" max="10" class="form-control mt-2 w-full"
                                    wire:model.defer="Einsurance_company_rating_from">
                            </div>
                            <div class="from-group">
                                <label for="Einsurance_company_rating_to" class="form-label">Rating to (0-10)</label>
                                <input name="Einsurance_company_rating_to" type="number" step="0.1" min="0" max="10" class="form-control mt-2 w-full"
                                    wire:model.defer="Einsurance_company_rating_to">
                            </div>
                        </div>
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setInsuranceCompanyRating"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Apply
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Processing Time Rating Filter Modal -->
    @if ($processingTimeRatingSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white">Processing Time Rating</h3>
                            <button wire:click="toggleProcessingTimeRating" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="from-group">
                                <label for="Eprocessing_time_rating_from" class="form-label">Rating from (0-10)</label>
                                <input name="Eprocessing_time_rating_from" type="number" step="0.1" min="0" max="10" class="form-control mt-2 w-full" wire:model.defer="Eprocessing_time_rating_from">
                            </div>
                            <div class="from-group">
                                <label for="Eprocessing_time_rating_to" class="form-label">Rating to (0-10)</label>
                                <input name="Eprocessing_time_rating_to" type="number" step="0.1" min="0" max="10" class="form-control mt-2 w-full" wire:model.defer="Eprocessing_time_rating_to">
                            </div>
                        </div>
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setProcessingTimeRating" class="btn inline-flex justify-center text-white bg-black-500">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Collection Channel Rating Filter Modal -->
    @if ($collectionChannelRatingSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white">Collection Channel Rating</h3>
                            <button wire:click="toggleCollectionChannelRating" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="from-group">
                                <label for="Ecollection_channel_rating_from" class="form-label">Rating from (0-10)</label>
                                <input name="Ecollection_channel_rating_from" type="number" step="0.1" min="0" max="10" class="form-control mt-2 w-full" wire:model.defer="Ecollection_channel_rating_from">
                            </div>
                            <div class="from-group">
                                <label for="Ecollection_channel_rating_to" class="form-label">Rating to (0-10)</label>
                                <input name="Ecollection_channel_rating_to" type="number" step="0.1" min="0" max="10" class="form-control mt-2 w-full" wire:model.defer="Ecollection_channel_rating_to">
                            </div>
                        </div>
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setCollectionChannelRating" class="btn inline-flex justify-center text-white bg-black-500">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Policy Conditions Rating Filter Modal -->
    @if ($policyConditionsRatingSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white">Policy Conditions Rating</h3>
                            <button wire:click="togglePolicyConditionsRating" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="from-group">
                                <label for="Epolicy_conditions_rating_from" class="form-label">Rating from (0-10)</label>
                                <input name="Epolicy_conditions_rating_from" type="number" step="0.1" min="0" max="10" class="form-control mt-2 w-full" wire:model.defer="Epolicy_conditions_rating_from">
                            </div>
                            <div class="from-group">
                                <label for="Epolicy_conditions_rating_to" class="form-label">Rating to (0-10)</label>
                                <input name="Epolicy_conditions_rating_to" type="number" step="0.1" min="0" max="10" class="form-control mt-2 w-full" wire:model.defer="Epolicy_conditions_rating_to">
                            </div>
                        </div>
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setPolicyConditionsRating" class="btn inline-flex justify-center text-white bg-black-500">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Provider Rating Filter Modal -->
    @if ($providerRatingSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white">Provider Rating</h3>
                            <button wire:click="toggleProviderRating" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="from-group">
                                <label for="Eprovider_rating_from" class="form-label">Rating from (0-10)</label>
                                <input name="Eprovider_rating_from" type="number" step="0.1" min="0" max="10" class="form-control mt-2 w-full" wire:model.defer="Eprovider_rating_from">
                            </div>
                            <div class="from-group">
                                <label for="Eprovider_rating_to" class="form-label">Rating to (0-10)</label>
                                <input name="Eprovider_rating_to" type="number" step="0.1" min="0" max="10" class="form-control mt-2 w-full" wire:model.defer="Eprovider_rating_to">
                            </div>
                        </div>
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setProviderRating" class="btn inline-flex justify-center text-white bg-black-500">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Claims Specialist Rating Filter Modal -->
    @if ($claimsSpecialistRatingSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white">Claims Specialist Rating</h3>
                            <button wire:click="toggleClaimsSpecialistRating" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="from-group">
                                <label for="Eclaims_specialist_rating_from" class="form-label">Rating from (0-10)</label>
                                <input name="Eclaims_specialist_rating_from" type="number" step="0.1" min="0" max="10" class="form-control mt-2 w-full" wire:model.defer="Eclaims_specialist_rating_from">
                            </div>
                            <div class="from-group">
                                <label for="Eclaims_specialist_rating_to" class="form-label">Rating to (0-10)</label>
                                <input name="Eclaims_specialist_rating_to" type="number" step="0.1" min="0" max="10" class="form-control mt-2 w-full" wire:model.defer="Eclaims_specialist_rating_to">
                            </div>
                        </div>
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setClaimsSpecialistRating" class="btn inline-flex justify-center text-white bg-black-500">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Wise Rating Filter Modal -->
    @if ($wiseRatingSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white">Wise Rating</h3>
                            <button wire:click="toggleWiseRating" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="from-group">
                                <label for="Ewise_rating_from" class="form-label">Rating from (0-10)</label>
                                <input name="Ewise_rating_from" type="number" step="0.1" min="0" max="10" class="form-control mt-2 w-full" wire:model.defer="Ewise_rating_from">
                            </div>
                            <div class="from-group">
                                <label for="Ewise_rating_to" class="form-label">Rating to (0-10)</label>
                                <input name="Ewise_rating_to" type="number" step="0.1" min="0" max="10" class="form-control mt-2 w-full" wire:model.defer="Ewise_rating_to">
                            </div>
                        </div>
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setWiseRating" class="btn inline-flex justify-center text-white bg-black-500">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- No Answer Filter Modal -->
    @if ($noAnswerSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white">Call Status</h3>
                            <button wire:click="toggleNoAnswer" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="from-group">
                                <label for="Eno_answer" class="form-label">Call Status</label>
                                <select name="Eno_answer" class="form-control mt-2 w-full" wire:model.defer="Eno_answer">
                                    <option value="">-- Select Status --</option>
                                    <option value="__null__">Not Yet Called</option>
                                    <option value="0">No Answer</option>
                                    <option value="1">Answered</option>
                                    <option value="2">Sent WhatsApp</option>
                                    <option value="3">Wrong Number</option>
                                    <option value="4">Callback</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setNoAnswerFilter" class="btn inline-flex justify-center text-white bg-black-500">Apply</button>
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
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white">Set Ratings & Comments</h3>
                            <button wire:click="closeRatingsModal" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6 space-y-4 max-h-96 overflow-y-auto">
                            @if ($selectedReview)
                                <div class="mb-4 p-3 bg-slate-100 rounded-lg">
                                    <h4 class="font-medium text-slate-700">{{ $selectedReview->title }}</h4>
                                    <p class="text-sm text-slate-600">{{ $selectedReview->desc }}</p>
                                </div>
                            @endif

                            @if ($selectedReview && $selectedReview->is_claim_review)
                                <!-- Claim Review Sections -->

                                <!-- Insurance Company Section -->
                                <div class="border-b border-slate-200 pb-4 mb-4">
                                    <h5 class="text-lg font-medium text-slate-700 mb-3">Insurance Company</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="from-group">
                                            <label for="form_insurance_company_rating" class="form-label">Rating
                                                (0-10)</label>
                                            <input type="number" step="0.1" min="0" max="10"
                                                class="form-control mt-2 w-full"
                                                wire:model.defer="form_insurance_company_rating"
                                                placeholder="e.g. 8.5">
                                        </div>
                                        <div class="from-group">
                                            <label for="form_insurance_company_comment"
                                                class="form-label">Comment</label>
                                            <textarea class="form-control mt-2 w-full" rows="2" wire:model.defer="form_insurance_company_comment"
                                                placeholder="Enter feedback about the insurance company..."></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Provider Section -->
                                <div class="border-b border-slate-200 pb-4 mb-4">
                                    <h5 class="text-lg font-medium text-slate-700 mb-3">Provider</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="from-group">
                                            <label for="form_provider_rating" class="form-label">Rating (0-10)</label>
                                            <input type="number" step="0.1" min="0" max="10"
                                                class="form-control mt-2 w-full"
                                                wire:model.defer="form_provider_rating" placeholder="e.g. 9.0">
                                        </div>
                                        <div class="from-group">
                                            <label for="form_provider_comment" class="form-label">Comment</label>
                                            <textarea class="form-control mt-2 w-full" rows="2" wire:model.defer="form_provider_comment"
                                                placeholder="Enter feedback about the provider..."></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Claims Specialist Section -->
                                <div class="border-b border-slate-200 pb-4 mb-4">
                                    <h5 class="text-lg font-medium text-slate-700 mb-3">Claims Specialist</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="from-group">
                                            <label for="form_claims_specialist_rating" class="form-label">Rating
                                                (0-10)</label>
                                            <input type="number" step="0.1" min="0" max="10"
                                                class="form-control mt-2 w-full"
                                                wire:model.defer="form_claims_specialist_rating"
                                                placeholder="e.g. 8.0">
                                        </div>
                                        <div class="from-group">
                                            <label for="form_claims_specialist_comment"
                                                class="form-label">Comment</label>
                                            <textarea class="form-control mt-2 w-full" rows="2" wire:model.defer="form_claims_specialist_comment"
                                                placeholder="Enter feedback about the claims specialist..."></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Wise Section -->
                                <div class="border-b border-slate-200 pb-4 mb-4">
                                    <h5 class="text-lg font-medium text-slate-700 mb-3">Wise</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="from-group">
                                            <label for="form_wise_rating" class="form-label">Rating (0-10)</label>
                                            <input type="number" step="0.1" min="0" max="10"
                                                class="form-control mt-2 w-full" wire:model.defer="form_wise_rating"
                                                placeholder="e.g. 7.5">
                                        </div>
                                        <div class="from-group">
                                            <label for="form_wise_comment" class="form-label">Comment</label>
                                            <textarea class="form-control mt-2 w-full" rows="2" wire:model.defer="form_wise_comment"
                                                placeholder="Enter feedback about Wise..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Sold Policy Review Sections -->

                                <!-- Employee Rating Section -->
                                <div class="border-b border-slate-200 pb-4 mb-4">
                                    <h5 class="text-lg font-medium text-slate-700 mb-3">Employee Performance</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="from-group">
                                            <label for="form_employee_rating" class="form-label">Rating (0-10)</label>
                                            <input type="number" step="0.1" min="0" max="10"
                                                class="form-control mt-2 w-full"
                                                wire:model.defer="form_employee_rating" placeholder="e.g. 8.5">
                                        </div>
                                        <div class="from-group">
                                            <label for="form_employee_comment" class="form-label">Comment</label>
                                            <textarea class="form-control mt-2 w-full" rows="2" wire:model.defer="form_employee_comment"
                                                placeholder="Enter feedback about the employee..."></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Policy Conditions Section -->
                                <div class="border-b border-slate-200 pb-4 mb-4">
                                    <h5 class="text-lg font-medium text-slate-700 mb-3">Policy Conditions</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="from-group">
                                            <label for="form_policy_conditions_rating" class="form-label">Rating
                                                (0-10)</label>
                                            <input type="number" step="0.1" min="0" max="10"
                                                class="form-control mt-2 w-full"
                                                wire:model.defer="form_policy_conditions_rating"
                                                placeholder="e.g. 9.0">
                                        </div>
                                        <div class="from-group">
                                            <label for="form_policy_conditions_comment"
                                                class="form-label">Comment</label>
                                            <textarea class="form-control mt-2 w-full" rows="2" wire:model.defer="form_policy_conditions_comment"
                                                placeholder="Enter feedback about policy conditions..."></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Service Quality Section -->
                                <div class="border-b border-slate-200 pb-4 mb-4">
                                    <h5 class="text-lg font-medium text-slate-700 mb-3">Service Quality</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="from-group">
                                            <label for="form_service_quality_rating" class="form-label">Rating
                                                (0-10)</label>
                                            <input type="number" step="0.1" min="0" max="10"
                                                class="form-control mt-2 w-full"
                                                wire:model.defer="form_service_quality_rating" placeholder="e.g. 8.0">
                                        </div>
                                        <div class="from-group">
                                            <label for="form_service_quality_comment"
                                                class="form-label">Comment</label>
                                            <textarea class="form-control mt-2 w-full" rows="2" wire:model.defer="form_service_quality_comment"
                                                placeholder="Enter feedback about service quality..."></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pricing Section -->
                                <div class="border-b border-slate-200 pb-4 mb-4">
                                    <h5 class="text-lg font-medium text-slate-700 mb-3">Pricing</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="from-group">
                                            <label for="form_pricing_rating" class="form-label">Rating (0-10)</label>
                                            <input type="number" step="0.1" min="0" max="10"
                                                class="form-control mt-2 w-full"
                                                wire:model.defer="form_pricing_rating" placeholder="e.g. 7.5">
                                        </div>
                                        <div class="from-group">
                                            <label for="form_pricing_comment" class="form-label">Comment</label>
                                            <textarea class="form-control mt-2 w-full" rows="2" wire:model.defer="form_pricing_comment"
                                                placeholder="Enter feedback about pricing..."></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Processing Time Section -->
                                <div class="border-b border-slate-200 pb-4 mb-4">
                                    <h5 class="text-lg font-medium text-slate-700 mb-3">Processing Time</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="from-group">
                                            <label for="form_processing_time_rating" class="form-label">Rating
                                                (0-10)</label>
                                            <input type="number" step="0.1" min="0" max="10"
                                                class="form-control mt-2 w-full"
                                                wire:model.defer="form_processing_time_rating" placeholder="e.g. 8.0">
                                        </div>
                                        <div class="from-group">
                                            <label for="form_processing_time_comment"
                                                class="form-label">Comment</label>
                                            <textarea class="form-control mt-2 w-full" rows="2" wire:model.defer="form_processing_time_comment"
                                                placeholder="Enter feedback about processing time..."></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Collection Channel Effectiveness Section -->
                                <div class="border-b border-slate-200 pb-4 mb-4">
                                    <h5 class="text-lg font-medium text-slate-700 mb-3">Collection Channel
                                        Effectiveness</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="from-group">
                                            <label for="form_collection_channel_rating" class="form-label">Rating
                                                (0-10)</label>
                                            <input type="number" step="0.1" min="0" max="10"
                                                class="form-control mt-2 w-full"
                                                wire:model.defer="form_collection_channel_rating"
                                                placeholder="e.g. 9.0">
                                        </div>
                                        <div class="from-group">
                                            <label for="form_collection_channel_comment"
                                                class="form-label">Comment</label>
                                            <textarea class="form-control mt-2 w-full" rows="2" wire:model.defer="form_collection_channel_comment"
                                                placeholder="Enter feedback about collection channel..."></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Suggestions Section -->
                                <div class="border-b border-slate-200 pb-4 mb-4">
                                    <h5 class="text-lg font-medium text-slate-700 mb-3">Suggestions</h5>
                                    <div class="from-group">
                                        <label for="form_suggestions" class="form-label">Suggestions for
                                            Improvement</label>
                                        <textarea class="form-control mt-2 w-full" rows="3" wire:model.defer="form_suggestions"
                                            placeholder="Enter your suggestions for improvement..."></textarea>
                                    </div>
                                </div>

                                <!-- Referral Section -->
                                <div>
                                    <h5 class="text-lg font-medium text-slate-700 mb-3">Referral</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="from-group">
                                            <label for="form_is_referred" class="form-label">Would you refer our
                                                service?</label>
                                            <select class="form-control mt-2 w-full"
                                                wire:model.defer="form_is_referred">
                                                <option value="">Select...</option>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>
                                        <div class="from-group">
                                            <label for="form_referral_comment" class="form-label">Referral
                                                Comment</label>
                                            <textarea class="form-control mt-2 w-full" rows="2" wire:model.defer="form_referral_comment"
                                                placeholder="Enter referral comment..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="closeRatingsModal"
                                class="btn inline-flex justify-center btn-outline-secondary">
                                Cancel
                            </button>
                            @if ($selectedReview && $selectedReview->is_claim_review)
                                <button wire:click="setClaimRatingsAndComments"
                                    class="btn inline-flex justify-center text-white bg-black-500">
                                    <span wire:loading.remove wire:target="setClaimRatingsAndComments">Save Claim
                                        Ratings</span>
                                    <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                        wire:loading wire:target="setClaimRatingsAndComments"
                                        icon="line-md:loading-twotone-loop"></iconify-icon>
                                </button>
                            @else
                                <button wire:click="setRatingsAndComments"
                                    class="btn inline-flex justify-center text-white bg-black-500">
                                    <span wire:loading.remove wire:target="setRatingsAndComments">Save Ratings</span>
                                    <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                        wire:loading wire:target="setRatingsAndComments"
                                        icon="line-md:loading-twotone-loop"></iconify-icon>
                                </button>
                            @endif
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
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-warning-500">
                            <h3 class="text-xl font-medium text-white">Manager Review</h3>
                            <button wire:click="closeManagerModal" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6 space-y-4">
                            @if ($selectedReview)
                                <div class="mb-4 p-3 bg-orange-100 rounded-lg">
                                    <h4 class="font-medium text-slate-700">{{ $selectedReview->title }}</h4>
                                    <p class="text-sm text-slate-600">{{ $selectedReview->desc }}</p>
                                    <div class="mt-2 grid grid-cols-3 gap-2 text-sm">
                                        <div>
                                            <span class="font-medium">Employee:</span>
                                            <span
                                                class="{{ $selectedReview->employee_rating < 8 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $selectedReview->employee_rating }}/10
                                            </span>
                                        </div>
                                        <div>
                                            <span class="font-medium">Policy Conditions:</span>
                                            <span
                                                class="{{ $selectedReview->policy_conditions_rating < 8 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $selectedReview->policy_conditions_rating }}/10
                                            </span>
                                        </div>
                                        <div>
                                            <span class="font-medium">Service Quality:</span>
                                            <span
                                                class="{{ $selectedReview->service_quality_rating < 8 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $selectedReview->service_quality_rating }}/10
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Manager Comment Section -->
                            <div class="from-group">
                                <label for="manager_comment" class="form-label">Manager Comment</label>
                                <textarea class="form-control mt-2 w-full" rows="4" wire:model.defer="manager_comment"
                                    placeholder="Enter your manager review comment..."></textarea>
                                <small class="text-slate-500">Optional: Add your review and feedback about this
                                    review.</small>
                            </div>
                        </div>
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="closeManagerModal"
                                class="btn inline-flex justify-center btn-outline-secondary">
                                Cancel
                            </button>
                            <button wire:click="markAsManagerReviewed"
                                class="btn inline-flex justify-center text-white bg-warning-500">
                                <span wire:loading.remove wire:target="markAsManagerReviewed">Mark as Manager
                                    Reviewed</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="markAsManagerReviewed"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Claim Manager Review Modal -->
    @if ($showClaimManagerModal)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-warning-500">
                            <h3 class="text-xl font-medium text-white">Claim Manager Review</h3>
                            <button wire:click="closeClaimManagerModal" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6 space-y-4">
                            @if ($selectedReview)
                                <div class="mb-4 p-3 bg-orange-100 rounded-lg">
                                    <h4 class="font-medium text-slate-700">{{ $selectedReview->title }}</h4>
                                    <p class="text-sm text-slate-600">{{ $selectedReview->desc }}</p>
                                    <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
                                        <div>
                                            <span class="font-medium">Insurance Company:</span>
                                            <span
                                                class="{{ $selectedReview->insurance_company_rating < 8 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $selectedReview->insurance_company_rating }}/10
                                            </span>
                                        </div>
                                        <div>
                                            <span class="font-medium">Provider:</span>
                                            <span
                                                class="{{ $selectedReview->provider_rating < 8 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $selectedReview->provider_rating }}/10
                                            </span>
                                        </div>
                                        <div>
                                            <span class="font-medium">Claims Specialist:</span>
                                            <span
                                                class="{{ $selectedReview->claims_specialist_rating < 8 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $selectedReview->claims_specialist_rating }}/10
                                            </span>
                                        </div>
                                        <div>
                                            <span class="font-medium">Wise:</span>
                                            <span
                                                class="{{ $selectedReview->wise_rating < 8 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $selectedReview->wise_rating }}/10
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Claim Manager Comment Section -->
                            <div class="from-group">
                                <label for="claim_manager_comment" class="form-label">Claim Manager Comment</label>
                                <textarea class="form-control mt-2 w-full" rows="4" wire:model.defer="claim_manager_comment"
                                    placeholder="Enter your claim manager review comment..."></textarea>
                                <small class="text-slate-500">Optional: Add your review and feedback about this claim
                                    review.</small>
                            </div>
                        </div>
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="closeClaimManagerModal"
                                class="btn inline-flex justify-center btn-outline-secondary">
                                Cancel
                            </button>
                            <button wire:click="markAsClaimManagerReviewed"
                                class="btn inline-flex justify-center text-white bg-warning-500">
                                <span wire:loading.remove wire:target="markAsClaimManagerReviewed">Mark as Claim
                                    Manager Reviewed</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="markAsClaimManagerReviewed"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- No Answer Modal -->
    @if ($showNoAnswerModal)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-blue-500">
                            <h3 class="text-xl font-medium text-white">Mark Call Status</h3>
                            <button wire:click="closeNoAnswerModal" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6 space-y-4">
                            @if ($selectedReview)
                                <div class="mb-4 p-3 bg-red-100 rounded-lg">
                                    <h4 class="font-medium text-slate-700">{{ $selectedReview->title }}</h4>
                                    <p class="text-sm text-slate-600">{{ $selectedReview->desc }}</p>
                                    <div class="mt-2 text-sm">
                                        <span class="font-medium">Current Status:</span>
                                        @if ($selectedReview->no_answer === 0)
                                            <span class="text-red-600">No Answer</span>
                                        @elseif ($selectedReview->no_answer === 1)
                                            <span class="text-green-600">Answered</span>
                                        @elseif ($selectedReview->no_answer === 2)
                                            <span class="text-blue-600">Sent WhatsApp</span>
                                        @elseif ($selectedReview->no_answer === 3)
                                            <span class="text-orange-600">Wrong Number</span>
                                        @elseif ($selectedReview->no_answer === 4)
                                            <span class="text-purple-600">Callback</span>
                                        @else
                                            <span class="text-slate-500">Not Yet Called</span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="text-center">
                                <p class="text-slate-600 mb-4">
                                    Choose the call status for this review:
                                </p>
                                <div class="grid grid-cols-2 gap-3">
                                    <button wire:click="setNoAnswerFlag(null)"
                                        class="btn {{ $selectedReview && $selectedReview->no_answer === null ? 'bg-slate-500 text-white' : 'btn-outline-secondary' }} text-sm">
                                        Not Yet Called
                                    </button>
                                    <button wire:click="setNoAnswerFlag(1)"
                                        class="btn {{ $selectedReview && $selectedReview->no_answer === 1 ? 'bg-green-500 text-white' : 'btn-outline-secondary' }} text-sm">
                                        Answered
                                    </button>
                                    <button wire:click="setNoAnswerFlag(0)"
                                        class="btn {{ $selectedReview && $selectedReview->no_answer === 0 ? 'bg-red-500 text-white' : 'btn-outline-secondary' }} text-sm">
                                        No Answer
                                    </button>
                                    <button wire:click="setNoAnswerFlag(2)"
                                        class="btn {{ $selectedReview && $selectedReview->no_answer === 2 ? 'bg-blue-500 text-white' : 'btn-outline-secondary' }} text-sm">
                                        Sent WhatsApp
                                    </button>
                                    <button wire:click="setNoAnswerFlag(3)"
                                        class="btn {{ $selectedReview && $selectedReview->no_answer === 3 ? 'bg-orange-500 text-white' : 'btn-outline-secondary' }} text-sm">
                                        Wrong Number
                                    </button>
                                    <button wire:click="setNoAnswerFlag(4)"
                                        class="btn {{ $selectedReview && $selectedReview->no_answer === 4 ? 'bg-purple-500 text-white' : 'btn-outline-secondary' }} text-sm">
                                        Callback
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="closeNoAnswerModal"
                                class="btn inline-flex justify-center btn-outline-secondary">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Review Info Modal -->
    @if ($showInfoModal && $selectedReview)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none max-w-4xl">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-slate-500">
                            <h3 class="text-xl font-medium text-white">Review Information</h3>
                            <button wire:click="closeInfoModal" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6 space-y-6 max-h-96 overflow-y-auto">
                            <!-- Basic Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="font-semibold text-slate-700 mb-2">Basic Information</h4>
                                    <div class="space-y-2 text-sm">
                                        <div><span class="font-medium">ID:</span> {{ $selectedReview->id }}</div>
                                        <div><span class="font-medium">Title:</span> {{ $selectedReview->title }}
                                        </div>
                                        <div><span class="font-medium">Type:</span>
                                            {{ class_basename($selectedReview->reviewable_type) }}</div>
                                        <div><span class="font-medium">Assignee:</span>
                                            {{ $selectedReview->assignee?->full_name ?? 'Not assigned' }}</div>
                                        <div><span class="font-medium">Status:</span>
                                            <span
                                                class="badge {{ $selectedReview->is_reviewed ? 'bg-green-500' : 'bg-yellow-500' }} text-white px-2 py-1 rounded">
                                                {{ $selectedReview->is_reviewed ? 'Reviewed' : 'Not Reviewed' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="font-semibold text-slate-700 mb-2">Timestamps</h4>
                                    <div class="space-y-2 text-sm">
                                        <div><span class="font-medium">Created:</span>
                                            {{ $selectedReview->created_at->format('d/m/Y H:i') }}</div>
                                        <div><span class="font-medium">Updated:</span>
                                            {{ $selectedReview->updated_at->format('d/m/Y H:i') }}</div>
                                        @if ($selectedReview->reviewed_at)
                                            <div><span class="font-medium">Reviewed:</span>
                                                {{ $selectedReview->reviewed_at->format('d/m/Y H:i') }}</div>
                                        @endif
                                        @if ($selectedReview->reviewedBy)
                                            <div><span class="font-medium">Reviewed By:</span>
                                                {{ $selectedReview->reviewedBy->full_name }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <h4 class="font-semibold text-slate-700 mb-2">Description</h4>
                                <p class="text-sm text-slate-600 bg-slate-50 p-3 rounded">{{ $selectedReview->desc }}
                                </p>
                            </div>

                            <!-- Claim Information (if applicable) -->
                            @if ($selectedReview->reviewable_type === 'App\Models\Tasks\Task' && $selectedReview->reviewable)
                                <div>
                                    <h4 class="font-semibold text-slate-700 mb-2">Claim Information</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <div><span class="font-medium">Claim ID:</span>
                                                {{ $selectedReview->reviewable->id }}</div>
                                            <div><span class="font-medium">Claim Title:</span>
                                                {{ $selectedReview->reviewable->title }}</div>
                                            <div><span class="font-medium">Claim Status:</span>
                                                <span class="badge bg-blue-500 text-white px-2 py-1 rounded text-xs">
                                                    {{ ucfirst(str_replace('_', ' ', $selectedReview->reviewable->status)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <div><span class="font-medium">Opened By:</span>
                                                {{ $selectedReview->reviewable->open_by?->full_name ?? 'Unknown' }}
                                            </div>
                                            <div><span class="font-medium">Assigned To:</span>
                                                {{ $selectedReview->reviewable->assigned_to?->full_name ?? 'Not assigned' }}
                                            </div>
                                            @if ($selectedReview->reviewable->due)
                                                <div><span class="font-medium">Due Date:</span>
                                                    {{ \Carbon\Carbon::parse($selectedReview->reviewable->due)->format('d/m/Y H:i') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Ratings Section -->
                            <div>
                                <h4 class="font-semibold text-slate-700 mb-3">Client Ratings & Comments</h4>
                                @if ($selectedReview->is_claim_review)
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Insurance Company Rating -->
                                        <div class="border rounded p-3">
                                            <div class="font-medium text-sm mb-1">Insurance Company</div>
                                            <div
                                                class="text-lg font-bold {{ $selectedReview->insurance_company_rating < 8 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $selectedReview->insurance_company_rating }}/10
                                            </div>
                                            @if ($selectedReview->insurance_company_comment)
                                                <div class="text-xs text-slate-600 mt-1">
                                                    {{ $selectedReview->insurance_company_comment }}</div>
                                            @endif
                                        </div>

                                        <!-- Provider Rating -->
                                        <div class="border rounded p-3">
                                            <div class="font-medium text-sm mb-1">Provider</div>
                                            <div
                                                class="text-lg font-bold {{ $selectedReview->provider_rating < 8 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $selectedReview->provider_rating }}/10
                                            </div>
                                            @if ($selectedReview->provider_comment)
                                                <div class="text-xs text-slate-600 mt-1">
                                                    {{ $selectedReview->provider_comment }}</div>
                                            @endif
                                        </div>

                                        <!-- Claims Specialist Rating -->
                                        <div class="border rounded p-3">
                                            <div class="font-medium text-sm mb-1">Claims Specialist</div>
                                            <div
                                                class="text-lg font-bold {{ $selectedReview->claims_specialist_rating < 8 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $selectedReview->claims_specialist_rating }}/10
                                            </div>
                                            @if ($selectedReview->claims_specialist_comment)
                                                <div class="text-xs text-slate-600 mt-1">
                                                    {{ $selectedReview->claims_specialist_comment }}</div>
                                            @endif
                                        </div>

                                        <!-- Wise Rating -->
                                        <div class="border rounded p-3">
                                            <div class="font-medium text-sm mb-1">Wise</div>
                                            <div
                                                class="text-lg font-bold {{ $selectedReview->wise_rating < 8 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $selectedReview->wise_rating }}/10
                                            </div>
                                            @if ($selectedReview->wise_comment)
                                                <div class="text-xs text-slate-600 mt-1">
                                                    {{ $selectedReview->wise_comment }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Employee Rating -->
                                        <div class="border rounded p-3">
                                            <div class="font-medium text-sm mb-1">Employee Performance</div>
                                            <div
                                                class="text-lg font-bold {{ $selectedReview->employee_rating < 8 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $selectedReview->employee_rating }}/10
                                            </div>
                                            @if ($selectedReview->client_employee_comment)
                                                <div class="text-xs text-slate-600 mt-1">
                                                    {{ $selectedReview->client_employee_comment }}</div>
                                            @endif
                                        </div>

                                        <!-- Policy Conditions Rating -->
                                        <div class="border rounded p-3">
                                            <div class="font-medium text-sm mb-1">Policy Conditions</div>
                                            <div
                                                class="text-lg font-bold {{ $selectedReview->policy_conditions_rating < 8 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $selectedReview->policy_conditions_rating }}/10
                                            </div>
                                            @if ($selectedReview->policy_conditions_comment)
                                                <div class="text-xs text-slate-600 mt-1">
                                                    {{ $selectedReview->policy_conditions_comment }}</div>
                                            @endif
                                        </div>

                                        <!-- Service Quality Rating -->
                                        <div class="border rounded p-3">
                                            <div class="font-medium text-sm mb-1">Service Quality</div>
                                            <div
                                                class="text-lg font-bold {{ $selectedReview->service_quality_rating < 8 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $selectedReview->service_quality_rating }}/10
                                            </div>
                                            @if ($selectedReview->service_quality_comment)
                                                <div class="text-xs text-slate-600 mt-1">
                                                    {{ $selectedReview->service_quality_comment }}</div>
                                            @endif
                                        </div>

                                        <!-- Pricing Rating -->
                                        <div class="border rounded p-3">
                                            <div class="font-medium text-sm mb-1">Pricing</div>
                                            <div
                                                class="text-lg font-bold {{ $selectedReview->pricing_rating < 8 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $selectedReview->pricing_rating }}/10
                                            </div>
                                            @if ($selectedReview->pricing_comment)
                                                <div class="text-xs text-slate-600 mt-1">
                                                    {{ $selectedReview->pricing_comment }}</div>
                                            @endif
                                        </div>

                                        <!-- Processing Time Rating -->
                                        <div class="border rounded p-3">
                                            <div class="font-medium text-sm mb-1">Processing Time</div>
                                            <div
                                                class="text-lg font-bold {{ $selectedReview->processing_time_rating < 8 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $selectedReview->processing_time_rating }}/10
                                            </div>
                                            @if ($selectedReview->processing_time_comment)
                                                <div class="text-xs text-slate-600 mt-1">
                                                    {{ $selectedReview->processing_time_comment }}</div>
                                            @endif
                                        </div>

                                        <!-- Collection Channel Rating -->
                                        <div class="border rounded p-3">
                                            <div class="font-medium text-sm mb-1">Collection Channel</div>
                                            <div
                                                class="text-lg font-bold {{ $selectedReview->collection_channel_rating < 8 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $selectedReview->collection_channel_rating }}/10
                                            </div>
                                            @if ($selectedReview->collection_channel_comment)
                                                <div class="text-xs text-slate-600 mt-1">
                                                    {{ $selectedReview->collection_channel_comment }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Additional Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="font-semibold text-slate-700 mb-2">Additional Information</h4>
                                    <div class="space-y-2 text-sm">
                                        @if ($selectedReview->suggestions)
                                            <div><span class="font-medium">Suggestions:</span>
                                                {{ $selectedReview->suggestions }}</div>
                                        @endif
                                        <div><span class="font-medium">Would Refer:</span>
                                            @if ($selectedReview->is_referred === true)
                                                <span class="text-green-600">Yes</span>
                                            @elseif($selectedReview->is_referred === false)
                                                <span class="text-red-600">No</span>
                                            @else
                                                <span class="text-slate-400">Not specified</span>
                                            @endif
                                        </div>
                                        @if ($selectedReview->referral_comment)
                                            <div><span class="font-medium">Referral Comment:</span>
                                                {{ $selectedReview->referral_comment }}</div>
                                        @endif
                                        <div><span class="font-medium">Call Status:</span>
                                            @if ($selectedReview->no_answer === 0)
                                                <span class="badge bg-red-500 text-white px-2 py-1 rounded text-xs">
                                                    No Answer
                                                </span>
                                            @elseif ($selectedReview->no_answer === 1)
                                                <span class="badge bg-green-500 text-white px-2 py-1 rounded text-xs">
                                                    Answered
                                                </span>
                                            @elseif ($selectedReview->no_answer === 2)
                                                <span class="badge bg-blue-500 text-white px-2 py-1 rounded text-xs">
                                                    Sent WhatsApp
                                                </span>
                                            @elseif ($selectedReview->no_answer === 3)
                                                <span class="badge bg-orange-500 text-white px-2 py-1 rounded text-xs">
                                                    Wrong Number
                                                </span>
                                            @elseif ($selectedReview->no_answer === 4)
                                                <span class="badge bg-purple-500 text-white px-2 py-1 rounded text-xs">
                                                    Callback
                                                </span>
                                            @else
                                                <span class="badge bg-slate-500 text-white px-2 py-1 rounded text-xs">
                                                    Not Yet Called
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    @if ($selectedReview->is_claim_review)
                                        <h4 class="font-semibold text-slate-700 mb-2">Claim Manager Review Status</h4>
                                        <div class="space-y-2 text-sm">
                                            <div><span class="font-medium">Needs Claim Manager Review:</span>
                                                <span
                                                    class="badge {{ $selectedReview->need_claim_manager_review ? 'bg-warning-500' : 'bg-green-500' }} text-white px-2 py-1 rounded text-xs">
                                                    {{ $selectedReview->need_claim_manager_review ? 'Yes' : 'No' }}
                                                </span>
                                            </div>
                                            <div><span class="font-medium">Claim Manager Reviewed:</span>
                                                <span
                                                    class="badge {{ $selectedReview->is_claim_manager_reviewed ? 'bg-green-500' : 'bg-slate-500' }} text-white px-2 py-1 rounded text-xs">
                                                    {{ $selectedReview->is_claim_manager_reviewed ? 'Yes' : 'No' }}
                                                </span>
                                            </div>
                                            @if ($selectedReview->claim_manager_comment)
                                                <div><span class="font-medium">Claim Manager Comment:</span>
                                                    {{ $selectedReview->claim_manager_comment }}</div>
                                            @endif
                                        </div>
                                    @else
                                        <h4 class="font-semibold text-slate-700 mb-2">Manager Review Status</h4>
                                        <div class="space-y-2 text-sm">
                                            <div><span class="font-medium">Needs Manager Review:</span>
                                                <span
                                                    class="badge {{ $selectedReview->need_manager_review ? 'bg-warning-500' : 'bg-green-500' }} text-white px-2 py-1 rounded text-xs">
                                                    {{ $selectedReview->need_manager_review ? 'Yes' : 'No' }}
                                                </span>
                                            </div>
                                            <div><span class="font-medium">Manager Reviewed:</span>
                                                <span
                                                    class="badge {{ $selectedReview->is_manager_reviewed ? 'bg-green-500' : 'bg-slate-500' }} text-white px-2 py-1 rounded text-xs">
                                                    {{ $selectedReview->is_manager_reviewed ? 'Yes' : 'No' }}
                                                </span>
                                            </div>
                                            @if ($selectedReview->manager_comment)
                                                <div><span class="font-medium">Manager Comment:</span>
                                                    {{ $selectedReview->manager_comment }}</div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="closeInfoModal"
                                class="btn inline-flex justify-center btn-outline-secondary">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Contacts Modal -->
    @if ($showContactsModal && $selectedReview)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none max-w-2xl">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-blue-500">
                            <h3 class="text-xl font-medium text-white">Contact Information</h3>
                            <button wire:click="closeContactsModal" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6 space-y-4 max-h-96 overflow-y-auto">
                            @if ($selectedReviewContacts && count($selectedReviewContacts) > 0)
                                @foreach ($selectedReviewContacts as $contact)
                                    <div class="border rounded-lg p-4 bg-slate-50">
                                        <div class="flex items-center justify-between mb-3">
                                            <a href="{{ $contact['client_type'] === 'customer' ? route('customers.show', $contact['client_id']) : route('corporates.show', $contact['client_id']) }}"
                                                class="font-semibold text-blue-600 hover:text-blue-800 hover:underline cursor-pointer">
                                                {{ $contact['name'] }}
                                            </a>
                                            <span class="badge bg-blue-500 text-white px-2 py-1 rounded text-xs">
                                                {{ $contact['type'] }}
                                            </span>
                                        </div>

                                        <div class="space-y-2">
                                            @if ($contact['phone'])
                                                <div class="flex items-center justify-between">
                                                    <span
                                                        class="text-sm text-slate-600">{{ $contact['phone'] }}</span>
                                                    <div class="flex space-x-2">
                                                        <a href="tel:{{ $contact['phone'] }}"
                                                            class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-colors"
                                                            title="Call {{ $contact['phone'] }}">
                                                            <iconify-icon icon="heroicons:phone-20-solid"
                                                                class="text-sm"></iconify-icon>
                                                        </a>
                                                        @php
                                                            $whatsappNumber =
                                                                '2' . preg_replace('/\D/', '', $contact['phone']);
                                                            $whatsappUrl = 'https://wa.me/' . $whatsappNumber;
                                                        @endphp
                                                        <a href="{{ $whatsappUrl }}" target="_blank"
                                                            class="inline-flex items-center justify-center w-8 h-8 bg-green-500 text-white rounded-full hover:bg-green-600 transition-colors"
                                                            title="WhatsApp {{ $contact['phone'] }}">
                                                            <iconify-icon icon="logos:whatsapp-icon"
                                                                class="text-sm"></iconify-icon>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($contact['phone2'])
                                                <div class="flex items-center justify-between">
                                                    <span
                                                        class="text-sm text-slate-600">{{ $contact['phone2'] }}</span>
                                                    <div class="flex space-x-2">
                                                        <a href="tel:{{ $contact['phone2'] }}"
                                                            class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-colors"
                                                            title="Call {{ $contact['phone2'] }}">
                                                            <iconify-icon icon="heroicons:phone-20-solid"
                                                                class="text-sm"></iconify-icon>
                                                        </a>
                                                        @php
                                                            $whatsappNumber2 =
                                                                '2' . preg_replace('/\D/', '', $contact['phone2']);
                                                            $whatsappUrl2 = 'https://wa.me/' . $whatsappNumber2;
                                                        @endphp
                                                        <a href="{{ $whatsappUrl2 }}" target="_blank"
                                                            class="inline-flex items-center justify-center w-8 h-8 bg-green-500 text-white rounded-full hover:bg-green-600 transition-colors"
                                                            title="WhatsApp {{ $contact['phone2'] }}">
                                                            <iconify-icon icon="logos:whatsapp-icon"
                                                                class="text-sm"></iconify-icon>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($contact['phone3'])
                                                <div class="flex items-center justify-between">
                                                    <span
                                                        class="text-sm text-slate-600">{{ $contact['phone3'] }}</span>
                                                    <div class="flex space-x-2">
                                                        <a href="tel:{{ $contact['phone3'] }}"
                                                            class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-colors"
                                                            title="Call {{ $contact['phone3'] }}">
                                                            <iconify-icon icon="heroicons:phone-20-solid"
                                                                class="text-sm"></iconify-icon>
                                                        </a>
                                                        @php
                                                            $whatsappNumber3 =
                                                                '2' . preg_replace('/\D/', '', $contact['phone3']);
                                                            $whatsappUrl3 = 'https://wa.me/' . $whatsappNumber3;
                                                        @endphp
                                                        <a href="{{ $whatsappUrl3 }}" target="_blank"
                                                            class="inline-flex items-center justify-center w-8 h-8 bg-green-500 text-white rounded-full hover:bg-green-600 transition-colors"
                                                            title="WhatsApp {{ $contact['phone3'] }}">
                                                            <iconify-icon icon="logos:whatsapp-icon"
                                                                class="text-sm"></iconify-icon>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-8">
                                    <iconify-icon icon="heroicons:phone-x-mark-20-solid"
                                        class="text-4xl text-slate-400 mb-3"></iconify-icon>
                                    <h4 class="text-lg font-medium text-slate-600 mb-2">No Contacts Available</h4>
                                    <p class="text-sm text-slate-500">No contact information found for this review.</p>
                                </div>
                            @endif
                        </div>
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="closeContactsModal"
                                class="btn inline-flex justify-center btn-outline-secondary">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
