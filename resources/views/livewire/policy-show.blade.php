<div>
    <div class="flex justify-center">
        <div class="">
            <div class="flex justify-between flex-wrap items-center mb-3">
                <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
                    <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                        {{ $policy->name }}

                    </h4>

                    <!---->

                </div>
                @if ($changesMade)
                    <button type="submit" wire:click="bulkEdit"
                        class="btn inline-flex justify-center btn-success rounded-[25px] btn-sm">Save</button>
                @endif

            </div>
            @if (session()->has('success'))
                    <div
                        class="py-[18px] px-6 font-normal text-sm rounded-md bg-success-500 text-white animate-\[fade-out_350ms_ease-in-out\] alert mb-2">
                        <div class="flex items-center space-x-3 rtl:space-x-reverse">
                            <p class="flex-1 font-Inter">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
            @elseif (session()->has('failed'))
                    <div class="py-[18px] px-6 font-normal text-sm rounded-md bg-danger-500 text-white mb-2">
                        <div class="flex items-center space-x-3 rtl:space-x-reverse">
                            <p class="flex-1 font-Inter">
                                {{ session('failed') }}
                            </p>
                        </div>
                    </div>
            @endif
            <div class="card mb-5">
                <div class="card-body">
                    <div class="card-text h-full">
                        <div class="px-4 pt-4 pb-3">
                            <div class="input-area mb-3">
                                <label for="name" class="form-label">Policy Name</label>
                                <input type="text" class="form-control" wire:change="markAsChanged"
                                    wire:model="policy_name">
                            </div>
                            <div class="input-area mb-3">
                                <label for="name" class="form-label">Business</label>
                                <select name="business" class="form-control w-full mt-2"
                                    wire:change="markAsChanged" wire:model="policy_business">
                                    @foreach ($linesOfBusiness as $line)
                                        <option {{ $line === $policy_business ? 'selected' : '' }}
                                            value="{{ $line }}"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ $line }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-area mb-3">
                                <label for="name" class="form-label">Company</label>
                                <select name="company_id" wire:change="markAsChanged"
                                    wire:model="policy_company_id" class="select2 form-control w-full mt-2 py-2">
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}"
                                            {{ $company->id === $policy_company_id ? 'selected' : '' }}
                                            class=" inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-area mb-3">
                                <label for="name" class="form-label">Note</label>
                                <textarea class="form-control" wire:change="markAsChanged" wire:model="policy_note" placeholder="Leave a note"
                                    name="note"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-5">
                <div class="card-body">
                    <div class="card-text h-full">
                        <div class="px-4 pt-4 pb-3">
                            <div class="flex justify-between">
                                <label class="form-label">Conditions</label>

                            </div>

                            <div class="card-body px-6 pb-6">
                                <div class="overflow-x-auto ">
                                    <div class="inline-block min-w-full align-middle">
                                        <div class="overflow-hidden ">
                                            <table
                                                class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                                <thead class="">
                                                    <tr>

                                                        <th scope="col" class=" table-th ">
                                                            Scope
                                                        </th>

                                                        <th scope="col" class=" table-th ">
                                                            Operator
                                                        </th>

                                                        <th scope="col" class=" table-th ">
                                                            Value
                                                        </th>

                                                        <th scope="col" class=" table-th ">
                                                            Rate
                                                        </th>

                                                        <th scope="col" class=" table-th ">
                                                            Note
                                                        </th>

                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-slate-800 ">
                                                    <form wire:submit.prevent="bulkEdit">
                                                        @foreach ($policy->conditions as $index => $condition)
                                                            <tr>
                                                                <td class="p-1">
                                                                    <select name="scope" id="basicSelect"
                                                                        wire:model="selectedScopes.{{ $index }}"
                                                                        wire:change="markAsChanged"
                                                                        class="form-control w-full text-center">
                                                                        @foreach ($scopes as $scope)
                                                                            <option
                                                                                {{ $scope === $condition->scope ? 'selected' : '' }}
                                                                                value="{{ $scope }}"
                                                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                                                {{ $scope }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>

                                                                <td class="p-1">
                                                                    <select name="scope" id="basicSelect"
                                                                        wire:model="selectedOperators.{{ $index }}"
                                                                        wire:change="markAsChanged"
                                                                        class="form-control w-full text-center">
                                                                        @foreach ($operators as $operator)
                                                                            <option
                                                                                {{ $operator === $condition->operator ? 'selected' : '' }}
                                                                                value="{{ $operator }}"
                                                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                                                @if ($operator === 'gte')
                                                                                    >=
                                                                                @elseif ($operator === 'gt')
                                                                                    >
                                                                                @elseif ($operator === 'lte')
                                                                                    <=
                                                                                @elseif ($operator === 'lt')
                                                                                    <
                                                                                @elseif ($operator === 'e')
                                                                                    =
                                                                                @endif
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>

                                                                <td class="p-1">
                                                                    <input id="name" type="number"
                                                                        wire:model="newValues.{{ $index }}"
                                                                        wire:change="markAsChanged"
                                                                        class="form-control text-center"
                                                                        value="{{ $condition->value }}">

                                                                </td>

                                                                <td class="p-1">
                                                                    <input id="name" type="number"
                                                                        wire:model="newRates.{{ $index }}"
                                                                        wire:change="markAsChanged"
                                                                        class="form-control text-center"
                                                                        value="{{ $condition->rate }}">
                                                                </td>

                                                                <td class="table-td p-1">
                                                                    <textarea wire:model="newNotes.{{ $index }}" wire:change="markAsChanged"
                                                                        class="form-control simplebar-content text-center" placeholder="Leave a note" name="note"
                                                                        style="min-height: 50px">{{ $condition->note }}</textarea>
                                                                </td>

                                                                <td class="p-1">
                                                                    <button class="action-btn" type="button"
                                                                        wire:click="deleteCondition({{ $index }})">
                                                                        <iconify-icon
                                                                            icon="heroicons:trash"></iconify-icon>
                                                                    </button>
                                                                </td>

                                                            </tr>
                                                        @endforeach
                                                    </form>
                                                </tbody>
                                            </table>
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


<script>
    window.onload = function() {
        window.addEventListener("beforeunload", function(e) {
            if (@json($changesMade)) {
                return null;
            }
        });
    }
</script>
