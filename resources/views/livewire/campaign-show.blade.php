<div>
    {{-- Header --}}
    <div class="flex justify-between flex-wrap items-center mb-6">
        <div class="flex items-center space-x-3 rtl:space-x-reverse">
            <a href="{{ route('campaigns.index') }}" class="text-slate-500 hover:text-primary-500">
                <iconify-icon icon="heroicons:arrow-left" class="text-xl"></iconify-icon>
            </a>
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 dark:text-white inline-block">
                {{ $campaign->name }}
            </h4>
            @if ($campaign->is_active)
                <span class="badge bg-success-500 text-white capitalize">Active</span>
            @else
                <span class="badge bg-danger-500 text-white capitalize">Inactive</span>
            @endif
        </div>
        <div class="flex items-center space-x-2 rtl:space-x-reverse text-sm text-slate-500 dark:text-slate-400">
            @if ($campaign->start_date)
                <span>
                    <iconify-icon icon="heroicons:calendar" class="inline-block mr-1"></iconify-icon>
                    {{ $campaign->start_date->format('M d, Y') }}
                </span>
            @endif
            @if ($campaign->start_date && $campaign->end_date)
                <span>→</span>
            @endif
            @if ($campaign->end_date)
                <span>{{ $campaign->end_date->format('M d, Y') }}</span>
            @endif
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid xl:grid-cols-4 lg:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
        <div class="bg-primary-500 rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-25 relative z-[1]">
            <span class="block mb-1 text-sm text-slate-600 dark:text-slate-300 font-medium">Total Clients</span>
            <span class="block text-3xl text-slate-900 dark:text-white font-semibold">{{ $totalClients }}</span>
            <div class="flex items-center space-x-1 rtl:space-x-reverse mt-2">
                <iconify-icon icon="heroicons:users" class="text-primary-500 text-lg"></iconify-icon>
                <span class="text-xs text-slate-500 dark:text-slate-400">Customers &amp; Corporates</span>
            </div>
        </div>

        <div class="bg-info-500 rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-25 relative z-[1]">
            <span class="block mb-1 text-sm text-slate-600 dark:text-slate-300 font-medium">Customers</span>
            <span class="block text-3xl text-slate-900 dark:text-white font-semibold">{{ $totalCustomers }}</span>
            <div class="flex items-center space-x-1 rtl:space-x-reverse mt-2">
                <iconify-icon icon="heroicons:user" class="text-info-500 text-lg"></iconify-icon>
                <span class="text-xs text-slate-500 dark:text-slate-400">Individual leads</span>
            </div>
        </div>

        <div class="bg-warning-500 rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-25 relative z-[1]">
            <span class="block mb-1 text-sm text-slate-600 dark:text-slate-300 font-medium">Corporates</span>
            <span class="block text-3xl text-slate-900 dark:text-white font-semibold">{{ $totalCorporates }}</span>
            <div class="flex items-center space-x-1 rtl:space-x-reverse mt-2">
                <iconify-icon icon="heroicons:building-office-2" class="text-warning-500 text-lg"></iconify-icon>
                <span class="text-xs text-slate-500 dark:text-slate-400">Corporate leads</span>
            </div>
        </div>

        <div class="bg-success-500 rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-25 relative z-[1]">
            <span class="block mb-1 text-sm text-slate-600 dark:text-slate-300 font-medium">Converted</span>
            <span class="block text-3xl text-slate-900 dark:text-white font-semibold">{{ $totalWithPolicies }}</span>
            <div class="flex items-center space-x-1 rtl:space-x-reverse mt-2">
                <iconify-icon icon="heroicons:check-badge" class="text-success-500 text-lg"></iconify-icon>
                <span class="text-xs text-slate-500 dark:text-slate-400">With sold policies</span>
            </div>
        </div>

        <div class="bg-secondary-500 rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-25 relative z-[1]">
            <span class="block mb-1 text-sm text-slate-600 dark:text-slate-300 font-medium">Had Offers</span>
            <span class="block text-3xl text-slate-900 dark:text-white font-semibold">{{ $totalWithOffers }}</span>
            <div class="flex items-center space-x-1 rtl:space-x-reverse mt-2">
                <iconify-icon icon="ic:outline-local-offer" class="text-secondary-500 text-lg"></iconify-icon>
                <span class="text-xs text-slate-500 dark:text-slate-400">Any offer status</span>
            </div>
        </div>

        <div class="bg-success-500 rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-25 relative z-[1]">
            <span class="block mb-1 text-sm text-slate-600 dark:text-slate-300 font-medium">Gross Income</span>
            <span class="block text-2xl text-slate-900 dark:text-white font-semibold">{{ number_format($grossIncome, 0) }}</span>
            <div class="flex items-center space-x-1 rtl:space-x-reverse mt-2">
                <iconify-icon icon="heroicons:banknotes" class="text-success-500 text-lg"></iconify-icon>
                <span class="text-xs text-slate-500 dark:text-slate-400">Sum of after-tax comm</span>
            </div>
        </div>

        <div class="bg-{{ $netIncome >= 0 ? 'success' : 'danger' }}-500 rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-25 relative z-[1]">
            <span class="block mb-1 text-sm text-slate-600 dark:text-slate-300 font-medium">Net Income</span>
            <span class="block text-2xl text-slate-900 dark:text-white font-semibold">{{ number_format($netIncome, 0) }}</span>
            <div class="flex items-center space-x-1 rtl:space-x-reverse mt-2">
                <iconify-icon icon="heroicons:calculator" class="text-{{ $netIncome >= 0 ? 'success' : 'danger' }}-500 text-lg"></iconify-icon>
                <span class="text-xs text-slate-500 dark:text-slate-400">After sales commissions</span>
            </div>
        </div>

        @if ($campaign->budget)
            <div class="bg-{{ $roi === null ? 'slate' : ($roi >= 0 ? 'success' : 'danger') }}-500 rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-25 relative z-[1]">
                <span class="block mb-1 text-sm text-slate-600 dark:text-slate-300 font-medium">ROI</span>
                @if ($roi !== null)
                    <span class="block text-2xl text-slate-900 dark:text-white font-semibold">
                        {{ $roi >= 0 ? '+' : '' }}{{ number_format($roi, 1) }}%
                    </span>
                @else
                    <span class="block text-2xl text-slate-900 dark:text-white font-semibold">—</span>
                @endif
                <div class="flex items-center space-x-1 rtl:space-x-reverse mt-2">
                    <iconify-icon icon="heroicons:arrow-trending-up" class="text-{{ $roi === null ? 'slate' : ($roi >= 0 ? 'success' : 'danger') }}-500 text-lg"></iconify-icon>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Budget: {{ number_format($campaign->budget, 0) }}</span>
                </div>
            </div>
        @endif
    </div>

    {{-- Tabs --}}
    <div class="card">
        <div class="card-body p-6">

            {{-- Tab Nav --}}
            <ul class="nav nav-tabs flex flex-col md:flex-row flex-wrap list-none border-b border-slate-100 dark:border-slate-700 pl-0 mb-6"
                id="campaign-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a wire:click.prevent="changeSection('overview')"
                        class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b-2 border-transparent px-5 py-3 mr-1 hover:border-slate-300 focus:border-transparent cursor-pointer @if ($section === 'overview') active border-primary-500 @endif dark:text-slate-300">
                        <iconify-icon class="mr-2" icon="heroicons:chart-bar"></iconify-icon>
                        Overview
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a wire:click.prevent="changeSection('customers')"
                        class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b-2 border-transparent px-5 py-3 mr-1 hover:border-slate-300 focus:border-transparent cursor-pointer @if ($section === 'customers') active border-primary-500 @endif dark:text-slate-300">
                        <iconify-icon class="mr-2" icon="heroicons:user"></iconify-icon>
                        Customers
                        <span class="ml-2 badge bg-info-500 bg-opacity-20 text-info-500 text-xs px-2">{{ $totalCustomers }}</span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a wire:click.prevent="changeSection('corporates')"
                        class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b-2 border-transparent px-5 py-3 mr-1 hover:border-slate-300 focus:border-transparent cursor-pointer @if ($section === 'corporates') active border-primary-500 @endif dark:text-slate-300">
                        <iconify-icon class="mr-2" icon="heroicons:building-office-2"></iconify-icon>
                        Corporates
                        <span class="ml-2 badge bg-warning-500 bg-opacity-20 text-warning-500 text-xs px-2">{{ $totalCorporates }}</span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a wire:click.prevent="changeSection('sold_policies')"
                        class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b-2 border-transparent px-5 py-3 mr-1 hover:border-slate-300 focus:border-transparent cursor-pointer @if ($section === 'sold_policies') active border-primary-500 @endif dark:text-slate-300">
                        <iconify-icon class="mr-2" icon="mdi:shield-check-outline"></iconify-icon>
                        Sold Policies
                        <span class="ml-2 badge bg-success-500 bg-opacity-20 text-success-500 text-xs px-2">{{ $totalSoldPoliciesCount }}</span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a wire:click.prevent="changeSection('offers')"
                        class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b-2 border-transparent px-5 py-3 mr-1 hover:border-slate-300 focus:border-transparent cursor-pointer @if ($section === 'offers') active border-primary-500 @endif dark:text-slate-300">
                        <iconify-icon class="mr-2" icon="ic:outline-local-offer"></iconify-icon>
                        Offers
                        <span class="ml-2 badge bg-secondary-500 bg-opacity-20 text-secondary-500 text-xs px-2">{{ $totalOffersCount }}</span>
                    </a>
                </li>
            </ul>

            {{-- Tab Content --}}

            {{-- Overview Tab --}}
            @if ($section === 'overview')
                <div class="grid lg:grid-cols-2 grid-cols-1 gap-6">
                    {{-- Campaign Details --}}
                    <div>
                        <h6 class="font-medium text-slate-700 dark:text-slate-300 mb-4 text-base">Campaign Details</h6>
                        <div class="space-y-4">
                            @if ($campaign->description)
                                <div>
                                    <span class="text-xs text-slate-400 uppercase tracking-wide font-medium">Description</span>
                                    <p class="text-sm text-slate-700 dark:text-slate-300 mt-1">{{ $campaign->description }}</p>
                                </div>
                            @endif
                            @if ($campaign->goal)
                                <div>
                                    <span class="text-xs text-slate-400 uppercase tracking-wide font-medium">Goal</span>
                                    <p class="text-sm text-slate-700 dark:text-slate-300 mt-1">{{ $campaign->goal }}</p>
                                </div>
                            @endif
                            @if ($campaign->offers)
                                <div>
                                    <span class="text-xs text-slate-400 uppercase tracking-wide font-medium">Offers</span>
                                    <p class="text-sm text-slate-700 dark:text-slate-300 mt-1">{{ $campaign->offers }}</p>
                                </div>
                            @endif
                            @if ($campaign->target_audience)
                                <div>
                                    <span class="text-xs text-slate-400 uppercase tracking-wide font-medium">Target Audience</span>
                                    <p class="text-sm text-slate-700 dark:text-slate-300 mt-1">{{ $campaign->target_audience }}</p>
                                </div>
                            @endif
                            @if ($campaign->marketing_channels)
                                <div>
                                    <span class="text-xs text-slate-400 uppercase tracking-wide font-medium">Marketing Channels</span>
                                    <p class="text-sm text-slate-700 dark:text-slate-300 mt-1">{{ $campaign->marketing_channels }}</p>
                                </div>
                            @endif
                            @if ($campaign->budget)
                                <div>
                                    <span class="text-xs text-slate-400 uppercase tracking-wide font-medium">Budget</span>
                                    <p class="text-sm text-slate-700 dark:text-slate-300 mt-1">{{ number_format($campaign->budget, 2) }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Conversion Breakdown --}}
                    <div>
                        <h6 class="font-medium text-slate-700 dark:text-slate-300 mb-4 text-base">Conversion Breakdown</h6>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 rounded-md bg-slate-50 dark:bg-slate-700">
                                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                    <iconify-icon icon="heroicons:user" class="text-info-500 text-lg"></iconify-icon>
                                    <span class="text-sm text-slate-600 dark:text-slate-300">Customers with Policies</span>
                                </div>
                                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $customersWithPolicies }}</span>
                                    <span class="text-xs text-slate-400">/ {{ $totalCustomers }}</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-md bg-slate-50 dark:bg-slate-700">
                                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                    <iconify-icon icon="heroicons:building-office-2" class="text-warning-500 text-lg"></iconify-icon>
                                    <span class="text-sm text-slate-600 dark:text-slate-300">Corporates with Policies</span>
                                </div>
                                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $corporatesWithPolicies }}</span>
                                    <span class="text-xs text-slate-400">/ {{ $totalCorporates }}</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-md bg-slate-50 dark:bg-slate-700">
                                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                    <iconify-icon icon="heroicons:user" class="text-info-500 text-lg"></iconify-icon>
                                    <span class="text-sm text-slate-600 dark:text-slate-300">Customers with Offers</span>
                                </div>
                                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $customersWithOffers }}</span>
                                    <span class="text-xs text-slate-400">/ {{ $totalCustomers }}</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-md bg-slate-50 dark:bg-slate-700">
                                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                    <iconify-icon icon="heroicons:building-office-2" class="text-warning-500 text-lg"></iconify-icon>
                                    <span class="text-sm text-slate-600 dark:text-slate-300">Corporates with Offers</span>
                                </div>
                                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $corporatesWithOffers }}</span>
                                    <span class="text-xs text-slate-400">/ {{ $totalCorporates }}</span>
                                </div>
                            </div>
                            @if ($totalClients > 0)
                                <div class="flex items-center justify-between p-3 rounded-md bg-success-500 bg-opacity-10 dark:bg-opacity-20">
                                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                        <iconify-icon icon="heroicons:check-badge" class="text-success-500 text-lg"></iconify-icon>
                                        <span class="text-sm text-slate-600 dark:text-slate-300 font-medium">Conversion Rate</span>
                                    </div>
                                    <span class="font-semibold text-success-600 dark:text-success-400">
                                        {{ number_format(($totalWithPolicies / $totalClients) * 100, 1) }}%
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ROI Breakdown --}}
                <div class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-700">
                    <h6 class="font-medium text-slate-700 dark:text-slate-300 mb-4 text-base">Financial Breakdown</h6>
                    <div class="grid lg:grid-cols-2 grid-cols-1 gap-4">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 rounded-md bg-slate-50 dark:bg-slate-700">
                                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                    <iconify-icon icon="heroicons:banknotes" class="text-success-500 text-lg"></iconify-icon>
                                    <span class="text-sm text-slate-600 dark:text-slate-300">Gross Income</span>
                                </div>
                                <span class="font-semibold text-slate-900 dark:text-white">{{ number_format($grossIncome, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-md bg-slate-50 dark:bg-slate-700">
                                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                    <iconify-icon icon="heroicons:minus-circle" class="text-danger-500 text-lg"></iconify-icon>
                                    <span class="text-sm text-slate-600 dark:text-slate-300">Sales Commissions</span>
                                </div>
                                <span class="font-semibold text-danger-500">- {{ number_format($grossIncome - $netIncome, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-md bg-{{ $netIncome >= 0 ? 'success' : 'danger' }}-500 bg-opacity-10 dark:bg-opacity-20">
                                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                    <iconify-icon icon="heroicons:calculator" class="text-{{ $netIncome >= 0 ? 'success' : 'danger' }}-500 text-lg"></iconify-icon>
                                    <span class="text-sm font-medium text-slate-600 dark:text-slate-300">Net Income</span>
                                </div>
                                <span class="font-semibold text-{{ $netIncome >= 0 ? 'success-600 dark:text-success-400' : 'danger-600 dark:text-danger-400' }}">
                                    {{ number_format($netIncome, 2) }}
                                </span>
                            </div>
                        </div>

                        @if ($campaign->budget)
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 rounded-md bg-slate-50 dark:bg-slate-700">
                                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                        <iconify-icon icon="heroicons:wallet" class="text-warning-500 text-lg"></iconify-icon>
                                        <span class="text-sm text-slate-600 dark:text-slate-300">Campaign Budget</span>
                                    </div>
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ number_format($campaign->budget, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between p-3 rounded-md bg-slate-50 dark:bg-slate-700">
                                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                        <iconify-icon icon="heroicons:currency-dollar" class="text-info-500 text-lg"></iconify-icon>
                                        <span class="text-sm text-slate-600 dark:text-slate-300">Net Income − Budget</span>
                                    </div>
                                    @php $profit = $netIncome - $campaign->budget; @endphp
                                    <span class="font-semibold {{ $profit >= 0 ? 'text-success-600 dark:text-success-400' : 'text-danger-600 dark:text-danger-400' }}">
                                        {{ $profit >= 0 ? '+' : '' }}{{ number_format($profit, 2) }}
                                    </span>
                                </div>
                                @if ($roi !== null)
                                    <div class="flex items-center justify-between p-3 rounded-md bg-{{ $roi >= 0 ? 'success' : 'danger' }}-500 bg-opacity-10 dark:bg-opacity-20">
                                        <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                            <iconify-icon icon="heroicons:arrow-trending-up" class="text-{{ $roi >= 0 ? 'success' : 'danger' }}-500 text-lg"></iconify-icon>
                                            <span class="text-sm font-medium text-slate-600 dark:text-slate-300">ROI</span>
                                        </div>
                                        <span class="font-bold text-lg {{ $roi >= 0 ? 'text-success-600 dark:text-success-400' : 'text-danger-600 dark:text-danger-400' }}">
                                            {{ $roi >= 0 ? '+' : '' }}{{ number_format($roi, 1) }}%
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="flex items-center justify-center p-6 rounded-md bg-slate-50 dark:bg-slate-700 text-center">
                                <div>
                                    <iconify-icon icon="heroicons:wallet" class="text-slate-300 text-4xl mb-2"></iconify-icon>
                                    <p class="text-sm text-slate-400">No budget set — ROI cannot be calculated.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Customers Tab --}}
            @if ($section === 'customers')
                <div class="mb-5">
                    <input type="text" wire:model.debounce.400ms="customerSearch"
                        class="form-control max-w-xs"
                        placeholder="Search customers...">
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                        <thead class="bg-slate-200 dark:bg-slate-700">
                            <tr>
                                <th class="table-th">#</th>
                                <th class="table-th">Name</th>
                                <th class="table-th">Phone</th>
                                <th class="table-th">Offers</th>
                                <th class="table-th">Sold Policies</th>
                                <th class="table-th">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                            @forelse ($customers as $customer)
                                <tr>
                                    <td class="table-td">{{ $customer->id }}</td>
                                    <td class="table-td">
                                        <a href="{{ route('customers.show', $customer->id) }}"
                                            target="_blank"
                                            class="text-primary-500 hover:underline font-medium">
                                            {{ $customer->first_name }} {{ $customer->last_name }}
                                        </a>
                                    </td>
                                    <td class="table-td">
                                        <span class="text-sm text-slate-600 dark:text-slate-300">
                                            {{ $customer->phones->first()?->number ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="table-td">
                                        <span class="badge {{ $customer->offers_count > 0 ? 'bg-info-500 text-white' : 'bg-slate-200 text-slate-500' }}">
                                            {{ $customer->offers_count }}
                                        </span>
                                    </td>
                                    <td class="table-td">
                                        <span class="badge {{ $customer->soldpolicies_count > 0 ? 'bg-success-500 text-white' : 'bg-slate-200 text-slate-500' }}">
                                            {{ $customer->soldpolicies_count }}
                                        </span>
                                    </td>
                                    <td class="table-td">
                                        @if ($customer->soldpolicies_count > 0)
                                            <span class="badge bg-success-500 text-white capitalize">Converted</span>
                                        @elseif ($customer->offers_count > 0)
                                            <span class="badge bg-warning-500 text-white capitalize">Has Offers</span>
                                        @else
                                            <span class="badge bg-slate-200 text-slate-500 capitalize">Lead</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="table-td text-center text-slate-400 py-8">
                                        No customers found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-5">
                    {{ $customers->links() }}
                </div>
            @endif

            {{-- Corporates Tab --}}
            @if ($section === 'corporates')
                <div class="mb-5">
                    <input type="text" wire:model.debounce.400ms="corporateSearch"
                        class="form-control max-w-xs"
                        placeholder="Search corporates...">
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                        <thead class="bg-slate-200 dark:bg-slate-700">
                            <tr>
                                <th class="table-th">#</th>
                                <th class="table-th">Name</th>
                                <th class="table-th">Phone</th>
                                <th class="table-th">Offers</th>
                                <th class="table-th">Sold Policies</th>
                                <th class="table-th">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                            @forelse ($corporates as $corporate)
                                <tr>
                                    <td class="table-td">{{ $corporate->id }}</td>
                                    <td class="table-td">
                                        <a href="{{ route('corporates.show', $corporate->id) }}"
                                            target="_blank"
                                            class="text-primary-500 hover:underline font-medium">
                                            {{ $corporate->name }}
                                        </a>
                                    </td>
                                    <td class="table-td">
                                        <span class="text-sm text-slate-600 dark:text-slate-300">
                                            {{ $corporate->phones->first()?->number ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="table-td">
                                        <span class="badge {{ $corporate->offers_count > 0 ? 'bg-info-500 text-white' : 'bg-slate-200 text-slate-500' }}">
                                            {{ $corporate->offers_count }}
                                        </span>
                                    </td>
                                    <td class="table-td">
                                        <span class="badge {{ $corporate->soldpolicies_count > 0 ? 'bg-success-500 text-white' : 'bg-slate-200 text-slate-500' }}">
                                            {{ $corporate->soldpolicies_count }}
                                        </span>
                                    </td>
                                    <td class="table-td">
                                        @if ($corporate->soldpolicies_count > 0)
                                            <span class="badge bg-success-500 text-white capitalize">Converted</span>
                                        @elseif ($corporate->offers_count > 0)
                                            <span class="badge bg-warning-500 text-white capitalize">Has Offers</span>
                                        @else
                                            <span class="badge bg-slate-200 text-slate-500 capitalize">Lead</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="table-td text-center text-slate-400 py-8">
                                        No corporates found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-5">
                    {{ $corporates->links() }}
                </div>
            @endif

            {{-- Sold Policies Tab --}}
            @if ($section === 'sold_policies')
                <div class="mb-5">
                    <input type="text" wire:model.debounce.400ms="soldPoliciesSearch"
                        class="form-control max-w-xs"
                        placeholder="Search by policy number...">
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                        <thead class="bg-slate-200 dark:bg-slate-700">
                            <tr>
                                <th class="table-th">Policy #</th>
                                <th class="table-th">Client</th>
                                <th class="table-th">Type</th>
                                <th class="table-th">Product</th>
                                <th class="table-th">Company</th>
                                <th class="table-th">Gross Premium</th>
                                <th class="table-th">Start</th>
                                <th class="table-th">Expiry</th>
                                <th class="table-th">Status</th>
                                <th class="table-th"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                            @forelse ($soldPolicies as $sp)
                                <tr>
                                    <td class="table-td font-medium">
                                        {{ $sp->policy_number ?? '—' }}
                                    </td>
                                    <td class="table-td">
                                        @if ($sp->client_type === 'customer')
                                            <a href="{{ route('customers.show', $sp->client_id) }}"
                                                target="_blank"
                                                class="text-primary-500 hover:underline font-medium">
                                                {{ $sp->client?->first_name }} {{ $sp->client?->last_name }}
                                            </a>
                                        @else
                                            <a href="{{ route('corporates.show', $sp->client_id) }}"
                                                target="_blank"
                                                class="text-primary-500 hover:underline font-medium">
                                                {{ $sp->client?->name }}
                                            </a>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        @if ($sp->client_type === 'customer')
                                            <span class="badge bg-info-500 bg-opacity-20 text-info-500">
                                                <iconify-icon icon="heroicons:user" class="mr-1"></iconify-icon>Customer
                                            </span>
                                        @else
                                            <span class="badge bg-warning-500 bg-opacity-20 text-warning-500">
                                                <iconify-icon icon="heroicons:building-office-2" class="mr-1"></iconify-icon>Corporate
                                            </span>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        {{ $sp->policy?->name ?? '—' }}
                                    </td>
                                    <td class="table-td">
                                        {{ $sp->policy?->company?->name ?? '—' }}
                                    </td>
                                    <td class="table-td">
                                        {{ number_format($sp->gross_premium, 2) }}
                                    </td>
                                    <td class="table-td">
                                        <span class="date-text">{{ $sp->start ? \Carbon\Carbon::parse($sp->start)->format('d M Y') : '—' }}</span>
                                    </td>
                                    <td class="table-td">
                                        <span class="date-text">{{ $sp->expiry ? \Carbon\Carbon::parse($sp->expiry)->format('d M Y') : '—' }}</span>
                                    </td>
                                    <td class="table-td">
                                        @if ($sp->cancellation_time)
                                            <span class="badge bg-danger-500 h-auto">Cancelled</span>
                                        @elseif ($sp->is_valid)
                                            <span class="badge bg-success-500 h-auto">Valid</span>
                                        @else
                                            <span class="badge bg-warning-500 h-auto">Pending</span>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        <a href="{{ route('sold.policy.show', $sp->id) }}"
                                            target="_blank"
                                            class="btn btn-sm inline-flex justify-center btn-light light">
                                            <iconify-icon icon="heroicons-outline:eye" class="mr-1"></iconify-icon>
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="table-td text-center text-slate-400 py-8">
                                        No sold policies found for this campaign.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-5">
                    {{ $soldPolicies->links() }}
                </div>
            @endif

            {{-- Offers Tab --}}
            @if ($section === 'offers')
                <div class="mb-5">
                    <input type="text" wire:model.debounce.400ms="offersSearch"
                        class="form-control max-w-xs"
                        placeholder="Search by item title...">
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                        <thead class="bg-slate-200 dark:bg-slate-700">
                            <tr>
                                <th class="table-th">#</th>
                                <th class="table-th">Client</th>
                                <th class="table-th">Type</th>
                                <th class="table-th">Item</th>
                                <th class="table-th">Value</th>
                                <th class="table-th">Offer Type</th>
                                <th class="table-th">Status</th>
                                <th class="table-th">Due</th>
                                <th class="table-th"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                            @forelse ($offers as $offer)
                                <tr>
                                    <td class="table-td">{{ $offer->id }}</td>
                                    <td class="table-td">
                                        @if ($offer->client_type === 'customer')
                                            <a href="{{ route('customers.show', $offer->client_id) }}"
                                                target="_blank"
                                                class="text-primary-500 hover:underline font-medium">
                                                {{ $offer->client?->first_name }} {{ $offer->client?->last_name }}
                                            </a>
                                        @else
                                            <a href="{{ route('corporates.show', $offer->client_id) }}"
                                                target="_blank"
                                                class="text-primary-500 hover:underline font-medium">
                                                {{ $offer->client?->name }}
                                            </a>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        @if ($offer->client_type === 'customer')
                                            <span class="badge bg-info-500 bg-opacity-20 text-info-500">
                                                <iconify-icon icon="heroicons:user" class="mr-1"></iconify-icon>Customer
                                            </span>
                                        @else
                                            <span class="badge bg-warning-500 bg-opacity-20 text-warning-500">
                                                <iconify-icon icon="heroicons:building-office-2" class="mr-1"></iconify-icon>Corporate
                                            </span>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        <span class="text-sm text-slate-700 dark:text-slate-300">{{ $offer->item_title ?? '—' }}</span>
                                    </td>
                                    <td class="table-td">
                                        {{ $offer->item_value ? number_format($offer->item_value, 2) : '—' }}
                                    </td>
                                    <td class="table-td">
                                        <span class="badge bg-secondary-500 bg-opacity-20 text-secondary-500 capitalize">
                                            {{ ucwords(str_replace('_', ' ', $offer->type ?? '')) }}
                                        </span>
                                    </td>
                                    <td class="table-td">
                                        @if ($offer->status === 'new')
                                            <span class="badge bg-info-500 h-auto">
                                                <iconify-icon icon="pajamas:status"></iconify-icon>&nbsp;New
                                            </span>
                                        @elseif (str_contains($offer->status ?? '', 'pending'))
                                            <span class="badge bg-warning-500 h-auto">
                                                <iconify-icon icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
                                            </span>
                                        @elseif (str_contains($offer->status ?? '', 'declined') || str_contains($offer->status ?? '', 'cancelled'))
                                            <span class="badge bg-danger-500 h-auto">
                                                <iconify-icon icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
                                            </span>
                                        @elseif ($offer->status === 'approved')
                                            <span class="badge bg-success-500 h-auto">
                                                <iconify-icon icon="pajamas:status"></iconify-icon>&nbsp;Approved
                                            </span>
                                        @else
                                            <span class="badge bg-slate-200 text-slate-500 h-auto">
                                                {{ ucwords(str_replace('_', ' ', $offer->status ?? '—')) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        <span class="date-text">{{ $offer->due ? \Carbon\Carbon::parse($offer->due)->format('d M Y') : '—' }}</span>
                                    </td>
                                    <td class="table-td">
                                        <a href="{{ route('offers.show', $offer->id) }}"
                                            target="_blank"
                                            class="btn btn-sm inline-flex justify-center btn-light light">
                                            <iconify-icon icon="heroicons-outline:eye" class="mr-1"></iconify-icon>
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="table-td text-center text-slate-400 py-8">
                                        No offers found for this campaign.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-5">
                    {{ $offers->links() }}
                </div>
            @endif

        </div>
    </div>
</div>
