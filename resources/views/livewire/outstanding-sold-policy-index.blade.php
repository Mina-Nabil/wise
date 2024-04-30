<div>
    <div>
        <div class="flex justify-between flex-wrap items-center">
            <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
                <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                    Outstanding Sold Policies
                </h4>
            </div>
        </div>
        <div class="card-body px-6 pb-6">
            <div class=" -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <div class="card">
                            <header class="card-header cust-card-header noborder">
                                <iconify-icon wire:loading class="loading-icon text-lg" icon="line-md:loading-twotone-loop"></iconify-icon>
                                <input type="text" class="form-control !pl-9 mr-1 basis-1/4" placeholder="Search" wire:model="search">
                            </header>
                            <div class="tab-content mt-6" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-list" role="tabpanel" aria-labelledby="pills-list-tab">
                                    <div class="tab-content">
                                        <div class="card">
                                            <div class="card-body px-6 rounded overflow-hidden pb-3">
                                                <div class="overflow-x-auto -mx-6">
                                                    <div class="inline-block min-w-full align-middle">
                                                        <div class="overflow-hidden ">
                                                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 ">
                                                                <thead class="bg-slate-200 dark:bg-slate-700">
                                                                    <tr>
                                                                        <th scope="col" class="table-th ">
                                                                            POLICY
                                                                        </th>
                                                                        <th scope="col" class="table-th ">
                                                                            START DATE
                                                                        </th>
                                                                        <th scope="col" class="table-th ">
                                                                            END DATE
                                                                        </th>
                                                                        <th scope="col" class="table-th ">
                                                                            POLICY NUMBER
                                                                        </th>
                                                                        <th scope="col" class="table-th ">
                                                                            CLIENT NAME
                                                                        </th>
                                                                        <th scope="col" class="table-th ">
                                                                            STATUS
                                                                        </th>
                                                                        <th scope="col" class="table-th ">
                                                                            ACTION
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                                                    @foreach ($soldPolicies as $policy)
                                                                        <tr class="even:bg-slate-50 dark:even:bg-slate-700">
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
                                                                                <span class="block date-text">{{ \Carbon\Carbon::parse($policy->start)->format('d-m-Y') }}</span>
                                                                            </td>
                                                                            <td class="table-td">
                                                                                <span class="block date-text">{{ \Carbon\Carbon::parse($policy->expiry)->format('d-m-Y') }}</span>
                                                                            </td>
                                                                            <td class="table-td">
                                                                                <span class="block date-text">{{ $policy->policy_number }}</span>
                                                                            </td>
                                                                            <td class="table-td">
                                                                                <div class="flex space-x-3 items-center text-left rtl:space-x-reverse">
                                                                                    <div class="flex-none">
                                                                                        <div class="h-10 w-10 rounded-full text-sm bg-[#E0EAFF] dark:bg-slate-700 flex flex-col items-center justify-center font-medium -tracking-[1px]">
                                                                                            @if ($policy->client_type === 'customer')
                                                                                                <iconify-icon icon="raphael:customer"></iconify-icon>
                                                                                            @elseif($policy->client_type === 'corporate')
                                                                                                <iconify-icon icon="mdi:company"></iconify-icon>
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="flex-1 font-medium text-sm leading-4 whitespace-nowrap">
                                                                                        <a class="hover:underline cursor-pointer" href="{{ route($policy->client_type . 's.show', $policy->client_id) }}">
                                                                                            @if ($policy->client_type === 'customer')
                                                                                                {{ $policy->client->first_name . ' ' . $policy->client->middle_name . ' ' . $policy->client->last_name }}
                                                                                            @elseif($policy->client_type === 'corporate')
                                                                                                {{ $policy->client->name }}
                                                                                            @endif
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td class="table-td">
                                                                                @if ($policy->is_valid)
                                                                                    <span class="badge bg-success-500 text-slate-800 text-success-500 bg-opacity-30 capitalize rounded-3xl">Validated</span>
                                                                                @endif
                                                                            </td>
                                                                            <td class="table-td">
                                                                                <div class="dropstart relative">
                                                                                    <button class="inline-flex justify-center items-center" type="button" id="tableDropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                        <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                                                    </button>
                                                                                    <ul
                                                                                        class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                                                                        <li>
                                                                                            <a href="{{ route('sold.policy.show', $policy->id) }}"
                                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                                <iconify-icon icon="heroicons-outline:eye"></iconify-icon>
                                                                                                <span>View</span></a>
                                                                                        </li>
                                                                                        {{-- <li>
                                                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#editModal"
                                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300 last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize rtl:space-x-reverse">
                                                                                                <iconify-icon icon="clarity:note-edit-line"></iconify-icon>
                                                                                                <span>Edit</span></a>
                                                                                        </li>
                                                                                        <li>
                                                                                            <a href="#"
                                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300 last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize rtl:space-x-reverse">
                                                                                                <iconify-icon icon="fluent:delete-28-regular"></iconify-icon>
                                                                                                <span>Delete</span></a>
                                                                                        </li> --}}
                                                                                    </ul>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    @if ($soldPolicies->isEmpty())
                                                        {{-- START: empty filter result --}}
                                                        <div class="card m-5 p-5">
                                                            <div class="card-body rounded-md bg-white dark:bg-slate-800">
                                                                <div class="items-center text-center p-5">
                                                                    <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon></h2>
                                                                    <h2 class="card-title text-slate-900 dark:text-white mb-3">No Sold Policies with the
                                                                        applied
                                                                        filters</h2>
                                                                    <p class="card-text">Try changing the filters or search terms for this view.
                                                                    </p>
                                                                    <a href="{{ url('/sold-policies') }}" class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">View
                                                                        all Sold Policies</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- END: empty filter result --}}
                                                    @endif
                                                </div>
                                                {{ $soldPolicies->links('vendor.livewire.bootstrap') }}
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
    </div>
</div>
