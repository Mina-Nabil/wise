<div>
    <div>
        <div class="flex justify-between flex-wrap items-center">
            <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
                <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                    <b>Reports:</b> Sold Policies
                </h4>
            </div>
            <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
                @if (Auth::user()->is_admin)
                    <button wire:click="exportReport"
                        class="btn inline-flex justify-center btn-outline-dark rounded-[25px]">
                        <span wire:loading.remove wire:target="exportReport">Export</span>
                        <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading
                            wire:target="exportReport" icon="line-md:loading-twotone-loop"></iconify-icon>
                    </button>
                @endif
                @if ($issued_from && $issued_to)
                    @can('viewCommission', App\Models\Business\SoldPolicy::class)
                        <button wire:click="exportHay2aReport"
                            class="btn inline-flex justify-center btn-outline-dark rounded-[25px]">
                            <span wire:loading.remove wire:target="exportHay2aReport">Export تقرير الهيئه</span>
                            <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading
                                wire:target="exportHay2aReport" icon="line-md:loading-twotone-loop"></iconify-icon>
                        </button>
                    @endcan
                @endif
                <div class="dropdown relative ">
                    <button class="btn inline-flex justify-center btn-dark items-center cursor-default relative !pr-14"
                        type="button" id="darksplitDropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        Add filter
                        <span
                            class="cursor-pointer absolute ltr:border-l rtl:border-r border-slate-100 h-full ltr:right-0 rtl:left-0 px-2 flex
                                    items-center justify-center leading-none">
                            <iconify-icon class="leading-none text-xl"
                                icon="ic:round-keyboard-arrow-down"></iconify-icon>
                        </span>
                    </button>
                    <ul
                        class=" dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow
                                z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                        <li wire:click="toggleStartDate">
                            <span
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white cursor-pointer">
                                Start date ( From-To )</span>
                        </li>

                        <li wire:click="toggleExpiryDate">
                            <span
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white cursor-pointer">
                                Expiry date ( From-To )</span>
                        </li>
                        <li wire:click="togglePaidDate">
                            <span
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white cursor-pointer">
                                Paid date ( From-To )</span>
                        </li>
                        <li wire:click="toggleIssuedDate">
                            <span
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white cursor-pointer">
                                Issued date ( From-To )</span>
                        </li>
                        <li wire:click="toggleProfiles">
                            <span
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white cursor-pointer">
                                Commissions Profile</span>
                        </li>
                        <li wire:click="openCreatorSection">
                            <span
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white cursor-pointer">
                                Creator</span>
                        </li>
                        <li wire:click="toggleMainSales">
                            <span
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white cursor-pointer">
                                Main Sales</span>
                        </li>
                        <li wire:click="toggleLob">
                            <span
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white cursor-pointer">
                                Line of business</span>
                        </li>
                        <li wire:click="toggleValues">
                            <span
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white cursor-pointer">
                                Insured Value ( From-To )</span>
                        </li>
                        <li wire:click="toggleNetPrem">
                            <span
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white cursor-pointer">
                                Net premium ( From-To )</span>
                        </li>
                        <li wire:click="toggleBrands">
                            <span href="#"
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white cursor-pointer">
                                Car brand</span>
                        </li>
                        <li wire:click="toggleCompany">
                            <span href="#"
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white cursor-pointer">
                                Insurance Company</span>
                        </li>
                        <li wire:click="togglePolicy">
                            <span href="#"
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white cursor-pointer">
                                Policy</span>
                        </li>
                        <li wire:click="toggleValidated">
                            <span href="#"
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white cursor-pointer">
                                is Valid</span>
                        </li>
                        <li wire:click="togglePaid">
                            <span href="#"
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white cursor-pointer">
                                is Paid</span>
                        </li>
                        <li wire:click="toggleRenewal">
                            <span href="#"
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white cursor-pointer">
                                is Renewal</span>
                        </li>
                        <li wire:click="togglePenalized">
                            <span href="#"
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white cursor-pointer">
                                is Penalty</span>
                        </li>
                        <li wire:click="toggleWelcomed">
                            <span href="#"
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white cursor-pointer">
                                is Welcomed</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
        <div class="card-body px-6 pb-6  overflow-x-auto">
            <div class=" -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="card">
                        <header class="card-header cust-card-header noborder">
                            <iconify-icon wire:loading class="loading-icon text-lg"
                                icon="line-md:loading-twotone-loop"></iconify-icon>
                            <input type="text" class="form-control !pl-9 mr-1 basis-1/4" placeholder="Search"
                                wire:model="search">

                        </header>

                        <header class="card-header cust-card-header noborder" style="display: block;">

                            @if ($start_from || $start_to)
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="toggleStartDate">
                                        {{ $start_from ? 'Start From: ' . \Carbon\Carbon::parse($start_from)->format('l d/m/Y') : '' }}
                                        {{ $start_from && $start_to ? '-' : '' }}
                                        {{ $start_to ? 'Start To: ' . \Carbon\Carbon::parse($start_to)->format('l d/m/Y') : '' }}
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearStartDates">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if ($expiry_from || $expiry_to)
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="setExpiryDates">
                                        {{ $expiry_from ? 'Expiry From: ' . \Carbon\Carbon::parse($expiry_from)->format('l d/m/Y') : '' }}
                                        {{ $expiry_from && $expiry_to ? '-' : '' }}
                                        {{ $expiry_to ? 'Expiry To: ' . \Carbon\Carbon::parse($expiry_to)->format('l d/m/Y') : '' }}
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearExpiryDates">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if ($paid_from || $paid_to)
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="setPaidDates">
                                        {{ $paid_from ? 'Paid From: ' . \Carbon\Carbon::parse($paid_from)->format('l d/m/Y') : '' }}
                                        {{ $paid_from && $paid_to ? '-' : '' }}
                                        {{ $paid_to ? 'Paid To: ' . \Carbon\Carbon::parse($paid_to)->format('l d/m/Y') : '' }}
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearPaidDates">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if ($profiles)
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="toggleProfiles">
                                        Profiles:&nbsp;(
                                        @foreach ($profiles as $profile)
                                            @php
                                                $decodedProfile = json_decode($profile, true); // Decode JSON string to array
                                            @endphp
                                            {{ $decodedProfile['title'] }}
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                        )
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearProfiles">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif


                            @if ($issued_from || $issued_to)
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="toggleIssuedDate">
                                        {{ $issued_from ? 'Issued From: ' . \Carbon\Carbon::parse($issued_from)->format('l d/m/Y') : '' }}
                                        {{ $issued_from && $issued_to ? '-' : '' }}
                                        {{ $issued_to ? 'Issued To: ' . \Carbon\Carbon::parse($issued_to)->format('l d/m/Y') : '' }}
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearIssuedDates">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if (!empty($FilteredCreators))
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span class="text-slate-300">Creators:</span>&nbsp;
                                    <span wire:click="openCreatorSection">
                                        @foreach ($FilteredCreators as $index => $creator)
                                            {{ $creator['name'] }}@if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearCreator">
                                        <iconify-icon icon="material-symbols:close" width="1.2em" height="1.2em"
                                            wire:loading.remove wire:target="clearCreator"></iconify-icon>
                                        <iconify-icon class="text-xl spin-slow" icon="line-md:loading-twotone-loop"
                                            wire:loading wire:target="clearCreator"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if ($main_sales_id)
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="toggleMainSales">
                                        {{ $main_sales_id ? 'Main Sales: ' . $mainSalesName : '' }}
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearMainSales">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if ($line_of_business)
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="toggleLob">
                                        {{ $line_of_business ? 'LOB: ' . ucwords(str_replace('_', ' ', $line_of_business)) : '' }}
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearLob">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if ($value_from || $value_to)
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="toggleValues">
                                        {{ $value_from ? 'Value From: ' . number_format($value_from, 0, '.', ',') : '' }}
                                        {{ $value_from && $value_to ? '-' : '' }}
                                        {{ $value_to ? 'Value To: ' . number_format($value_to, 0, '.', ',') : '' }}
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearValues">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if ($net_premium_from || $net_premium_to)
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="toggleNetPrem">
                                        {{ $net_premium_from ? 'Net Premium From: ' . number_format($net_premium_from, 0, '.', ',') : '' }}
                                        {{ $net_premium_from && $net_premium_to ? '-' : '' }}
                                        {{ $net_premium_to ? 'Net Premium To: ' . number_format($net_premium_to, 0, '.', ',') : '' }}
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearNetPrems">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if ($brand_ids)
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="toggleBrands">
                                        Brnads(
                                        @foreach ($brand_ids as $id)
                                            @php
                                                $brand = \App\Models\Cars\Brand::find($id)->name;
                                            @endphp

                                            {{ $brand }}
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach

                                        )
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearBrands">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if ($company_ids)
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="toggleCompany">
                                        Company(
                                        @foreach ($company_ids as $id)
                                            @php
                                                $company = \App\Models\Insurance\Company::find($id)->name;
                                            @endphp

                                            {{ $company }}
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach

                                        )
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearCompany">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if ($Epolicy_ids)
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="togglePolicy">
                                        Policy(
                                        @foreach ($policy_ids as $id)
                                            @php
                                                $pol = \App\Models\Insurance\Policy::find($id);
                                            @endphp

                                            {{ $pol->company->name }} - {{ $pol->name }}
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach

                                        )
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearPolicy">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if (!is_null($is_valid))
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="toggleValidated">
                                        @if ($is_valid)
                                            Valid:&nbsp;Yes
                                        @else
                                            Valid:&nbsp;No
                                        @endif
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearvalid">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if (!is_null($is_paid))
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="togglePaid">
                                        @if ($is_paid)
                                            Paid:&nbsp;Yes
                                        @else
                                            Paid:&nbsp;No
                                        @endif
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearpaid">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if (!is_null($is_renewal))
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="toggleRenewal">
                                        @if ($is_renewal)
                                            Renewal:&nbsp;Yes
                                        @else
                                            Renewal:&nbsp;No
                                        @endif
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearrenewal">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if (!is_null($is_penalized))
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="togglePenalized">
                                        @if ($is_penalized)
                                            Penalty:&nbsp;Yes
                                        @else
                                            Penalty:&nbsp;No
                                        @endif
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearpenalized">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if (!is_null($is_cancelled))
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="togglePenalized">
                                        @if ($is_cancelled)
                                            Cancelled:&nbsp;Yes
                                        @else
                                            Cancelled:&nbsp;No
                                        @endif
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearcancelled">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if (!is_null($is_welcomed))
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="toggleWelcomed">
                                        @if ($is_welcomed)
                                            Welcomed:&nbsp;Yes
                                        @else
                                            Welcomed:&nbsp;No
                                        @endif
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearwelcomed">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                        </header>

                        <div class="tab-content mt-6 " id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-list" role="tabpanel"
                                aria-labelledby="pills-list-tab">
                                <div class="tab-content">
                                    <div class="card ">
                                        <div class="card-body px-6 rounded pb-3">
                                            <div class=" -mx-6">
                                                <div class="inline-block min-w-full align-middle">
                                                    <div class="">
                                                        <table
                                                            class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 no-wrap">
                                                            <thead class="bg-slate-200 dark:bg-slate-700">
                                                                <tr>
                                                                    @can('review',
                                                                        \App\Models\Business\SoldPolicy::class)
                                                                        <th scope="col" class="table-th ">
                                                                            REVIEW
                                                                        </th>
                                                                    @endcan
                                                                    <th scope="col" class="table-th ">
                                                                        POLICY
                                                                    </th>
                                                                    <th scope="col" class="table-th ">
                                                                        START
                                                                    </th>
                                                                    <th scope="col" class="table-th ">
                                                                        END
                                                                    </th>
                                                                    <th scope="col" class="table-th ">
                                                                        PYMT
                                                                    </th>
                                                                    <th scope="col" class="table-th ">
                                                                        NUMBER
                                                                    </th>
                                                                    <th scope="col" class="table-th ">
                                                                        CLIENT NAME
                                                                    </th>

                                                                    <th scope="col" class="table-th ">
                                                                        NET PREM.
                                                                    </th>
                                                                    @can('viewCommission',
                                                                        App\Models\Business\SoldPolicy::class)
                                                                        <th scope="col" class="table-th ">
                                                                            SalesOut
                                                                        </th>
                                                                        <th scope="col" class="table-th ">
                                                                            SalesOut Paid
                                                                        </th>
                                                                        <th scope="col" class="table-th ">
                                                                            SalesOut Paid Date
                                                                        </th>
                                                                        <th scope="col" class="table-th ">
                                                                            Expected
                                                                        </th>
                                                                        <th scope="col" class="table-th ">
                                                                            Invoice
                                                                        </th>
                                                                        <th scope="col" class="table-th ">
                                                                            Collected
                                                                        </th>
                                                                    @endcan
                                                                    <th scope="col" class="table-th ">
                                                                        STATUS
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody
                                                                class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                                                @foreach ($policies as $policy)
                                                                    <tr
                                                                        class="even:bg-slate-50 dark:even:bg-slate-700">
                                                                        @can('review', $policy)
                                                                            <td class="table-td">
                                                                                <div class="flex flex-col gap-1">
                                                                                    <div class="flex items-center gap-2">
                                                                                        @if ($policy->is_reviewed)
                                                                                            <span
                                                                                                class="badge bg-success-500 text-slate-800 bg-opacity-30 rounded-3xl">Reviewed</span>
                                                                                        @else
                                                                                            <span
                                                                                                class="badge bg-warning-500 text-slate-800 bg-opacity-30 rounded-3xl">Not
                                                                                                Reviewed</span>
                                                                                        @endif
                                                                                        @if ($policy->review_comment)
                                                                                            <iconify-icon
                                                                                                id="comment-icon-{{ $policy->id }}"
                                                                                                icon="mdi:comment-text-outline"
                                                                                                class="text-base cursor-help"></iconify-icon>
                                                                                            <script>
                                                                                                tippy('#comment-icon-{{ $policy->id }}', {
                                                                                                    content: '{{ $policy->review_comment }}',
                                                                                                });
                                                                                            </script>
                                                                                        @endif
                                                                                    </div>

                                                                                    @if ($policy->is_valid_data)
                                                                                        <span
                                                                                            class="badge bg-success-500 text-slate-800 bg-opacity-30 rounded-3xl">Valid
                                                                                            Data</span>
                                                                                    @else
                                                                                        <span
                                                                                            class="badge bg-danger-500 text-slate-800 bg-opacity-30 rounded-3xl">Invalid
                                                                                            Data</span>
                                                                                    @endif


                                                                                </div>

                                                                                <div class="flex items-center gap-3">
                                                                                    <button
                                                                                        wire:click="openReviewModal({{ $policy->id }})"
                                                                                        class="btn btn-sm btn-outline-primary flex items-center"
                                                                                        title="{{ $policy->is_reviewed ? 'Edit Review' : 'Review Policy' }}">
                                                                                        <iconify-icon
                                                                                            icon="mdi:clipboard-check-outline"
                                                                                            class="text-base"></iconify-icon>
                                                                                        <span
                                                                                            class="ml-1">{{ $policy->is_reviewed ? 'Edit Review' : 'Review' }}</span>
                                                                                    </button>
                                                                                </div>
                                                                            </td>
                                                                        @endcan


                                                                        <td class="table-td">
                                                                            <div class="flex-1 text-start">
                                                                                <h4
                                                                                    class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                                                    {{ $policy->policy->company->name }}
                                                                                </h4>
                                                                                <div
                                                                                    class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                                                                    {{ $policy->policy->name }}
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td class="table-td">
                                                                            <span
                                                                                class="block date-text">{{ \Carbon\Carbon::parse($policy->start)->format('d-m-Y') }}</span>
                                                                        </td>
                                                                        <td class="table-td">
                                                                            <span
                                                                                class="block date-text">{{ \Carbon\Carbon::parse($policy->expiry)->format('d-m-Y') }}</span>
                                                                        </td>
                                                                        <td class="table-td">
                                                                            <span
                                                                                class="block date-text">{{ \Carbon\Carbon::parse($policy->client_payment_date)->format('d-m-Y') }}</span>
                                                                        </td>
                                                                        <td class="table-td">
                                                                            <a href="{{ route('sold.policy.show', $policy->id) }}"
                                                                                target="_blank"
                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <span class="block date-text">
                                                                                    {{ $policy->policy_number }}
                                                                                </span>
                                                                            </a>
                                                                        </td>
                                                                        <td class="table-td">
                                                                            <div
                                                                                class="flex space-x-3 items-center text-left rtl:space-x-reverse">

                                                                                <div
                                                                                    class="flex-1 font-medium text-sm leading-4 whitespace-nowrap">
                                                                                    <a class="hover:underline cursor-pointer"
                                                                                        href="{{ route($policy->client_type . 's.show', $policy->client_id) }}">
                                                                                        @if ($policy->client_type === 'customer')
                                                                                            {{ $policy->client->first_name . ' ' . $policy->client->middle_name . ' ' . $policy->client->last_name }}
                                                                                        @elseif($policy->client_type === 'corporate')
                                                                                            {{ $policy->client->name }}
                                                                                        @endif
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </td>

                                                                        <td class="table-td ">
                                                                            <b>{{ number_format($policy->net_premium, 0, '.', ',') }}</b>
                                                                        </td>
                                                                        @can('viewCommission', $policy)
                                                                            <td class="table-td ">
                                                                                <b>{{ number_format($policy->sales_out_comm, 0, '.', ',') }}</b>
                                                                            </td>
                                                                            <td class="table-td ">
                                                                                <b>{{ number_format($policy->sales_out_comm_paid, 0, '.', ',') }}</b>
                                                                            </td>
                                                                            <td class="table-td ">
                                                                                <b>{{ $policy->sales_out_comm_paid_date }}</b>
                                                                            </td>
                                                                            <td class="table-td ">
                                                                                <b>{{ number_format($policy->total_policy_comm, 0, '.', ',') }}</b>
                                                                            </td>
                                                                            <td class="table-td ">
                                                                                <b>{{ number_format($policy->invoiced_amount, 0, '.', ',') }}</b>
                                                                            </td>
                                                                            <td class="table-td ">
                                                                                <b>
                                                                                    {{ number_format($policy->total_comp_paid, 0, '.', ',') }}</b>
                                                                            </td>
                                                                        @endcan
                                                                        <td class="table-td">
                                                                            @if ($policy->is_valid)
                                                                                <span
                                                                                    class="badge bg-success-500 text-slate-800 text-success-500 bg-opacity-30 capitalize rounded-3xl">Validated</span>
                                                                            @endif
                                                                            @if ($policy->is_paid)
                                                                                <span
                                                                                    class="badge bg-success-500 text-slate-800 text-success-500 bg-opacity-30 capitalize rounded-3xl">Paid</span>
                                                                            @endif
                                                                            @if ($policy->is_renewal)
                                                                                <span
                                                                                    class="badge bg-success-500 text-slate-800 text-success-500 bg-opacity-30 capitalize rounded-3xl">Renewal</span>
                                                                            @endif
                                                                            @if ($policy->is_penalized)
                                                                                <span
                                                                                    class="badge bg-danger-500 text-slate-800 text-danger-500 bg-opacity-30 capitalize rounded-3xl">Penalty</span>
                                                                            @endif
                                                                            @if ($policy->cancellation_time)
                                                                                <span
                                                                                    class="badge bg-danger-500 text-slate-800 text-danger-500 bg-opacity-30 capitalize rounded-3xl">Cancelled
                                                                                    on:
                                                                                    {{ \Carbon\Carbon::parse($policy->cancellation_time)->format('D d/m/Y') }}
                                                                                </span>
                                                                            @endif
                                                                            @if ($policy->client->is_welcomed)
                                                                                <span
                                                                                    class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize cursor-pointer"
                                                                                    wire:click="openEditIsWelcomed({{ $policy->client_id }}, '{{ $policy->client_type }}')"><iconify-icon
                                                                                        icon="fa6-solid:handshake"
                                                                                        width="1.2em"
                                                                                        height="1.2em"></iconify-icon>&nbsp;Welcomed</span>
                                                                            @else
                                                                                <span
                                                                                    class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize cursor-pointer"
                                                                                    wire:click="openEditIsWelcomed({{ $policy->client_id }}, '{{ $policy->client_type }}')">
                                                                                    <iconify-icon
                                                                                        icon="fa6-solid:handshake-simple-slash"
                                                                                        width="1.2em"
                                                                                        height="1.2em"></iconify-icon>
                                                                                    &nbsp;Not Welcomed</span>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                @if ($policies->isEmpty())
                                                    {{-- START: empty filter result --}}
                                                    <div class="card m-5 p-5">
                                                        <div class="card-body rounded-md bg-white dark:bg-slate-800">
                                                            <div class="items-center text-center p-5">
                                                                <h2><iconify-icon
                                                                        icon="icon-park-outline:search"></iconify-icon>
                                                                </h2>
                                                                <h2
                                                                    class="card-title text-slate-900 dark:text-white mb-3">
                                                                    No Pold Policies with the
                                                                    applied
                                                                    filters</h2>
                                                                <p class="card-text">Try changing the filters or
                                                                    search terms for this view.
                                                                </p>
                                                                <a href="{{ url('/sold-policies') }}"
                                                                    class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">View
                                                                    all Sold Policies</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- END: empty filter result --}}
                                                @endif
                                            </div>
                                            {{ $policies->links('vendor.livewire.bootstrap') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- commProfilesSection --}}
    @if ($commProfilesSection)
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
                                Commissions Profiles
                            </h3>
                            <button wire:click="toggleProfiles" type="button"
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
                                <label for="Eline_of_business" class="form-label">Select Profile</label>
                                @foreach ($COMM_PROFILES as $COMM_PROFILE)
                                    <div class="checkbox-area">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="hidden"
                                                value="{{ json_encode(['id' => $COMM_PROFILE->id, 'title' => $COMM_PROFILE->title]) }}"
                                                name="checkbox" wire:model.defer="Eprofiles">
                                            <span
                                                class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                <img src="{{ asset('assets/images/icon/ck-white.svg') }}"
                                                    alt=""
                                                    class="h-[10px] w-[10px] block m-auto opacity-0"></span>
                                            <span
                                                class="text-slate-500 dark:text-slate-400 text-sm leading-6">{{ $COMM_PROFILE->title }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setProfiles" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setProfiles">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setProfiles"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($isWelcomedClientId)
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
                                Welcomed Status
                            </h3>
                            <button wire:click="closeEditIsWelcomed" type="button"
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
                                <label for="Estart_from" class="form-label">Welcomed ?</label>
                                <select name="isWelcomed" id="isWelcomed" class="form-control w-full mt-2"
                                    wire:model.defer="isWelcomed">
                                    <option value="yes"
                                        class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                        Yes
                                    </option>
                                    <option value="no"
                                        class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                        No
                                    </option>
                                </select>
                            </div>
                            <div class="from-group">
                                <label class="form-label">Welcome Note</label>
                                <textarea rows=3 class="form-control py-2 flatpickr cursor-pointer flatpickr-input active" id="default-picker"
                                    type="text" wire:model.defer="welcomedNote" autocomplete="off"></textarea>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="updateIsWelcomed" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="updateIsWelcomed">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="updateIsWelcomed"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($startSection)
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
                                Start date
                            </h3>
                            <button wire:click="toggleStartDate" type="button"
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
                                <label for="Estart_from" class="form-label">Start from</label>
                                <input name="Estart_from" type="date"
                                    class="form-control mt-2 w-full @error('Estart_from') !border-danger-500 @enderror"
                                    wire:model.defer="Estart_from">
                                @error('Estart_from')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="Estart_to" class="form-label">Start to</label>
                                <input name="Estart_to" type="date"
                                    class="form-control mt-2 w-full @error('Estart_to') !border-danger-500 @enderror"
                                    wire:model.defer="Estart_to">
                                @error('Estart_to')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setStartDates" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setStartDates">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setStartDates"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($expirySection)
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
                                Expiry date
                            </h3>
                            <button wire:click="toggleExpiryDate" type="button"
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
                                <label for="Eexpiry_from" class="form-label">Expiry from</label>
                                <input name="Eexpiry_from" type="date"
                                    class="form-control mt-2 w-full @error('Eexpiry_from') !border-danger-500 @enderror"
                                    wire:model.defer="Eexpiry_from">
                                @error('Eexpiry_from')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="Eexpiry_to" class="form-label">Expiry to</label>
                                <input name="Eexpiry_to" type="date"
                                    class="form-control mt-2 w-full @error('Eexpiry_to') !border-danger-500 @enderror"
                                    wire:model.defer="Eexpiry_to">
                                @error('Eexpiry_to')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setExpiryDates" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setExpiryDates">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setExpiryDates"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($paidSection)
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
                                Paid date
                            </h3>
                            <button wire:click="toggleExpiryDate" type="button"
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
                                <label for="Epaid_from" class="form-label">Paid from</label>
                                <input name="Epaid_from" type="date"
                                    class="form-control mt-2 w-full @error('Epaid_from') !border-danger-500 @enderror"
                                    wire:model.defer="Epaid_from">
                                @error('Epaid_from')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="Epaid_to" class="form-label">Paid to</label>
                                <input name="Epaid_to" type="date"
                                    class="form-control mt-2 w-full @error('Epaid_to') !border-danger-500 @enderror"
                                    wire:model.defer="Epaid_to">
                                @error('Epaid_to')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setPaidDates" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setPaidDates">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setPaidDates"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($issuedSection)
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
                                Issued date
                            </h3>
                            <button wire:click="toggleIssuedDate" type="button"
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
                                <label for="Eissued_from" class="form-label">Issued from</label>
                                <input name="Eissued_from" type="date"
                                    class="form-control mt-2 w-full @error('Eissued_from') !border-danger-500 @enderror"
                                    wire:model.defer="Eissued_from">
                                @error('Eissued_from')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="Eissued_to" class="form-label">Issued to</label>
                                <input name="Eissued_to" type="date"
                                    class="form-control mt-2 w-full @error('Eissued_to') !border-danger-500 @enderror"
                                    wire:model.defer="Eissued_to">
                                @error('Eissued_to')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setIssuedDates" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setIssuedDates">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setIssuedDates"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($creatorSection)
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
                                Filter by creator
                            </h3>
                            <button wire:click="closeCreatorSection" type="button"
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

                            <div>
                                <div class="from-group">
                                    <label for="selectAUser" class="form-label">User</label>
                                    <select name="selectAUser" id="selectAUser" class="form-control w-full mt-2"
                                        wire:model="selectAUser">
                                        <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600"
                                            value="">Select user</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ $user->first_name . ' ' . $user->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="checkbox-area mt-2">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input wire:model.defer='isAddCommProfiles' type="checkbox" class="hidden"
                                            name="checkbox">
                                        <span
                                            class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                            <img src="{{ asset('assets/images/icon/ck-white.svg') }}" alt=""
                                                class="h-[10px] w-[10px] block m-auto opacity-0"></span>
                                        <span class="text-slate-500 dark:text-slate-400 text-sm leading-6">Select
                                            Commission Profiles ?</span>
                                    </label>
                                </div>

                                <div class="mt-5">
                                    <button wire:click='selectChildrens'
                                        class="btn inline-flex justify-center btn-dark btn-sm">
                                        <span wire:loading.remove wire:target="selectChildrens">Select all team</span>
                                        <iconify-icon class="text-md spin-slow relative top-[1px]" wire:loading
                                            wire:target="selectChildrens"
                                            icon="line-md:loading-twotone-loop"></iconify-icon>
                                    </button>
                                    <button wire:click='clearSelectedCreatorst'
                                        class="btn inline-flex justify-center btn-light btn-sm">
                                        <span wire:loading.remove wire:target="clearSelectedCreatorst">Clear</span>
                                        <iconify-icon class="text-md spin-slow relative top-[1px]" wire:loading
                                            wire:target="clearSelectedCreatorst"
                                            icon="line-md:loading-twotone-loop"></iconify-icon>
                                    </button>
                                </div>



                            </div>
                            <div class='mt-5'>
                                @foreach ($selectedCreators as $index => $creator)
                                    <span class="badge bg-slate-900 text-white capitalize">
                                        {{ $creator['name'] }} &nbsp;
                                        <iconify-icon class="cursor-pointer"
                                            wire:click="removeCreator({{ $index }})" icon="mdi:remove"
                                            width="12" height="12"></iconify-icon>
                                    </span>
                                @endforeach
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setCtreators" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setCtreators">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setCtreators"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($mainSalesSection)
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
                                Main Sales
                            </h3>
                            <button wire:click="toggleMainSales" type="button"
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
                                <label for="Emain_sales_id" class="form-label">Main Sales</label>
                                <select name="Emain_sales_id" id="Emain_sales_id" class="form-control w-full mt-2"
                                    wire:model.defer="Emain_sales_id">
                                    <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600"
                                        value="">
                                        Select user</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ $user->first_name . ' ' . $user->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setMainSales" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setMainSales">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setMainSales"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($lobSection)
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
                                Line of business
                            </h3>
                            <button wire:click="toggleLob" type="button"
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
                                <label for="Eline_of_business" class="form-label">Line of business</label>
                                <select name="Eline_of_business" id="Eline_of_business"
                                    class="form-control w-full mt-2" wire:model.defer="Eline_of_business">
                                    <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600"
                                        value="">
                                        Select user</option>
                                    @foreach ($LINES_OF_BUSINESS as $LOB)
                                        <option value="{{ $LOB }}"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ ucwords(str_replace('_', ' ', $LOB)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setLob" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setLob">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setLob"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($valueSection)
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
                                Insured Values
                            </h3>
                            <button wire:click="toggleValues" type="button"
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
                                <label for="Evalue_from" class="form-label">Value from</label>
                                <input name="Evalue_from" type="number"
                                    class="form-control mt-2 w-full @error('Evalue_from') !border-danger-500 @enderror"
                                    wire:model.defer="Evalue_from">
                                @error('Evalue_from')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="Evalue_to" class="form-label">Value to</label>
                                <input name="Evalue_to" type="number"
                                    class="form-control mt-2 w-full @error('Evalue_to') !border-danger-500 @enderror"
                                    wire:model.defer="Evalue_to">
                                @error('Evalue_to')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setValues" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setValues">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setValues"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($netPremSection)
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
                                Net Premium Values
                            </h3>
                            <button wire:click="toggleNetPrem" type="button"
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
                                <label for="Enet_premium_from" class="form-label">Net Premium from</label>
                                <input name="Enet_premium_from" type="number"
                                    class="form-control mt-2 w-full @error('Enet_premium_from') !border-danger-500 @enderror"
                                    wire:model.defer="Enet_premium_from">
                                @error('Enet_premium_from')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="Enet_premium_to" class="form-label">Net Premium to</label>
                                <input name="Enet_premium_to" type="number"
                                    class="form-control mt-2 w-full @error('Enet_premium_to') !border-danger-500 @enderror"
                                    wire:model.defer="Enet_premium_to">
                                @error('Enet_premium_to')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setNetPrem" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setNetPrem">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setNetPrem"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($brandSection)
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
                                Car Brands
                            </h3>
                            <button wire:click="toggleBrands" type="button"
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
                            <div>
                                @foreach ($Ebrand_ids as $id)
                                    <!-- Fetch brand name based on ID -->
                                    @php
                                        $brand = \App\Models\Cars\Brand::find($id)->name;
                                    @endphp

                                    <!-- Display brand name -->
                                    <span
                                        class="badge bg-slate-900 text-white capitalize rounded-3xl">{{ $brand }}</span>
                                @endforeach

                            </div>
                            <div class="from-group">
                                <label for="searchBrand" class="form-label">Search Brand</label>
                                <input name="searchBrand" type="text" class="form-control mt-2 w-full"
                                    wire:model="searchBrand">
                            </div>
                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                <thead class="bg-slate-200 dark:bg-slate-700">
                                    <tr>

                                        <th scope="col" class=" table-th ">
                                            Name
                                        </th>

                                        <th scope="col" class=" table-th ">
                                            Country
                                        </th>

                                        <th scope="col" class=" table-th ">
                                            Action
                                        </th>

                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                    @foreach ($brands as $brand)
                                        @if (!in_array($brand->id, $Ebrand_ids))
                                            <tr class="even:bg-slate-50 dark:even:bg-slate-700">
                                                <td class="table-td">{{ $brand->name }}</td>
                                                <td class="table-td">{{ $brand->country->name }}</td>
                                                <td class="table-td "><button
                                                        wire:click="pushBrand({{ $brand->id }})"
                                                        class="btn inline-flex justify-center btn-success light">Add</button>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setBrands" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setBrands">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setBrands"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($companySection)
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
                                Insurance Company
                            </h3>
                            <button wire:click="toggleCompany" type="button"
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
                            <div>
                                @foreach ($Ecompany_ids as $id)
                                    <!-- Fetch brand name based on ID -->
                                    @php
                                        $company = \App\Models\Insurance\Company::find($id)->name;
                                    @endphp

                                    <!-- Display brand name -->
                                    <span
                                        class="badge bg-slate-900 text-white capitalize rounded-3xl">{{ $company }}</span>
                                @endforeach

                            </div>
                            <div class="from-group">
                                <label for="searchCompany" class="form-label">Search Company</label>
                                <input name="searchCompany" type="text" class="form-control mt-2 w-full"
                                    wire:model="searchCompany">
                            </div>
                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                <thead class="bg-slate-200 dark:bg-slate-700">
                                    <tr>

                                        <th scope="col" class=" table-th ">
                                            Name
                                        </th>

                                        <th scope="col" class=" table-th ">
                                            Action
                                        </th>

                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                    @foreach ($companies as $company)
                                        @if (!in_array($company->id, $Ecompany_ids))
                                            <tr class="even:bg-slate-50 dark:even:bg-slate-700">
                                                <td class="table-td">{{ $company->name }}</td>
                                                <td class="table-td "><button
                                                        wire:click="pushCompany({{ $company->id }})"
                                                        class="btn inline-flex justify-center btn-success light">Add</button>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setCompany" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setCompany">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setCompany"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($PolicySection)
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
                                Insurance Policy
                            </h3>
                            <button wire:click="togglePolicy" type="button"
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
                            <div>
                                @foreach ($Epolicy_ids as $id)
                                    <!-- Fetch brand name based on ID -->
                                    @php
                                        $pol = \App\Models\Insurance\Policy::find($id);
                                    @endphp

                                    <!-- Display brand name -->
                                    <span
                                        class="badge bg-slate-900 text-white capitalize rounded-3xl">{{ $pol->company->name }}
                                        - {{ ucwords(str_replace('_', ' ', $pol->business)) }}</span>
                                @endforeach

                            </div>
                            <div class="from-group">
                                <label for="searchPolicy" class="form-label">Search Policy</label>
                                <input name="searchPolicy" type="text" class="form-control mt-2 w-full"
                                    wire:model="searchPolicy">
                            </div>
                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                <thead class="bg-slate-200 dark:bg-slate-700">
                                    <tr>

                                        <th scope="col" class=" table-th ">
                                            Company
                                        </th>

                                        <th scope="col" class=" table-th ">
                                            Name
                                        </th>

                                        <th scope="col" class=" table-th ">
                                            Action
                                        </th>

                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                    @foreach ($InsurancePolicies as $pol)
                                        @if (!in_array($pol->id, $Epolicy_ids))
                                            <tr class="even:bg-slate-50 dark:even:bg-slate-700">
                                                <td class="table-td">{{ $pol->company->name }}</td>
                                                <td class="table-td">{{ $pol->name }}</td>
                                                <td class="table-td "><button
                                                        wire:click="pushPolicy({{ $pol->id }})"
                                                        class="btn inline-flex justify-center btn-success light">Add</button>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setPolicy" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setPolicy">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setPolicy"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <!-- Review Modal -->
    @if ($reviewModal)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="reviewModal" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white">
                                Review Policy: {{ $policyToReview->policy_number ?? '' }}
                            </h3>
                            <button wire:click="closeReviewModal" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="flex flex-col space-y-6">
                                <div class="flex flex-col space-y-2">
                                    <label class="form-label font-medium">Review Status:</label>
                                    <div class="flex items-center space-x-4">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" class="form-radio" wire:model="reviewStatus"
                                                value="1" name="reviewStatus">
                                            <span class="ml-2">Reviewed</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" class="form-radio" wire:model="reviewStatus"
                                                value="0" name="reviewStatus">
                                            <span class="ml-2">Not Reviewed</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <label class="form-label font-medium">Data Validity:</label>
                                    <div class="flex items-center space-x-4">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" class="form-radio" wire:model="reviewValidData"
                                                value="1" name="reviewValidData">
                                            <span class="ml-2">Valid Data</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" class="form-radio" wire:model="reviewValidData"
                                                value="0" name="reviewValidData">
                                            <span class="ml-2">Invalid Data</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="flex flex-col">
                                    <label for="reviewComment" class="form-label font-medium">Review
                                        Comment:</label>
                                    <textarea id="reviewComment" rows="4" class="form-control" wire:model.defer="reviewComment"
                                        placeholder="Add your review comments here..."></textarea>
                                    @error('reviewComment')
                                        <span class="text-danger-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="closeReviewModal" type="button" class="btn btn-outline-danger">
                                Cancel
                            </button>
                            <button wire:click="saveReview" type="button" class="btn btn-dark">
                                <span wire:loading.remove wire:target="saveReview">Save Review</span>
                                <span wire:loading wire:target="saveReview">
                                    <iconify-icon class="text-xl spin-slow"
                                        icon="line-md:loading-twotone-loop"></iconify-icon>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

</div>
