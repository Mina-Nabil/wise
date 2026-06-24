<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Bulk Assign Offers
            </h4>
        </div>

        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
            <a href="{{ route('offers.index') }}"
                class="btn inline-flex justify-center btn-outline-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:arrow-left-bold"></iconify-icon>
                Back to Offers
            </a>
        </div>
    </div>

    {{-- Top bar: user select + assign button --}}
    <div class="card mb-5">
        <div class="card-body p-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-7 from-group">
                    <label class="form-label">Assign selected offers to</label>
                    <select class="form-control w-full mt-2 @error('assignTo') !border-danger-500 @enderror"
                        wire:model="assignTo">
                        <option value="">Select user...</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->first_name . ' ' . $user->last_name }} ({{ ucwords($user->type) }})
                            </option>
                        @endforeach
                    </select>
                    @error('assignTo')
                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                    @enderror
                    @error('selectedOffers')
                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="md:col-span-5 flex justify-end">
                    <button wire:click="assignSelected"
                        class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                        <iconify-icon wire:loading wire:target="assignSelected" class="loading-icon text-lg ltr:mr-2 rtl:ml-2"
                            icon="line-md:loading-twotone-loop"></iconify-icon>
                        <iconify-icon wire:loading.remove wire:target="assignSelected" class="text-xl ltr:mr-2 rtl:ml-2"
                            icon="ph:user-switch-bold"></iconify-icon>
                        Assign to User
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Selected Offers card --}}
    <div class="card mb-5">
        <header class="card-header noborder">
            <h4 class="card-title">
                Selected Offers
                <span class="badge bg-primary-500 text-white ml-2">{{ count($selectedOffers) }}</span>
            </h4>
            @if (count($selectedOffers))
                <button wire:click="clearSelection" class="btn btn-sm btn-outline-danger">Clear all</button>
            @endif
        </header>
        <div class="card-body px-6 pb-6">
            @if ($selectedOffersData->isEmpty())
                <p class="text-sm text-slate-500 dark:text-slate-400">No offers selected yet. Use the checkboxes below to
                    select offers.</p>
            @else
                <div class="flex flex-wrap gap-2">
                    @foreach ($selectedOffersData as $sel)
                        <span
                            class="inline-flex items-center bg-slate-100 dark:bg-slate-700 rounded-full px-3 py-1 text-sm text-slate-700 dark:text-slate-200">
                            <b class="mr-1">#{{ $sel->id }}</b>
                            @if ($sel->client_type === 'corporate')
                                {{ $sel->client?->name }}
                            @elseif($sel->client_type === 'customer')
                                {{ $sel->client?->first_name . ' ' . $sel->client?->last_name }}
                            @endif
                            <iconify-icon wire:click="removeSelectedOffer({{ $sel->id }})"
                                class="ml-2 cursor-pointer text-danger-500 text-lg" icon="heroicons:x-mark"></iconify-icon>
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex items-center space-x-7 flex-wrap h-[30px]">
        <div class="dropdown relative">
            <button class="btn inline-flex justify-center btn-dark items-center" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                @if ($filteredStatus)
                    Status: {{ str_replace('_', ' ', $filteredStatus[0]) }}
                @else
                    Select Status
                @endif
                <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="ic:round-keyboard-arrow-down"></iconify-icon>
            </button>
            <ul
                class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                <li wire:click="filterByStatus('active')">
                    <a href="#"
                        class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                        Active
                    </a>
                </li>
                <li wire:click="filterByStatus('all')">
                    <a href="#"
                        class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                        All
                    </a>
                </li>
                @foreach ($statuses as $status)
                    <li wire:click="filterByStatus('{{ $status }}')">
                        <a href="#"
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                            {{ ucwords(str_replace('_', ' ', $status)) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="dropdown relative">
            <button class="btn inline-flex justify-center btn-dark items-center" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                @if ($lineOfBusiness)
                    {{ ucwords(str_replace('_', ' ', $lineOfBusiness)) }}
                @else
                    Line of Business
                @endif
                <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="ic:round-keyboard-arrow-down"></iconify-icon>
            </button>
            <ul
                class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                <li wire:click="$set('lineOfBusiness', '')">
                    <a href="#"
                        class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                        All
                    </a>
                </li>
                @foreach ($LINES_OF_BUSINESS as $lob)
                    <li wire:click="$set('lineOfBusiness', '{{ $lob }}')">
                        <a href="#"
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                            {{ ucwords(str_replace('_', ' ', $lob)) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="secondary-radio">
            <label class="flex items-center cursor-pointer">
                <input type="radio" class="hidden" value="all" wire:model="isRenewalCB">
                <span
                    class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                <span class="text-secondary-500 text-sm leading-6 capitalize">All</span>
            </label>
        </div>
        <div class="secondary-radio">
            <label class="flex items-center cursor-pointer">
                <input type="radio" class="hidden" value="isRenewal" wire:model="isRenewalCB">
                <span
                    class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                <span class="text-secondary-500 text-sm leading-6 capitalize">Renewal</span>
            </label>
        </div>
        <div class="secondary-radio">
            <label class="flex items-center cursor-pointer">
                <input type="radio" class="hidden" value="notRenewal" wire:model="isRenewalCB">
                <span
                    class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                <span class="text-secondary-500 text-sm leading-6 capitalize">New Business</span>
            </label>
        </div>
        <input class="form-control py-2 flatpickr flatpickr-input active w-auto ml-5" style="width:300px"
            id="range-picker" data-mode="range" value="" type="text" readonly="readonly" wire:model="dateRange">
    </div>

    <div class="card mt-5 pt-5">
        <header class="card-header cust-card-header noborder">
            <iconify-icon wire:loading class="loading-icon text-lg" icon="line-md:loading-twotone-loop"></iconify-icon>
            <input type="text" class="form-control !pl-9 mr-1 basis-1/4"
                placeholder="Search using client name, email or phone number" wire:model="search">
        </header>

        <div class="card-body px-6 pb-6">
            <div class=" -mx-6">
                <div class="overflow-x-auto -mx-6">
                    <div class="inline-block min-w-full align-middle px-5">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead
                                class=" border-t border-slate-100 dark:border-slate-800 bg-slate-200 dark:bg-slate-700">
                                <tr>
                                    <th scope="col" class=" table-th " style="width: 40px;">
                                        <span class="sr-only">Select</span>
                                    </th>
                                    <th scope="col" class=" table-th ">
                                        #
                                    </th>
                                    <th scope="col" class=" table-th " style="min-width: 200px;">
                                        Client
                                    </th>
                                    <th scope="col" class=" table-th ">
                                        Type
                                    </th>
                                    <th scope="col" class=" table-th ">
                                        Offer
                                    </th>
                                    <th scope="col" class=" table-th ">
                                        Status
                                    </th>
                                    <th scope="col" class=" table-th ">
                                        Renewal
                                    </th>
                                    <th scope="col" class=" table-th ">
                                        Value
                                    </th>
                                    <th scope="col" class=" table-th ">
                                        Assignee
                                    </th>
                                    <th scope="col" class=" table-th ">
                                        Profiles
                                    </th>
                                    <th scope="col" class=" table-th ">
                                        Creator
                                    </th>
                                    <th scope="col" class=" table-th ">
                                        Due
                                    </th>
                                    <th scope="col" class=" table-th ">
                                        Selected
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @foreach ($offers as $offer)
                                    <tr
                                        class="hover:bg-slate-200 dark:hover:bg-slate-700 {{ in_array($offer->id, $selectedOffers) ? 'bg-primary-500 bg-opacity-10' : '' }}">
                                        <td class="table-td " wire:click.stop>
                                            <input type="checkbox" class="cursor-pointer"
                                                value="{{ $offer->id }}" wire:model="selectedOffers">
                                        </td>
                                        <td class="table-td cursor-pointer"
                                            wire:click="redirectToShowPage({{ $offer->id }})">
                                            {{ $offer->id }}
                                        </td>
                                        <td class="table-td cursor-pointer"
                                            wire:click="redirectToShowPage({{ $offer->id }})">
                                            @if ($offer->client_type === 'corporate')
                                                <b>{{ $offer->client->name }}</b>
                                            @elseif($offer->client_type === 'customer')
                                                <b>{{ $offer->client->first_name . ' ' . $offer->client->middle_name . ' ' . $offer->client->last_name }}</b>
                                            @endif
                                        </td>
                                        <td class="table-td cursor-pointer"
                                            wire:click="redirectToShowPage({{ $offer->id }})">
                                            {{ $offer->client_type }}
                                        </td>
                                        <td class="table-td cursor-pointer"
                                            wire:click="redirectToShowPage({{ $offer->id }})">
                                            {{ ucwords(str_replace('_', ' ', $offer->type)) }}
                                        </td>
                                        <td class="table-td cursor-pointer"
                                            wire:click="redirectToShowPage({{ $offer->id }})">
                                            @if ($offer->status === 'new')
                                                <span class="badge bg-info-500 h-auto">
                                                    <iconify-icon
                                                        icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
                                                </span>
                                            @elseif(str_contains($offer->status, 'pending'))
                                                <span class="badge bg-warning-500 h-auto">
                                                    <iconify-icon
                                                        icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
                                                </span>
                                            @elseif(str_contains($offer->status, 'declined') || str_contains($offer->status, 'cancelled'))
                                                <span class="badge bg-danger-500 h-auto">
                                                    <iconify-icon
                                                        icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
                                                </span>
                                            @elseif($offer->status === 'approved')
                                                <span class="badge bg-success-500 h-auto">
                                                    <iconify-icon
                                                        icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
                                                </span>
                                            @endif

                                            @if ($offer->is_renewal)
                                                <span
                                                    class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize rounded-3xl"
                                                    style="vertical-align: top;">Renewal</span>
                                            @endif
                                            <small>{{ $offer->sub_status }}</small>
                                        </td>
                                        <td class="table-td cursor-pointer"
                                            wire:click="redirectToShowPage({{ $offer->id }})">
                                            <b>{{ $offer->renewal_policy }}</b>
                                        </td>
                                        <td class="table-td cursor-pointer"
                                            wire:click="redirectToShowPage({{ $offer->id }})">
                                            <b>{{ number_format($offer->item_value, 0, '.', ',') }}</b>
                                        </td>
                                        <td class="table-td cursor-pointer"
                                            wire:click="redirectToShowPage({{ $offer->id }})">
                                            {{ $offer->assignee ? ucwords($offer->assignee->first_name) . ' ' . ucwords($offer->assignee->last_name) : ($offer->assignee_type ? ucwords($offer->assignee_type) : 'No one/team assigned') }}
                                        </td>
                                        <td class="table-td cursor-pointer"
                                            wire:click="redirectToShowPage({{ $offer->id }})">
                                            {{ $offer->comm_profiles->pluck('title')->implode(', ') }}
                                        </td>
                                        <td class="table-td cursor-pointer"
                                            wire:click="redirectToShowPage({{ $offer->id }})">
                                            {{ ucwords($offer->creator->first_name) . ' ' . ucwords($offer->creator->last_name) }}
                                        </td>
                                        <td class="table-td cursor-pointer"
                                            wire:click="redirectToShowPage({{ $offer->id }})">
                                            {{ date_format(date_create($offer->due), 'Y-m-d') }}
                                        </td>
                                        <td class="table-td cursor-pointer"
                                            wire:click="redirectToShowPage({{ $offer->id }})">
                                            @if ($offer->selected_option)
                                                <div class="flex-1 text-start">
                                                    <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                        {{ $offer->selected_option?->policy?->name }}
                                                    </h4>
                                                    <div
                                                        class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                                        Gross: {{ $offer->selected_option?->gross_premium }}
                                                    </div>
                                                    <div
                                                        class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                                        Net: {{ $offer->selected_option?->net_premium }}
                                                    </div>
                                                </div>
                                            @else
                                                <span class="badge bg-danger-500 h-auto">
                                                    <iconify-icon icon="pajamas:status"></iconify-icon>&nbsp;No
                                                    selected option
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if ($offers->isEmpty())
                            <div class="card m-5 p-5">
                                <div class="card-body rounded-md bg-white dark:bg-slate-800">
                                    <div class="items-center text-center p-5">
                                        <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon></h2>
                                        <h2 class="card-title text-slate-900 dark:text-white mb-3">No offers with the
                                            applied filters</h2>
                                        <p class="card-text">Try changing the filters or search terms for this view.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{ $offers->links('vendor.livewire.bootstrap') }}
                </div>
            </div>
        </div>
    </div>
</div>
