<div>
    @if (count($selectedOptions) > 0)
        <div class="grid md:grid-cols-3 select-action-btns-container gap-2">
            <button class="btn btn-sm btn-primary float-right" wire:click="exportComparison">Export Comparison</button>
            <button class="btn btn-sm btn-success float-right" wire:click="toggleWhatsappSection"><iconify-icon
                    icon="ic:baseline-whatsapp"></iconify-icon> Send WhatAapp Message</button>
            <button class="btn btn-sm btn-dark float-right" wire:click="toggleEmailMsgSection"><iconify-icon
                    icon="ic:outline-email"></iconify-icon> Send Email</button>
        </div>
    @endif
    <div>
        <div class="max-w-screen-lg grid grid-cols-1 md:grid-cols-8 gap-5 mb-5">
            <div class="grid-cols-1 gap-5 mb-5 col-span-5">

                <div>
                    <p class="text-sm text-slate-400  font-light" wire:click="setStatus">
                        {{ ucwords($offer->client_type) }}
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <h5><b><bdi>{{ $offer->client->name }}</bdi> - #{{ $offer->id }}</b>
                            @if ($offer->status === 'new')
                                <span class="badge bg-info-500 h-auto">
                                    <iconify-icon
                                        icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
                                </span>
                            @elseif(str_contains($offer->status, 'pending'))
                                <span class="badge bg-warning-500 h-auto">
                                    <iconify-icon
                                        icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
                                    {{ $offer->sub_status ? " - $offer->sub_status" : '' }}
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
                            <span class="badge bg-secondary-500 h-auto">
                                <iconify-icon icon="mdi:category"></iconify-icon>&nbsp;
                                {{ ucwords(str_replace('_', ' ', $offer->type)) }}
                            </span>
                        </h5>
                        <div>
                            <div class="dropdown relative float-right">
                                <button
                                    class="btn btn-sm inline-flex justify-center btn-secondary items-center cursor-default relative !pr-14"
                                    type="button" id="secondarysplitDropdownMenuButton" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Actions
                                    <span
                                        class="cursor-pointer absolute  h-full ltr:right-0 rtl:left-0 px-2 flex
                                                items-center justify-center leading-none">
                                        <iconify-icon class="leading-none text-xl"
                                            icon="ic:round-keyboard-arrow-down"></iconify-icon>
                                    </span>
                                </button>
                                <ul
                                    class=" dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow
                                            z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                    @foreach ($STATUSES as $status)
                                        @if (!($status === $offer->status))
                                            @if (str_contains($status, 'insur') && !Auth::user()->can('setInsuranceStatuses', $offer))
                                                @continue
                                            @endif
                                            <li wire:click="setStatus('{{ $status }}')">
                                                <p
                                                    class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                                                    Set As {{ ucwords(str_replace('_', ' ', $status)) }}
                                                </p>
                                            </li>
                                        @endif
                                    @endforeach

                                    @if ($offer->is_renewal)
                                        <li wire:click="removeRenewal" class="cursor-pointer">
                                            <p
                                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                                    dark:hover:text-white border-t border-slate-100 dark:border-slate-800">
                                                Remove Renewal</p>
                                        </li>
                                    @else
                                        <li wire:click="openSetRenewal" class="cursor-pointer">
                                            <p
                                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                                    dark:hover:text-white border-t border-slate-100 dark:border-slate-800">
                                                Set is Renewal</p>
                                        </li>
                                    @endif
                                    @if ($offer->sold_policy_id)
                                        <li wire:click="goTo" class="cursor-pointer">
                                            <a href="{{ route('sold.policy.show', $offer->sold_policy_id) }}"
                                                target="_blank">
                                                <p
                                                    class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                                dark:hover:text-white border-t border-slate-100 dark:border-slate-800">
                                                    Open Sold Policy</p>
                                            </a>
                                        </li>
                                    @endif

                                    <li wire:click="confirmDeleteOffer">
                                        <a
                                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                                    dark:hover:text-white border-t border-slate-100 dark:border-slate-800">
                                            Delete Offer</a>
                                    </li>
                                </ul>
                            </div>
                            <a href="{{ route($offer->client_type . 's.show', $offer->client_id) }}" target="_blank">
                                <button wire:click="toggleEditInfo"
                                    class="btn inline-flex justify-center btn-secondary shadow-base2 float-right btn-sm mr-2">View
                                    {{ ucwords($offer->client_type) }}</button>
                            </a>
                        </div>
                    </div>
                    <p class="text-sm text-slate-400 font-light">
                        Created at {{ $offer->created_at->format('l d-m-Y') }}
                    </p>
                </div>

                @if ($offer->is_renewal)
                    <div
                        class="py-2 px-6 font-normal text-sm rounded-md text-success-500 border border-success-500
                                    dark:bg-slate-800 mt-2">
                        <div class="flex items-start space-x-3 rtl:space-x-reverse">
                            <div class="flex-1 font-Inter">
                                <iconify-icon class="text-lg" icon="mdi:tick-circle-outline"></iconify-icon> This Offer
                                is <b>Renewal</b>!
                                @if ($offer->renewal_sold_policy)
                                    For: {{ $offer->renewal_sold_policy->policy_number }}
                                    Expiry:
                                    {{ \Carbon\Carbon::parse($offer->renewal_sold_policy->expiry)->format('l d-m-Y') }}
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <div class="rounded-md overlay mt-5">
                    <div
                        class="card-body flex flex-col justify-center  bg-no-repeat bg-center bg-cover card p-4 active">
                        <div class="card-text flex flex-col justify-between h-full menu-open">
                            <p class="mb-2">
                                <b>Offered Item</b>
                            </p>
                            <div class="card-body flex flex-col justify-between border rounded-lg h-full menu-open p-0 mb-5"
                                style="border-color:rgb(224, 224, 224)">
                                <div class="break-words flex items-center my-1 m-4">

                                    @if ($offer->item)
                                        <h3 class="text-base capitalize py-3">
                                            <ul class="m-0 p-0 list-none">
                                                <li class="inline-block relative top-[3px] text-base font-Inter ">
                                                    {{ $offer->item->car->car_model->brand->name }}
                                                    <iconify-icon icon="heroicons-outline:chevron-right"
                                                        class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                                                </li>
                                                <li class="inline-block relative top-[3px] text-base font-Inter ">
                                                    {{ $offer->item->car->car_model->name }}
                                                    <iconify-icon icon="heroicons-outline:chevron-right"
                                                        class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                                                </li>
                                                <li
                                                    class="inline-block relative text-sm top-[3px] text-slate-500 font-Inter dark:text-white mr-5">
                                                    {{ $offer->item->car->category }} {{ $offer->item->model_year }}
                                                </li>
                                            </ul>
                                        </h3>
                                    @elseif($offer->medical_offer_clients->isNotEmpty())
                                        <h3 class="text-base capitalize py-3">
                                            Medical Offer Clients (Showing first 20)
                                        </h3>
                                    @elseif($offer->medical_offer_clients->isNotEmpty())
                                        <h3 class="text-base capitalize py-3">
                                            Application Fields
                                        </h3>
                                    @elseif($offer->item_title)
                                        <h3 class="text-base capitalize py-3">
                                            {{ $offer->item_title }}
                                        </h3>
                                    @else
                                        <h3 class="text-base capitalize py-3">
                                            {{ number_format($offer->item_value, 0, '.', ',') }}EGP
                                        </h3>
                                    @endif
                                    {{-- @if ($car->payment_frequency)
                                                <span class="badge bg-primary-500 text-primary-500 bg-opacity-30 capitalize rounded-3xl float-right">{{ $car->payment_frequency }} Payment</span>
                                            @endif --}}

                                    <div class="ml-auto">
                                        <div class="relative">
                                            <div class="dropdown relative">
                                                <button class="text-xl text-center block w-full " type="button"
                                                    id="tableDropdownMenuButton1" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <iconify-icon icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                </button>
                                                <ul
                                                    class=" dropdown-menu min-w-[120px] absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700
                                            shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                                    <li>
                                                        <button wire:click="toggleEditItem"
                                                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                                dark:hover:text-white">
                                                            Edit</button>
                                                    </li>
                                                    @if ($offer->is_medical)
                                                        <li>
                                                            <button wire:click="downloadMedicalTemplate"
                                                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                            dark:hover:text-white">
                                                                Download Template</button>
                                                        </li>
                                                        <li>
                                                            <button wire:click="openMedicalFileModal"
                                                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                            dark:hover:text-white">
                                                                Upload Medical File</button>
                                                        </li>
                                                        @if ($offer->medical_offer_clients->isNotEmpty())
                                                            <li>
                                                                <button wire:click="openPolicyCalculationModal"
                                                                    class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                            dark:hover:text-white">
                                                                    Download Calculated Medical Template</button>
                                                            </li>
                                                        @endif
                                                    @elseif ($offer->fields->count())
                                                        <li>
                                                            <button wire:click="openOfferFieldsModal"
                                                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                                dark:hover:text-white">
                                                                Edit Fields</button>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <hr>
                                <br>
                                <div class="grid grid-cols-2 mb-4 ml-5">
                                    @if ($offer->fields->count())
                                        <ul class="m-0 p-0 list-none">
                                            @foreach ($offer->fields as $field)
                                                <li class="inline-block relative top-[3px] text-base font-Inter ">
                                                    {{ $field->field }}:
                                                    <span class="text-primary-500">
                                                        {{ is_numeric($field->value) ? number_format($field->value, 0, '.', ',') : $field->value }}
                                                    </span>
                                                </li> <br />
                                                <br />
                                            @endforeach
                                        </ul>
                                    @elseif($offer->medical_offer_clients->isNotEmpty())
                                        <ul class="m-0 p-0 ">
                                            @foreach ($offer->medical_offer_clients->take(20) as $c)
                                                <li class="inline-block relative top-[3px] text-base font-Inter ">
                                                    {{ $c->name }} ({{ ucwords($c->relation) }}) - Age:
                                                    <span class="text-primary-500">
                                                        {{ \Carbon\Carbon::parse($c->birth_date)->diffInYears(\Carbon\Carbon::now()) }}
                                                    </span>
                                                </li> <br />
                                                <br />
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-center">
                                            <span class="text-lg">
                                                <b>{{ number_format($offer->item_value, 0, '.', ',') }}</b><span
                                                    class="text-sm">EGP</span>
                                            </span>
                                        </p>
                                        <div class=" border-l pl-2  text-sm text-wrap">
                                            {{ $offer->item_desc }}
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="rounded-md overlay mt-5">
                    <div class="card">
                        <header class="card-header noborder">
                            <h4 class="card-title">Available Policies
                            </h4>
                        </header>
                        <div class="card-body px-6 pb-6">
                            <div class="overflow-x-auto -mx-6 ">
                                <span class=" col-span-8  hidden"></span>
                                <span class="  col-span-4 hidden"></span>
                                <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-hidden ">
                                        <table
                                            class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                            @if (!empty($available_pols))
                                                <thead class=" border-t border-slate-100 dark:border-slate-800">
                                                    <tr>

                                                        <th scope="col" class=" table-th ">
                                                            Policy
                                                        </th>

                                                        <th scope="col" class=" table-th ">
                                                            Type
                                                        </th>

                                                        <th scope="col" class=" table-th ">
                                                            Net
                                                        </th>
                                                        <th scope="col" class=" table-th ">
                                                            GROSS
                                                        </th>

                                                        <th scope="col" class=" table-th ">
                                                            Action
                                                        </th>

                                                    </tr>
                                                </thead>
                                                <tbody
                                                    class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                                    @foreach ($available_pols as $policy)
                                                        <tr>
                                                            <td class="table-td ">
                                                                <div class="min-w-[170px]">
                                                                    <span class="text-slate-500 dark:text-slate-400">
                                                                        <span
                                                                            class="block text-slate-600 dark:text-slate-300">{{ $policy['policy']['company']['name'] }}</span>
                                                                        <span
                                                                            class="block text-slate-500 text-xs">{{ $policy['policy']['name'] }}</span>
                                                                    </span>
                                                                </div>
                                                            </td>
                                                            <td class="table-td ">
                                                                {{ ucwords(str_replace('_', ' ', $policy['policy']['business'])) }}
                                                            </td>
                                                            <td class="table-td ">

                                                                <div class=" text-success-500">
                                                                    @if (!$offer->is_medical)
                                                                        {{ $policy['cond']['rate'] }}%
                                                                    @endif
                                                                    {{ number_format($policy['net_value']) }}EGP
                                                                </div>

                                                            </td>
                                                            <td class="table-td ">

                                                                <div class=" text-success-500">
                                                                    {{ number_format($policy['gross_value']) }}EGP
                                                                </div>

                                                            </td>
                                                            <td class="table-td ">
                                                                <div class="flex items-center gap-2">
                                                                @if($policy['policy']['note'])
                                                                    <button 
                                                                        wire:click="showPolicyNote('{{ addslashes($policy['policy']['note']) }}')"
                                                                        class="btn inline-flex justify-center btn-light rounded-[25px] btn-sm me-2"
                                                                    >
                                                                        <iconify-icon icon="heroicons:information-circle" class="text-slate-600"></iconify-icon>
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    wire:click="generateOption({{ $policy['policy']['id'] . ',' . $policy['cond']['id'] }})"
                                                                    class="btn inline-flex justify-center btn-light rounded-[25px] btn-sm"><iconify-icon
                                                                        icon="bi:stars"
                                                                        class="text-primary-600"></iconify-icon>&nbsp;
                                                                    Generate</button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            @else
                                                <div
                                                    class="py-[18px] px-6 font-normal font-Inter text-sm rounded-md bg-warning-500 bg-opacity-[14%] text-warning-500 mx-2">
                                                    <div class="flex items-start space-x-3 rtl:space-x-reverse">
                                                        <div class="flex-1">
                                                            No available policies for this offer!
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-md overlay mt-5">
                    <div
                        class="card-body flex flex-col justify-center  bg-no-repeat bg-center bg-cover card p-4 active">
                        <div class="card-text flex flex-col justify-between h-full menu-open">
                            <div class="flex justify-between mb-3">
                                <p class="mb-2">
                                    <b>Options ({{ $offer->options->count() }})</b>

                                </p>
                                @if ($offer->selected_option_id && $offer->options()->clientSelected()->get()->count())
                                    <div>
                                        <button class="btn btn-sm btn-primary float-right"
                                            wire:click="openGenerateSoldPolicy">Generate Sold Policy</button>
                                    </div>
                                @endif
                            </div>

                            @if ($offer->options->isEmpty())
                                <div class="text-center">
                                    <p class="text-center m-5 text-primary">No options added to this offer.</p>
                                    <button wire:click="toggleAddOption"
                                        class="btn inline-flex justify-center btn-dark btn-sm">Create option</button>
                                </div>
                            @else
                                @foreach ($offer->options as $option)
                                    {{-- card-body flex flex-col justify-between border rounded-lg h-full menu-open p-0 mb-5" style="border-color:rgb(224, 224, 224)" --}}
                                    <div class="card-body rounded-md bg-[#E5F9FF] dark:bg-slate-700 shadow-base mb-5">
                                        @if ($option->is_renewal)
                                            <span
                                                class="badge bg-success-500 text-white capitalize inline-flex items-center w-full">
                                                <iconify-icon class="ltr:mr-1 rtl:ml-1"
                                                    icon="material-symbols:autorenew-rounded"></iconify-icon>
                                                Is Renewal</span>
                                        @endif

                                        <div class="checkbox-area my-1 ml-2">
                                            <label class="inline-flex items-center cursor-pointer">
                                                {{-- <span class="text-slate-500 dark:text-slate-400 text-sm leading-6 mr-1">Select</span> --}}
                                                <input type="checkbox" class="hidden" name="checkbox"
                                                    value="{{ $option->id }}" wire:model="selectedOptions"
                                                    value="true">
                                                <span
                                                    class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900"
                                                    style="background-color: #575757">
                                                    <img src="{{ asset('assets/images/icon/ck-white.svg') }}"
                                                        alt=""
                                                        class="h-[10px] w-[10px] block m-auto opacity-0"></span>

                                            </label>

                                        </div>

                                        <div class="break-words flex items-center my-1 m-4">
                                            <h3 class="text-base capitalize py-3">
                                                {{ ucwords($option->policy->company->name) }} |
                                                {{ ucwords($option->policy->name) }}

                                                @if ($option->payment_frequency)
                                                    <span
                                                        class="badge bg-primary-500 text-primary-500 bg-opacity-30 capitalize rounded-3xl float-right">{{ $option->payment_frequency }}
                                                        Payment</span>
                                                @endif

                                                @if (str_contains($option->status, 'qoutation') || str_contains($option->status, 'received'))
                                                    <span class="badge bg-info-500 mr-2  bg-opacity-50 h-auto">
                                                        <iconify-icon
                                                            icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $option->status)) }}
                                                    </span>
                                                @elseif(str_contains($option->status, 'rejected'))
                                                    <span class="badge bg-danger-500  mr-2 bg-opacity-50 h-auto">
                                                        <iconify-icon
                                                            icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $option->status)) }}
                                                    </span>
                                                @elseif(str_contains($option->status, 'accepted') || str_contains($option->status, 'issued'))
                                                    <span class="badge bg-success-500  mr-2 bg-opacity-50 h-auto">
                                                        <iconify-icon
                                                            icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $option->status)) }}
                                                    </span>
                                                @elseif($option->status)
                                                    <span class="badge bg-dark-500  mr-2 bg-opacity-50 h-auto">
                                                        <iconify-icon
                                                            icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $option->status)) }}
                                                    </span>
                                                @endif

                                            </h3>


                                            <div class="ml-auto">
                                                <div class="relative">
                                                    <div class="dropdown relative">
                                                        <button class="text-xl text-center block w-full "
                                                            type="button" data-bs-toggle="dropdown">
                                                            <iconify-icon
                                                                icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                        </button>
                                                        <ul
                                                            class=" dropdown-menu min-w-[184px] absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                                            @foreach ($optionStatuses as $oStatus)
                                                                <li>
                                                                    <button
                                                                        wire:click="changeOptionState({{ $option->id }},'{{ $oStatus }}')"
                                                                        class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                                        Set as
                                                                        {{ ucwords(str_replace('_', ' ', $oStatus)) }}</button>
                                                                </li>
                                                            @endforeach

                                                            <li>
                                                                <button
                                                                    wire:click="openAddFieldSec({{ $option->id }})"
                                                                    class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                                    Add Field</button>
                                                            </li>
                                                            <li>
                                                                <label for="myFile"
                                                                    wire:click="uploadDocOptionId({{ $option->id }})"
                                                                    class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                                                                    Add Doc
                                                                </label>
                                                                <input type="file" id="myFile" name="filename"
                                                                    style="display: none;"
                                                                    wire:model="uploadedOptionFile">
                                                            </li>
                                                            <li>
                                                                <button
                                                                    wire:click="editThisOption({{ $option->id }})"
                                                                    class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                                    Edit</button>
                                                            </li>
                                                            <li>
                                                                <button
                                                                    wire:click="deleteThisOption({{ $option->id }})"
                                                                    class="text-slate-600 dark:text-white block font-Inter text-left font-normal w-full px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                                    Delete</button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        {{-- <p class="ml-4">
                                            {{ ucwords(str_replace('_', ' ', $option->policy->business)) }}
                                        </p> --}}

                                        <table
                                            class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                            <thead class="">
                                                <tr>

                                                    <th scope="col" class=" table-th" style="padding-bottom:0">
                                                        Insured Value:
                                                    </th>

                                                    <th scope="col" class=" table-th" style="padding-bottom:0">
                                                        Gross Premium:
                                                    </th>

                                                    <th scope="col" class=" table-th" style="padding-bottom:0">
                                                        Net Premium:
                                                    </th>

                                                </tr>
                                            </thead>
                                            <tbody class=" ">

                                                <tr>

                                                    <td class="table-td " style="vertical-align: top;">
                                                        <h6>
                                                            {{ number_format($option->insured_value, 0, '.', ',') }}
                                                            <span class="text-sm text-slate-400 block">
                                                                {{ ucwords(str_replace('_', ' ', $option->policy->business)) }}</span>
                                                        </h6>
                                                    </td>

                                                    <td class="table-td " style="vertical-align: top;">
                                                        <h6>
                                                            {{ number_format($option->gross_premium, 0, '.', ',') }}
                                                        </h6>
                                                    </td>

                                                    <td class="table-td  " style="vertical-align: top;">
                                                        <h6>
                                                            {{ number_format($option->net_premium, 0, '.', ',') }}
                                                        </h6>
                                                    </td>
                                                </tr>


                                            </tbody>
                                        </table>


                                        {{-- <div class="break-words flex items-center m-4 mt-0">
                                            <p class="">
                                                Insured Value:
                                            <h6 class="ml-3">
                                                {{ number_format($option->insured_value, 0, '.', ',') }}</h6>
                                            <p class="text-slate-900 dark:text-slate-300 ml-4">
                                                <span class="text-sm text-slate-400 block">|
                                                    {{ ucwords(str_replace('_', ' ', $option->policy->business)) }}</span>
                                            </p>
                                            </p>
                                        </div>
                                        <div class="break-words flex items-center m-4 mt-0">
                                            <p class="">
                                                Gross Premium:
                                            <h6 class="ml-3">
                                                {{ number_format($option->gross_premium, 0, '.', ',') }}</h6>
                                            </p>
                                        </div>
                                        <div class="break-words flex items-center m-4 mt-0">
                                            <p class="">
                                                Net Premium:
                                            <h6 class="ml-3">
                                                {{ number_format($option->net_premium, 0, '.', ',') }}</h6>
                                            </p>
                                        </div> --}}


                                        <hr><br>
                                        <div class="grid sm:gridcols-1 md:grid-cols-2 mb-4">
                                            <div class="border-r ml-5">
                                                <p><b>Fields ({{ $option->fields->count() }})</b></p>
                                                @if ($option->fields->isEmpty())
                                                    <p>No Fields added to this option.</p>
                                                @endif
                                                <ul class=" rounded p-4 min-w-[184px] space-y-5">

                                                    @foreach ($option->fields as $field)
                                                        <li
                                                            class="flex justify-between text-xs text-slate-600 dark:text-slate-300">
                                                            <span
                                                                class="flex space-x-2 rtl:space-x-reverse items-center">
                                                                <span
                                                                    class="inline-flex h-[6px] w-[6px] bg-primary-500 ring-opacity-25 rounded-full ring-4 bg-primary-500 ring-{{ ['info', 'secondary', 'success', 'primary'][array_rand(['info', 'secondary', 'success', 'primary'])] }}-500 "></span>
                                                                <span>{{ $field->name }}

                                                                </span>
                                                            </span>
                                                            <span>
                                                                <span
                                                                    class="text-lg">{{ is_numeric($field->value) ? number_format($field->value, 0, '.', ',') : $field->value }}</span>
                                                                <button type="button"
                                                                    wire:click="deleteOptionField({{ $field->id }})"
                                                                    class="font-normal text-xs text-slate-500 mt-1">
                                                                    Delete
                                                                </button>
                                                            </span>
                                                        </li>
                                                    @endforeach
                                                </ul>


                                            </div>
                                            <div class="ml-5">
                                                <p>
                                                    <b>Documents</b>
                                                    @if ($option->docs->isEmpty())
                                                        <p>No Documents added to this option.</p>
                                                    @endif
                                                    <br><br>

                                                    @if ($optionId === $option->id)
                                                        <span style="display: inline-block; align-items: center;"
                                                            wire:loading wire:target="uploadedOptionFile"><iconify-icon
                                                                class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                                                icon="line-md:loading-twotone-loop"></iconify-icon></span>
                                                    @endif
                                                </p>



                                                @foreach ($option->docs as $file)
                                                    <div class="flex space-x-2 rtl:space-x-reverse mb-3">
                                                        <div class="flex-1 flex space-x-2 rtl:space-x-reverse">
                                                            <div class="flex-none">
                                                                <div class="h-8 w-8">
                                                                    @php
                                                                        $extension = pathinfo(
                                                                            $file->name,
                                                                            PATHINFO_EXTENSION,
                                                                        );
                                                                        $icon = '';
                                                                        $view = false;

                                                                        switch ($extension) {
                                                                            case 'doc':
                                                                            case 'docx':
                                                                            case 'xls':
                                                                            case 'xlsx':
                                                                                $icon = 'pdf-2';

                                                                                break;

                                                                            case 'jpg':
                                                                            case 'jpeg':
                                                                            case 'png':
                                                                                $icon = 'scr-1';
                                                                                $view = true;
                                                                                break;

                                                                            case 'bmp':
                                                                            case 'gif':
                                                                            case 'svg':
                                                                            case 'webp':
                                                                                $icon = 'zip-1';
                                                                                break;

                                                                            case 'pdf':
                                                                                $icon = 'pdf-1';
                                                                                $view = true;
                                                                                break;
                                                                        }
                                                                    @endphp

                                                                    <img src="{{ asset('assets/images/icon/' . $icon . '.svg') }}"
                                                                        alt=""
                                                                        class="block w-full h-full object-cover rounded-full border hover:border-white border-transparent">
                                                                </div>

                                                            </div>
                                                            <div class="flex-1">
                                                                <span
                                                                    class="block text-slate-600 text-sm dark:text-slate-300"
                                                                    style="overflow-wrap: anywhere">
                                                                    {{ mb_strimwidth($file->name, 0, 30, '...') }}
                                                                </span>
                                                                <span
                                                                    class="block font-normal text-xs text-slate-500 mt-1">
                                                                    uploaded by
                                                                    {{ $file->user->first_name . ' ' . $file->user->last_name }}
                                                                    / <span class="cursor-pointer"
                                                                        wire:click="removeOptionFile({{ $file->id }})">remove</span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <button wire:click="downloadOptionDoc({{ $file->id }})"
                                                            class="action-btn float-right text-xs border-blue-600"
                                                            type="button"
                                                            style="border-color: darkgrey;margin-right:10px">
                                                            <iconify-icon icon="ic:baseline-download"></iconify-icon>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div>
                                    <button wire:click="toggleAddOption"
                                        class="btn inline-flex justify-center btn-dark btn-sm">Add option</button>
                                </div>

                            @endif

                            {{-- <button wire:click="" class="btn inline-flex justify-center btn-light rounded-[25px] btn-sm float-right">Add car</button> --}}

                        </div>
                    </div>
                </div>

            </div>
            <div class=" col-span-3">
                <span class="badge bg-primary-500 h-auto w-full mb-5 text-white" style="padding: 10px">
                    <iconify-icon icon="mingcute:time-line"></iconify-icon>&nbsp;Due:
                    {{ \Carbon\Carbon::parse($offer->due)->format('l d-m-Y h:ia') }}
                    <span class="ml-5 cursor-pointer" wire:click="toggleEditDue">
                        <iconify-icon icon="carbon:edit"></iconify-icon>
                    </span>
                </span>
                {{-- assignee --}}
                <div class="flex-1 rounded-md overlay mb-5">
                    <div
                        class="card-body flex flex-col justify-center  bg-no-repeat bg-center bg-cover card p-4 active">
                        <div class="card-text flex flex-col justify-between h-full menu-open">
                            <div class="mb-2 text-wrap flex justify-between">
                                <b><iconify-icon icon="mdi:user"></iconify-icon> Assigned To</b>

                                <span class="ml-5 cursor-pointer float-right" wire:click="toggleEditAssignee">
                                    <iconify-icon icon="carbon:edit"></iconify-icon>
                                </span>

                            </div>
                            <p><span
                                    class="mt-2">{{ $offer->assignee ? ucwords($offer->assignee->first_name) . ' ' . ucwords($offer->assignee->last_name) : ($offer->assignee_type ? ucwords($offer->assignee_type) : 'No one/team assigned') }}</span>
                            </p>
                        </div>

                        <div class="card-text h-full menu-open mb-5">
                            <div class="card-subtitle font-Inter mb-1">
                                <iconify-icon icon="carbon:view-filled"></iconify-icon> Watchers
                                <span class="float-right cursor-pointer" wire:click="OpenChangeWatchers">
                                    <iconify-icon icon="carbon:edit"></iconify-icon>
                                </span>
                            </div>
                            <div {{ $changeWatchers ? '' : "style=display:none;'" }}>
                                <div class="w-full">
                                    <select wire:model.defer="setWatchersList" id="multiSelect" multiple
                                        aria-label="multiple select example"
                                        class="select2 form-control w-full mt-2 py-2" multiple="multiple"
                                        style="height: 250px">
                                        @foreach ($users as $user)
                                            <option
                                                {{ in_array($user->id, $watchersList->pluck('user_id')->all()) ? 'selected="selected"' : '' }}
                                                value="{{ $user->id }}" class="">
                                                {{ $user->first_name . ' ' . $user->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button wire:click="saveWatchers"
                                    class="btn inline-flex justify-center btn-success mt-3 float-right btn-sm">
                                    <div class="flex items-center">
                                        <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                            wire:loading wire:target="saveWatchers"
                                            icon="line-md:loading-twotone-loop"></iconify-icon>
                                        <span>Save Watchers</span>
                                    </div>
                                </button>

                                {{-- <button class="toolTip onTop action-btn m-1 h-full" data-tippy-content="saveWatchers" type="button" wire:click="saveWatchers">
                                <iconify-icon icon="material-symbols:save"></iconify-icon>
                            </button> --}}
                            </div>

                            <div {{ $changeWatchers ? "style=display:none;'" : '' }}>
                                @foreach ($watchersList as $watcher)
                                    <span
                                        class="badge bg-slate-200 text-slate-900 capitalize rounded-3xl mb-1 me-1">{{ $watcher->user->first_name }}
                                        {{ $watcher->user->last_name }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                {{-- End assignee --}}
                {{-- Notes --}}
                <div class="flex-1 rounded-md overlay">
                    <div
                        class="card-body flex flex-col justify-center  bg-no-repeat bg-center bg-cover card p-4 active">
                        <div class="card-text flex flex-col justify-between h-full menu-open">
                            <p class="mb-2 text-wrap">
                                <b><iconify-icon icon="material-symbols:note"></iconify-icon> Notes</b><br>
                                <span class="ml-5 cursor-pointer float-right" wire:click="toggleEditNote">
                                    <iconify-icon icon="carbon:edit"></iconify-icon>
                                </span>
                                <span class="mt-2">{{ $offer->note ?? 'No notes for this offer.' }}</span>

                            </p>
                            @if (!is_Null($offer->in_favor_to) && $offer->in_favor_to !== '')
                                <p class="mb-2 text-wrap">
                                    <b><iconify-icon icon="mdi:book-favorite-outline"></iconify-icon> In Favor
                                        To</b><br>
                                    <span class="mt-2">{{ $offer->in_favor_to }}</span>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- End Notes --}}

                {{-- Files --}}
                <div class="card mt-5">
                    <div class="card-body flex flex-col p-6">
                        <header
                            class="flex mb-5 items-center border-b border-slate-100 dark:border-slate-700 pb-5 -mx-6 px-6">
                            <div class="flex-1">
                                <div class="card-title text-slate-900 dark:text-white">
                                    <h6>Files <iconify-icon wire:loading wire:target="downloadOfferFile"
                                            icon="svg-spinners:3-dots-move"></iconify-icon></h6>
                                </div>
                            </div>
                            {{-- <label for="myFile" class="custom-file-label cursor-pointer">
                                <span class="btn inline-flex justify-center btn-sm btn-outline-dark float-right">
                                    <span style="display: flex; align-items: center;"><iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="uploadedFile" icon="line-md:loading-twotone-loop"></iconify-icon></span>
                                    <span style="display: flex; align-items: center;"><iconify-icon wire:loading.remove wire:target="uploadedFile" icon="ic:baseline-upload"></iconify-icon>&nbsp;upload File</span>
                                </span>

                            </label> --}}
                            <input type="file" id="myFile" name="filename" style="display: none;"
                                wire:model="uploadedFile"><br>

                        </header>
                        <div class="loader" wire:loading wire:target="downloadFile">
                            <div class="loaderBar"></div>
                        </div>
                        @error('uploadedFile')
                            <span class="font-Inter text-danger-500 pt-2 inline-block text-xs">*
                                {{ $message }}</span>
                        @enderror
                        <div class="card-body">
                            <!-- BEGIN: Files Card -->
                            <ul class="divide-y divide-slate-100 dark:divide-slate-700">

                                @if ($offer->files->isEmpty())
                                    <div class="text-center text-xs text-slate-500 dark:text-slate-400 mt-1">
                                        No files added to this offer.
                                    </div>
                                @endif

                                @foreach ($offer->files as $file)
                                    <li class="block py-[8px]">

                                        <div class="flex space-x-2 rtl:space-x-reverse">
                                            <div class="flex-1 flex space-x-2 rtl:space-x-reverse">
                                                <div class="flex-none">
                                                    <div class="h-8 w-8">
                                                        @php
                                                            $extension = pathinfo($file->name, PATHINFO_EXTENSION);
                                                            $icon = '';
                                                            $view = false;

                                                            switch ($extension) {
                                                                case 'doc':
                                                                case 'docx':
                                                                case 'xls':
                                                                case 'xlsx':
                                                                    $icon = 'pdf-2';

                                                                    break;

                                                                case 'jpg':
                                                                case 'jpeg':
                                                                case 'png':
                                                                    $icon = 'scr-1';
                                                                    $view = true;
                                                                    break;

                                                                case 'bmp':
                                                                case 'gif':
                                                                case 'svg':
                                                                case 'webp':
                                                                    $icon = 'zip-1';
                                                                    break;

                                                                case 'pdf':
                                                                    $icon = 'pdf-1';
                                                                    $view = true;
                                                                    break;
                                                            }
                                                        @endphp

                                                        <img src="{{ asset('assets/images/icon/' . $icon . '.svg') }}"
                                                            alt=""
                                                            class="block w-full h-full object-cover rounded-full border hover:border-white border-transparent">
                                                    </div>

                                                </div>
                                                <div class="flex-1">
                                                    <span class="block text-slate-600 text-sm dark:text-slate-300"
                                                        style="overflow-wrap: anywhere">
                                                        {{ mb_strimwidth($file->name, 0, 30, '...') }}
                                                    </span>
                                                    <span class="block font-normal text-xs text-slate-500 mt-1">
                                                        uploaded by
                                                        {{ $file->user->first_name . ' ' . $file->user->last_name }} /
                                                        <span class="cursor-pointer"
                                                            onclick="confirm('Are you sure ?')"
                                                            wire:click="removeOfferFile({{ $file->id }})">remove</span>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="flex-none">
                                                <span class="font-normal text-xs text-slate-500 mt-1"></span>
                                                <button wire:click="downloadOfferFile({{ $file->id }})"
                                                    class="action-btn float-right mr-1 text-xs" type="button">
                                                    <iconify-icon icon="ic:baseline-download"></iconify-icon>
                                                </button>
                                                {{-- <button type="button" wire:click="downloadFile({{ $file->id }})" class="text-xs text-slate-900 dark:text-white">
                                                    Download
                                                </button> --}}
                                            </div>
                                        </div>
                                    </li>
                                @endforeach


                                <div class="border-dashed border dropzone-container cursor-pointer"
                                    style="border-color: #aeaeae">
                                    <p class="dropzone-para" wire:loading wire:target="uploadedFile"
                                        style="font-size:20px"><iconify-icon
                                            icon="svg-spinners:tadpole"></iconify-icon>
                                    </p>
                                    <p class="dropzone-para" wire:loading.remove wire:target="uploadedFile">Choose a
                                        file or drop it here...</p>
                                    <input name="file" id="fileInput" type="file"
                                        class="dropzone dropzone-input" multiple wire:model="uploadedFile" />
                                </div>
                            </ul>


                            <!-- END: FIles Card -->
                        </div>
                        <div class="loader" wire:loading wire:target="previewFile">
                            <div class="loaderBar"></div>
                        </div>
                        @if ($preview)
                            <iframe src='{{ $preview }}' height='400px' frameborder='0'></iframe>
                        @endif
                        {{-- <iframe src='https://wiseins.s3.eu-north-1.amazonaws.com/tasks/GGxyo5OihDGEJnn6dW51XyQ2x9544vNDGBqCMMVj.pdf' height='400px' frameborder='0'></iframe> --}}
                    </div>
                </div>
                {{-- End Files --}}

                <div class="card mt-5">
                    <div class="card-body flex flex-col p-6">
                        <header
                            class="flex mb-5 items-center border-b border-slate-100 dark:border-slate-700 pb-5 -mx-6 px-6">
                            <div class="flex-1">
                                <div class="card-title text-slate-900 dark:text-white">
                                    <h6>Commissions</h6>
                                </div>
                            </div>
                            @can('updateCommission', $offer)
                                <button wire:click="toggleAddComm"
                                    class="btn btn-sm inline-flex justify-center btn-outline-dark rounded-[25px]">Add
                                    commission</button>
                            @endcan

                        </header>

                        <div class="card-body">
                            <ul class="divide-y divide-slate-100 dark:divide-slate-700">
                                @if ($offer->comm_profiles->isEmpty())
                                    <div class="text-center text-xs text-slate-500 dark:text-slate-400 mt-1">
                                        No commission added to this offers
                                    </div>
                                @else
                                    <table
                                        class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                        <thead class="">
                                            <tr>

                                                <th scope="col" class=" table-th ">
                                                    Title
                                                </th>


                                                <th scope="col" class=" table-th ">
                                                    Type
                                                </th>

                                                <th scope="col" class=" table-th ">

                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody
                                            class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                            @foreach ($offer->comm_profiles as $profile)
                                                <tr>
                                                    <td class="table-td ">
                                                        <a href="{{ route('comm.profile.show', $profile->id) }}">
                                                            <div class="min-w-[170px] hover:underline cursor-pointer">
                                                                <span class="text-slate-500 dark:text-slate-400">
                                                                    <span
                                                                        class="block text-slate-600 dark:text-slate-300">{{ $profile->title }}</span>
                                                                    <span class="block text-slate-500 text-xs">
                                                                        {{ $profile->user?->username }}
                                                                    </span>
                                                                </span>
                                                            </div>
                                                        </a>
                                                    </td>

                                                    <th scope="col" class=" table-th ">
                                                        {{ ucwords(str_replace('_', ' ', $profile->type)) }}
                                                    </th>

                                                    @can('updateCommission', $offer)
                                                        <td class="table-td flex justify-between">
                                                            <div class="">
                                                                <div class="relative">
                                                                    <div class="dropdown relative">
                                                                        <button class="text-xl text-center block w-full "
                                                                            type="button" data-bs-toggle="dropdown"
                                                                            aria-expanded="false">
                                                                            <iconify-icon
                                                                                icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                                        </button>
                                                                        <ul
                                                                            class=" dropdown-menu min-w-[120px] absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700
                                                    shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                                                            <li>
                                                                                <a wire:click="removeCommProfile({{ $profile->id }})"
                                                                                    class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                                            dark:hover:text-white">
                                                                                    Delete</a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    @endcan


                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>

                                @endif
                            </ul>
                        </div>
                    </div>
                </div>




                <div class="card mt-5">
                    <div class="card-body flex flex-col p-6">
                        <header
                            class="flex mb-5 items-center border-b border-slate-100 dark:border-slate-700 pb-5 -mx-6 px-6">
                            <div class="flex-1">
                                <div class="card-title text-slate-900 dark:text-white">
                                    <h6>Discounts <iconify-icon wire:loading wire:target="downloadOfferFile"
                                            icon="svg-spinners:3-dots-move"></iconify-icon></h6>
                                </div>
                            </div>
                            <button wire:click="toggleAddDiscount"
                                class="btn btn-sm inline-flex justify-center btn-outline-dark rounded-[25px]">Add
                                Discount</button>

                        </header>

                        <div class="card-body">
                            <ul class="divide-y divide-slate-100 dark:divide-slate-700">
                                @if ($offer->discounts->isEmpty())
                                    <div class="text-center text-xs text-slate-500 dark:text-slate-400 mt-1">
                                        No discounts added to this offer.
                                    </div>
                                @else
                                    <table
                                        class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                        <thead class="">
                                            <tr>

                                                <th scope="col" class=" table-th ">
                                                    Type
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    Discount
                                                </th>


                                            </tr>
                                        </thead>
                                        <tbody
                                            class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                            @foreach ($offer->discounts as $discount)
                                                <tr>
                                                    <td class="table-td ">
                                                        <div class="min-w-[170px]">
                                                            <span class="text-slate-500 dark:text-slate-400">
                                                                <span
                                                                    class="block text-slate-600 dark:text-slate-300">{{ ucwords(str_replace('_', ' ', $discount->type)) }}</span>
                                                                <span class="block text-slate-500 text-xs">Offered By:
                                                                    {{ $discount->user->username }}</span>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td class="table-td ">

                                                        <div class=" text-success-500 text-lg">
                                                            {{ $discount->value }}
                                                        </div>

                                                    </td>
                                                    <td class="table-td ">
                                                        <div>
                                                            <div class="relative">
                                                                <div class="dropdown relative">
                                                                    <button class="text-xl text-center block w-full "
                                                                        type="button"
                                                                        id="transactionDropdownMenuButton2"
                                                                        data-bs-toggle="dropdown"
                                                                        aria-expanded="false">
                                                                        <iconify-icon
                                                                            icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                                    </button>
                                                                    <ul
                                                                        class=" dropdown-menu min-w-[120px] absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700
                                                    shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                                                        <li>
                                                                            <a wire:click="editThisDicount({{ $discount->id }})"
                                                                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                                            dark:hover:text-white">
                                                                                Edit</a>
                                                                        </li>
                                                                        <li>
                                                                            <a wire:click="deleteThisDiscount({{ $discount->id }})"
                                                                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                                            dark:hover:text-white">
                                                                                Delete</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>

                                                </tr>
                                                <tr style="border: 0">
                                                    <td class="table-td col-span-2 text-wrap"><b>Note:</b>
                                                        {{ $discount->note }}</td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>

                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <br><br>

                {{-- Comments --}}
                <div>
                    <div>
                        Timeline
                    </div>
                    <div class="card mb-5" style="margin-bottom:50px">
                        <div class="card-body">
                            <div class="card-text h-full">
                                <div class="mt-5">
                                    <div
                                        class="text-slate-600 dark:text-slate-300 block w-full px-4 py-3 text-sm mb-2 last:mb-0">
                                        <div class="flex ltr:text-left rtl:text-right">
                                            <div class="flex-none ltr:mr-3 rtl:ml-3">
                                                <div class="h-8 w-8 rounded-full relative text-white bg-blue-500">

                                                    <span
                                                        class="block w-full h-full object-cover text-center text-lg leading-8">
                                                        {{ strtoupper(substr('michael', 0, 1)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <input type="text" class="form-control border-0"
                                                    placeholder="Leave a comment..." wire:model="newComment"
                                                    wire:keydown.enter="addComment"
                                                    style="border: none; box-shadow: 0 0 0px rgba(0, 0, 0, 0.5);">
                                            </div>
                                            <div class="">
                                                <button class="btn inline-flex justify-center btn-primary btn-sm"
                                                    wire:click="addComment">
                                                    <span class="flex items-center">
                                                        <span>Post</span>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @foreach ($offer->comments as $comment)
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="card-text h-full">
                                    <div class="mt-5">
                                        <div
                                            class="text-slate-600 dark:text-slate-300 block w-full px-4 py-3 text-sm mb-2 last:mb-0">
                                            <div class="flex ltr:text-left rtl:text-right">
                                                <div class="flex-none ltr:mr-3 rtl:ml-3">
                                                    <div class="h-8 w-8 rounded-full relative text-white bg-blue-500">

                                                        <span
                                                            class="block w-full h-full object-cover text-center text-lg leading-8">
                                                            {{ strtoupper(substr($comment->user?->username ?? 'System', 0, 1)) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <div
                                                        class="text-slate-800 dark:text-slate-300 text-sm font-medium mb-1`">
                                                        {{ ucwords($comment->user->username) ?? 'System' }}
                                                    </div>
                                                    <div
                                                        class="text-xs hover:text-[#68768A] font-normal text-slate-600 dark:text-slate-300">
                                                        {{ $comment->comment }}
                                                    </div>
                                                </div>
                                                <div class="">
                                                    <span class="flex items-center justify-center text-sm">
                                                        {{ $comment->created_at->format('Y-m-d H:i') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{-- End Comments --}}
            </div>
        </div>
    </div>

    @if ($deleteCommId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                            rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-danger-500">
                            <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                Delete Commission
                            </h3>
                            <button wire:click="dismissDeleteComm" type="button"
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
                                Are you sure ! you Want to delete this Commission ?
                            </h6>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="deleteComm" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-danger-500">Yes, Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($genarteSoldPolicySection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none" style="max-width: 800px;">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Create Sold Policy
                            </h3>

                            <button wire:click="closeGenerateSoldPolicy" type="button"
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
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif


                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-2">
                                <div class="from-group">
                                    <label for="policy_number" class="form-label">Policy Number</label>
                                    <input name="policy_number" class="form-control mt-2 w-full"
                                        wire:model.defer="policy_number">
                                    @error('policy_number')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="from-group">
                                    <label for="sold_insured_value" class="form-label">Sold Insured Value</label>
                                    <input type="number" name="sold_insured_value" class="form-control mt-2 w-full"
                                        wire:model.defer="sold_insured_value">
                                    @error('sold_insured_value')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="input-area mb-3">
                                <label for="policyDoc" class="form-label">Policy Document</label>
                                <input name="policyDoc"
                                    class="form-control py-2 flatpickr cursor-pointer flatpickr-input active @error('policyDoc') !border-danger-500 @enderror"
                                    id="default-picker" type="file" wire:model.defer="policyDoc">
                                @error('policyDoc')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 mt-2">
                                <div class="from-group">
                                    <label for="net_rate" class="form-label">Net Rate</label>
                                    <input type="number" name="net_rate" class="form-control mt-2 w-full"
                                        wire:model.defer="net_rate">
                                    @error('net_rate')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="from-group">
                                    <label for="net_premium" class="form-label">Net Premium</label>
                                    <input type="number" name="net_premium" class="form-control mt-2 w-full"
                                        wire:model.defer="net_premium">
                                    @error('net_premium')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="from-group">
                                    <label for="gross_premium" class="form-label">Gross Premium</label>
                                    <input type="number" name="gross_premium" class="form-control mt-2 w-full"
                                        wire:model.defer="gross_premium">
                                    @error('gross_premium')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-2">
                                <div class="from-group">
                                    <label for="installments_count" class="form-label">Installments Count</label>
                                    <input type="number" name="installments_count" class="form-control mt-2 w-full"
                                        wire:model.defer="installments_count">
                                    @error('installments_count')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="from-group">
                                    <label for="sold_payment_frequency" class="form-label">Sold Payment
                                        Frequency</label>
                                    <select name="sold_payment_frequency" id="basicSelect"
                                        class="form-control w-full mt-2  @error('sold_payment_frequency') !border-danger-500 @enderror"
                                        wire:model="sold_payment_frequency">
                                        <option
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            Select an option...</option>
                                        @foreach ($PAYMENT_FREQS as $freqs)
                                            <option value="{{ $freqs }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ ucwords($freqs) }}
                                            </option>
                                        @endforeach

                                    </select>
                                    @error('sold_payment_frequency')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-2">
                                <div class="from-group">
                                    <label for="issuing_date" class="form-label">Issuing</label>
                                    <input type="date" name="issuing_date" class="form-control mt-2 w-full"
                                        wire:model.defer="issuing_date">
                                    @error('issuing_date')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-2">
                                <div class="from-group">
                                    <label for="start" class="form-label">Start</label>
                                    <input type="date" name="start" class="form-control mt-2 w-full"
                                        wire:model.defer="start">
                                    @error('start')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="from-group">
                                    <label for="expiry" class="form-label">Expiry</label>
                                    <input type="date" name="expiry" class="form-control mt-2 w-full"
                                        wire:model.defer="expiry">
                                    @error('expiry')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            @if ($offer->is_motor)
                                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 mt-2">
                                    <div class="from-group">
                                        <label for="car_chassis" class="form-label">Car Chassis</label>
                                        <input type="text" name="car_chassis" class="form-control mt-2 w-full"
                                            wire:model.defer="car_chassis">
                                        @error('car_chassis')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="from-group">
                                        <label for="car_plate_no" class="form-label">Car Plate No.</label>
                                        <input type="text" name="car_plate_no" class="form-control mt-2 w-full"
                                            wire:model.defer="car_plate_no">
                                        @error('car_plate_no')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="from-group">
                                        <label for="car_engine" class="form-label">Car Engine</label>
                                        <input type="text" name="car_engine" class="form-control mt-2 w-full"
                                            wire:model.defer="car_engine">
                                        @error('car_engine')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                            <div class="from-group">
                                <label for="soldInFavorTo" class="form-label">in Favor To</label>
                                <input type="text" name="soldInFavorTo" class="form-control mt-2 w-full"
                                    wire:model.defer="soldInFavorTo">
                                @error('soldInFavorTo')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="generateSoldPolicy" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="generateSoldPolicy">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="generateSoldPolicy"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if ($discountId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Edit Discount
                            </h3>
                            <button wire:click="closeEditDiscount" type="button"
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
                                <label for="lastName" class="form-label">Type</label>
                                <select name="basicSelect" id="basicSelect"
                                    class="form-control w-full mt-2 @error('discountType') !border-danger-500 @enderror"
                                    wire:model="discountType">
                                    <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                        Select an option...</option>
                                    @foreach ($DISCOUNT_TYPES as $type)
                                        <option value="{{ $type }}"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ ucwords(str_replace('_', ' ', $type)) }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="from-group">
                                <label for="lastName" class="form-label">Value in EGP</label>
                                <input type="number"
                                    class="form-control mt-2 w-full @error('discountValue') !border-danger-500 @enderror"
                                    wire:model.defer="discountValue">
                                @error('discountValue')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="lastName" class="form-label">Note</label>
                                <textarea class="form-control mt-2 w-full @error('discountNote') !border-danger-500 @enderror"
                                    wire:model.defer="discountNote"></textarea>
                                @error('discountNote')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="updateDiscount" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- addCommSec --}}
    @can('updateCommission', $offer)
        @if ($addCommSec)
            <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
                tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
                style="display: block;">
                <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                    <div
                        class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                        <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                            <!-- Modal header -->
                            <div
                                class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                                <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                    Add Commission
                                </h3>
                                <button wire:click="toggleAddComm" type="button"
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

                                <div class="from-group">
                                    <label for="commSearch" class="form-label">Search</label>
                                    <input type="text" placeholder="Search..." name="commSearch"
                                        class="form-control mt-2 w-full @error('commSearch') !border-danger-500 @enderror"
                                        wire:model="commSearch">
                                </div>

                                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                    <thead class="bg-slate-200 dark:bg-slate-700">
                                        <tr>

                                            <th scope="col" class=" table-th ">
                                                Title
                                            </th>

                                            <th scope="col" class=" table-th ">
                                                Type
                                            </th>

                                            <th scope="col" class=" table-th ">
                                                User
                                            </th>

                                            <th scope="col" class=" table-th ">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                        @foreach ($profilesRes as $profileRes)
                                            <tr class="even:bg-slate-50 dark:even:bg-slate-700">
                                                <td class="table-td">{{ $profileRes->title }}</td>
                                                <td class="table-td">
                                                    {{ ucwords(str_replace('_', ' ', $profileRes->type)) }}</td>
                                                <td class="table-td">{{ $profileRes->user?->username }}</td>
                                                <td class="table-td"><button
                                                        class="btn inline-flex justify-center btn-success light btn-sm"
                                                        wire:click="addCommProfile({{ $profileRes->id }})">Add</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Modal footer -->
                            {{-- <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addComm" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endcan

    @if ($addDiscountSec)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Add Discount
                            </h3>
                            <button wire:click="toggleAddDiscount" type="button"
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
                                <label for="lastName" class="form-label">Type</label>
                                <select name="basicSelect" id="basicSelect"
                                    class="form-control w-full mt-2 @error('discountType') !border-danger-500 @enderror"
                                    wire:model="discountType">
                                    <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                        Select an option...</option>
                                    @foreach ($DISCOUNT_TYPES as $type)
                                        <option value="{{ $type }}"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ ucwords(str_replace('_', ' ', $type)) }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="from-group">
                                <label for="lastName" class="form-label">Value in EGP</label>
                                <input type="number"
                                    class="form-control mt-2 w-full @error('discountValue') !border-danger-500 @enderror"
                                    wire:model.defer="discountValue">
                                @error('discountValue')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="lastName" class="form-label">Note</label>
                                <textarea class="form-control mt-2 w-full @error('discountNote') !border-danger-500 @enderror"
                                    wire:model.defer="discountNote"></textarea>
                                @error('discountNote')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addDiscount" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($editItemSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Edit Item
                            </h3>
                            <button wire:click="toggleEditItem" type="button"
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
                        @if ($offer->is_medical)
                            <div class="p-6 space-y-4">
                                <div class="from-group">
                                    @if (!empty($relatives))
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                            <div class="input-area">
                                                <label for="time-date-picker" class="form-label"
                                                    style="margin: 0">Relative Info</label>
                                            </div>
                                        </div>
                                    @endif
                                    @foreach ($relatives as $index => $relative)
                                        <div
                                            class="card-body rounded-md bg-[#E5F9FF] dark:bg-slate-700 shadow-base mb-5 p-2">
                                            <div
                                                class="grid grid-cols-12 md:grid-cols-12 lg:grid-cols-12 gap-2 items-center">
                                                <div class="input-area col-span-4">
                                                    <input
                                                        class="form-control w-full mt-2  @error('relatives.' . $index . '.name') !border-danger-500 @enderror"
                                                        wire:model="relatives.{{ $index }}.name"
                                                        type="text" placeholder="Relative name">
                                                </div>
                                                <div class="input-area col-span-3">
                                                    <input
                                                        class="form-control w-full mt-2   @error('relatives.' . $index . '.birth_date') !border-danger-500 @enderror"
                                                        wire:model="relatives.{{ $index }}.birth_date"
                                                        type="date" placeholder="birth_date">
                                                </div>
                                                <div class="input-area col-span-4">
                                                    <select name="basicSelect"
                                                        class="form-control w-full mt-2  @error('relatives.' . $index . '.relation') !border-danger-500 @enderror"
                                                        wire:model="relatives.{{ $index }}.relation">
                                                        <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                            Select Relation...</option>
                                                        @foreach ($RELATIONS as $relation)
                                                            <option value="{{ $relation }}"
                                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                                {{ ucwords($relation) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-span-1 flex items-center">
                                                    <button class="action-btn"
                                                        wire:click="removeRelative({{ $index }})"
                                                        type="button">
                                                        <iconify-icon icon="heroicons:trash"></iconify-icon>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <button wire:click="addAnotherRelative"
                                        class="btn btn-sm mt-2 inline-flex justify-center btn-dark">Add
                                        Relative</button>


                                </div>
                            </div>
                        @elseif ($offer->is_motor)
                            <div class="p-6 space-y-4">
                                @if ($offer->client->cars)
                                    <div class="from-group">
                                        <label for="lastName" class="form-label">Car</label>
                                        @if ($offer->client->cars->isEmpty())



                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                                                style="margin: 0">
                                                <div class="input-area">
                                                    <label for="firstName" class="form-label">Car Brand</label>
                                                    <select name="basicSelect" class="form-control w-full mt-2"
                                                        wire:model="carBrand">
                                                        <option value=''>Select an Option</option>
                                                        @foreach ($brands as $brand)
                                                            <option value="{{ $brand->id }}"
                                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                                {{ $brand->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @if ($carBrand && $carBrand !== '')
                                                    <div class="input-area">
                                                        <label for="lastName" class="form-label">Car Model</label>

                                                        <select name="basicSelect" class="form-control w-full mt-2"
                                                            wire:model="carModel">
                                                            <option value=''>Select an Option</option>
                                                            @foreach ($models as $model)
                                                                <option value="{{ $model->id }}"
                                                                    class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                                    {{ $model->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif

                                                @if ($carModel && $carModel !== '' && $carBrand && $carBrand !== '')
                                                    <div class="input-area">
                                                        <label for="lastName" class="form-label">Car Category</label>
                                                        <select name="basicSelect"
                                                            class="form-control w-full mt-2 @error('CarCategory') !border-danger-500 @enderror"
                                                            wire:model="CarCategory">
                                                            <option selected>Select an Option</option>
                                                            @foreach ($cars as $car)
                                                                <option value="{{ $car->id }}"
                                                                    class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                                    {{ $car->category }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif
                                            </div>
                                            @error('CarCategory')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror



                                            @if ($CarCategory || $item)
                                                <div class="input-area">
                                                    <label for="lastName" class="form-label">Model Year</label>
                                                    <select name="basicSelect"
                                                        class="form-control w-full mt-2 @error('carPrice') !border-danger-500 @enderror text-dark"
                                                        wire:model="carPrice">
                                                        <option value="" selected>Select an Option</option>
                                                        @foreach ($CarPrices as $price)
                                                            <option value="{{ $price }}" class="">
                                                                {{ $price->model_year }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif
                                        @else
                                            <select name="basicSelect" id="basicSelect"
                                                class="form-control w-full mt-2" wire:model="carId">
                                                @foreach ($offer->client->cars as $car)
                                                    <option value="{{ $car->id }}"
                                                        class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                        {{ $car->car->category }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif


                                    </div>
                                @endif

                                <div class="from-group">
                                    <label for="lastName" class="form-label">Item title</label>
                                    <input type="text" class="form-control mt-2 w-full"
                                        wire:model.defer="item_title">
                                    @error('item_title')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="from-group">
                                    <label for="lastName" class="form-label">Item value</label>
                                    <input type="number" class="form-control mt-2 w-full"
                                        wire:model.defer="item_value">
                                    @error('item_value')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="from-group">
                                    <label for="lastName" class="form-label">Item Description</label>
                                    <textarea class="form-control mt-2 w-full" wire:model.defer="item_desc"></textarea>
                                    @error('item_desc')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endif
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editItem" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($addOptionSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Create Option
                            </h3>
                            <button wire:click="toggleAddOption" type="button"
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
                        <div class="p-6">

                            @error('conditionId')
                                <div
                                    class="py-[18px] px-6 font-normal font-Inter text-sm rounded-md bg-danger-500 bg-opacity-[14%] text-danger-500">
                                    <div class="flex items-start space-x-3 rtl:space-x-reverse">
                                        <div class="flex-1">
                                            {{ $message }}
                                        </div>
                                    </div>
                                </div>
                            @enderror
                            @if ($policyId)
                                <label for="lastName" class="form-label" style="margin: 0">Policy</label>
                                <p>{{ $policyData->company->name }} | {{ $policyData->name }}</p><br>
                            @else
                                <div class="from-group">
                                    <label for="lastName" class="form-label">
                                        Search Policy
                                        <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                            wire:loading wire:target="searchPolicy"
                                            icon="line-md:loading-twotone-loop"></iconify-icon>
                                    </label>
                                    <input type="text" class="form-control mt-2 w-full" wire:model="searchPolicy">
                                </div>
                                <div class="text-sm mt-0">
                                    @if ($policiesData)
                                        @foreach ($policiesData as $policy)
                                            <p><iconify-icon icon="material-symbols:policy"></iconify-icon>
                                                {{ $policy->company->name }} | {{ $policy->name }} | <Span
                                                    wire:click="selectPolicy({{ $policy->id }})"
                                                    class="cursor-pointer text-primary-500">Select Policy</Span></p>
                                        @endforeach

                                    @endif
                                </div>
                            @endif


                            @if ($policyConditions)
                                @if ($conditionId)
                                    <label for="lastName" class="form-label" style="margin:0">Condition</label>
                                    <p> Rate:{{ $conditionData?->rate }}
                                    </p>
                                    <br>
                                @else
                                    <div class="text-sm mt-0">
                                        @foreach ($policyConditions as $condition)
                                            <p><iconify-icon icon="material-symbols:policy"></iconify-icon>
                                                {{ ucwords(str_replace('_', ' ', $condition->scope)) }}
                                                <b>
                                                    {{ $condition->operator == 'gte' ? '>=' : ($condition->operator == 'gt' ? '>' : ($condition->operator == 'lte' ? '<=' : ($condition->operator == 'lt' ? '<' : ($condition->operator == 'e' ? '=' : '')))) }}
                                                </b>

                                                {{ $condition->value }} | Rate:{{ $condition->rate }}

                                                <Span wire:click="selectCondition({{ $condition->id }})"
                                                    class="cursor-pointer text-primary-500">Select Condition</Span>
                                            </p>
                                        @endforeach
                                    </div>
                                @endif
                            @endif


                            @if ($policyId || $conditionId)
                                <p class="text-sm m-3"><Span wire:click="clearPolicy"
                                        class="cursor-pointer text-primary-500">Clear policy</Span></p>
                            @endif


                            <div class="from-group mt-3">
                                <label for="lastName" class="form-label">Insured Value</label>
                                <input type="text"
                                    class="form-control mt-2 w-full @error('insured_value') !border-danger-500 @enderror"
                                    wire:model.defer="insured_value">
                                @error('insured_value')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                <div class="from-group mt-3">
                                    <label for="lastName" class="form-label ">Gross Premium</label>
                                    <input wire:model="grossPremium" type="text"
                                        class="form-control mt-2 w-full @error('grossPremium') !border-danger-500 @enderror">
                                    @error('grossPremium')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="from-group mt-3">
                                    <label for="lastName" class="form-label">Net Premium</label>
                                    <input wire:model="netPremium" type="text"
                                        class="form-control mt-2 w-full  @error('netPremium') !border-danger-500 @enderror">
                                    @error('netPremium')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                <div class="from-group">
                                    <label for="lastName" class="form-label">Payment Frequency</label>
                                    <select name="basicSelect" id="basicSelect"
                                        class="form-control w-full mt-2  @error('payment_frequency') !border-danger-500 @enderror"
                                        wire:model="payment_frequency">
                                        <option
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            Select an option...</option>
                                        @foreach ($PAYMENT_FREQS as $freqs)
                                            <option value="{{ $freqs }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ ucwords($freqs) }}
                                            </option>
                                        @endforeach

                                    </select>
                                    @error('payment_frequency')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="from-group">
                                    <label for="lastName" class="form-label">Is Renewal</label>
                                    <div class="flex items-center mr-2 sm:mr-4 mt-2 space-x-2">
                                        <label
                                            class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" checked class="sr-only peer"
                                                wire:model="optionIsRenewal">
                                            <div
                                                class="w-14 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:z-10 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-500">
                                            </div>
                                            <span
                                                class="absolute left-1 z-20 text-xs text-white font-Inter font-normal opacity-0 peer-checked:opacity-100">On</span>
                                            <span
                                                class="absolute right-1 z-20 text-xs text-white font-Inter font-normal opacity-100 peer-checked:opacity-0">Off</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            @if ($payment_frequency === 'installements')
                                <div class="from-group mt-3">
                                    <label for="lastName" class="form-label">Installments Count</label>
                                    <input name="basicSelect"
                                        class="form-control mt-2 w-full !border-success-500 @error('installmentsCount') !border-danger-500 @enderror"
                                        type="number" wire:model="installmentsCount">
                                    @error('installmentsCount')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif

                            <div class="input-area mt-3">
                                <div class="filegroup">
                                    <label>
                                        <label for="time-date-picker" class="form-label">
                                            Upload Files ({{ count($files) ?? '0' }})
                                        </label>
                                        <input type="file" class="w-full hidden " name="basic"
                                            multiple="multiple" wire:model="files" />
                                        <span
                                            class="w-full h-[40px] file-control flex items-center custom-class  @error('files') !border-danger-500 @enderror">
                                            <span class="flex-1 overflow-hidden text-ellipsis whitespace-nowrap">
                                                <span id="placeholder" class="text-slate-400">
                                                    @foreach ($files as $file)
                                                        <span
                                                            class="badge bg-slate-900 text-white capitalize rounded-3xl">{{ $file->getClientOriginalName() }}</span>
                                                    @endforeach
                                                    @if (empty($files))
                                                        Choose a file or drop it here...
                                                    @endif
                                                </span>
                                            </span>
                                            <span
                                                class="file-name flex-none cursor-pointer border-l px-4 border-slate-200 dark:border-slate-700 h-full inline-flex items-center bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 text-sm rounded-tr rounded-br font-normal">Browse</span>
                                        </span>
                                    </label>
                                    @error('files')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                    @error('files.*')
                                        @foreach ($errors->get('files.*') as $each_file_errors)
                                            @foreach ($each_file_errors as $msg)
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $msg }}</span>
                                            @endforeach
                                        @endforeach
                                    @enderror
                                </div>
                            </div>

                            <div class="from-group mt-3">
                                @if (!empty($fields))
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                        <div class="input-area">
                                            <label for="time-date-picker" class="form-label"
                                                style="margin: 0">Field</label>
                                        </div>
                                        <div class="input-area">
                                            <label for="time-date-picker" class="form-label"
                                                style="margin: 0">Value</label>
                                        </div>
                                    </div>
                                @endif
                                @foreach ($fields as $index => $field)
                                    <div class="grid grid-cols-8 md:grid-cols-8 lg:grid-cols-8 gap-2 items-center">
                                        <div class="input-area col-span-4">
                                            <input
                                                class="form-control w-full mt-2  @error('fields.{{ $index }}.field') !border-danger-500 @enderror"
                                                wire:model="fields.{{ $index }}.field" type="text"
                                                placeholder="Field">
                                        </div>
                                        <div class="input-area col-span-3">
                                            <input
                                                class="form-control w-full mt-2   @error('fields.{{ $index }}.value') !border-danger-500 @enderror"
                                                wire:model="fields.{{ $index }}.value" type="number"
                                                placeholder="Value">
                                        </div>
                                        <div class="col-span-1 flex items-center">
                                            <button class="action-btn"
                                                wire:click="removeField({{ $index }})" type="button">
                                                <iconify-icon icon="heroicons:trash"></iconify-icon>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach

                                <button wire:click="addAnotherField"
                                    class="btn btn-sm mt-2 inline-flex justify-center btn-dark">Add Field</button>
                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addOption" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="addOption"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($subStatusSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Set Sub Status
                            </h3>
                            <button wire:click="closeSubStatusSection" type="button"
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
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                <div class="from-group">
                                    <label for="subStatus" class="form-label">Sub Status</label>
                                    <input type="text" name="subStatus" class="form-control mt-2 w-full"
                                        wire:model.defer="subStatus">
                                    @error('subStatus')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setSubStatus" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($editDueSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Edit Due Date
                            </h3>
                            <button wire:click="toggleEditDue" type="button"
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
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                <div class="input-area mb-3">
                                    <label for="time-date-picker" class="form-label">Due Date</label>
                                    <input
                                        class="form-control py-2 flatpickr cursor-pointer flatpickr-input active @error('dueDate') !border-danger-500 @enderror"
                                        id="default-picker" type="date" wire:model.defer="dueDate"
                                        autocomplete="off">
                                    @error('dueDate')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="input-area mb-3">
                                    <label for="time-date-picker" class="form-label">Time </label>
                                    <input type="time"
                                        class="form-control  @error('dueTime') !border-danger-500 @enderror"
                                        id="appt" name="appt" min="09:00" max="18:00"
                                        wire:model.defer="dueTime" autocomplete="off" />
                                    {{-- <input class="form-control cursor-pointer py-2 flatpickr time flatpickr-input active @error('dueTime') !border-danger-500 @enderror" id="time-picker" data-enable-time="true" value="" type="text" wire:model.defer="dueTime" autocomplete="off"> --}}
                                    @error('dueTime')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror

                                </div>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editDue" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($setRenewalSec)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Set as Renewal
                            </h3>
                            <button wire:click="closeSetRenewal" type="button"
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
                            <div class="from-group">
                                <p class="text-lg"><b>Select renewal policy</b></p>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">
                                        @if (isset($selectedPolicy) && $selectedPolicy)
                                            Selected Policy
                                        @else
                                            Search policy <iconify-icon wire:loading wire:target="searchPolicyText"
                                                class="loading-icon text-lg"
                                                icon="line-md:loading-twotone-loop"></iconify-icon>
                                        @endif

                                    </label>
                                    @if (isset($selectedPolicy) && $selectedPolicy)
                                        {{ $selectedPolicy->policy_number . ' | ' . $selectedpolicy->client?->name }}
                                        <Span wire:click="clearSelectedPolicy"
                                            class="cursor-pointer text-primary-500">clear</Span></p>
                                    @else
                                        <input placeholder="Search policy..." type="text"
                                            class="form-control  @error('selectedPolicy') !border-danger-500 @enderror"
                                            wire:model="searchPolicyText">
                                        @error('selectedPolicy')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    @endif

                                </div>
                            </div>
                            <div class="text-sm">
                                @if ($searchedPolicies)
                                    @foreach ($searchedPolicies as $searchedPolicy)
                                        <p><iconify-icon icon="iconoir:privacy-policy"></iconify-icon>
                                            {{ $searchedPolicy->policy_number }} |
                                            {{ $searchedpolicy->client?->name ?? 'N/A' }} | <Span
                                                wire:click="selectRenewalPolicy({{ $searchedPolicy->id }})"
                                                class="cursor-pointer text-primary-500">Select Policy</Span></p>
                                    @endforeach

                                @endif
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setIsRenewal" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <iconify-icon class="text-xl spin-slow rtl:ml-2 relative top-[1px]" wire:loading
                                    wire:target="setIsRenewal" icon="line-md:loading-twotone-loop"></iconify-icon>
                                <span wire:loading.remove wire:target="setIsRenewal">Submit</span>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($editNoteSec)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Edit Note
                            </h3>
                            <button wire:click="toggleEditNote" type="button"
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
                                <label for="time-date-picker" class="form-label">Note</label>
                                <textarea rows=3
                                    class="form-control py-2 flatpickr cursor-pointer flatpickr-input active @error('offerNote') !border-danger-500 @enderror"
                                    id="default-picker" type="text" wire:model.defer="offerNote" autocomplete="off"></textarea>
                                @error('offerNote')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-area mb-3">
                                <label for="inFavorTo" class="form-label">In Favor To</label>
                                <input name="inFavorTo"
                                    class="form-control py-2 flatpickr cursor-pointer flatpickr-input active @error('inFavorTo') !border-danger-500 @enderror"
                                    id="default-picker" type="text" wire:model.defer="inFavorTo"
                                    autocomplete="off">
                                @error('inFavorTo')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Modal footer -->
                            <div
                                class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                <button wire:click="editNote" data-bs-dismiss="modal"
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

    @if ($addFieldSection_id)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Add Field
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
                                <label class="form-label">Field Name</label>
                                <input class="form-control py-2 @error('newFieldName') !border-danger-500 @enderror"
                                    id="default-picker" type="text" wire:model.defer="newFieldName"
                                    autocomplete="off">
                                @error('newFieldName')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-area mb-3">
                                <label class="form-label">Value </label>
                                <input type="text"
                                    class="form-control  @error('newFieldValue') !border-danger-500 @enderror"
                                    id="appt" name="appt" min="09:00" max="18:00"
                                    wire:model.defer="newFieldValue" autocomplete="off" />
                                {{-- <input class="form-control cursor-pointer py-2 flatpickr time flatpickr-input active @error('dueTime') !border-danger-500 @enderror" id="time-picker" data-enable-time="true" value="" type="text" wire:model.defer="dueTime" autocomplete="off"> --}}
                                @error('newFieldValue')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                            </div>
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
    @endif

    @if ($editInfoSection)
    @endif

    @if ($editOptionId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Create Option
                            </h3>
                            <button wire:click="closeEditOption" type="button"
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
                        <div class="p-6">
                            <label for="lastName" class="form-label" style="margin: 0">Policy</label>
                            <p>{{ $policyData->company->name }} | {{ $policyData->company->name }}</p><br>
                            @if ($conditionData)
                                <label for="lastName" class="form-label" style="margin:0">Condition</label>
                                <p>{{ ucwords(str_replace('_', ' ', $conditionData->scope)) }}
                                    <b>
                                        {{ $conditionData->operator == 'gte' ? '>=' : ($conditionData->operator == 'gt' ? '>' : ($conditionData->operator == 'lte' ? '<=' : ($conditionData->operator == 'lt' ? '<' : ($conditionData->operator == 'e' ? '=' : '')))) }}
                                    </b>
                                    {{ $conditionData->value }} | Rate:{{ $conditionData->value }}
                                </p>
                            @endif
                            <br>

                            <div class="from-group">
                                <label for="lastName" class="form-label">Insured Value</label>
                                <input type="text" class="form-control mt-2 w-full"
                                    wire:model.defer="insured_value">
                                @error('insured_value')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                <div class="from-group mt-3">
                                    <label for="lastName" class="form-label ">Gross Premium</label>
                                    <input wire:model="grossPremium" type="text"
                                        class="form-control mt-2 w-full @error('grossPremium') !border-danger-500 @enderror">
                                    @error('grossPremium')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="from-group mt-3">
                                    <label for="lastName" class="form-label">Net Premium</label>
                                    <input wire:model="netPremium" type="text"
                                        class="form-control mt-2 w-full  @error('netPremium') !border-danger-500 @enderror">
                                    @error('netPremium')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                <div class="from-group">
                                    <label for="lastName" class="form-label">Payment Frequency</label>
                                    <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2"
                                        wire:model="payment_frequency">
                                        <option
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            Select an option...</option>
                                        @foreach ($PAYMENT_FREQS as $freqs)
                                            <option value="{{ $freqs }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ $freqs }}
                                            </option>
                                        @endforeach

                                    </select>
                                    @error('payment_frequency')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="from-group">
                                    <label for="lastName" class="form-label">Is Renewal</label>
                                    <div class="flex items-center mr-2 sm:mr-4 mt-2 space-x-2">
                                        <label
                                            class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" checked class="sr-only peer"
                                                wire:model="optionIsRenewal">
                                            <div
                                                class="w-14 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:z-10 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-500">
                                            </div>
                                            <span
                                                class="absolute left-1 z-20 text-xs text-white font-Inter font-normal opacity-0 peer-checked:opacity-100">On</span>
                                            <span
                                                class="absolute right-1 z-20 text-xs text-white font-Inter font-normal opacity-100 peer-checked:opacity-0">Off</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editOption" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($editAssigneeSec)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Edit Assignee
                            </h3>
                            <button wire:click="toggleEditAssignee" type="button"
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
                        <div class="p-6">
                            <div class="from-group">
                                <label for="lastName" class="form-label">Select Assignee</label>
                                <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2"
                                    wire:model="newAsignee">
                                    <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                        Select user</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ $user->first_name . ' ' . $user->last_name }}
                                        </option>
                                    @endforeach
                                    @foreach ($usersTypes as $type)
                                        <option value="{{ $type }}"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            <b>{{ ucwords($type) }} Team </b>
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="changeAsignee" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($showOfferFieldsModal)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Edit Line Fields
                            </h3>
                            <button wire:click="closeOfferFieldsModal" type="button"
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
                            @error('lineFields')
                                <div
                                    class="py-[18px] px-6 font-normal font-Inter text-sm rounded-md bg-danger-500 bg-opacity-[14%] text-danger-500 mb-2">
                                    <div class="flex items-start space-x-3 rtl:space-x-reverse">
                                        <div class="flex-1">
                                            {{ $message }}
                                        </div>
                                    </div>
                                </div>
                            @enderror

                            @foreach ($lineFields as $index => $item)
                                <div class="input-area mb-3">
                                    <label class="form-label">
                                        {{ $item['field'] }}
                                        @if ($item['is_mandatory'])
                                            <span class="text-xs text-danger-500">(Mandatory)</span>
                                        @endif
                                    </label>

                                    <input type="text"
                                        class="form-control @error("lineFields.{$index}.value") !border-danger-500 @enderror"
                                        wire:model.defer="lineFields.{{ $index }}.value"
                                        autocomplete="off" />

                                    @error("lineFields.{$index}.value")
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setOfferFields" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($deleteDiscountId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                                rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-danger-500">
                            <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                Delete Discount
                            </h3>
                            <button wire:click="dismissDeleteDiscount" type="button"
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
                                Are you sure ! you Want to delete this Discount ?
                            </h6>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="deleteDiscount" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-danger-500">Yes, Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($deleteOptionId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                                rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-danger-500">
                            <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                Delete Option
                            </h3>
                            <button wire:click="dismissDeleteOption" type="button"
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
                                Are you sure ! you Want to delete this Option ?
                            </h6>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="deleteOption" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-danger-500">Yes, Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($deleteThisOffer)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                                rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-danger-500">
                            <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                Delete Offer
                            </h3>
                            <button wire:click="dismissDeleteOffer" type="button"
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
                                Are you sure ! you Want to delete this Offer ?
                            </h6>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="deleteOffer" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-danger-500">Yes, Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($whatsappMsgSec)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Send Comparison via Whatsapp Message
                            </h3>
                            <button wire:click="toggleWhatsappSection" type="button"
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
                                <label for="whatsappMsgPhone" class="form-label">Phone</label>
                                <select name="whatsappMsgPhone" id="basicSelect"
                                    class="form-control w-full mt-2 @error('whatsappMsgPhone') !border-danger-500 @enderror"
                                    wire:model="whatsappMsgPhone">
                                    <option> Select an option...</option>
                                    @foreach ($offer->client->phones as $phone)
                                        <option value="{{ $phone->number }}">{{ $phone->number }}</option>
                                    @endforeach
                                    @if ($offer->client_type === 'corporate')
                                        @foreach ($offer->client->contacts as $contact)
                                            <option value="{{ $contact->phone }}">{{ $contact->name }} |
                                                {{ $contact->phone }}</option>
                                        @endforeach
                                    @endif
                                    <option value="other">other</option>
                                </select>
                            </div>
                            @if ($whatsappMsgPhone === 'other')
                                <input type="number"
                                    class="form-control w-full mt-2 @error('otherPhone') !border-danger-500 @enderror"
                                    wire:model="otherPhone" placeholder="Enter Phone...">
                                @error('otherPhone')
                                    <span
                                        class="font-Inter text-danger-500 pt-2 inline-block text-xs">{{ $message }}</span>
                                @enderror
                            @endif
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="submitWhastappMsg" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($showMedicalFileModal)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Upload Medical File
                            </h3>
                            <button wire:click="closeMedicalFileModal" type="button"
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
                            <p class="text-lg mt-3"><b>Upload Clients</b></p>
                            <div class="input-area mt-3">
                                <input wire:model="uploadedMedicalFile" type="file"
                                    class="form-control w-full " name="basic" />
                                @error('uploadedMedicalFile')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setIsRenewal" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <iconify-icon class="text-xl spin-slow rtl:ml-2 relative top-[1px]" wire:loading
                                    wire:target="setIsRenewal" icon="line-md:loading-twotone-loop"></iconify-icon>
                                <span wire:loading.remove wire:target="setIsRenewal">Submit</span>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($showDownloadPolicyCalculationModal)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Download Policy Calculated File
                            </h3>
                            <button wire:click="closePolicyCalculationModal" type="button"
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
                            <div class="from-group">
                                <label for="lastName" class="form-label">Medical Policy</label>
                                <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2"
                                    wire:model="selectedMedicalPolicyCalculation">
                                    <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                        Select an option...</option>
                                    @foreach ($type_policies as $p)
                                        <option value="{{ $p->id }}"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ $p->company?->name }} - {{ $p->name }}
                                        </option>
                                    @endforeach

                                </select>
                                @error('selectedMedicalPolicyCalculation')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="downloadCalculatedFile" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <iconify-icon class="text-xl spin-slow rtl:ml-2 relative top-[1px]" wire:loading
                                    wire:target="downloadCalculatedFile"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                                <span wire:loading.remove wire:target="downloadCalculatedFile">Download</span>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if ($emailMsgSec)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Send Comparison via Email
                            </h3>
                            <button wire:click="toggleEmailMsgSection" type="button"
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

                            @if ($offer->client_type === 'customer')

                                <div class="from-group">
                                    <label for="whatsappMsgPhone" class="form-label">Email</label>
                                    <select name="whatsappMsgPhone" id="basicSelect"
                                        class="form-control w-full mt-2 @error('emailMsgEmail') !border-danger-500 @enderror"
                                        wire:model="emailMsgEmail">
                                        <option>Select an option...</option>
                                        <option value="{{ $offer->client->email }}">{{ $offer->client->email }}
                                        </option>
                                        <option value="other">other</option>
                                    </select>
                                </div>
                                @if ($emailMsgEmail === 'other')
                                    <input type="email"
                                        class="form-control w-full mt-2 @error('otherEmail') !border-danger-500 @enderror"
                                        wire:model="otherEmail" placeholder="Enter Email...">
                                    @error('otherEmail')
                                        <span
                                            class="font-Inter text-danger-500 pt-2 inline-block text-xs">{{ $message }}</span>
                                    @enderror
                                @endif
                            @elseif($offer->client_type === 'corporate')
                                <div class="from-group">
                                    <label for="whatsappMsgPhone" class="form-label">Email</label>
                                    <select name="whatsappMsgPhone" id="basicSelect"
                                        class="form-control w-full mt-2 @error('emailMsgEmail') !border-danger-500 @enderror"
                                        wire:model="emailMsgEmail">
                                        <option>Select an option...</option>
                                        <option value="{{ $phone->client->email }}">Corporate email:
                                            {{ $phone->client->email }}</option>
                                        @foreach ($offer->client->contacts as $contact)
                                            <option value="{{ $contact->email }}">Contact email:
                                                {{ $contact->name }} | {{ $contact->email }}</option>
                                        @endforeach
                                        <option value="other">other</option>
                                    </select>
                                </div>
                                @if ($emailMsgEmail === 'other')
                                    <input type="email"
                                        class="form-control w-full mt-2 @error('otherEmail') !border-danger-500 @enderror"
                                        wire:model="otherEmail" placeholder="Enter Email...">
                                    @error('otherEmail')
                                        <span
                                            class="font-Inter text-danger-500 pt-2 inline-block text-xs">{{ $message }}</span>
                                    @enderror
                                @endif

                            @endif

                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="submitEmailMsg" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($showPolicyNoteModal)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="policyNoteModal" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                        <h3 class="text-xl font-medium text-white dark:text-white">
                            Policy Note
                        </h3>
                        <button wire:click="hidePolicyNote" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 space-y-6">
                        <p class="text-base leading-relaxed text-slate-500 dark:text-slate-400">
                            {{ $currentPolicyNote }}
                        </p>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="hidePolicyNote" type="button" class="btn btn-secondary">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Backdrop -->
        <div class="modal-backdrop fade show" style="display: block;"></div>
    @endif
</div>
