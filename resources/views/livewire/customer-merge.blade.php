<div>
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 dark:text-white">
            <b>Merge Customers</b>
        </h4>
        <a href="{{ route('reports.customer-duplicates') }}"
            class="btn inline-flex justify-center btn-outline-dark btn-sm items-center">
            <iconify-icon icon="mdi:arrow-left" class="mr-1"></iconify-icon> Back to report
        </a>
    </div>

    <div
        class="bg-warning-500 bg-opacity-20 text-warning-500 border border-warning-500 rounded-md p-3 mb-6 flex items-start">
        <iconify-icon icon="mdi:alert" class="text-xl mr-2 mt-0.5"></iconify-icon>
        <div class="text-sm space-y-1">
            <p class="font-semibold">How merging works:</p>
            <ul class="list-disc ltr:ml-5 rtl:mr-5">
                <li>Tick <b>Include in merge</b> on each profile you want to combine (up to 3).</li>
                <li>Pick <b>Keep this profile</b> on the single <b>master</b> record to retain — exactly one
                    must be kept.</li>
                <li>All data from the other included profiles is moved into the master, then those profiles are
                    <b>permanently deleted</b>.</li>
            </ul>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        @foreach ($candidates as $candidate)
            @php $cid = (string) $candidate->id; @endphp
            <div wire:key="merge-cand-{{ $candidate->id }}"
                class="card border-2 {{ (string) $survivorId === $cid ? 'border-primary-500' : 'border-slate-200 dark:border-slate-700' }}">
                <div class="card-body p-5">
                    <div class="flex items-center justify-between mb-3">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="selectedIds" value="{{ $cid }}"
                                class="form-checkbox h-4 w-4">
                            <span class="ml-2 text-sm">Include in merge</span>
                        </label>
                        @if ((string) $survivorId === $cid)
                            <span class="badge bg-primary-500 text-white">Keep</span>
                        @endif
                    </div>

                    <h5 class="text-base font-semibold text-slate-700 dark:text-slate-200">
                        {{ $candidate->full_name }}
                    </h5>
                    <div class="text-xs text-slate-400 mb-3">#{{ $candidate->id }}</div>

                    <a href="{{ route('customers.show', $candidate->id) }}" target="_blank"
                        class="btn btn-outline-dark btn-sm inline-flex items-center mb-4">
                        <iconify-icon icon="mdi:open-in-new" class="mr-1"></iconify-icon> Open profile
                    </a>

                    <ul class="text-sm text-slate-500 dark:text-slate-300 space-y-1 mb-4">
                        <li><b>ID:</b> {{ $candidate->id_number }}
                            ({{ str_replace('_', ' ', $candidate->id_type) }})</li>
                        <li><b>Owner:</b> {{ $candidate->owner?->username ?? '-' }}</li>
                        <li><b>Email:</b> {{ $candidate->email ?? '-' }}</li>
                        <li><b>Offers:</b> {{ $candidate->offers_count }} • <b>Policies:</b>
                            {{ $candidate->soldpolicies_count }}</li>
                        <li><b>Phones:</b> {{ $candidate->phones_count }} • <b>Addresses:</b>
                            {{ $candidate->addresses_count }} • <b>Cars:</b> {{ $candidate->cars_count }}</li>
                    </ul>

                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="survivor" value="{{ $cid }}"
                            wire:click="setSurvivor('{{ $cid }}')" @checked((string) $survivorId === $cid)
                            class="form-radio h-4 w-4">
                        <span class="ml-2 text-sm font-medium">Keep this profile (master)</span>
                    </label>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card mb-6">
        <div class="card-body p-5">
            <label class="form-label" for="ownerId">Who can access the merged profile (owner)</label>
            <select wire:model="ownerId" id="ownerId" class="form-control">
                <option value="">-- Select owner --</option>
                @foreach ($ownerOptions as $opt)
                    <option value="{{ $opt['id'] }}">{{ $opt['name'] }}</option>
                @endforeach
            </select>
            <p class="text-xs text-slate-400 mt-2">
                Only the owner and their managers can access the merged profile.
            </p>
        </div>
    </div>

    <div
        class="bg-danger-500 bg-opacity-20 text-danger-500 border border-danger-500 rounded-md p-3 mb-6 flex items-center {{ $errorMessage ? '' : 'hidden' }}">
        <iconify-icon icon="mdi:alert-circle" class="text-xl mr-2"></iconify-icon>
        <span>{{ $errorMessage }}</span>
    </div>

    <div class="card mb-6 border border-danger-500 {{ $confirmingMerge ? '' : 'hidden' }}">
        <div class="card-body p-5">
            <h5 class="text-base font-semibold text-danger-500 mb-2">Confirm merge</h5>
            <p class="text-sm text-slate-600 dark:text-slate-300 mb-4">
                This will move all data into the kept profile and <b>permanently delete</b> the other selected
                profile(s). This action cannot be undone.
            </p>
            <div class="flex justify-end space-x-3 rtl:space-x-reverse">
                <button wire:click="cancelMerge" class="btn inline-flex justify-center btn-outline-dark">
                    Cancel
                </button>
                <button wire:click="mergeCustomers" wire:loading.attr="disabled"
                    class="btn inline-flex justify-center bg-danger-500 text-white">
                    <iconify-icon icon="mdi:merge" class="mr-1"></iconify-icon> Yes, merge
                </button>
            </div>
        </div>
    </div>

    <div class="flex justify-end {{ $confirmingMerge ? 'hidden' : '' }}">
        <button wire:click="confirmMerge" class="btn inline-flex justify-center btn-dark">
            <iconify-icon icon="mdi:merge" class="mr-1"></iconify-icon> Merge profiles
        </button>
    </div>
</div>
