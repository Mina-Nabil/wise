<div>
    <div class="flex justify-center">
        <div class="">
            <div class="flex justify-between flex-wrap items-center mb-3 sticky-top">
                <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
                    <h4 onclick="launch_toast()"
                        class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                        {{ $policy->name }}

                    </h4>

                    <!---->

                </div>
                <button type="submit" wire:click="bulkEdit"
                    class="btn inline-flex justify-center btn-success rounded-[25px] btn-sm">Save</button>

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
                                <input type="text" class="form-control" wire:model="policy_name">
                            </div>
                            <div class="input-area mb-3">
                                <label for="name" class="form-label">Business</label>
                                <select name="business" class="form-control w-full mt-2" wire:model="policy_business">
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
                                <label for="name" class="form-label">Note</label>
                                <textarea class="form-control" wire:model="policy_note" placeholder="Leave a note" name="note"></textarea>
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

                                                        <th scope="col" class=" table-th ">
                                                            Action
                                                        </th>

                                                    </tr>
                                                </thead>
                                                <tbody
                                                    class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                                    @foreach ($policy->conditions as $condtion)
                                                        @if ($editedRowId != $condtion->id)
                                                            <tr class="hover:bg-slate-200 dark:hover:bg-slate-700">

                                                                <td class="table-td">
                                                                    {{ $condtion->scope }}
                                                                </td>

                                                                <td class="table-td">
                                                                    {{ $condtion->operator }}
                                                                </td>

                                                                <td class="table-td">
                                                                    {{ $condtion->value }}
                                                                </td>

                                                                <td class="table-td">
                                                                    {{ $condtion->rate }}
                                                                </td>

                                                                <td class="table-td">
                                                                    {{ $condtion->note }}
                                                                </td>

                                                                <td class="p-1">
                                                                    <div class=" flex justify-center">
                                                                        <button class="toolTip onTop action-btn m-1 "
                                                                            data-tippy-content="Edit"
                                                                            wire:click="editRow({{ $condtion->id }})"
                                                                            type="button">
                                                                            <iconify-icon
                                                                                icon="iconamoon:edit-bold"></iconify-icon>
                                                                        </button>
                                                                        <button class="toolTip onTop action-btn m-1"
                                                                            data-tippy-content="Move Up" type="button">
                                                                            <iconify-icon
                                                                                icon="ion:arrow-up"></iconify-icon>
                                                                        </button>
                                                                        <button class="toolTip onTop action-btn m-1"
                                                                            data-tippy-content="Move Down"
                                                                            type="button">
                                                                            <iconify-icon
                                                                                icon="ion:arrow-down"></iconify-icon>
                                                                        </button>
                                                                        <button class="toolTip onTop action-btn m-1"
                                                                            data-tippy-content="Delete" type="button">
                                                                            <iconify-icon
                                                                                icon="heroicons:trash"></iconify-icon>
                                                                        </button>
                                                                    </div>

                                                                </td>
                                                            </tr>
                                                        @else
                                                            <tr>
                                                                <td class="p-1">
                                                                    <select name="scope" wire:model="editedScope"
                                                                        class="form-control w-full text-center">
                                                                        @foreach ($scopes as $scope)
                                                                            <option value="{{ $scope }}"
                                                                                {{ $condtion->scope === $scope ? 'selected' : '' }}
                                                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                                                {{ $scope }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>

                                                                <td class="p-1">
                                                                    <select name="scope" wire:model="editedOperator"
                                                                        class="form-control w-full text-center">
                                                                        @foreach ($operators as $operator)
                                                                            <option value="{{ $operator }}"
                                                                                {{ $condtion->operator === $operator ? 'selected' : '' }}
                                                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">

                                                                                @if ($operator === 'gte')
                                                                                    >=
                                                                                @elseif($operator === 'gt')
                                                                                    >
                                                                                @elseif($operator === 'lte')
                                                                                <= @elseif($operator === 'lt')
                                                                                        <
                                                                                    @elseif($operator === 'e')=@endif

                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>

                                                                <td class="p-1">
                                                                    <input type="number" wire:model="editedValue"
                                                                        class="form-control text-center"
                                                                        value="{{ $condtion->value }}">

                                                                </td>

                                                                <td class="p-1">
                                                                    <input type="number" wire:model="editedRate"
                                                                        class="form-control text-center"
                                                                        value="{{ $condtion->rate }}">
                                                                </td>

                                                                <td class="table-td p-1">
                                                                    <input type="text" wire:model="editedNote"
                                                                        class="form-control text-center"
                                                                        value="{{ $condtion->note }}">
                                                                </td>

                                                                <td class="p-1">
                                                                    <div class="flex justify-center">
                                                                        <button class="toolTip onTop action-btn m-1"
                                                                            wire:click="editCondition({{ $condtion->id }})"
                                                                            data-tippy-content="Save" type="button">
                                                                            <iconify-icon
                                                                                icon="material-symbols:save"></iconify-icon>
                                                                        </button>
                                                                        <button class="toolTip onTop action-btn m-1"
                                                                            data-tippy-content="Close"
                                                                            wire:click="closeEditRow" type="button">
                                                                            <iconify-icon
                                                                                icon="material-symbols:close"></iconify-icon>
                                                                        </button>
                                                                    </div>

                                                                </td>

                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @if (!$newConditionSection)
                                                        <tr class="mt-5">
                                                            <td>
                                                                <button wire:click="openNewConditionSection"
                                                                    class="btn inline-flex justify-center btn-light btn-sm">
                                                                    Add new condition
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @else
                                                        <tr>
                                                            <td class="p-1">
                                                                <select name="scope" wire:model="addedScope"
                                                                    class="!border-success-500 form-control w-full text-center">
                                                                    @foreach ($scopes as $scope)
                                                                        <option value="{{ $scope }}"
                                                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                                            {{ $scope }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>

                                                            <td class="p-1 text-center">
                                                                @if ($addedScope === 'car_model' || $addedScope === 'brand')
                                                                    =
                                                                    <input type="hidden" wire:model="addedOperator"
                                                                        value="=">
@else
<select name="scope"
                                                                        wire:model="addedOperator"
                                                                        class="!border-success-500 form-control w-full text-center">
                                                                    @foreach ($operators as $operator)
                                                                        <option value="{{ $operator }}"
                                                                            {{ $operator === 'e' ? 'selected' : '' }}
                                                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">

                                                                            @if ($operator === 'gte')
                                                                                >=
                                                                            @elseif($operator === 'gt')
                                                                                >
                                                                            @elseif($operator === 'lte')
                                                                            <= @elseif($operator === 'lt')
                                                                                    <
                                                                                @elseif($operator === 'e')=@endif

                                                                        </option>
                                                                    @endforeach
                                                                    </select>
                                                                @endif
                                                            </td>

                                                            <td class="p-1">
                                                                @if ($addedScope === 'car_model' || $addedScope === 'brand')
                                                                    <select name="select2basic" id=""
                                                                        class="select2 form-control w-full mt-2 py-2">
                                                                        <option value="option1"
                                                                            class=" inline-block font-Inter font-normal text-sm text-slate-600">
                                                                            Option 1</option>
                                                                        <option value="option2"
                                                                            class=" inline-block font-Inter font-normal text-sm text-slate-600">
                                                                            Option 2</option>
                                                                        <option value="option3"
                                                                            class=" inline-block font-Inter font-normal text-sm text-slate-600">
                                                                            Option 3</option>
                                                                    </select>
                                                                @else
                                                                    <input type="number" wire:model="addedValue"
                                                                        class="form-control text-center !border-success-500">
                                                                @endif

                                                            </td>

                                                            <td class="p-1">
                                                                <input type="number" wire:model="addedRate"
                                                                    class="form-control text-center !border-success-500">
                                                            </td>

                                                            <td class="table-td p-1">
                                                                <input
                                                                    class="form-control text-center !border-success-500"
                                                                    placeholder="Leave a note" name="note"
                                                                    wire:model="addedNote">
                                                            </td>

                                                            <td class="p-1">
                                                                <button wire:click="addCondition"
                                                                    class="btn inline-flex items-center justify-center btn-outline-success btn-sm">
                                                                    <span class="flex items-center">
                                                                        add
                                                                    </span>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endif
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
