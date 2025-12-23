<div>
    <div class="space-y-5">
        <div class="card">
            <header class="card-header noborder">
                <h4 class="card-title">Account Settings for Financial Reports</h4>
                <div>
                    @if(\App\Models\Accounting\AccountSetting::isFullyConfigured())
                        <span class="badge bg-success-500 text-white capitalize">
                            <iconify-icon class="text-xl mr-1" icon="heroicons:check-circle"></iconify-icon>
                            Fully Configured
                        </span>
                    @else
                        <span class="badge bg-warning-500 text-white capitalize">
                            <iconify-icon class="text-xl mr-1" icon="heroicons:exclamation-triangle"></iconify-icon>
                            {{ count(\App\Models\Accounting\AccountSetting::getMissingKeys()) }} Missing
                        </span>
                    @endif
                </div>
            </header>
            <div class="card-body px-6 pb-6">
                <div class="overflow-x-auto -mx-6 dashcode-data-table">
                    <span class="col-span-8 hidden"></span>
                    <span class="col-span-4 hidden"></span>
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                <thead class="bg-slate-200 dark:bg-slate-700">
                                    <tr>
                                        <th scope="col" class="table-th" style="width: 40%;">
                                            <div class="flex items-center">
                                                Setting Key
                                            </div>
                                        </th>
                                        <th scope="col" class="table-th" style="width: 40%;">
                                            <div class="flex items-center">
                                                Account
                                            </div>
                                        </th>
                                        <th scope="col" class="table-th" style="width: 20%;">
                                            <div class="flex items-center">
                                                Actions
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                    @forelse($settings as $setting)
                                        <tr>
                                            <td class="table-td">
                                                <div>
                                                    <span class="text-sm text-slate-600 dark:text-slate-400 font-medium">
                                                        {{ $setting['label'] }}
                                                    </span>
                                                    <br>
                                                    <span class="text-xs text-slate-400 dark:text-slate-500">
                                                        {{ $setting['key'] }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="table-td">
                                                @if(count($setting['accounts']) > 0)
                                                    <div class="space-y-2">
                                                        @foreach($setting['accounts'] as $account)
                                                            <div class="flex items-center gap-2">
                                                                @if($account['calc_type'] === 'add')
                                                                    <span class="badge bg-success-500 text-white text-xs px-2 py-1">+</span>
                                                                @else
                                                                    <span class="badge bg-danger-500 text-white text-xs px-2 py-1">-</span>
                                                                @endif
                                                                <span class="badge bg-slate-900 text-white capitalize px-3 py-1">
                                                                    {{ $account['code'] }}
                                                                </span>
                                                                <span class="text-sm text-slate-600 dark:text-slate-300">
                                                                    {{ $account['name'] }}
                                                                </span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-sm text-slate-400 dark:text-slate-500 italic">
                                                        Not configured
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="table-td">
                                                <button wire:click="openEditModal('{{ $setting['key'] }}', '{{ $setting['label'] }}')"
                                                    class="action-btn" type="button">
                                                    <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="table-td text-center">
                                                <span class="text-slate-500 dark:text-slate-400">No settings configured</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    @if($isEditModalOpen)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Configure: {{ $currentKeyLabel }}
                            </h3>
                            <button wire:click="closeEditModal" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
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
                            <!-- Selected Accounts -->
                            @if(count($selectedAccounts) > 0)
                                <div class="from-group">
                                    <label class="form-label">Selected Accounts ({{ count($selectedAccounts) }})</label>
                                    <div class="space-y-3">
                                        @foreach($selectedAccounts as $index => $account)
                                            <div class="flex items-center gap-3 p-3 bg-success-50 dark:bg-success-500/10 border border-success-200 dark:border-success-700 rounded-md">
                                                <iconify-icon icon="heroicons:check-circle" class="text-success-500 text-lg flex-shrink-0"></iconify-icon>
                                                
                                                <!-- Account Info -->
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2">
                                                        <span class="badge bg-slate-900 text-white text-xs">{{ $account['code'] }}</span>
                                                        <span class="text-sm text-slate-600 dark:text-slate-300">{{ $account['name'] }}</span>
                                                    </div>
                                                </div>

                                                <!-- Calc Type Selector -->
                                                <div class="flex items-center gap-2">
                                                    <select wire:change="updateCalcType({{ $account['id'] }}, $event.target.value)"
                                                        class="form-control py-1 px-2 text-sm min-w-[120px]">
                                                        <option value="add" {{ $account['calc_type'] === 'add' ? 'selected' : '' }}>
                                                            ➕ Add
                                                        </option>
                                                        <option value="subtract" {{ $account['calc_type'] === 'subtract' ? 'selected' : '' }}>
                                                            ➖ Subtract
                                                        </option>
                                                    </select>
                                                </div>

                                                <!-- Remove Button -->
                                                <button wire:click="removeAccount({{ $account['id'] }})" type="button"
                                                    class="text-danger-500 hover:text-danger-600">
                                                    <iconify-icon icon="heroicons:x-mark" class="text-xl"></iconify-icon>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                    @if(count($selectedAccounts) > 1)
                                        <button wire:click="clearAllAccounts" type="button"
                                            class="mt-2 text-sm text-danger-500 hover:text-danger-600">
                                            Clear all
                                        </button>
                                    @endif
                                </div>
                            @endif

                            <!-- Search Account -->
                            <div class="from-group">
                                <label for="searchAccount" class="form-label">
                                    {{ count($selectedAccounts) > 0 ? 'Add Another Account' : 'Search Account' }}
                                </label>
                                <div class="relative">
                                    <input wire:model.debounce.300ms="searchAccount" type="text"
                                        class="form-control py-2" placeholder="Search by code or name...">
                                </div>
                                
                                @if(count($accounts) > 0)
                                    <div class="mt-2 border border-slate-200 dark:border-slate-600 rounded-md max-h-60 overflow-y-auto">
                                        @foreach($accounts as $account)
                                            <div wire:click="selectAccount({{ $account['id'] }})"
                                                class="p-3 hover:bg-slate-100 dark:hover:bg-slate-600 cursor-pointer border-b border-slate-100 dark:border-slate-700 last:border-b-0">
                                                <span class="badge bg-slate-900 text-white text-xs mr-2">{{ $account['acc_code'] }}</span>
                                                <span class="text-sm text-slate-600 dark:text-slate-300">{{ $account['acc_name'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="closeEditModal" type="button"
                                class="btn inline-flex justify-center text-slate-700 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600">
                                Cancel
                            </button>
                            <button wire:click="saveAccountSetting" type="button"
                                class="btn inline-flex justify-center text-white bg-black-500 hover:bg-black-600">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show bg-slate-900 bg-opacity-50 backdrop-blur-sm"></div>
    @endif
</div>
