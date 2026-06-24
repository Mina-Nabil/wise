<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Bulk Show Sold Policies
            </h4>
        </div>

        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
            <a href="{{ route('sold.policy.index') }}"
                class="btn inline-flex justify-center btn-outline-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:arrow-left-bold"></iconify-icon>
                Back to Sold Policies
            </a>
        </div>
    </div>

    {{-- Top bar: user select + show button --}}
    <div class="card mb-5">
        <div class="card-body p-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-7 from-group">
                    <label class="form-label">Show selected sold policies to</label>
                    <select class="form-control w-full mt-2 @error('showToUser') !border-danger-500 @enderror"
                        wire:model="showToUser">
                        <option value="">Select user...</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->first_name . ' ' . $user->last_name }} ({{ ucwords($user->type) }})
                            </option>
                        @endforeach
                    </select>
                    @error('showToUser')
                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                    @enderror
                    @error('selectedPolicies')
                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="md:col-span-5 flex justify-end">
                    <button wire:click="showSelected"
                        class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                        <iconify-icon wire:loading wire:target="showSelected" class="loading-icon text-lg ltr:mr-2 rtl:ml-2"
                            icon="line-md:loading-twotone-loop"></iconify-icon>
                        <iconify-icon wire:loading.remove wire:target="showSelected" class="text-xl ltr:mr-2 rtl:ml-2"
                            icon="ph:eye-bold"></iconify-icon>
                        Show to User
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Latest bulk show errors card --}}
    @if (!empty($lastErrors) && count($lastErrors['failures'] ?? []))
        <div class="card mb-5 border border-danger-500">
            <header class="card-header noborder">
                <h4 class="card-title text-danger-500">
                    <iconify-icon icon="heroicons:exclamation-triangle" class="mr-1"></iconify-icon>
                    Latest Bulk Show Errors
                    <span class="badge bg-danger-500 text-white ml-2">{{ count($lastErrors['failures']) }}</span>
                </h4>
                <button wire:click="clearLastErrors" class="btn btn-sm btn-outline-danger">Dismiss</button>
            </header>
            <div class="card-body px-6 pb-6">
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-3">
                    {{ $lastErrors['at'] }} —
                    showing to <b>{{ $lastErrors['user'] ?? 'N/A' }}</b>:
                    {{ $lastErrors['success'] }} of {{ $lastErrors['total'] }} succeeded,
                    {{ count($lastErrors['failures']) }} failed.
                </p>
                <div class="flex flex-col gap-2">
                    @foreach ($lastErrors['failures'] as $failure)
                        <div
                            class="flex items-center bg-danger-500 bg-opacity-10 rounded-md px-3 py-2 text-sm text-slate-700 dark:text-slate-200">
                            <b class="mr-2 whitespace-nowrap">Policy #{{ $failure['policy_id'] }}
                                ({{ $failure['policy_number'] }})</b>
                            <span class="text-danger-500">{{ $failure['reason'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Selected Sold Policies card --}}
    <div class="card mb-5">
        <header class="card-header noborder">
            <h4 class="card-title">
                Selected Sold Policies
                <span class="badge bg-primary-500 text-white ml-2">{{ count($selectedPolicies) }}</span>
            </h4>
            @if (count($selectedPolicies))
                <button wire:click="clearSelection" class="btn btn-sm btn-outline-danger">Clear all</button>
            @endif
        </header>
        <div class="card-body px-6 pb-6">
            @if ($selectedPoliciesData->isEmpty())
                <p class="text-sm text-slate-500 dark:text-slate-400">No sold policies selected yet. Use the checkboxes
                    below to select policies.</p>
            @else
                <div class="flex flex-wrap gap-2">
                    @foreach ($selectedPoliciesData as $sel)
                        <span
                            class="inline-flex items-center bg-slate-100 dark:bg-slate-700 rounded-full px-3 py-1 text-sm text-slate-700 dark:text-slate-200">
                            <b class="mr-1">#{{ $sel->id }}</b>
                            {{ $sel->policy_number }}
                            @if ($sel->client_type === 'corporate')
                                · {{ $sel->client?->name }}
                            @elseif($sel->client_type === 'customer')
                                · {{ $sel->client?->first_name . ' ' . $sel->client?->last_name }}
                            @endif
                            <iconify-icon wire:click="removeSelectedPolicy({{ $sel->id }})"
                                class="ml-2 cursor-pointer text-danger-500 text-lg" icon="heroicons:x-mark"></iconify-icon>
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex items-center space-x-7 flex-wrap h-[60px]">
        <div class="secondary-radio pb-2">
            <label class="flex items-center cursor-pointer">
                <input type="radio" class="hidden" value="all" wire:model="isPaidCB">
                <span
                    class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                <span class="text-secondary-500 text-sm leading-6 capitalize">All</span>
            </label>
        </div>
        <div class="secondary-radio pb-2">
            <label class="flex items-center cursor-pointer">
                <input type="radio" class="hidden" value="isPaid" wire:model="isPaidCB">
                <span
                    class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                <span class="text-secondary-500 text-sm leading-6 capitalize">Paid</span>
            </label>
        </div>
        <div class="secondary-radio pb-2">
            <label class="flex items-center cursor-pointer">
                <input type="radio" class="hidden" value="notPaid" wire:model="isPaidCB">
                <span
                    class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                <span class="text-secondary-500 text-sm leading-6 capitalize">Not Paid</span>
            </label>
        </div>
        <input class="form-control py-2 flatpickr flatpickr-input active w-auto ml-5 mb-5" style="width:300px"
            id="range-picker" data-mode="range" value="" type="text" readonly="readonly" wire:model="dateRange">
    </div>

    <div class="card">
        <header class="card-header cust-card-header noborder">
            <iconify-icon wire:loading wire:target='search' class="loading-icon text-lg"
                icon="line-md:loading-twotone-loop"></iconify-icon>
            <input type="text" class="form-control !pl-9 mr-1 basis-1/4" placeholder="Search"
                wire:model.debounce.800ms="search">
        </header>

        <div class="card-body px-6 rounded overflow-hidden pb-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 ">
                            <thead class="bg-slate-200 dark:bg-slate-700">
                                <tr>
                                    <th scope="col" class="table-th " style="width: 40px;">
                                        <span class="sr-only">Select</span>
                                    </th>
                                    <th scope="col" class="table-th ">
                                        POLICY
                                    </th>
                                    <th scope="col" class="table-th ">
                                        CREATOR
                                    </th>
                                    <th scope="col" class="table-th ">
                                        START
                                    </th>
                                    <th scope="col" class="table-th ">
                                        END
                                    </th>
                                    <th scope="col" class="table-th ">
                                        POLICY#
                                    </th>
                                    <th scope="col" class="table-th ">
                                        CLIENT
                                    </th>
                                    <th scope="col" class="table-th ">
                                        STATUS
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @foreach ($soldPolicies as $policy)
                                    <tr
                                        class="even:bg-slate-50 dark:even:bg-slate-700 {{ in_array($policy->id, $selectedPolicies) ? '!bg-primary-500 !bg-opacity-10' : '' }}">
                                        <td class="table-td">
                                            <input type="checkbox" class="cursor-pointer" value="{{ $policy->id }}"
                                                wire:model="selectedPolicies">
                                        </td>
                                        <td class="table-td">
                                            <div class="flex-1 text-start">
                                                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                    {{ $policy->policy->company->name }}
                                                </h4>
                                                <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                                    {{ $policy->policy->name }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="table-td">
                                            <span
                                                class="block date-text">{{ $policy->creator_id == 10 ? 'Uploaded' : $policy->creator->username }}</span>
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
                                            <a href="{{ route('sold.policy.show', $policy->id) }}" target="_blank"
                                                class="hover:bg-slate-900  hover:text-white dark:hover:bg-slate-600 dark:hover:bg-opacity-70  w-full px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                {{ $policy->policy_number }}
                                            </a>
                                        </td>
                                        <td class="table-td">
                                            <div class="flex space-x-3 items-center text-left rtl:space-x-reverse">
                                                <div class="flex-none">
                                                    <div
                                                        class="h-10 w-10 rounded-full text-sm bg-[#E0EAFF] dark:bg-slate-700 flex flex-col items-center justify-center font-medium -tracking-[1px]">
                                                        @if ($policy->client_type === 'customer')
                                                            <iconify-icon icon="raphael:customer"></iconify-icon>
                                                        @elseif($policy->client_type === 'corporate')
                                                            <iconify-icon icon="mdi:company"></iconify-icon>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex-1 font-medium text-sm leading-4 whitespace-nowrap">
                                                    <a class="hover:underline cursor-pointer"
                                                        href="{{ route($policy->client_type . 's.show', $policy->client_id) }}"
                                                        target="_blank">
                                                        @if ($policy->client_type === 'customer')
                                                            {{ $policy->client?->first_name . ' ' . $policy->client?->middle_name . ' ' . $policy->client?->last_name }}
                                                        @elseif($policy->client_type === 'corporate')
                                                            {{ $policy->client?->name }}
                                                        @endif
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="table-td">
                                            @if ($policy->is_paid)
                                                <span
                                                    class="badge bg-success-500 text-slate-800 text-success-500 bg-opacity-30 capitalize rounded-3xl">Paid</span>
                                            @endif
                                            @if ($policy->is_renewal)
                                                <span
                                                    class="badge bg-success-500 text-slate-800 text-success-500 bg-opacity-30 capitalize rounded-3xl">Renewal</span>
                                            @endif
                                            @if ($policy->is_expired)
                                                <span
                                                    class="badge bg-danger-500 text-slate-800 text-danger-500 bg-opacity-30 capitalize rounded-3xl">Expired</span>
                                            @endif
                                            @if ($policy->is_penalized)
                                                <span class="badge bg-danger-500 text-white capitalize">Penalty</span>
                                            @endif
                                            @if ($policy->cancellation_time)
                                                <span
                                                    class="badge bg-danger-500 text-slate-800 text-danger-500 bg-opacity-30 capitalize rounded-3xl">Cancelled
                                                    on:
                                                    {{ \Carbon\Carbon::parse($policy->cancellation_time)->format('D d/m/Y') }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($soldPolicies->isEmpty())
                    <div class="card m-5 p-5">
                        <div class="card-body rounded-md bg-white dark:bg-slate-800">
                            <div class="items-center text-center p-5">
                                <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon></h2>
                                <h2 class="card-title text-slate-900 dark:text-white mb-3">No Sold Policies with the
                                    applied filters</h2>
                                <p class="card-text">Try changing the filters or search terms for this view.</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{ $soldPolicies->links('vendor.livewire.bootstrap') }}
            </div>
        </div>
    </div>
</div>
