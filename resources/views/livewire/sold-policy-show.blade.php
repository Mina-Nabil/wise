<div>
    <div class="bg-no-repeat bg-cover mb-5 p-5 rounded-[6px] relative  flex justify-between"
        style="background-color: black; padding: 30px;padding-bottom: 5px;">
        <div class="max-w-xl">

            <h4 class=" font-medium text-white mb-2">
                <div>
                    <span class="block"><b><iconify-icon icon="iconoir:privacy-policy"></iconify-icon>
                            {{ $soldPolicy->policy->company->name }} - {{ $soldPolicy->policy->name }} -
                            {{ $soldPolicy->policy_number }}
                        </b></span>
                </div>
                <span class="block mb-3">
                    <p class="text-sm text-slate-400  font-light">
                        {{ ucwords($soldPolicy->client_type) }}
                    </p>
                    <a class="hover:underline cursor-pointer"
                        href="{{ route($soldPolicy->client_type . 's.show', $soldPolicy->client_id) }}">
                        @if ($soldPolicy->client_type === 'customer')
                            <iconify-icon icon="raphael:customer"></iconify-icon>
                            {{ $soldPolicy->client->first_name . ' ' . $soldPolicy->client->middle_name . ' ' . $soldPolicy->client->last_name }}
                        @elseif($soldPolicy->client_type === 'corporate')
                            <iconify-icon icon="mdi:company"></iconify-icon> {{ $soldPolicy->client->name }}
                        @endif
                    </a>
                </span>
                @if ($soldPolicy->in_favor_to)
                    <span class="block mb-3">
                        <p class="text-sm text-slate-400  font-light">
                            In Favor To
                        </p>
                        <iconify-icon icon="mdi:company"></iconify-icon>
                        {{ ucwords($soldPolicy->in_favor_to) }}
                    </span>
                @endif



            </h4>
            @if ($soldPolicy->policy_doc)
                <button wire:click="downloadDoc" class="btn btn-sm btn-dark text-left">
                    Check policy details
                </button>
            @endif
        </div>
        <h4 class=" font-medium text-white mb-2">


            <p class="text-sm text-white font-normal">
                Issuing: {{ \Carbon\Carbon::parse($soldPolicy->created_at)->format('l d/m/Y') }}

            </p>

            <p class="text-sm text-white font-normal">
                Start: {{ \Carbon\Carbon::parse($soldPolicy->start)->format('l d/m/Y') }}

            </p>
            <p class="text-sm text-white font-normal mb-3">
                Expired: {{ \Carbon\Carbon::parse($soldPolicy->expiry)->format('l d/m/Y') }}
            </p>
        </h4>

    </div>

    <div class="flex justify-between">

        {{-- Nav --}}
        <div class="card-body flex flex-col col-span-2 mb-5" wire:ignore>
            <div class="card-text h-full">
                <div>
                    <ul class="nav nav-tabs flex flex-col md:flex-row flex-wrap list-none border-b-0 pl-0"
                        id="tabs-tab" role="tablist">
                        <li class="nav-item" role="presentation" wire:click="changeSection('profile')">
                            <a href="#tabs-profile-withIcon"
                                class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'profile') active @endif dark:text-slate-300"
                                id="tabs-profile-withIcon-tab" data-bs-toggle="pill"
                                data-bs-target="#tabs-profile-withIcon" role="tab"
                                aria-controls="tabs-profile-withIcon" aria-selected="false">
                                <iconify-icon class="mr-1" icon="heroicons-outline:user"></iconify-icon>
                                Profile</a>
                        </li>

                        <li class="nav-item" role="presentation" wire:click="changeSection('payments')">
                            <a href="#tabs-messages-withIcon"
                                class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'tasks') active @endif dark:text-slate-300"
                                id="tabs-messages-withIcon-tab" data-bs-toggle="pill"
                                data-bs-target="#tabs-messages-withIcon" role="tab"
                                aria-controls="tabs-messages-withIcon" aria-selected="false">
                                <iconify-icon class="mr-1" icon="material-symbols:payments"></iconify-icon>
                                Payments</a>
                        </li>

                        <li class="nav-item" role="presentation" wire:click="changeSection('cars')">
                            <a href="#tabs-messages-withIcon"
                                class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'cars') active @endif dark:text-slate-300"
                                id="tabs-messages-withIcon-tab" data-bs-toggle="pill"
                                data-bs-target="#tabs-messages-withIcon" role="tab"
                                aria-controls="tabs-messages-withIcon" aria-selected="false">
                                <iconify-icon class="mr-1" icon="mingcute:car-line"></iconify-icon>
                                Car Specs</a>
                        </li>
                        <li class="nav-item" role="presentation" wire:click="changeSection('operations')">
                            <a href="#tabs-messages-withIcon"
                                class="@if (!$soldPolicy->endorsements->isEmpty()) text-danger-500 @else dark:text-slate-300! @endif  w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'operations') active @endif "
                                id="tabs-messages-withIcon-tab" data-bs-toggle="pill"
                                data-bs-target="#tabs-messages-withIcon" role="tab"
                                aria-controls="tabs-messages-withIcon" aria-selected="false">
                                <iconify-icon class="mr-1" icon="cib:when-i-work"></iconify-icon>
                                Operations</a>
                        </li>
                        <li class="nav-item" role="presentation" wire:click="changeSection('policydetails')">
                            <a href="#tabs-messages-withIcon"
                                class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'policydetails') active @endif dark:text-slate-300"
                                id="tabs-messages-withIcon-tab" data-bs-toggle="pill"
                                data-bs-target="#tabs-messages-withIcon" role="tab"
                                aria-controls="tabs-messages-withIcon" aria-selected="false">
                                <iconify-icon class="mr-1" icon="ic:outline-info"></iconify-icon>
                                Details</a>
                        </li>

                        {{-- <li class="nav-item" role="presentation" wire:click="changeSection('claims')">
                        <a href="#tabs-messages-withIcon" class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'claims') active @endif dark:text-slate-300" id="tabs-messages-withIcon-tab" data-bs-toggle="pill" data-bs-target="#tabs-messages-withIcon" role="tab" aria-controls="tabs-messages-withIcon"
                            aria-selected="false">
                            <iconify-icon class="mr-1" icon="academicons:acclaim"></iconify-icon>
                            Claims</a>
                    </li> --}}
                    </ul>
                </div>
            </div>
        </div>

        {{-- Action btn --}}
        <div class="flex justify-end mb-5">
            <div class="dropdown relative">
                <button class="btn btn-sm inline-flex justify-center btn-secondary items-center" type="button"
                    id="secondaryDropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading
                        wire:target="docFile" icon="line-md:loading-twotone-loop"></iconify-icon>
                    Actions
                    <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2"
                        icon="ic:round-keyboard-arrow-down"></iconify-icon>
                </button>
                <ul
                    class=" dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow
                        z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                    <li>
                        <a wire:click="openEditInfoSection"
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                dark:hover:text-white cursor-pointer">
                            Edit</a>
                    </li>
                    @can('generateRenewalOffer', $soldPolicy)
                    <li>
                        <a wire:click="toggleGenerateRenewalOfferSec"
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                dark:hover:text-white cursor-pointer">
                            Generate Renewal Offer</a>
                    </li>
                    @endcan
                    @can('update', $soldPolicy)
                    <li>
                        <a wire:click="generatePolicyCommission"
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                dark:hover:text-white cursor-pointer">
                            Generate Policy Commission</a>
                    </li>
                    @endcan
                    @if (!$soldPolicy->client_payment_date)
                        <li>
                            <a wire:click="openPaymentDateSec"
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                dark:hover:text-white cursor-pointer">
                                Set client payment date</a>
                        </li>
                    @endif
                    @can('updatePayments', $soldPolicy)
                        @if ($soldPolicy->cancellation_time)
                            <li>
                                <a wire:click="openCancellationDateSec"
                                    class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                dark:hover:text-white cursor-pointer">
                                    Set client cancellation date</a>
                            </li>
                        @endif
                    @endcan
                    @can('updatePenalty', $soldPolicy)
                        <li>
                            <a wire:click="openPenaltyModal"
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                dark:hover:text-white cursor-pointer">
                                Update Penalty</a>
                        </li>
                    @endcan
                    @if (!$soldPolicy->main_sales)
                        <li>
                            <a wire:click="openSetMainSalesSection"
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                dark:hover:text-white cursor-pointer">
                                Set main sales</a>
                        </li>
                    @endif
                    {{-- @if ($soldPolicy->is_valid)
                        <li>
                            <a wire:click="setInvalid"
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                dark:hover:text-white cursor-pointer">
                                Set as invalid</a>
                        </li>
                    @else
                        <li>
                            <a wire:click="setValid"
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                dark:hover:text-white cursor-pointer">
                                Set as Valid</a>
                        </li>
                    @endif --}}
                    {{-- @if ($soldPolicy->is_paid)
                        <li>
                            <a wire:click="setUnpaid"
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                dark:hover:text-white cursor-pointer">
                                Set as unpaid</a>
                        </li>
                    @else
                        <li>
                            <a wire:click="openSetPaidSec"
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                dark:hover:text-white cursor-pointer">
                                Set as Paid</a>
                        </li>
                    @endif --}}
                    @if (!$soldPolicy->policy_doc)
                        <label for="uploadDoc">
                            <a
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                dark:hover:text-white cursor-pointer">
                                Add document</a>
                        </label>
                        <input type="file" style="display: none" name="uploadDoc" id="uploadDoc"
                            wire:model="docFile">
                    @endif
                    @can('delete', $soldPolicy)
                        <li>
                            <a wire:click="toggleDeleteSoldPolicy"
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                dark:hover:text-white cursor-pointer">
                                Delete Sold Policy</a>
                        </li>
                    @endcan
                </ul>
            </div>

            @error('docFile')
                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
            @enderror
        </div>

    </div>

    {{-- Profile --}}
    @if ($section === 'profile')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base mb-5">
                    <div class="card-body flex flex-col p-6 active justify-center">
                        <div>
                            <span class="text-xs text-slate-500 dark:text-slate-400 block mb-1">
                                Policy Number
                                <span class="float-right">
                                    @if ($soldPolicy->is_valid)
                                        <span class="badge bg-success-500 text-white capitalize">Valid</span>
                                    @else
                                        <span class="badge bg-warning-500 text-white capitalize">Invalid</span>
                                    @endif

                                    @if ($soldPolicy->offer?->is_renewal)
                                        <span class="badge bg-success-500 text-white capitalize">Renwal</span>
                                    @endif
                                    @if ($soldPolicy->is_penalized)
                                        <span class="badge bg-danger-500 text-white capitalize">Penalty</span>
                                    @endif
                                    @if ($soldPolicy->cancellation_time)
                                        <span
                                            class="badge bg-danger-500 text-slate-800 
                                    text-danger-500 bg-opacity-30 capitalize rounded-3xl">Cancelled
                                            on:
                                            {{ \Carbon\Carbon::parse($soldPolicy->cancellation_time)->format('D d/m/Y') }}
                                        </span>
                                    @endif
                                </span>
                            </span>
                            <span class="text-lg font-medium text-slate-900 dark:text-white block">
                                {{ $soldPolicy->policy_number }}
                            </span>
                        </div>
                    </div>
                </div>

                @if ($soldPolicy->fields->isNotEmpty())
                    <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base mb-5">
                        <div class="card-body flex flex-col p-6 active justify-center">

                            Application Fields

                            @foreach ($soldPolicy->fields as $f)
                                <div>
                                    <span class="text-xs text-slate-500 dark:text-slate-400 block mb-1">
                                        {{ $f->field }}
                                    </span>
                                    <span class="text-lg font-medium text-slate-900 dark:text-white block">
                                        {{ $f->value }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($clientPaymentDate)
                    <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base mb-5">
                        <div class="card-body flex flex-col p-6 active justify-center">
                            <div>
                                <span class="text-xs text-slate-500 dark:text-slate-400 block mb-1">
                                    Client payment Date
                                    @can('updateClientPaymentDate', $soldPolicy)
                                        <span class="float-right">
                                            <iconify-icon wire:click="openPaymentDateSec" class="cursor-pointer"
                                                icon="carbon:edit"></iconify-icon>
                                        </span>
                                    @endcan
                                </span>
                                <span class="text-lg font-medium text-slate-900 dark:text-white block">
                                    {{ \Carbon\Carbon::parse($clientPaymentDate)->format('l d/m/Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($soldPolicy->in_favor_to)
                    <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base mt-5">
                        <div class="card-body flex flex-col p-6 active justify-center">
                            <div>
                                <p>
                                    <b>In favor to</b>
                                </p>
                                <p class="mb-5">
                                    {{ $soldPolicy->in_favor_to ?? 'in favor to not added.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($soldPolicy->policy_doc)
                    <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base mt-5">
                        <div class="card-body flex flex-col p-6 active justify-center">
                            <div class="flex justify-between gap-2">
                                <button wire:click="downloadDoc"
                                    class="btn inline-flex justify-center btn-success block-btn btn-sm w-3/4">
                                    <span class="flex items-center">
                                        <iconify-icon wire:loading.remove wire:target="downloadDoc" class="text-xl"
                                            icon="material-symbols:download"></iconify-icon>
                                        <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                            wire:loading wire:target="downloadDoc"
                                            icon="line-md:loading-twotone-loop"></iconify-icon>
                                        <span>Download document</span>
                                    </span>
                                </button>
                                <button wire:click="toggleDeleteDoc"
                                    class="btn inline-flex justify-center  btn-danger  btn-sm w-1/4">
                                    <span class="flex items-center">
                                        <iconify-icon wire:loading.remove wire:target="toggleDeleteDoc"
                                            class="text-xl  ltr:mr-2 rtl:ml-2"
                                            icon="material-symbols:delete-outline"></iconify-icon>
                                        <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                            wire:loading wire:target="toggleDeleteDoc"
                                            icon="line-md:loading-twotone-loop"></iconify-icon>
                                        <span>Remove</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Insured values --}}
                <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base mb-5">
                    <div class="card-body flex flex-col p-6 active text-center">
                        <header class=" mb-5 items-center">
                            <button wire:click="togglePaymentInfoSection" class="action-btn float-right text-sm"
                                type="button">
                                <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                            </button>
                            <div class="flex-1">
                                <div class="card-title font-Inter text-slate-900 dark:text-white">
                                    {{ number_format($soldPolicy->insured_value, 0, '.', ',') }}</div>
                                <div class="card-subtitle font-Inter">Insured Value</div>
                                @if ($soldPolicy->payment_frequency)
                                    <div
                                        class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 bg-warning-500 text-xs">
                                        {{ ucwords(str_replace('-', ' ', $soldPolicy->payment_frequency)) }}
                                        @if ($soldPolicy->installements_count)
                                            <span
                                                class="w-5 h-5 inline-flex items-center justify-center bg-danger-500 text-white rounded-full font-Inter text-xs ltr:ml-1 rtl:mr-1 relative">
                                                {{ $soldPolicy->installements_count }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <hr class="mb-3 w-[96px]" style="margin: 0 auto;margin-top:10px;">
                        </header>

                        <div class="grid md:grid-cols-4 gap-3 mb-4 text-base text-center">
                            <div class="border-r">
                                <h5>{{ number_format($soldPolicy->net_premium, 2, '.', ',') }}</h5>
                                <p class="text-xs">Net Premium</p>

                            </div>
                            <div class="">
                                <h5 class="text-info-500">{{ $soldPolicy->net_rate }}%</h5>
                                <p class="mr-2 text-xs">Net Rate</p>
                            </div>
                            <div class="border-l">
                                <h5>{{ number_format($soldPolicy->gross_premium, 2, '.', ',') }}</h5>
                                <p class="text-xs">Gross Premium</p>
                            </div>
                            <div class="border-l">
                                <h5>{{ $soldPolicy->discount }} EGP</h5>
                                <p class="text-xs">Comm. Discount</p>
                            </div>
                            <div class="border-r">
                                <h5>{{ $soldPolicy->origin_discount }} EGP</h5>
                                <p class="text-xs">Origin Discount</p>
                            </div>
                            <div class="">
                                <h5>{{ $soldPolicy->penalty_amount }} EGP</h5>
                                <p class="text-xs">Penalty</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($soldPolicy->renewal_policy)
                    <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base mt-5">
                        <div class="card-body flex flex-col p-6 active justify-center">
                            <div>
                                <div class="flex justify-between">
                                    <b>Renewal Policy</b>
                                    <a href="{{ route('sold.policy.show', $soldPolicy->renewal_policy->id) }}"
                                        target="_blank">
                                        <button class="btn inline-flex justify-center btn-outline-light btn-sm">View
                                            Policy</button>
                                    </a>
                                </div>
                                <p class="mb-5">
                                    Policy Number: {{ $soldPolicy->renewal_policy->policy_number }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div>
                @if ($soldPolicy->is_paid)
                    <span class="badge bg-success-500 text-white capitalize rounded-3xl w-full mb-5"><iconify-icon
                            class="text-lg" icon="mdi:tick-circle"></iconify-icon>&nbsp; Paid:
                        {{ \Carbon\Carbon::parse($soldPolicy->client_payment_date)->format('l d/m/Y') }}</span>
                @endif

                <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base mb-5">
                    <div class="card-body flex flex-col p-6 active justify-center">
                        <div>
                            <p>
                                <iconify-icon icon="carbon:view-filled"></iconify-icon> Watchers
                                <span class="float-right cursor-pointer" wire:click="OpenChangeWatchers">
                                    <iconify-icon icon="carbon:edit"></iconify-icon>
                                </span>
                            </p>
                        </div>

                        <div {{ $changeWatchers ? '' : "style=display:none;'" }}>
                            <div class="w-full">
                                <select wire:model.defer="setWatchersList" id="multiSelect" multiple
                                    aria-label="multiple select example" class="select2 form-control w-full mt-2 py-2"
                                    multiple="multiple" style="height: 250px">
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

                <div class="card rounded-md bg-white dark:bg-slate-800 shadow-base mb-5">
                    <div class="card-body flex flex-col p-6 active justify-center">
                        <div>
                            <p>
                                <iconify-icon icon="carbon:delivery"></iconify-icon> Delivery Type
                                {{ $soldPolicy->delivery_type ? ': (' . str_replace('_', ' ', ucfirst($soldPolicy->delivery_type)) . ')' : '' }}
                                <span class="float-right cursor-pointer" wire:click="openChangeDeliveryType">
                                    <iconify-icon icon="carbon:edit"></iconify-icon>
                                </span>
                            </p>
                        </div>

                        <div {{ $changeDeliveryType ? '' : 'style=display:none;' }}>
                            <div class="w-full">
                                <select wire:model.defer="deliveryType" class="form-control w-full mt-2 py-2">
                                    @foreach ($DEL_TYPES as $type)
                                        <option value="{{ $type }}">
                                            {{ str_replace('_', ' ', ucfirst($type)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button wire:click="saveDeliveryType"
                                class="btn inline-flex justify-center btn-success mt-3 float-right btn-sm">
                                <div class="flex items-center">
                                    <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                        wire:loading wire:target="saveDeliveryType"
                                        icon="line-md:loading-twotone-loop"></iconify-icon>
                                    <span>Save</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>


                @can('viewFinanceWhileReview', $soldPolicy)
                    <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base mb-5">
                        <div class="card-body flex flex-col p-6 active justify-center">
                            <div>
                                <span class="text-xs text-slate-500 dark:text-slate-400 block mb-1">
                                    Main sales
                                    @can('updateMainSales', $soldPolicy)
                                        <span class="float-right">
                                            <button wire:click="openSetMainSalesSection" class="action-btn" type="button">
                                                <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                                            </button>
                                        </span>
                                    @endcan
                                </span>
                                <span class="text-lg font-medium text-slate-900 dark:text-white block">
                                    {{ $soldPolicy->main_sales?->full_name }}
                                </span>
                            </div>
                            <div>
                                <span class="text-xs text-slate-500 dark:text-slate-400 block mb-1">
                                    Other Sales Linked
                                </span>
                                <span class="text-lg font-medium text-slate-900 dark:text-white block">
                                    @foreach ($soldPolicy->sales_comms as $comm)
                                        @if (!$soldPolicy->main_sales_id || $soldPolicy->main_sales_id != $comm->comm_profile->user_id)
                                            {{ $comm->comm_profile->title }} &nbsp;
                                        @endif
                                    @endforeach
                                </span>
                            </div>
                        </div>
                    </div>
                @endcan


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

                                @if ($soldPolicy->files->isEmpty())
                                    <div class="text-center text-xs text-slate-500 dark:text-slate-400 mt-1">
                                        No files added to this sold policy.
                                    </div>
                                @endif

                                @foreach ($soldPolicy->files as $file)
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
                                                            wire:click="removeSoldPolicyFile({{ $file->id }})">remove</span>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="flex-none">
                                                <span class="font-normal text-xs text-slate-500 mt-1"></span>
                                                <button wire:click="downloadSoldPolicyFile({{ $file->id }})"
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
                                            icon="svg-spinners:tadpole"></iconify-icon></p>
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

                <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base mb-5 mt-5">
                    <div class="card-body flex flex-col p-6 active justify-center">
                        <div>
                            <p>
                                <b>Note</b>
                                <button wire:click="toggleNoteSection" class="action-btn float-right text-sm"
                                    type="button">
                                    <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                                </button>
                            </p>
                            <p class="mb-5">
                                {{ $soldPolicy->note ?? 'No note added for this sold policy.' }}
                            </p>
                        </div>
                    </div>
                </div>


            </div>

        </div>


    @endif

    @if ($section === 'cars')
        {{-- Car Specs --}}
        <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base mb-5">
            <div class="card-body flex flex-col p-6 active justify-center">
                <header class=" mb-2 items-center text-center">
                    <div class="flex-1">
                        <div class=" text-base font-Inter text-slate-900 dark:text-white">Car specifications
                        </div>
                    </div>
                    <hr class="mb-3 w-[96px]" style="margin: 0 auto;">
                </header>
                @if ($soldPolicy->customer_car)
                    <div class="text-center">
                        {{ $soldPolicy->customer_car->model_year }}
                        {{ $soldPolicy->customer_car->car->car_model->brand->name }}
                        {{ $soldPolicy->customer_car->car->car_model->name }}
                        {{ $soldPolicy->customer_car->car->category }}
                    </div>
                @endif

                <hr class="mt-3 m-5">

                <table class=" divide-slate-100 dark:divide-slate-700">
                    <tbody class="bg-white dark:bg-slate-800 ">

                        <tr>
                            <td class="table-td ">Car Chassis</td>
                            <td class="table-td  !text-lg"><b>{{ $soldPolicy->car_chassis }}</b></td>
                        </tr>

                        <tr>
                            <td class="table-td ">Car Plate No.</td>
                            <td class="table-td  !text-lg"><b>{{ $soldPolicy->car_plate_no }}</b></td>
                        </tr>

                        <tr>
                            <td class="table-td ">Car Engine</td>
                            <td class="table-td  !text-lg"><b>{{ $soldPolicy->car_engine }}</b></td>
                        </tr>



                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if ($section === 'operations')
        {{-- Claims --}}
        <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base mb-5">
            <div class="card-body flex flex-col p-6 active">
                <header class="flex mb-5 items-center">
                    <div class="flex-1">
                        <div class="card-title font-Inter text-slate-900 dark:text-white">
                            <iconify-icon icon="ic:round-add-task"></iconify-icon>
                            Claims
                            <button wire:click="toggleNewClaimSection"
                                class="btn inline-flex justify-center btn-dark shadow-base2 float-right btn-sm">Create</button>
                        </div>

                    </div>
                </header>
                <div>

                    @if (!$soldPolicy->claims->isEmpty())
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class=" border-t border-slate-100 dark:border-slate-800">
                                <tr>

                                    <th scope="col" class=" table-th ">
                                        title
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Fields
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Due
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        status
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                @if ($soldPolicy->claims)
                                    @foreach ($soldPolicy->claims as $claim)
                                        <tr>
                                            <td class="table-td">
                                                <a class="hover:underline cursor-pointer"
                                                    href="{{ route('tasks.show', $claim->id) }}">
                                                    <b>{{ $claim->title }}</b>
                                                </a>
                                            </td>
                                            <td class="table-td ">{{ $claim->fields->count() }}</td>
                                            <td class="table-td ">
                                                {{ \Carbon\Carbon::parse($claim->due)->format('l d/m/Y') }}</td>
                                            <td class="table-td ">

                                                @if ($claim->status === 'new')
                                                    <div
                                                        class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-primary-500 bg-primary-500 text-xs">
                                                        New
                                                    </div>
                                                @elseif($claim->status === 'assigned')
                                                    <div
                                                        class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-info-500 bg-info-500 text-xs">
                                                        Assigned
                                                    </div>
                                                @elseif($claim->status === 'in_progress')
                                                    <div
                                                        class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-secondary-500 bg-secondary-500 text-xs">
                                                        in Progress
                                                    </div>
                                                @elseif($claim->status === 'pending')
                                                    <div
                                                        class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-warning-500 bg-warning-500 text-xs">
                                                        Pending
                                                    </div>
                                                @elseif($claim->status === 'completed')
                                                    <div
                                                        class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-success-500 bg-success-500 text-xs">
                                                        Completed
                                                    </div>
                                                @elseif($claim->status === 'closed')
                                                    <div
                                                        class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-black-500 bg-black-500 text-xs">
                                                        Closed
                                                    </div>
                                                @endif

                                            </td>

                                        </tr>
                                    @endforeach
                                @endif


                            </tbody>
                        </table>
                    @else
                        <div class="text-center">
                            <p class="text-center text-sm m-5 text-primary">No Claims for this sold policy!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Endorsement --}}
        <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base mb-5">
            <div class="card-body flex flex-col p-6 active">
                <header class="flex mb-5 items-center">
                    <div class="flex-1">
                        <div class="card-title font-Inter text-slate-900 dark:text-white">
                            <iconify-icon icon="ic:round-add-task"></iconify-icon>
                            Endorsement
                            <button wire:click="toggleNewEndorsementSection"
                                class="btn inline-flex justify-center btn-dark shadow-base2 float-right btn-sm">Create</button>
                        </div>

                    </div>
                </header>
                <div>

                    @if (!$soldPolicy->endorsements->isEmpty())
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class=" border-t border-slate-100 dark:border-slate-800">
                                <tr>

                                    <th scope="col" class=" table-th ">
                                        title
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Actions
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Due
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        status
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                @if ($soldPolicy->endorsements)
                                    @foreach ($soldPolicy->endorsements as $endorsement)
                                        <tr>
                                            <td class="table-td">
                                                <a class="hover:underline cursor-pointer"
                                                    href="{{ route('tasks.show', $endorsement->id) }}">
                                                    <b>{{ $endorsement->title }}</b>
                                                </a>
                                            </td>
                                            <td class="table-td ">{{ $endorsement->actions->count() }}</td>
                                            <td class="table-td ">
                                                {{ \Carbon\Carbon::parse($endorsement->due)->format('l d/m/Y') }}
                                            </td>
                                            <td class="table-td ">

                                                @if ($endorsement->status === 'new')
                                                    <div
                                                        class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-primary-500 bg-primary-500 text-xs">
                                                        New
                                                    </div>
                                                @elseif($endorsement->status === 'assigned')
                                                    <div
                                                        class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-info-500 bg-info-500 text-xs">
                                                        Assigned
                                                    </div>
                                                @elseif($endorsement->status === 'in_progress')
                                                    <div
                                                        class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-secondary-500 bg-secondary-500 text-xs">
                                                        in Progress
                                                    </div>
                                                @elseif($endorsement->status === 'pending')
                                                    <div
                                                        class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-warning-500 bg-warning-500 text-xs">
                                                        Pending
                                                    </div>
                                                @elseif($endorsement->status === 'completed')
                                                    <div
                                                        class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-success-500 bg-success-500 text-xs">
                                                        Completed
                                                    </div>
                                                @elseif($endorsement->status === 'closed')
                                                    <div
                                                        class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-black-500 bg-black-500 text-xs">
                                                        Closed
                                                    </div>
                                                @endif

                                            </td>

                                        </tr>
                                    @endforeach
                                @endif


                            </tbody>
                        </table>
                    @else
                        <div class="text-center">
                            <p class="text-center text-sm m-5 text-primary">No endorsement for this sold policy!
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base mb-5">
            <div class="card-body flex flex-col p-6 active">
                <header class="flex mb-5 items-center">
                    <div class="flex-1">
                        <div class="card-title font-Inter text-slate-900 dark:text-white">
                            <iconify-icon icon="ic:round-add-task"></iconify-icon>
                            Tasks
                            <button wire:click="toggleNewTaskSection"
                                class="btn inline-flex justify-center btn-dark shadow-base2 float-right btn-sm">Create</button>
                        </div>

                    </div>
                </header>
                <div>

                    @if (!$soldPolicy->tasks->isEmpty())
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class=" border-t border-slate-100 dark:border-slate-800">
                                <tr>

                                    <th scope="col" class=" table-th ">
                                        title
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Fields
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Due
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        status
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                @if ($soldPolicy->tasks)
                                    @foreach ($soldPolicy->tasks as $task)
                                        <tr>
                                            <td class="table-td">
                                                <a class="hover:underline cursor-pointer"
                                                    href="{{ route('tasks.show', $task->id) }}">
                                                    <b>{{ $task->title }}</b>
                                                </a>
                                            </td>
                                            <td class="table-td ">{{ $task->fields->count() }}</td>
                                            <td class="table-td ">
                                                {{ \Carbon\Carbon::parse($task->due)->format('l d/m/Y') }}</td>
                                            <td class="table-td ">

                                                @if ($task->status === 'new')
                                                    <div
                                                        class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-primary-500 bg-primary-500 text-xs">
                                                        New
                                                    </div>
                                                @elseif($task->status === 'assigned')
                                                    <div
                                                        class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-info-500 bg-info-500 text-xs">
                                                        Assigned
                                                    </div>
                                                @elseif($task->status === 'in_progress')
                                                    <div
                                                        class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-secondary-500 bg-secondary-500 text-xs">
                                                        in Progress
                                                    </div>
                                                @elseif($task->status === 'pending')
                                                    <div
                                                        class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-warning-500 bg-warning-500 text-xs">
                                                        Pending
                                                    </div>
                                                @elseif($task->status === 'completed')
                                                    <div
                                                        class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-success-500 bg-success-500 text-xs">
                                                        Completed
                                                    </div>
                                                @elseif($task->status === 'closed')
                                                    <div
                                                        class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-black-500 bg-black-500 text-xs">
                                                        Closed
                                                    </div>
                                                @endif

                                            </td>

                                        </tr>
                                    @endforeach
                                @endif


                            </tbody>
                        </table>
                    @else
                        <div class="text-center">
                            <p class="text-center text-sm m-5 text-primary">No Tasks for this sold policy!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif


    @if ($section === 'policydetails')
        {{-- benefits --}}
        <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base mb-5">
            <div class="card-body flex flex-col p-6 active">
                <header class="flex mb-5 items-center">
                    <div class="flex-1">
                        <div class="card-title font-Inter text-slate-900 dark:text-white">
                            <iconify-icon icon="subway:save"></iconify-icon>
                            benefits
                            <button wire:click="openNewBenefitSec"
                                class="btn inline-flex justify-center btn-dark shadow-base2 float-right btn-sm">New
                                Benefit</button>
                        </div>

                    </div>
                </header>
                <div>
                    @if (!$soldPolicy->benefits->isEmpty())
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class=" border-t border-slate-100 dark:border-slate-800">
                                <tr>

                                    <th scope="col" class=" table-th ">
                                        Benefit
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Value
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Action
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                @foreach ($soldPolicy->benefits as $benefit)
                                    <tr>
                                        <td class="table-td">{{ $benefit->benefit }}</td>
                                        <td class="table-td ">{{ $benefit->value }}</td>
                                        <td class="table-td ">
                                            <div class="flex space-x-3 rtl:space-x-reverse">
                                                <button wire:click="editThisBenefit({{ $benefit->id }})"
                                                    class="action-btn" type="button">
                                                    <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                                                </button>
                                                <button wire:click="deleteThisBenefit({{ $benefit->id }})"
                                                    class="action-btn" type="button">
                                                    <iconify-icon icon="heroicons:trash"></iconify-icon>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    @else
                        <div class="text-center">
                            <p class="text-center text-sm m-5 text-primary">No benefits for this sold policy!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Exclusions --}}
        <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base">
            <div class="card-body flex flex-col p-6 active">
                <header class="flex mb-5 items-center">
                    <div class="flex-1">
                        <div class="card-title font-Inter text-slate-900 dark:text-white">
                            <iconify-icon icon="ooui:special-pages-ltr"></iconify-icon>
                            Exclusions
                            <button wire:click="openAddExcSec"
                                class="btn inline-flex justify-center btn-dark shadow-base2 float-right btn-sm">New
                                Exclusions</button>
                        </div>

                    </div>
                </header>
                <div>
                    @if (!$soldPolicy->exclusions->isEmpty())
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class=" border-t border-slate-100 dark:border-slate-800">
                                <tr>

                                    <th scope="col" class=" table-th ">
                                        Title
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Value
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Action
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                @foreach ($soldPolicy->exclusions as $exclusion)
                                    <tr>
                                        <td class="table-td">{{ $exclusion->title }}</td>
                                        <td class="table-td ">{{ $exclusion->value }}</td>
                                        <td class="table-td ">
                                            <div class="flex space-x-3 rtl:space-x-reverse">
                                                <button wire:click="editThisExc({{ $exclusion->id }})"
                                                    class="action-btn" type="button">
                                                    <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                                                </button>
                                                <button wire:click="deleteThisExc({{ $exclusion->id }})"
                                                    class="action-btn" type="button">
                                                    <iconify-icon icon="heroicons:trash"></iconify-icon>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    @else
                        <div class="text-center">
                            <p class="text-center text-sm m-5 text-primary">No exclusions for this sold policy!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if ($section === 'payments')
        {{-- Totals Numbers --}}
        <div>
            <div class="grid md:grid-cols-4 grid-cols-1 gap-4">
                <!-- BEGIN: Group Chart -->
                @can('viewCommission', $soldPolicy)
                    <div class="card">
                        <div class="card-body pt-4 pb-3 px-4">
                            <div class="flex space-x-3 rtl:space-x-reverse">
                                <div class="flex-none">
                                    <div
                                        class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-[#E5F9FF] dark:bg-slate-900	 text-info-500">
                                        <iconify-icon icon="ph:seal-percent-bold"></iconify-icon>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                                        Policy Commission
                                    </div>
                                    <div class="text-slate-800 dark:text-white text-md font-medium flex justify-between">
                                        <span>
                                            {{ $soldPolicy->total_policy_comm ? number_format($soldPolicy->total_policy_comm, 0, '.', ',') . ' / ' . number_format($soldPolicy->after_tax_comm, 0, '.', ',') : '-' }}
                                        </span>
                                        @can('updateWiseCommPayments', $soldPolicy)
                                            <button class="action-btn btn-sm" type="button"
                                                wire:click="openEditTotalPolCommSection">
                                                <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                                            </button>
                                        @endcan
                                    </div>
                                    @if ($soldPolicy->policy_comm_note)
                                        <small class="text-wrap">{{ $soldPolicy->policy_comm_note }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan

                <div class="card">
                    <div class="card-body pt-4 pb-3 px-4">
                        <div class="flex space-x-3 rtl:space-x-reverse">
                            <div class="flex-none">
                                <div
                                    class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-[#FFEDE6] dark:bg-slate-900	 text-warning-500">
                                    <iconify-icon icon="tdesign:money"></iconify-icon>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                                    Client Paid
                                </div>
                                <div class="text-slate-900 dark:text-white text-lg font-medium">
                                    {{ $soldPolicy->total_client_paid ? number_format($soldPolicy->total_client_paid, 0, '.', ',') : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @can('viewCommission', $soldPolicy)
                    <div class="card">
                        <div class="card-body pt-4 pb-3 px-4">
                            <div class="flex space-x-3 rtl:space-x-reverse">
                                <div class="flex-none">
                                    <div
                                        class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-[#EAE6FF] dark:bg-slate-900	 text-[#5743BE]">
                                        <iconify-icon icon="fluent:money-hand-24-regular"></iconify-icon>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                                        Sales Commission
                                    </div>
                                    <div class="text-slate-900 dark:text-white text-md font-medium">
                                        {{ $soldPolicy->total_sales_comm ? number_format($soldPolicy->total_sales_comm, 0, '.', ',') : '-' }}
                                        /
                                        {{ $soldPolicy->sales_out_comm ? number_format($soldPolicy->sales_out_comm, 0, '.', ',') : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('viewCommission', $soldPolicy)
                    <div class="card">
                        <div class="card-body pt-4 pb-3 px-4">
                            <div class="flex space-x-3 rtl:space-x-reverse">
                                <div class="flex-none">
                                    <div
                                        class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-[#FFDA00] dark:bg-slate-900	 text-[#CEAE00]">
                                        <iconify-icon icon="fa6-solid:money-bills"></iconify-icon>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                                        Company Paid
                                    </div>
                                    <div class="text-slate-900 dark:text-white text-lg font-medium">
                                        {{ $soldPolicy->total_comp_paid ? number_format($soldPolicy->total_comp_paid, 0, '.', ',') : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
                <!-- END: Group Chart -->
            </div>
        </div>

        @can('viewCommission', $soldPolicy)
            {{-- Company Payments --}}
            <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base mt-5">
                <div class="card-body flex flex-col p-6 active justify-center">
                    <header class="card-header noborder flex justify-between">
                        <h4 class="card-title">
                            WISE Payments
                        </h4>
                        <button wire:click="toggleAddCompanyPayment"
                            class="btn btn-sm inline-flex justify-center btn-outline-dark rounded-[25px]">Add
                            payment</button>
                    </header>
                    <div class="card-body px-6 pb-6">
                        <div class="overflow-x-auto -mx-6 ">
                            <div class="inline-block min-w-full align-middle">
                                <div class="overflow-hidden ">
                                    @if ($soldPolicy->company_comm_payments->isEmpty())
                                        <p class="text-sm text-center">
                                            No company payments found.
                                        </p>
                                    @else
                                        <table
                                            class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                            <thead class=" border-t border-slate-100 dark:border-slate-800">
                                                <tr>

                                                    <th scope="col" class=" table-th ">
                                                        Due
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Amount
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Type
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Status
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Payment date
                                                    </th>

                                                    <th scope="col" class=" table-th ">

                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Action
                                                    </th>

                                                </tr>
                                            </thead>
                                            <tbody
                                                class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                                @foreach ($soldPolicy->company_comm_payments as $payment)
                                                    <tr>

                                                        <td class="table-td ">
                                                            {{ $payment->due ? \Carbon\Carbon::parse($payment->due)->format('D d/m/Y') : 'Not set.' }}
                                                        </td>

                                                        <td class="table-td ">
                                                            <div class="text-lg text-success-500">
                                                                {{ number_format($payment->amount, 2, '.', ',') }} EGP 
                                                                @if($taxRate)  /
                                                                {{ number_format($payment->amount / (1 - $taxRate), 2, '.', ',') }}
                                                                @endif
                                                                EGP
                                                            </div>
                                                        </td>

                                                        <td class="table-td">
                                                            <span
                                                                class="badge bg-primary-500 text-primary-500 bg-opacity-30 capitalize">{{ ucwords(str_replace('_', ' ', $payment->type)) }}</span>
                                                        </td>


                                                        <td class="table-td">
                                                            @if (str_contains($payment->status, 'new'))
                                                                <span class="badge bg-warning-500 text-white h-auto">
                                                                    <iconify-icon
                                                                        icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $payment->status)) }}
                                                                </span>
                                                            @elseif(str_contains($payment->status, 'cancelled'))
                                                                <span class="badge bg-danger-500 text-white h-auto">
                                                                    <iconify-icon
                                                                        icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $payment->status)) }}
                                                                </span>
                                                            @elseif($payment->status === 'confirmed' || str_contains($payment->status, 'paid'))
                                                                <span class="badge bg-success-500 text-white h-auto">
                                                                    <iconify-icon
                                                                        icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $payment->status)) }}
                                                                </span>
                                                            @endif
                                                        </td>

                                                        <td class="table-td ">
                                                            {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('D d/m/Y') : 'Not set.' }}
                                                        </td>


                                                        <td class="table-td px-0">

                                                            @if ($payment->doc_url)
                                                                <iconify-icon class=" cursor-pointer" wire:loading.remove
                                                                    wire:target="downloadCompPaymentDoc({{ $payment->id }})"
                                                                    wire:click="downloadCompPaymentDoc({{ $payment->id }})"
                                                                    icon="pepicons-pop:file" width="1.2em"
                                                                    height="1.2em"></iconify-icon>
                                                                <iconify-icon
                                                                    class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                                                    wire:loading
                                                                    wire:target="downloadCompPaymentDoc({{ $payment->id }})"
                                                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                                                            @endif
                                                            @if ($payment->note)
                                                                <iconify-icon class=" cursor-pointer"
                                                                    wire:click="showCompPaymentNote({{ $payment->id }})"
                                                                    icon="gravity-ui:comment" width="1.2em"
                                                                    height="1.2em"></iconify-icon>
                                                            @endif
                                                        </td>

                                                        @can('update', $payment)
                                                            <td class="table-td ">
                                                                <div class="dropstart relative">
                                                                    <button class="inline-flex justify-center items-center"
                                                                        type="button" data-bs-toggle="dropdown"
                                                                        aria-expanded="false">
                                                                        <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2"
                                                                            icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                                    </button>
                                                                    <ul
                                                                        class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                                                        @if ($payment->is_new)
                                                                            <li>
                                                                                <a wire:click="setCompanyPaymentPaid({{ $payment->id }})"
                                                                                    class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                    <iconify-icon
                                                                                        icon="material-symbols:paid"></iconify-icon>
                                                                                    <span>Set as paid</span></a>
                                                                            </li>
                                                                            <li>
                                                                                <a wire:click="setCompanyPaymentCancelled({{ $payment->id }})"
                                                                                    class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                    <iconify-icon
                                                                                        icon="line-md:cancel"></iconify-icon>
                                                                                    <span>Set as Cancelled</span></a>
                                                                            </li>
                                                                        @endif

                                                                        @if ($payment->doc_url)
                                                                            <li>
                                                                                <a wire:click="ConfirmRemoveCompPaymentDoc({{ $payment->id }})"
                                                                                    class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                    <iconify-icon
                                                                                        icon="lucide:file-x"></iconify-icon>
                                                                                    <span>Remove document</span>
                                                                                </a>
                                                                            </li>
                                                                        @else
                                                                            <li>
                                                                                <label for="compPaymentDoc"
                                                                                    wire:click="setCompPaymentDoc({{ $payment->id }})"
                                                                                    class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                    <iconify-icon
                                                                                        icon="pepicons-pop:file"></iconify-icon>
                                                                                    <span>Add document</span></label>
                                                                                <input type="file" id="compPaymentDoc"
                                                                                    name="compPaymentDoc"
                                                                                    style="display: none;"
                                                                                    wire:model="compPaymentDoc">
                                                                            </li>
                                                                        @endif
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        @endcan
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        {{-- Client Payments --}}
        <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base mt-5">
            <div class="card-body flex flex-col p-6 active justify-center">
                <header class="card-header noborder flex justify-between">
                    <h4 class="card-title">
                        Client Payments
                    </h4>
                    @can('create', \App\Models\Payments\ClientPayment::class)
                        <button wire:click="toggleAddClientPayment"
                            class="btn btn-sm inline-flex justify-center btn-outline-dark rounded-[25px]">Add
                            payment</button>
                    @endcan
                </header>
                <div class="card-body px-6 pb-6">
                    <div class="overflow-x-auto -mx-6 ">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden ">
                                @if ($soldPolicy->client_payments->isEmpty())
                                    <p class="text-sm text-center">
                                        No client payments found.
                                    </p>
                                @else
                                    <table
                                        class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                        <thead class=" border-t border-slate-100 dark:border-slate-800">
                                            <tr>

                                                <th scope="col" class=" table-th ">
                                                    Due
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    Amount
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    Type
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    Status
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    Payment date
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    Assigned to
                                                </th>

                                                <th scope="col" class=" table-th ">

                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    Action
                                                </th>

                                            </tr>
                                        </thead>
                                        <tbody
                                            class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                            @foreach ($soldPolicy->client_payments as $payment)
                                                <tr>

                                                    <td class="table-td ">
                                                        {{ $payment->due ? \Carbon\Carbon::parse($payment->due)->format('D d/m/Y') : 'Not set.' }}
                                                    </td>

                                                    <td class="table-td ">
                                                        <div class="text-lg text-success-500">
                                                            {{ number_format($payment->amount, 2, '.', ',') }} EGP
                                                        </div>
                                                    </td>

                                                    <td class="table-td">
                                                        <span
                                                            class="badge bg-primary-500 text-primary-500 bg-opacity-30 capitalize">{{ ucwords(str_replace('_', ' ', $payment->type)) }}
                                                            @if ($payment->type == 'sales_out')
                                                                - {{ $payment->sales_out?->title }}
                                                            @endif

                                                        </span>
                                                    </td>


                                                    <td class="table-td">
                                                        @if (str_contains($payment->status, 'new'))
                                                            <span class="badge bg-warning-500 text-white h-auto">
                                                                <iconify-icon
                                                                    icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $payment->status)) }}
                                                            </span>
                                                        @elseif(str_contains($payment->status, 'cancelled'))
                                                            <span class="badge bg-danger-500 text-white h-auto">
                                                                <iconify-icon
                                                                    icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $payment->status)) }}
                                                            </span>
                                                        @elseif(str_contains($payment->status, 'prem_collected'))
                                                            <span class="badge bg-info-500 text-white h-auto">
                                                                <iconify-icon
                                                                    icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $payment->status)) }}
                                                            </span>
                                                        @elseif($payment->status === 'confirmed' || str_contains($payment->status, 'paid'))
                                                            <span class="badge bg-success-500 text-white h-auto">
                                                                <iconify-icon
                                                                    icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $payment->status)) }}
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <td class="table-td ">
                                                        {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('D d/m/Y') : 'Not set.' }}
                                                    </td>

                                                    <td class="table-td ">{{ $payment->assigned->first_name }}
                                                        {{ $payment->assigned->last_name }}</td>


                                                    <td class="table-td px-0">

                                                        @if ($payment->doc_url)
                                                            <iconify-icon class=" cursor-pointer" wire:loading.remove
                                                                wire:target="downloadPaymentDoc({{ $payment->id }})"
                                                                wire:click="downloadPaymentDoc({{ $payment->id }})"
                                                                icon="pepicons-pop:file" width="1.2em"
                                                                height="1.2em"></iconify-icon>
                                                            <iconify-icon
                                                                class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                                                wire:loading
                                                                wire:target="downloadPaymentDoc({{ $payment->id }})"
                                                                icon="line-md:loading-twotone-loop"></iconify-icon>
                                                        @endif
                                                        @if ($payment->note)
                                                            <iconify-icon class=" cursor-pointer"
                                                                wire:click="showPaymentNote({{ $payment->id }})"
                                                                icon="gravity-ui:comment" width="1.2em"
                                                                height="1.2em"></iconify-icon>
                                                        @endif
                                                    </td>


                                                    <td class="table-td">
                                                        <div class="dropstart relative">
                                                            <button class="inline-flex justify-center items-center"
                                                                type="button" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2"
                                                                    icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                            </button>
                                                            <ul
                                                                class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                                                @if ($payment->is_new || is_Null($payment->status))
                                                                    @can('updateClientPayments', $soldPolicy)
                                                                        <li>
                                                                            <a wire:click="openEditPaymentSec({{ $payment->id }})"
                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon
                                                                                    icon="material-symbols:paid"></iconify-icon>
                                                                                <span>Edit</span></a>
                                                                        </li>
                                                                    @endcan
                                                                    @can('collect', $payment)
                                                                        <li>
                                                                            <a wire:click="openSetPaymentCollectedSec({{ $payment->id }})"
                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon
                                                                                    icon="material-symbols:check"></iconify-icon>
                                                                                <span>Set as collected</span></a>
                                                                        </li>
                                                                    @endcan
                                                                    {{-- @can('pay', $payment)
                                                                        <li>
                                                                            <a wire:click="openSetPaymentPaidSec({{ $payment->id }})" class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon icon="material-symbols:paid"></iconify-icon>
                                                                                <span>Set as paid</span></a>
                                                                        </li>
                                                                        @endcan --}}
                                                                    {{-- <li>
                                                                            <a wire:click="setPaymentCancelled({{ $payment->id }})"
                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon
                                                                                    icon="line-md:cancel"></iconify-icon>
                                                                                <span>Set as Cancelled</span></a>
                                                                        </li> --}}
                                                                    @can('update', $payment)
                                                                        <li>
                                                                            <a wire:click="deleteClientPayment({{ $payment->id }})"
                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon
                                                                                    icon="line-md:cancel"></iconify-icon>
                                                                                <span>Delete Payment</span></a>
                                                                        </li>
                                                                    @endcan
                                                                @elseif($payment->is_collected)
                                                                    @can('pay', $payment)
                                                                        <li>
                                                                            <a wire:click="setClientPaymentAsNew({{ $payment->id }})"
                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon
                                                                                    icon="material-symbols:paid"></iconify-icon>
                                                                                <span>Set as new</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a wire:click="openSetPaymentPaidSec({{ $payment->id }})"
                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon
                                                                                    icon="material-symbols:paid"></iconify-icon>
                                                                                <span>Set as paid</span></a>
                                                                        </li>
                                                                    @endcan
                                                                @endif

                                                                @if ($payment->doc_url)
                                                                @can('updateClientPayments', $soldPolicy)
                                                                        <li>
                                                                            <a wire:click="ConfirmRemovePaymentDoc({{ $payment->id }})"
                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon
                                                                                    icon="lucide:file-x"></iconify-icon>
                                                                                <span>Remove document</span>
                                                                            </a>
                                                                        </li>
                                                                    @endcan
                                                                @else
                                                                @can('updateClientPayments', $soldPolicy)
                                                                        <li>
                                                                            <label for="paymentDoc"
                                                                                wire:click="setPaymentDoc({{ $payment->id }})"
                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon
                                                                                    icon="pepicons-pop:file"></iconify-icon>
                                                                                <span>Add document</span></label>
                                                                            <input type="file" id="paymentDoc"
                                                                                name="paymentDoc" style="display: none;"
                                                                                wire:model="paymentDoc">
                                                                        </li>
                                                                    @endcan
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </td>

                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @can('viewCommission', $soldPolicy)
            {{-- Sales Commission --}}
            <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base mt-5">
                <div class="card-body flex flex-col p-6 active justify-center">
                    <header class="card-header noborder flex justify-between">
                        <h4 class="card-title">
                            Add Target Commission
                        </h4>
                        <div>
                            @can('create', \App\Models\Payments\SalesComm::class)
                                <button wire:click="toggleAdjustComm"
                                    class="btn btn-sm inline-flex justify-center btn-outline-dark rounded-[25px]">
                                    Add Direct Commission
                                </button>
                            @endcan

                            @can('create', \App\Models\Payments\SalesComm::class)
                                <button wire:click="toggleAddComm"
                                    class="btn btn-sm inline-flex justify-center btn-outline-dark rounded-[25px]">Add
                                    commission</button>
                            @endcan
                        </div>

                        <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading
                            wire:target="updatedCommDoc" icon="line-md:loading-twotone-loop"></iconify-icon>
                    </header>
                    <div class="card-body px-6 pb-6">
                        <div class="overflow-x-auto -mx-6 ">
                            <div class="inline-block min-w-full align-middle">
                                <div class="overflow-hidden ">
                                    @if ($soldPolicy->sales_comms->isEmpty())
                                        <p class="text-sm text-center">
                                            No sales commissions found.
                                        </p>
                                    @else
                                        <table
                                            class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                            <thead class=" border-t border-slate-100 dark:border-slate-800">
                                                <tr>

                                                    <th scope="col" class=" table-th ">
                                                        Profile
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Amount
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        From
                                                    </th>
                                                    <th scope="col" class=" table-th ">
                                                        &
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Payment Date
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Status
                                                    </th>

                                                    <th scope="col" class=" table-th ">

                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Action
                                                    </th>

                                                </tr>
                                            </thead>
                                            <tbody
                                                class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                                @foreach ($soldPolicy->sales_comms as $comm)
                                                    <tr>
                                                        <td class="table-td ">
                                                            <div class="">
                                                                <span class="text-slate-500 dark:text-slate-400">
                                                                    <span
                                                                        class="block text-slate-600 dark:text-slate-300">{{ $comm->comm_profile?->title }}
                                                                        ({{ $comm->title }})
                                                                    </span>
                                                                    <span class="block text-slate-500 text-xs">
                                                                        {{-- {{ $comm->sales->first_name }} {{ $comm->sales->last_name }} --}}
                                                                    </span>
                                                                </span>
                                                            </div>
                                                        </td>

                                                        <td class="table-td ">
                                                            <div class="text-lg text-success-500">
                                                                {{ number_format($comm->amount, 2, '.', ',') }} EGP
                                                            </div>
                                                        </td>

                                                        <td class="table-td ">
                                                            {{ ucwords(str_replace('_', ' ', $comm->from)) }}
                                                        </td>
                                                        <td class="table-td ">
                                                            {{ $comm->comm_percentage }}%
                                                        </td>

                                                        <td class="table-td ">
                                                            {{ $comm->payment_date ? \Carbon\Carbon::parse($comm->payment_date)->format('D d/m/Y') : 'Not set.' }}
                                                        </td>

                                                        <td class="table-td">
                                                            @if (str_contains($comm->status, 'not_confirmed'))
                                                                <span class="badge bg-warning-500 text-white h-auto">
                                                                    <iconify-icon
                                                                        icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $comm->status)) }}
                                                                </span>
                                                            @elseif(str_contains($comm->status, 'cancelled'))
                                                                <span class="badge bg-danger-500 text-white h-auto">
                                                                    <iconify-icon
                                                                        icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $comm->status)) }}
                                                                </span>
                                                            @elseif($comm->status === 'confirmed' || str_contains($comm->status, 'paid'))
                                                                <span class="badge bg-success-500 text-white h-auto">
                                                                    <iconify-icon
                                                                        icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $comm->status)) }}
                                                                </span>
                                                            @endif


                                                        </td>

                                                        <td class="table-td px-0">

                                                            @if ($comm->doc_url)
                                                                <iconify-icon class=" cursor-pointer" wire:loading.remove
                                                                    wire:target="downloadCommDoc({{ $comm->id }})"
                                                                    wire:click="downloadCommDoc({{ $comm->id }})"
                                                                    icon="pepicons-pop:file" width="1.2em"
                                                                    height="1.2em"></iconify-icon>
                                                                <iconify-icon
                                                                    class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                                                    wire:loading
                                                                    wire:target="downloadCommDoc({{ $comm->id }})"
                                                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                                                            @endif
                                                            @if ($comm->note)
                                                                <iconify-icon class=" cursor-pointer"
                                                                    wire:click="showCommNote({{ $comm->id }})"
                                                                    icon="gravity-ui:comment" width="1.2em"
                                                                    height="1.2em"></iconify-icon>
                                                            @endif
                                                        </td>

                                                        @can('update', $comm)
                                                            <td class="table-td ">
                                                                <div class="dropstart relative">
                                                                    <button class="inline-flex justify-center items-center"
                                                                        type="button" data-bs-toggle="dropdown"
                                                                        aria-expanded="false">
                                                                        <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2"
                                                                            icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                                    </button>
                                                                    <ul
                                                                        class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                                                        @if ($comm->is_new)
                                                                            <li>
                                                                                <a wire:click="setCommPaid({{ $comm->id }})"
                                                                                    class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                    <iconify-icon
                                                                                        icon="material-symbols:paid"></iconify-icon>
                                                                                    <span>Set as paid</span></a>
                                                                            </li>
                                                                            <li>
                                                                                <a wire:click="setCommCancelled({{ $comm->id }})"
                                                                                    class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                    <iconify-icon
                                                                                        icon="line-md:cancel"></iconify-icon>
                                                                                    <span>Set as Cancelled</span></a>
                                                                            </li>
                                                                        @endif

                                                                        <li>
                                                                            <a wire:click="refreshCommAmmount({{ $comm->id }})"
                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon
                                                                                    icon="material-symbols:refresh"></iconify-icon>
                                                                                <span>Refresh amount</span></a>
                                                                        </li>


                                                                        @if ($comm->doc_url)
                                                                            <li>
                                                                                <a wire:click="ConfirmRemoveCommDoc({{ $comm->id }})"
                                                                                    class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                    <iconify-icon
                                                                                        icon="lucide:file-x"></iconify-icon>
                                                                                    <span>Remove document</span>
                                                                                </a>
                                                                            </li>
                                                                        @else
                                                                            <li>
                                                                                <label for="commDoc"
                                                                                    wire:click="setCommDoc({{ $comm->id }})"
                                                                                    class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                    <iconify-icon
                                                                                        icon="pepicons-pop:file"></iconify-icon>
                                                                                    <span>Add document</span></label>
                                                                                <input type="file" id="commDoc"
                                                                                    name="filename" style="display: none;"
                                                                                    wire:model="commDoc">
                                                                            </li>
                                                                        @endif
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        @endcan
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        @can('viewCommission', $soldPolicy)
            {{-- Policy Commission --}}
            <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base mt-5">
                <div class="card-body flex flex-col p-6 active justify-center">
                    <header class="card-header noborder flex justify-between">
                        <h4 class="card-title">
                            Policy Commissions
                        </h4>
                        @can('updateWiseCommPayments', $soldPolicy)
                            <button wire:click="openNewPolCom"
                                class="btn btn-sm inline-flex justify-center btn-outline-dark rounded-[25px]">Add
                                commission</button>
                        @endcan
                    </header>
                    <div class="card-body px-6 pb-6">
                        <div class="overflow-x-auto -mx-6 ">
                            <div class="inline-block min-w-full align-middle">
                                <div class="overflow-hidden ">
                                    @if ($soldPolicy->comms_details->isEmpty())
                                        <p class="text-sm text-center">
                                            No Policy commissions found.
                                        </p>
                                    @else
                                        <table
                                            class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                            <thead class=" border-t border-slate-100 dark:border-slate-800">
                                                <tr>

                                                    <th scope="col" class=" table-th ">
                                                        Title
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Amount
                                                    </th>
                                                    <th scope="col" class=" table-th ">
                                                        Action
                                                    </th>

                                                </tr>
                                            </thead>
                                            <tbody
                                                class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                                @foreach ($soldPolicy->comms_details as $comm)
                                                    <tr>

                                                        <td class="table-td ">
                                                            <div class="">
                                                                <span class="text-slate-500 dark:text-slate-400">
                                                                    <span
                                                                        class="block text-slate-600 dark:text-slate-300">{{ $comm->title }}</span>
                                                                </span>
                                                            </div>
                                                        </td>

                                                        <td class="table-td ">
                                                            <div class="text-lg text-success-500">
                                                                {{ number_format($comm->amount, 2, '.', ',') }} EGP
                                                            </div>
                                                        </td>
                                                        <td class="table-td">
                                                            <button class="action-btn btn-sm" type="button"
                                                                wire:click='openUpdatePolCom({{ $comm->id }})'>
                                                                <iconify-icon
                                                                    icon="heroicons:pencil-square"></iconify-icon>
                                                            </button>
                                                        </td>

                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

    @endif

    @if ($newClaimSection)
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
                                New Claim
                            </h3>
                            <button wire:click="closeNewClaimSection" type="button"
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


                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif


                        <!-- Modal body -->
                        <div class="p-6 py-1">

                            <div class="input-area mt-3">
                                <label for="textarea" class="form-label">Claim Description</label>
                                <textarea wire:model.defer="newTaskDesc" id="textarea" class="form-control" placeholder="Text Area"></textarea>
                            </div>

                            <div class="input-area mt-3">
                                <label for="textarea" class="form-label">Due Date</label>
                                <input wire:model.defer="newTaskDue" id="textarea" type="date"
                                    class="form-control">
                            </div>
                        </div>

                        <div class="p-6 py-1">
                            <p class="text-lg"><b>Enter Fields</b></p>
                        </div>
                        @foreach ($fields as $index => $field)
                            <div class="p-6 py-1">
                                <div class="grid grid-cols-8 md:grid-cols-8 lg:grid-cols-8 gap-2 items-center">
                                    <div class="from-group col-span-3">
                                        <label for="newExcValue" class="form-label">Title</label>
                                        <input list="claim_title"
                                            class="form-control text-center @error('fields.' . $index . '.title') !border-danger-500 @enderror"
                                            wire:model="fields.{{ $index }}.title" />
                                        <datalist id="claim_title">
                                            @foreach ($FIELDSTITLES as $FIELDSTITLE)
                                                <option>{{ $FIELDSTITLE }}</option>
                                            @endforeach
                                        </datalist>
                                        @error('fields.{{ $index }}.title')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="from-group col-span-4">
                                        <label for="claim_fields" class="form-label">Value</label>
                                        <input list="claim_fields"
                                            class="form-control mt-2 w-full @error('fields.' . $index . '.value') !border-danger-500 @enderror"
                                            wire:model="fields.{{ $index }}.value">
                                        <datalist id="claim_fields">
                                            <option>Yes</option>
                                            <option>No</option>
                                        </datalist>
                                        @error('fields.{{ $index }}.value')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="from-group col-span-1">
                                        <label for="newExcValue" class="form-label">remove</label>
                                        <button class="action-btn" wire:click="removeField({{ $index }})"
                                            type="button">
                                            <iconify-icon icon="heroicons:trash"></iconify-icon>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="p-6 space-y-4">
                            <button wire:click="addAnotherField"
                                class="btn btn-sm mt-2 inline-flex justify-center btn-dark">Add Field</button>
                        </div>


                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="createClaim" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="createClaim">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="createClaim"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($newEndorsementSection)
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
                                New Endorsement
                            </h3>
                            <button wire:click="closeNewEndorsementSection" type="button"
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


                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif


                        <!-- Modal body -->
                        <div class="p-6 py-1">
                            <div class="input-area mt-3">
                                <label for="textarea" class="form-label">Text Description</label>
                                <textarea wire:model.defer="newTaskDesc" id="textarea" class="form-control" placeholder="Text Area"></textarea>
                            </div>

                            <div class="input-area mt-3">
                                <label for="textarea" class="form-label">Due Date</label>
                                <input wire:model.defer="newTaskDue" id="textarea" type="date"
                                    class="form-control">
                            </div>
                        </div>



                        <div class="p-6 py-1">
                            <p class="text-lg"><b>Enter Actions</b></p>
                        </div>
                        @foreach ($actions as $index => $action)
                            <div class="p-6 py-1">
                                <div class="grid grid-cols-8 md:grid-cols-8 lg:grid-cols-8 gap-2 items-center">
                                    <div class="from-group col-span-3">
                                        <label for="newExcValue" class="form-label">Title</label>
                                        <select name="basicSelect"
                                            class="form-control w-full mt-2  @error('actions.' . $index . '.column_name') !border-danger-500 @enderror"
                                            wire:model="actions.{{ $index }}.column_name">
                                            <option
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                Select Relation...</option>
                                            @foreach ($COLUMNS as $COLUMN)
                                                <option value="{{ $COLUMN }}"
                                                    class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                    {{ ucwords(str_replace('_', ' ', $COLUMN)) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="from-group col-span-4">
                                        <label for="newExcValue" class="form-label">Value</label>
                                        <input name="newExcValue" type="text"
                                            class="form-control mt-2 w-full @error('actions.' . $index . '.value') !border-danger-500 @enderror"
                                            wire:model="actions.{{ $index }}.value">
                                        @error('actions.{{ $index }}.value')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="from-group col-span-1">
                                        <label for="newExcValue" class="form-label">remove</label>
                                        <button class="action-btn" wire:click="removeAcion({{ $index }})"
                                            type="button">
                                            <iconify-icon icon="heroicons:trash"></iconify-icon>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <span class="font-Inter text-sm pt-2 inline-block p-6">Expiry example: 2024-08-23</span>
                        <div class="p-6 space-y-4">
                            <button wire:click="addAnotherAction"
                                class="btn btn-sm mt-2 inline-flex justify-center btn-dark">Add Action</button>
                        </div>


                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="createEndorsement" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="createEndorsement">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="createEndorsement"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($newTaskSection)
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
                                New Task
                            </h3>
                            <button wire:click="closeNewTaskSection" type="button"
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


                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif


                        <!-- Modal body -->
                        <div class="p-6 py-1">
                            <div class="input-area mt-3">
                                <label for="textarea" class="form-label">Text Description</label>
                                <textarea wire:model.defer="newTaskDesc" id="textarea" class="form-control" placeholder="Text Area"></textarea>
                            </div>

                            <div class="input-area mt-3">
                                <label for="textarea" class="form-label">Due Date</label>
                                <input wire:model.defer="newTaskDue" id="textarea" type="date"
                                    class="form-control">
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="createTask" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="createTask">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="createTask"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($editInfoSec)
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
                                Edit Sold Policy
                            </h3>
                            <button wire:click="closeEditInfoSection" type="button"
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
                            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-2 gap-2">
                                <div class="from-group">
                                    <label for="issuing_date" class="form-label">Issuing Date</label>
                                    <input name="issuing_date" type="date"
                                        class="form-control mt-2 w-full @error('issuing_date') !border-danger-500 @enderror"
                                        wire:model.defer="issuing_date">
                                    @error('issuing_date')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-2 gap-2">
                                <div class="from-group">
                                    <label for="start" class="form-label">Start Date</label>
                                    <input name="start" type="date"
                                        class="form-control mt-2 w-full @error('start') !border-danger-500 @enderror"
                                        wire:model.defer="start">
                                    @error('start')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="from-group">
                                    <label for="expiry" class="form-label">Expiry Date</label>
                                    <input name="expiry" type="date"
                                        class="form-control mt-2 w-full @error('expiry') !border-danger-500 @enderror"
                                        wire:model.defer="expiry">
                                    @error('expiry')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="from-group">
                                <label for="policy_number" class="form-label">Policy Number</label>
                                <input name="policy_number" type="text"
                                    class="form-control mt-2 w-full @error('policy_number') !border-danger-500 @enderror"
                                    wire:model.defer="policy_number">
                                @error('policy_number')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="car_chassis" class="form-label">Car Chassis</label>
                                <input name="car_chassis" type="text"
                                    class="form-control mt-2 w-full @error('car_chassis') !border-danger-500 @enderror"
                                    wire:model.defer="car_chassis">
                                @error('car_chassis')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="car_plate_no" class="form-label">Car Plate No</label>
                                <input name="car_plate_no" type="text"
                                    class="form-control mt-2 w-full @error('car_plate_no') !border-danger-500 @enderror"
                                    wire:model.defer="car_plate_no">
                                @error('car_plate_no')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="car_engine" class="form-label">Car Engine</label>
                                <input name="car_engine" type="text"
                                    class="form-control mt-2 w-full @error('car_engine') !border-danger-500 @enderror"
                                    wire:model.defer="car_engine">
                                @error('car_engine')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="in_favor_to" class="form-label">In favor to</label>
                                <input name="in_favor_to" type="text"
                                    class="form-control mt-2 w-full @error('in_favor_to') !border-danger-500 @enderror"
                                    wire:model.defer="in_favor_to">
                                @error('in_favor_to')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editInfo" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="editInfo">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="editInfo"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($noteSection)
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
                            <button wire:click="toggleNoteSection" type="button"
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
                                <label for="note" class="form-label">Note</label>
                                <textarea class="form-control mt-2 w-full @error('note') !border-danger-500 @enderror" wire:model.defer="note"
                                    rows=7></textarea>
                                @error('note')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editNote" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="editNote">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="editNote"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($commNote)
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
                                Commission Note
                            </h3>
                            <button wire:click="hideCommComment" type="button"
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
                            {{ $commNote }}
                        </div>
                        <!-- Modal footer -->

                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($setPaidSec)
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
                                Set paid
                            </h3>
                            <button wire:click="closeSetPaidSec" type="button"
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
                                <label for="client_payment_date" class="form-label">Client payment date</label>
                                <input name="client_payment_date" type="date"
                                    class="form-control mt-2 w-full @error('client_payment_date') !border-danger-500 @enderror"
                                    wire:model.defer="client_payment_date">
                                @error('client_payment_date')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setPaid" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setPaid">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setPaid"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($clientPaymentDateSec)
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
                                Client payment date
                            </h3>
                            <button wire:click="closePaymentDateSec" type="button"
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
                                <label for="clientPaymentDate" class="form-label">Date</label>
                                <input name="clientPaymentDate" type="date"
                                    class="form-control mt-2 w-full @error('clientPaymentDate') !border-danger-500 @enderror"
                                    wire:model.defer="clientPaymentDate">
                                @error('clientPaymentDate')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="changePaymentDate" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="changePaymentDate">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="changePaymentDate"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($clientCancellationDateSec)
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
                                Cancellation date
                            </h3>
                            <button wire:click="closeCancellationDateSec" type="button"
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
                                <label for="clientPaymentDate" class="form-label">Date</label>
                                <input name="clientPaymentDate" type="date"
                                    class="form-control mt-2 w-full @error('clientCancellationDate') !border-danger-500 @enderror"
                                    wire:model.defer="clientCancellationDate">
                                @error('clientCancellationDate')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="changeCancellationDate" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="changeCancellationDate">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="changeCancellationDate"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if ($paymentNoteSec)
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
                                Payment Note
                            </h3>
                            <button wire:click="hidePaymentComment" type="button"
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
                            {{ $paymentNoteSec }}
                        </div>
                        <!-- Modal footer -->

                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($CompPaymentNoteSec)
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
                                Payment Note
                            </h3>
                            <button wire:click="hideCompPaymentComment" type="button"
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
                            {{ $CompPaymentNoteSec }}
                        </div>
                        <!-- Modal footer -->

                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($updatePolComSec)
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
                                Update commission amount
                            </h3>
                            <button wire:click="closeUpdatePolCom" type="button"
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
                                <label for="policyCommAmount" class="form-label">Amount</label>
                                <input name="policyCommAmount" type="number"
                                    class="form-control mt-2 w-full @error('policyCommAmount') !border-danger-500 @enderror"
                                    wire:model.defer="policyCommAmount">
                                @error('policyCommAmount')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="updateCommAmount" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="updateCommAmount">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="updateCommAmount"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endif

    @can('updateWiseCommPayments', $soldPolicy)
        @if ($newPolComSec)
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
                                    New policy Commission
                                </h3>
                                <button wire:click="closeNewPolCom" type="button"
                                    class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
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
                                <div class="from-group">
                                    <label for="policyCommTitle" class="form-label">Title</label>
                                    <input name="policyCommTitle" type="text"
                                        class="form-control mt-2 w-full @error('policyCommTitle') !border-danger-500 @enderror"
                                        wire:model.defer="policyCommTitle">
                                    @error('policyCommTitle')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="from-group">
                                    <label for="policyCommAmount" class="form-label">Amount</label>
                                    <input name="policyCommAmount" type="number"
                                        class="form-control mt-2 w-full @error('policyCommAmount') !border-danger-500 @enderror"
                                        wire:model.defer="policyCommAmount">
                                    @error('policyCommAmount')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!-- Modal footer -->
                            <div
                                class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                <button wire:click="newPolicyComm" data-bs-dismiss="modal"
                                    class="btn inline-flex justify-center text-white bg-black-500">
                                    <span wire:loading.remove wire:target="newPolicyComm">Submit</span>
                                    <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                        wire:loading wire:target="newPolicyComm"
                                        icon="line-md:loading-twotone-loop"></iconify-icon>

                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endcan


    @if ($editPaymentInfoSection)
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
                                Set Payment Info
                            </h3>
                            <button wire:click="togglePaymentInfoSection" type="button"
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
                                <label for="insured_value" class="form-label">Insured Value</label>
                                <input name="insured_value" type="number"
                                    class="form-control mt-2 w-full @error('insured_value') !border-danger-500 @enderror"
                                    wire:model.defer="insured_value">
                                @error('insured_value')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="net_rate" class="form-label">Net Rate</label>
                                <input name="net_rate" type="number" max="100" min="0"
                                    class="form-control mt-2 w-full @error('net_rate') !border-danger-500 @enderror"
                                    wire:model.defer="net_rate">
                                @error('net_rate')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="net_premium" class="form-label">Net Premium</label>
                                <input name="net_premium" type="number"
                                    class="form-control mt-2 w-full @error('net_premium') !border-danger-500 @enderror"
                                    wire:model.defer="net_premium">
                                @error('net_premium')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="gross_premium" class="form-label">Gross Premium</label>
                                <input name="gross_premium" type="number"
                                    class="form-control mt-2 w-full @error('gross_premium') !border-danger-500 @enderror"
                                    wire:model.defer="gross_premium">
                                @error('gross_premium')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="installements_count" class="form-label">Installements Count</label>
                                <input name="installements_count" max="12" min="1" type="number"
                                    class="form-control mt-2 w-full @error('installements_count') !border-danger-500 @enderror"
                                    wire:model.defer="installements_count">
                                @error('installements_count')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="payment_frequency" class="form-label">Payment Frequency</label>
                                <select name="payment_frequency" id="basicSelect"
                                    class="form-control w-full mt-2 @error('payment_frequency') !border-danger-500 @enderror"
                                    wire:model="payment_frequency">
                                    <option> Select an option...</option>
                                    @foreach ($PAYMENT_FREQS as $PAYMENT_FREQ)
                                        <option value="{{ $PAYMENT_FREQ }}">{{ $PAYMENT_FREQ }}</option>
                                    @endforeach
                                </select>
                                @error('payment_frequency')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <label for="discount" class="form-label">Discount</label>
                            <div class="from-group">
                                <div class="relative">
                                    <input type="number"
                                        class="form-control !px-9 @error('discount') !border-danger-500 @enderror"
                                        placeholder="100" wire:model.defer="discount">
                                    <span
                                        class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center">
                                        EGP </span>
                                </div>

                            </div>

                            <label for="origin_discount" class="form-label">Origin Discount</label>
                            <div class="from-group">
                                <div class="relative">
                                    <input type="number"
                                        class="form-control !px-9 @error('origin_discount') !border-danger-500 @enderror"
                                        placeholder="100" wire:model.defer="origin_discount">
                                    <span
                                        class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center">
                                        EGP </span>
                                </div>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editPaymentInfo" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="addExc">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="addExc"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($newExcSection)
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
                                New Exclusion
                            </h3>
                            <button wire:click="closeAddExcSec" type="button"
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
                                <label for="newExcTitle" class="form-label">Title</label>
                                <input name="newExcTitle" type="text"
                                    class="form-control mt-2 w-full @error('newExcTitle') !border-danger-500 @enderror"
                                    wire:model.defer="newExcTitle">
                                @error('newExcTitle')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="newExcValue" class="form-label">Value</label>
                                <input name="newExcValue" type="text"
                                    class="form-control mt-2 w-full @error('newExcValue') !border-danger-500 @enderror"
                                    wire:model.defer="newExcValue">
                                @error('newExcValue')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addExc" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="addExc">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="addExc"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($newBenefitSec)
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
                                New Benefit
                            </h3>
                            <button wire:click="closeNewBenefitSec" type="button"
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
                                <label for="newBenefit" class="form-label">Benefit</label>
                                <select name="newBenefit"
                                    class="form-control w-full mt-2 @error('newBenefit') !border-danger-500 @enderror"
                                    wire:model="newBenefit">
                                    <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                        Select an option...</option>
                                    @foreach ($BENEFITS as $BENEFIT)
                                        <option value="{{ $BENEFIT }}">
                                            {{ $BENEFIT }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('newBenefit')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="newValue" class="form-label">Value</label>
                                <input type="text"
                                    class="form-control mt-2 w-full @error('newValue') !border-danger-500 @enderror"
                                    wire:model.defer="newValue">
                                @error('newValue')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addBenefit" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="editBenefit">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="editBenefit"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($excId)
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
                                Edit Exclusion
                            </h3>
                            <button wire:click="closeEditExc" type="button"
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
                                <label for="lastName" class="form-label">Title</label>
                                <input type="text"
                                    class="form-control mt-2 w-full @error('eExcTitle') !border-danger-500 @enderror"
                                    wire:model.defer="eExcTitle">
                                @error('eExcTitle')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="lastName" class="form-label">Value</label>
                                <input type="text"
                                    class="form-control mt-2 w-full @error('eExcValue') !border-danger-500 @enderror"
                                    wire:model.defer="eExcValue">
                                @error('eExcValue')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editExc" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="editExc">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="editExc"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($benefitId)
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
                                Edit Benefit
                            </h3>
                            <button wire:click="closeEditBenefit" type="button"
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
                                <label for="eBenefit" class="form-label">Benefit</label>
                                <select name="eBenefit"eBenefit
                                    class="form-control w-full mt-2 @error('eBenefit') !border-danger-500 @enderror"
                                    wire:model="eBenefit">
                                    <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                        Select an option...</option>
                                    @foreach ($BENEFITS as $BENEFIT)
                                        <option value="{{ $BENEFIT }}">
                                            {{ $BENEFIT }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('eBenefit')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="lastName" class="form-label">Value</label>
                                <input type="text"
                                    class="form-control mt-2 w-full @error('eValue') !border-danger-500 @enderror"
                                    wire:model.defer="eValue">
                                @error('eValue')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editBenefit" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="editBenefit">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="editBenefit"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($generateRenewalOfferSec)
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
                                Generate Renewal Offer
                            </h3>
                            <button wire:click="toggleGenerateRenewalOfferSec" type="button"
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
                                <label for="renewalOfferDue" class="form-label">Due Date</label>
                                <input type="Date" name="renewalOfferDue"
                                    class="form-control mt-2 w-full @error('renewalOfferDue') !border-danger-500 @enderror"
                                    wire:model.defer="renewalOfferDue">
                                @error('renewalOfferDue')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="inFavorTo" class="form-label">In Favor To</label>
                                <input type="text" name="inFavorTo"
                                    class="form-control mt-2 w-full @error('inFavorTo') !border-danger-500 @enderror"
                                    wire:model.defer="inFavorTo">
                                @error('inFavorTo')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="generateRenewalOffer" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="generateRenewalOffer">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="generateRenewalOffer"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($deleteSoldPolicySec)
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
                                Delete Sold Policy
                            </h3>
                            <button wire:click="toggleDeleteSoldPolicy" type="button"
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
                                Are you sure ! you Want to delete this sold policy ?
                            </h6>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="deleteSoldPolicy" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-danger-500">Yes,
                                Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($deleteBenefitId)
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
                                Delete Benefit
                            </h3>
                            <button wire:click="dismissDeleteBenefit" type="button"
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
                                Are you sure ! you Want to delete this Benefit ?
                            </h6>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="deleteBenefit" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-danger-500">Yes,
                                Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($deleteExcId)
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
                                Delete Exclusions
                            </h3>
                            <button wire:click="dismissDeleteExc" type="button"
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
                                Are you sure ! you Want to delete this Exclusions ?
                            </h6>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="deleteExc" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-danger-500">Yes,
                                Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($deleteDocSec)
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
                                Delete Document File
                            </h3>
                            <button wire:click="toggleDeleteDoc" type="button"
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
                                Are you sure ! you Want to delete this Document ?
                            </h6>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="deleteDucment" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-danger-500">Yes,
                                Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($deletePolComSec)
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
                                Delete policy commission
                            </h3>
                            <button wire:click="dismissDeletePolCom" type="button"
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
                                Are you sure ! you Want to delete this Policy commission ?
                            </h6>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="deletePolicyComm" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-danger-500">Yes,
                                Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($RemoveCommDocId)
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
                                Remove Commission Document
                            </h3>
                            <button wire:click="DissRemoveCommDoc" type="button"
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
                                Are you sure ! you Want to remove commission Document ?
                            </h6>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="removeCommDoc" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-danger-500">Yes,
                                Remove</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($RemovePaymentDocId)
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
                                Remove Payment Document
                            </h3>
                            <button wire:click="DissRemovePaymentDoc" type="button"
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
                                Are you sure ! you Want to remove payment Document ?
                            </h6>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="removePaymentDoc" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-danger-500">Yes,
                                Remove</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($RemoveCompPaymentDocId)
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
                                Remove Payment Document
                            </h3>
                            <button wire:click="DissRemoveCompPaymentDoc" type="button"
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
                                Are you sure ! you Want to remove payment Document ?
                            </h6>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="removeCompPaymentDoc" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-danger-500">Yes,
                                Remove</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($editTotalPolCommSection)
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
                                Total Policy Commission
                            </h3>
                            <button wire:click="closeEditTotalPolCommSection" type="button"
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
                                <label for="updateTotalPolComm" class="form-label">Amount</label>
                                <input name="updateTotalPolComm" type="number"
                                    class="form-control mt-2 w-full @error('updateTotalPolComm') !border-danger-500 @enderror"
                                    wire:model.defer="updateTotalPolComm">
                                @error('updateTotalPolComm')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group mt-2">
                                <label for="updateTotalPolCommNote" class="form-label">Note</label>
                                <textarea name="updateTotalPolCommNote"
                                    class="form-control mt-2 w-full @error('updateTotalPolCommNote') !border-danger-500 @enderror"
                                    wire:model.defer="updateTotalPolCommNote"></textarea>
                                @error('updateTotalPolCommNote')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="updateTotalPolComm" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="updateTotalPolComm">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="updateTotalPolComm"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($setPaymentPaidSec)
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
                                Set Payment As Paid
                            </h3>
                            <button wire:click="closeSetPaymentPaidSec" type="button"
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
                                <label for="payment_type" class="form-label">Payment type</label>
                                <select name="payment_type" id="basicSelect" class="form-control w-full mt-2"
                                    wire:model="payment_type">
                                    <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                        Select type</option>
                                    @foreach ($PYMT_TYPES as $paymentType)
                                        <option value="{{ $paymentType }}"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ ucwords(str_replace('_', ' ', $paymentType)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="from-group">
                                <label for="payment_date" class="form-label">Due</label>
                                <input type="date" name="payment_date"
                                    class="form-control mt-2 w-full @error('payment_date') !border-danger-500 @enderror"
                                    wire:model.defer="payment_date">
                                @error('payment_date')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setPaymentPaid" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setPaymentPaid">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setPaymentPaid"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($setPaymentCollectedSec)
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
                                Set Payment As Collected
                            </h3>
                            <button wire:click="closeSetPaymentCollectedSec" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd">
                                    </path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <label for="uploadCollectedDoc">
                                <a
                                    class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                dark:hover:text-white cursor-pointer">
                                    Add document</a>
                            </label>
                            <input type="file" style="display: none" name="uploadCollectedDoc"
                                id="uploadCollectedDoc" wire:model="paymentCollectedDoc">
                            <hr />

                            <div class="from-group">
                                <label class="form-label">Note</label>
                                <textarea class="form-control mt-2 w-full @error('payment_collected_note') !border-danger-500 @enderror"
                                    wire:model.defer="payment_collected_note"></textarea>
                                @error('payment_collected_note')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>


                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setPaymentCollected" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setPaymentCollected">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setPaymentCollected"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- addCommSec --}}
    @can('create', \App\Models\Payments\SalesComm::class)
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
                                    Add Sales Commission
                                </h3>
                                <button wire:click="toggleAddComm" type="button"
                                    class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
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

                                <div class="from-group">
                                    <label for="commTitle" class="form-label">Title</label>
                                    <input type="text" name="commTitle"
                                        class="form-control mt-2 w-full @error('commTitle') !border-danger-500 @enderror"
                                        wire:model.defer="commTitle">
                                    @error('commTitle')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="from-group">
                                    <label for="commFrom" class="form-label">From</label>
                                    <select name="commFrom" id="commFrom" class="form-control w-full mt-2"
                                        wire:model="commFrom">
                                        <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600"
                                            value="">
                                            Select option</option>
                                        @foreach ($FROMS as $FROM)
                                            <option value="{{ $FROM }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ ucwords(str_replace('_', ' ', $FROM)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('commFrom')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="from-group">
                                    <label for="commPer" class="form-label">Percentage</label>
                                    <input type="number" min="0" max="100" name="commPer"
                                        class="form-control mt-2 w-full @error('commPer') !border-danger-500 @enderror"
                                        wire:model.defer="commPer">
                                    @error('commPer')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="from-group">
                                    <label for="lastName" class="form-label">Commission Profile</label>
                                    <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2"
                                        wire:model="commProfile">
                                        <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            Select Profile</option>
                                        @foreach ($CommProfiles as $CommProfile)
                                            <option value="{{ $CommProfile->id }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ $CommProfile->title }} -
                                                {{ ucwords(str_replace('_', ' ', $CommProfile->type)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('commProfile')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="from-group">
                                    <label for="lastName" class="form-label">Note</label>
                                    <textarea class="form-control mt-2 w-full @error('newcommNote') !border-danger-500 @enderror"
                                        wire:model.defer="newcommNote"></textarea>
                                    @error('newcommNote')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!-- Modal footer -->
                            <div
                                class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                <button wire:click="addComm" data-bs-dismiss="modal"
                                    class="btn inline-flex justify-center text-white bg-black-500">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endcan

    @can('updatePayments', $soldPolicy)
        @if ($adjustCommSec)
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
                                    Add Sales Commission
                                </h3>
                                <button wire:click="toggleAdjustComm" type="button"
                                    class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
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

                                <div class="from-group">
                                    <label for="commFrom" class="form-label">From</label>
                                    <select name="commFrom" id="commFrom" class="form-control w-full mt-2"
                                        wire:model="commFrom">
                                        <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600"
                                            value="">
                                            Select option</option>
                                        @foreach ($FROMS as $FROM)
                                            <option value="{{ $FROM }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ ucwords(str_replace('_', ' ', $FROM)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('commFrom')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="from-group">
                                    <label for="commAmount" class="form-label">Amount</label>
                                    <input type="number" name="commAmount"
                                        class="form-control mt-2 w-full @error('commAmount') !border-danger-500 @enderror"
                                        wire:model.defer="commAmount"></textarea>
                                    @error('commAmount')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="from-group">
                                    <label for="lastName" class="form-label">Commission Profile</label>
                                    <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2"
                                        wire:model="commProfile">
                                        <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            Select Profile</option>
                                        @foreach ($linkedCommProfiles as $CommProfile)
                                            <option value="{{ $CommProfile->id }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ $CommProfile->title }} -
                                                {{ ucwords(str_replace('_', ' ', $CommProfile->type)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('commProfile')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="from-group">
                                    <label for="lastName" class="form-label">Note</label>
                                    <textarea class="form-control mt-2 w-full @error('newcommNote') !border-danger-500 @enderror"
                                        wire:model.defer="newcommNote"></textarea>
                                    @error('newcommNote')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!-- Modal footer -->
                            <div
                                class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                <button wire:click="adjustComm" data-bs-dismiss="modal"
                                    class="btn inline-flex justify-center text-white bg-black-500">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endcan


    @if ($editPaymentSec)
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
                                Client Payment
                            </h3>
                            <button wire:click="closeEditPaymentSec" type="button"
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
                                <label for="paymentType" class="form-label">Payment type</label>
                                <select name="paymentType" id="basicSelect" class="form-control w-full mt-2"
                                    wire:model="paymentType">
                                    <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                        Select type</option>
                                    @foreach ($PYMT_TYPES as $paymentType)
                                        <option value="{{ $paymentType }}"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ ucwords(str_replace('_', ' ', $paymentType)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @if ($salesOutSelected)
                                <div class="from-group">
                                    <label for="salesOut" class="form-label">Sales Out Profile</label>
                                    <select name="salesOut" id="basicSelect2" class="form-control w-full mt-2"
                                        wire:model="salesOutID">
                                        <option
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600"
                                            disabled>
                                            Select type</option>
                                        @foreach ($salesOuts as $s)
                                            <option value="{{ $s->id }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ $s->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('salesOutID')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif


                            <div class="from-group">
                                <label for="paymentDue" class="form-label">Due</label>
                                <input type="date" name="paymentDue"
                                    class="form-control mt-2 w-full @error('paymentDue') !border-danger-500 @enderror"
                                    wire:model.defer="paymentDue">
                                @error('paymentDue')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="from-group">
                                <label for="paymentAssignee" class="form-label">Assigned to</label>
                                <select name="paymentAssignee" id="paymentAssignee"
                                    class="form-control w-full mt-2" wire:model="paymentAssignee">
                                    <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                        Select user</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ $user->first_name . ' ' . $user->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="from-group">
                                <label for="paymentNote" class="form-label">Note</label>
                                <textarea name="paymentNote" class="form-control mt-2 w-full @error('paymentNote') !border-danger-500 @enderror"
                                    wire:model.defer="paymentNote"></textarea>
                                @error('paymentNote')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editClientPayment" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="editClientPayment">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="editClientPayment"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @can('create', \App\Models\Payments\ClientPayment::class)
        @if ($addClientPaymentSec)
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
                                    Add Client Payment
                                </h3>
                                <button wire:click="toggleAddClientPayment" type="button"
                                    class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
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
                                <div class="from-group">
                                    <label for="paymentType" class="form-label">Payment type</label>
                                    <select name="paymentType" id="basicSelect" class="form-control w-full mt-2"
                                        wire:model="paymentType">
                                        <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            Select type</option>
                                        @foreach ($PYMT_TYPES as $paymentType)
                                            <option value="{{ $paymentType }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ ucwords(str_replace('_', ' ', $paymentType)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                @if ($salesOutSelected)
                                    <div class="from-group">
                                        <label for="salesOut" class="form-label">Sales Out Profile</label>
                                        <select name="salesOut" id="basicSelect2" class="form-control w-full mt-2"
                                            wire:model="salesOutID">
                                            <option
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600"
                                                disabled>
                                                Select type</option>
                                            @foreach ($salesOuts as $s)
                                                <option value="{{ $s->id }}"
                                                    class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                    {{ $s->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('salesOutID')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endif

                                <div class="from-group">
                                    <label for="paymentAmount" class="form-label">Amount</label>
                                    <input type="number" name="paymentAmount"
                                        class="form-control mt-2 w-full @error('paymentAmount') !border-danger-500 @enderror"
                                        wire:model.defer="paymentAmount">
                                    @error('paymentAmount')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="from-group">
                                    <label for="paymentDue" class="form-label">Due</label>
                                    <input type="date" name="paymentDue"
                                        class="form-control mt-2 w-full @error('paymentDue') !border-danger-500 @enderror"
                                        wire:model.defer="paymentDue">
                                    @error('paymentDue')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="from-group">
                                    <label for="paymentAssignee" class="form-label">Assigned to</label>
                                    <select name="paymentAssignee" id="paymentAssignee"
                                        class="form-control w-full mt-2" wire:model="paymentAssignee">
                                        <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            Select user</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ $user->first_name . ' ' . $user->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="from-group">
                                    <label for="paymentNote" class="form-label">Note</label>
                                    <textarea name="paymentNote" class="form-control mt-2 w-full @error('paymentNote') !border-danger-500 @enderror"
                                        wire:model.defer="paymentNote"></textarea>
                                    @error('paymentNote')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!-- Modal footer -->
                            <div
                                class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                <button wire:click="addClientPayment" data-bs-dismiss="modal"
                                    class="btn inline-flex justify-center text-white bg-black-500">
                                    <span wire:loading.remove wire:target="addClientPayment">Submit</span>
                                    <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                        wire:loading wire:target="addClientPayment"
                                        icon="line-md:loading-twotone-loop"></iconify-icon>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endcan

    @can('create', \App\Models\Payments\CompanyCommPayment::class)
        @if ($addCompanyPaymentSec)
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
                                    Add Company Payment
                                </h3>
                                <button wire:click="toggleAddCompanyPayment" type="button"
                                    class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
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

                                <div class="from-group">
                                    <label for="compPaymentType" class="form-label">Payment type</label>
                                    <select name="compPaymentType" id="basicSelect"
                                        class="form-control w-full mt-2 @error('compPaymentType') !border-danger-500 @enderror"
                                        wire:model="compPaymentType">
                                        <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            Select type</option>
                                        @foreach ($PYMT_TYPES as $paymentType)
                                            <option value="{{ $paymentType }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ ucwords(str_replace('_', ' ', $paymentType)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('compPaymentType')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="from-group">
                                    <label for="compPaymentAmount" class="form-label">Amount</label>
                                    <input type="number" name="compPaymentAmount"
                                        class="form-control mt-2 w-full @error('compPaymentAmount') !border-danger-500 @enderror"
                                        wire:model.defer="compPaymentAmount">
                                    @error('compPaymentAmount')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="from-group">
                                    <label for="compPaymentNote" class="form-label">Note</label>
                                    <textarea name="compPaymentNote"
                                        class="form-control mt-2 w-full @error('compPaymentNote') !border-danger-500 @enderror"
                                        wire:model.defer="compPaymentNote"></textarea>
                                    @error('compPaymentNote')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!-- Modal footer -->
                            <div
                                class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                <button wire:click="addCompanyPayment" data-bs-dismiss="modal"
                                    class="btn inline-flex justify-center text-white bg-black-500">
                                    <span wire:loading.remove wire:target="addCompanyPayment">Submit</span>
                                    <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                        wire:loading wire:target="addCompanyPayment"
                                        icon="line-md:loading-twotone-loop"></iconify-icon>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endcan

    @can('updateMainSales', $soldPolicy)
        @if ($setMainSalesSec)
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
                                    Set main sales
                                </h3>
                                <button wire:click="closeSetMainSalesSection" type="button"
                                    class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
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

                                <div class="from-group">
                                    <label for="updatedMainSaledID" class="form-label">Main sales</label>
                                    <select name="updatedMainSaledID" id="basicSelect"
                                        class="form-control w-full mt-2 @error('updatedMainSaledID') !border-danger-500 @enderror"
                                        wire:model="updatedMainSaledID">
                                        <option value=""
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            None</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ $user->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('updatedMainSaledID')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!-- Modal footer -->
                            <div
                                class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                <button wire:click="updateMainSales" data-bs-dismiss="modal"
                                    class="btn inline-flex justify-center text-white bg-black-500">
                                    <span wire:loading.remove wire:target="updateMainSales">Submit</span>
                                    <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                        wire:loading wire:target="updateMainSales"
                                        icon="line-md:loading-twotone-loop"></iconify-icon>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endcan

    {{-- START: Penalty Modal --}}
    @if ($penaltyModal)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="penaltyModalLabel" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Update Penalty Information
                            </h3>
                            <button wire:click="closePenaltyModal" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
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
                            <div class="from-group">
                                <label class="form-label">Apply Penalty</label>
                                <div class="flex items-center mt-2">
                                    <div class="flex-col space-y-2">
                                        <div>
                                            <input type="checkbox" id="isManualPenalty"
                                                class="form-checkbox w-4 h-4 text-black-500 bg-transparent border-slate-300 rounded focus:ring-black-500 focus:ring-2 dark:bg-slate-700 dark:border-slate-600 dark:focus:ring-black-500 dark:focus:ring-offset-slate-800"
                                                wire:model.live="isManualPenalty">
                                            <label for="isManualPenalty"
                                                class="ml-2 text-sm font-medium text-slate-700 dark:text-slate-300">
                                                Enable manual penalty calculation for this policy
                                            </label>
                                        </div>
                                        <span class="text-sm text-slate-700 dark:text-slate-300">
                                            If disabled, this will trigger a generate commissions process to recalculate
                                            the penalty.
                                        </span>
                                    </div>
                                </div>
                                @error('isManualPenalty')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                @if ($isManualPenalty)
                                    <div class="flex items-center mt-2">
                                        <input type="checkbox" id="isPenalized"
                                            class="form-checkbox w-4 h-4 text-black-500 bg-transparent border-slate-300 rounded focus:ring-black-500 focus:ring-2 dark:bg-slate-700 dark:border-slate-600 dark:focus:ring-black-500 dark:focus:ring-offset-slate-800"
                                            wire:model="isPenalized">
                                        <label for="isPenalized"
                                            class="ml-2 text-sm font-medium text-slate-700 dark:text-slate-300">
                                            Is Sold Policy Penalized?
                                        </label>
                                    </div>
                                    @error('isPenalized')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                @endif
                            </div>
                            @if ($isManualPenalty)
                                <div class="from-group">
                                    <label for="penaltyAmount" class="form-label">Penalty Amount</label>
                                    <input type="number" name="penaltyAmount" step="0.01" min="0"
                                        class="form-control mt-2 w-full @error('penaltyAmount') !border-danger-500 @enderror"
                                        wire:model.defer="penaltyAmount" placeholder="Enter penalty amount"
                                        {{ !$isPenalized ? 'disabled' : '' }}>
                                    @error('penaltyAmount')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="closePenaltyModal" type="button"
                                class="btn inline-flex justify-center btn-white dark:bg-slate-700 dark:text-slate-300">
                                Cancel
                            </button>
                            <button wire:click="updatePenalty" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="updatePenalty">Update Penalty</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="updatePenalty"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- END: Penalty Modal --}}

</div>
