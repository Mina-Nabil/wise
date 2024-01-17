<div>
    <div class="flex justify-center">
        <div class="">
            <div class="flex justify-between flex-wrap items-center mb-3 sticky-top card">
                <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
                    <h4 onclick="launch_toast()" class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mt-3 ml-3">
                        {{ $policy->company->name }} - {{ $policy->name }}

                    </h4>

                    <!---->

                </div>
                @can('update', $policy)
                    @if ($changes)
                        <button type="submit" wire:click="save" class="btn inline-flex justify-center btn-success rounded-[25px] btn-sm mr-3">Save</button>
                    @endif
                @endcan

            </div>
            @if (session()->has('success'))
                <div class="py-[18px] px-6 font-normal text-sm rounded-md bg-success-500 text-white animate-\[fade-out_350ms_ease-in-out\] alert mb-2">
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
                                <input type="text" class="form-control @error('policyName') !border-danger-500 @enderror" wire:model="policyName">
                                @error('policyName')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-area mb-3">
                                <label for="name" class="form-label">Business</label>
                                <select name="business" class="form-control w-full mt-2  @error('policyBusiness') !border-danger-500 @enderror" wire:model="policyBusiness">
                                    @foreach ($linesOfBusiness as $line)
                                        <option {{ $line === $policy_business ? 'selected' : '' }} value="{{ $line }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ $line }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('policyBusiness')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-area mb-3">
                                <label for="name" class="form-label">Note</label>
                                <textarea class="form-control @error('policyNote') !border-danger-500 @enderror" wire:model="policyNote" placeholder="Leave a note" name="note"></textarea>
                                @error('policyNote')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
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
                                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                                <thead class="">
                                                    <tr>

                                                        <th scope="col" class=" table-th ">
                                                            Scope
                                                        </th>

                                                        <th scope="col" class=" table-th ">

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
                                                <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                                    @foreach ($policy->conditions as $condition)
                                                        @if ($editedRowId != $condition->id)
                                                            <tr class="hover:bg-slate-200 dark:hover:bg-slate-700">

                                                                <td class="table-td">
                                                                    {{ $condition->scope }}
                                                                </td>

                                                                <td class="table-td">
                                                                    @switch($condition->operator)
                                                                        @case('gte')
                                                                            >=
                                                                        @break

                                                                        @case('gt')
                                                                            >
                                                                        @break

                                                                        @case('lte')
                                                            <= @break @case('lt') < @break @case('e')=@break @endswitch </td>

                                                        <td class="table-td">
                                                            {{ $condition->value_name }}
                                                        </td>

                                                        <td class="table-td">
                                                            {{ $condition->rate }}
                                                        </td>

                                                        <td class="table-td" style="word-wrap: break-word;">
                                                            {{-- {{ Str::limit($condition->note, 50) }} --}}
                                                            {{ $condition->note }}
                                                        </td>

                                                        <td class="p-1">
                                                            @can('update', $policy)
                                                                <div class=" flex justify-center">
                                                                    <button class="toolTip onTop action-btn m-1 " data-tippy-content="Edit" wire:click="editRow({{ $condition->id }})" type="button">
                                                                        <iconify-icon icon="iconamoon:edit-bold"></iconify-icon>
                                                                    </button>
                                                                    <button class="toolTip onTop action-btn m-1" data-tippy-content="Move Up" type="button" wire:click="moveConditionUp({{ $condition->id }})">
                                                                        <iconify-icon icon="ion:arrow-up"></iconify-icon>
                                                                    </button>
                                                                    <button class="toolTip onTop action-btn m-1" data-tippy-content="Move Down" type="button" wire:click="moveConditionDown({{ $condition->id }})">
                                                                        <iconify-icon icon="ion:arrow-down"></iconify-icon>
                                                                    </button>
                                                                    <button class="toolTip onTop action-btn m-1" data-tippy-content="Delete" type="button" wire:click="deleteCondition({{ $condition->id }})">
                                                                        <iconify-icon icon="heroicons:trash"></iconify-icon>
                                                                    </button>
                                                                </div>
                                                            @endcan
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td class="p-1">
                                                            <select name="scope" wire:model="editedScope" class="form-control w-full text-center @error('editedScope') !border-danger-500 @else !border-success-500 @enderror">
                                                                @foreach ($scopes as $scope)
                                                                    <option value="{{ $scope }}" @selected($condition->scope === $scope) class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                                        {{ $scope }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>

                                                        <td class="p-1">
                                                            <select name="scope" wire:model="editedOperator" class="form-control w-full text-center @error('editedOperator') !border-danger-500 @else !border-success-500 @enderror">
                                                                @foreach ($operators as $operator)
                                                                    <option value="{{ $operator }}" @selected($condition->operator === $operator) class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                                        @switch($operator)
                                                                            @case('gte')
                                                                                >=
                                                                            @break

                                                                            @case('gt')
                                                                                >
                                                                            @break

                                                                            @case('lte')
                                                                <= @break @case('lt') < @break @case('e')=@break @endswitch </option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td class="p-1">
                                                    @if ($editedScope === 'car_model')
                                                        <select wire:model="editedValue" class="select2  @error('editedValue') !border-danger-500 @else !border-success-500 @enderror form-control w-full text-center">
                                                            @foreach ($models as $model)
                                                                <option class=" inline-block font-Inter font-normal text-sm text-slate-600" value="{{ $model->id }}" @selected($model->id === $condition->value)>
                                                                    {{ $model->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @elseif($editedScope === 'brand')
                                                        <select wire:model="editedValue" class="select2  @error('editedValue') !border-danger-500 @else !border-success-500 @enderror form-control w-full text-center">
                                                            @foreach ($brands as $brand)
                                                                <option class=" inline-block font-Inter font-normal text-sm text-slate-600" value="{{ $brand->id }}" @selected($brand->id === $condition->value)>
                                                                    {{ $brand->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @elseif($editedScope === 'country')
                                                        <select wire:model="editedValue" class="select2 @error('editedValue') !border-danger-500 @else !border-success-500 @enderror form-control w-full text-center">
                                                            @foreach ($countries as $country)
                                                                <option class=" inline-block font-Inter font-normal text-sm text-slate-600" value="{{ $country->id }}" @selected($country->id === $condition->value)>
                                                                    {{ $country->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <input type="number" wire:model="editedValue" class="form-control text-center @error('editedValue') !border-danger-500 @else !border-success-500 @enderror" value="{{ $condition->value }}">
                                                    @endif
                                                </td>

                                                <td class="p-1">
                                                    <input type="number" wire:model="editedRate" class="form-control text-center @error('editedRate') !border-danger-500 @else !border-success-500 @enderror" value="{{ $condition->rate }}">
                                                </td>

                                                <td class="table-td p-1" style="max-width: 50px;">
                                                    <input type="text" wire:model="editedNote" class="form-control text-center" value="{{ $condition->note }}">
                                                </td>

                                                <td class="p-1">
                                                    <div class="flex justify-center">
                                                        <button class="toolTip onTop action-btn m-1" wire:click="editCondition({{ $condition->id }})" data-tippy-content="Save" type="button">
                                                            <iconify-icon icon="material-symbols:save"></iconify-icon>
                                                        </button>
                                                        <button class="toolTip onTop action-btn m-1" data-tippy-content="Close" wire:click="closeEditRow" type="button">
                                                            <iconify-icon icon="material-symbols:close"></iconify-icon>
                                                        </button>
                                                    </div>

                                                </td>

                                            </tr>
                                        @endif
                                    @endforeach
                                    @if ($policy->conditions->isEmpty())
                                        <tr>
                                            <td colspan="6" class="text-center p-5">No Conditions
                                                added to this Policy</td>
                                        </tr>
                                    @endif
                                    @if (!$newConditionSection)
                                        <tr>
                                            <td colspan="6" class="pt-3">
                                                @can('update', $policy)
                                                    <button wire:click="openNewConditionSection" class="btn inline-flex justify-center btn-light btn-sm">
                                                        Add new condition
                                                    </button>
                                                @endcan
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td class="p-1">
                                                <select name="scope" wire:model="addedScope" class="@error('addedScope') !border-danger-500 @else !border-success-500 @enderror form-control w-full text-center">
                                                    @foreach ($scopes as $scope)
                                                        <option value="{{ $scope }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                            {{ $scope }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td class="p-1 text-center">
                                                @if ($addedScope === 'car_model' || $addedScope === 'brand' || $addedScope === 'country')
                                                    <input type="hidden" value="=" wire:model="addedOperator" />
                                                    =
                                                @else
                                                    <select name="scope" wire:model="addedOperator" class="@error('addedOperator') !border-danger-500 @else !border-success-500 @enderror form-control w-full text-center">
                                                        @foreach ($operators as $operator)
                                                            <option value="{{ $operator }}" {{ $operator === 'e' ? 'selected' : '' }} class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">

                                                                @if ($operator === 'gte')
                                                                    >=
                                                                @elseif($operator === 'gt')
                                                                    >
                                                                @elseif($operator === 'lte')
                                                            <= @elseif($operator === 'lt') < @elseif($operator === 'e')=@endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </td>

                                        <td class="p-1">
                                            @if ($addedScope === 'car_model' || $addedScope === 'brand' || $addedScope === 'country')
                                                @if ($addedScope === 'car_model')
                                                    <select name="select2basic" wire:model="addedValue" class="select2 @error('addedValue') !border-danger-500 @else !border-success-500 @enderror form-control w-full text-center">
                                                        @foreach ($models as $model)
                                                            <option class=" inline-block font-Inter font-normal text-sm text-slate-600" value="{{ $model->id }}">
                                                                {{ $model->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @elseif($addedScope === 'brand')
                                                    <select name="select2basic" wire:model="addedValue" class="select2 @error('addedValue') !border-danger-500 @else !border-success-500 @enderror form-control w-full text-center">
                                                        @foreach ($brands as $brand)
                                                            <option class=" inline-block font-Inter font-normal text-sm text-slate-600" value="{{ $brand->id }}">
                                                                {{ $brand->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <select name="select2basic" wire:model="addedValue" class="select2 @error('addedValue') !border-danger-500 @else !border-success-500 @enderror form-control w-full text-center">
                                                        @foreach ($countries as $country)
                                                            <option class=" inline-block font-Inter font-normal text-sm text-slate-600" value="{{ $country->id }}">
                                                                {{ $country->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            @else
                                                <input type="number" wire:model="addedValue" style="max-width: 70px" class="form-control text-center @error('addedValue') !border-danger-500 @else !border-success-500 @enderror">
                                            @endif

                                        </td>

                                        <td class="p-1">
                                            <input type="number" wire:model="addedRate" style="max-width: 70px" class="form-control text-center @error('addedRate') !border-danger-500 @else !border-success-500 @enderror">
                                        </td>

                                        <td class="table-td p-1">
                                            <input class="form-control text-center @error('addedNote') !border-danger-500 @else !border-success-500 @enderror" style="min-width: 300px;" placeholder="Leave a note" name="note" wire:model="addedNote">
                                        </td>

                                        <td class="p-1">
                                            <button wire:click="addCondition" class="btn inline-flex items-center justify-center btn-outline-success btn-sm">
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


<div class="card mb-5">
<div class="card-body">
<div class="card-text h-full">
    <div class="px-4 pt-4 pb-3">
        <div class="flex justify-between">
            <label class="form-label">Benefits</label>
        </div>



        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto ">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">

                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="">
                                <tr>

                                    <th scope="col" class=" table-th ">
                                        Benefit
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Value
                                        <span class="lowercase text-xs text-slate-500 dark:text-slate-400 mt-1">&nbsp; * click on value to edit</span>
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Actions
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @foreach ($policy->benefits as $benefit)
                                    @if ($editBenefitId && $benefit->id === $editBenefitId)
                                        <tr class="hover:bg-slate-200 dark:hover:bg-slate-700">
                                            <td class="table-td">
                                                {{ $ebenefit }}
                                            </td>

                                            <td class="table-td">
                                                <input list="benefit_options" class="form-control text-center @error('benefitValue') !border-danger-500 @else !border-success-500 @enderror" wire:model="benefitValue" />
                                                <datalist id="benefit_options">
                                                    <option>مغطي</option>
                                                    <option>غير مغطي</option>
                                                </datalist>
                                            </td>

                                            <td class="table-td  p-1">
                                                <div class="flex">
                                                    <button class="toolTip onTop action-btn m-1" wire:click="editBenefit" data-tippy-content="Save" type="button">
                                                        <iconify-icon icon="material-symbols:save"></iconify-icon>
                                                    </button>
                                                    <button class="toolTip onTop action-btn m-1" data-tippy-content="Close" wire:click="closeEditBenefit" type="button">
                                                        <iconify-icon icon="material-symbols:close"></iconify-icon>
                                                    </button>
                                                </div>

                                            </td>

                                        </tr>
                                    @else
                                        <tr class="">

                                            <td class="table-td">
                                                {{ $benefit->benifit }}
                                            </td>

                                            <td class="table-td hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer" wire:click="editThisBenefit({{ $benefit->id }})">
                                                {{ $benefit->value }}
                                            </td>

                                            <td class="table-td  p-1">
                                                <div class="flex">
                                                    <button class="toolTip onTop bg-slate-900 text-white action-btn m-1" data-tippy-content="Delete" wire:click="deleteThisBenefit({{ $benefit->id }})" type="button">
                                                        <iconify-icon icon="heroicons:trash"></iconify-icon>
                                                    </button>
                                                </div>

                                            </td>

                                        </tr>
                                    @endif
                                @endforeach




                                <tr class="hover:bg-slate-200 dark:hover:bg-slate-700">
                                    <td class="table-td">

                                        <select wire:model="newBenefit" class=" @error('newBenefit') !border-danger-500  @enderror form-control w-full text-center">
                                            @foreach ($BENEFITS as $BENEFIT)
                                                @if (!in_array($BENEFIT, $policy->benefits->pluck('benifit')->toArray()))
                                                    <option class=" inline-block font-Inter font-normal text-sm text-slate-600" value="{{ $BENEFIT }}">
                                                        {{ $BENEFIT }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>

                                    <td class="table-td">
                                        <input list="benefit_options" class="form-control text-center @error('newValue') !border-danger-500 @enderror" wire:model="newValue" />
                                        <datalist id="benefit_options">
                                            <option>مغطي</option>
                                            <option>غير مغطي</option>
                                        </datalist>
                                    </td>

                                    <td class="table-td  p-1">
                                        <div class="flex">
                                            <button wire:click="addBenefit" class="btn btn-sm inline-flex justify-center btn-success shadow-base2">Add</button>
                                        </div>

                                    </td>

                                </tr>
                                

                            </tbody>
                            <p class=" text-xs text-slate-500 dark:text-slate-400 mt-1">&nbsp; <iconify-icon icon="icons8:idea"></iconify-icon> Added benefits will not appear in add new list</p>
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
                                Are you sure ! you Want to delete this Benefit ?
                            </h6>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="deleteBenefit" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-danger-500">Yes, Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif



</div>
