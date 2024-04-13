<div>

    <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base">
        <div class="card-body flex flex-col p-6 active">
            <div class="order-2 card-text h-full menu-open active">
                <div class="flex justify-between mb-4">
                    <div>
                        <div class="text-xl text-slate-900 dark:text-white text-wrap">
                            {{ $profile->title }}
                            @if ($profile->per_policy)
                                <span class="badge bg-primary-500 text-primary-500 bg-opacity-30 capitalize">Per Policy</span>
                            @endif
                        </div>
                        <div class="text-base">
                            {{ ucwords(str_replace('_', ' ', $profile->type)) }}
                        </div>
                    </div>
                    <div>
                        @if ($profile->user)
                            <a href="card.html" class="inline-flex leading-5 text-slate-500 dark:text-slate-400 text-sm font-normal active">
                                <iconify-icon class="text-secondary-500 ltr:mr-2 rtl:ml-2 text-lg" icon="lucide:user"></iconify-icon>
                                {{ $profile->user->first_name }} {{ $profile->user->last_name }}
                            </a>
                        @endif

                    </div>

                </div>
                <div class="card-text mt-4 menu-open">
                    <p>{{ $profile->desc }}</p>
                    <div class="mt-4 space-x-4 rtl:space-x-reverse">
                        <button wire:click="openUpdateSec" class="btn inline-flex justify-center btn-light btn-sm">Edit info</button>
                    </div>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 float-right">Created {{ \Carbon\Carbon::parse($profile->created_at)->format('l d/m/Y') }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 float-right">Updated {{ \Carbon\Carbon::parse($profile->updated_at)->format('l d/m/Y h:m') }} - &nbsp;</p>
            </div>
        </div>
    </div>


    <div class="card mt-5">
        <div class="card-body">
            <div class="card-text h-full">
                <div class="px-4 pt-4 pb-3">
                    <div class="flex justify-between">
                        <label class="form-label">Configurations</label>

                    </div>

                    <div class="card-body px-6 pb-6">
                        <div class="overflow-x-auto ">
                            <div class="inline-block min-w-full align-middle">
                                <div class="overflow-hidden ">
                                    <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                        <thead class="">
                                            <tr>

                                                <th scope="col" class=" table-th ">
                                                    Percentage
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    From
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    LOB/Condition
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    Action
                                                </th>

                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                            @foreach ($profile->configurations as $conf)
                                                @if ($editedRowId != $conf->id)
                                                    <tr class="hover:bg-slate-200 dark:hover:bg-slate-700">

                                                        <td class="table-td">
                                                            {{ $conf->percentage }}
                                                        </td>

                                                        <td class="table-td">
                                                            {{ ucwords(str_replace('_', ' ', $conf->from)) }}

                                                        </td>

                                                        <td class="table-td">
                                                            {{ $conf->line_of_business ? ucwords(str_replace('_', ' ', $conf->line_of_business)) : ($conf->condition->company ? $conf->condition->company->name.' | '.$conf->condition->name : $conf->condition->name) }}
                                                        </td>

                                                        <td class="p-1">
                                                            <div class=" flex justify-center">
                                                                <button class="toolTip onTop action-btn m-1 " data-tippy-content="Edit" wire:click="editRow({{ $conf->id }})" type="button">
                                                                    <iconify-icon icon="iconamoon:edit-bold"></iconify-icon>
                                                                </button>
                                                                <button class="toolTip onTop action-btn m-1" data-tippy-content="Move Up" type="button" wire:click="moveConditionUp({{ $conf->id }})">
                                                                    <iconify-icon icon="ion:arrow-up"></iconify-icon>
                                                                </button>
                                                                <button class="toolTip onTop action-btn m-1" data-tippy-content="Move Down" type="button" wire:click="moveConditionDown({{ $conf->id }})">
                                                                    <iconify-icon icon="ion:arrow-down"></iconify-icon>
                                                                </button>
                                                                <button class="toolTip onTop action-btn m-1" data-tippy-content="Delete" type="button" wire:click="deleteCondition({{ $conf->id }})">
                                                                    <iconify-icon icon="heroicons:trash"></iconify-icon>
                                                                </button>
                                                            </div>
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
                                    {{-- @if (isEmpty($profile->conf))
                                        <tr>
                                            <td colspan="6" class="text-center p-5">No configrations added to this profile</td>
                                        </tr>
                                    @endif --}}
                                    <tr>
                                        <td colspan="6" class="pt-3">
                                            <button wire:click="openNewConfSection" class="btn inline-flex justify-center btn-light btn-sm">
                                                Add new configuration
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>




                @if ($newConfSec)
                    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
                        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                                <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                                    <!-- Modal header -->
                                    <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                                        <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                            Add Commission Configuration
                                        </h3>

                                        <button wire:click="closeNewConfSection" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="p-6 space-y-4">
                                        <div class="from-group">

                                            <div class="input-area mt-3">
                                                <label for="percentage" class="form-label">Percentage</label>
                                                <div class="relative">
                                                    <input type="number" class="form-control @error('percentage') !border-danger-500 @enderror !pr-32" wire:model.defer="percentage">
                                                    <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                                        %
                                                    </span>
                                                </div>
                                                {{-- <input id="percentage" type="number" class="form-control @error('percentage') !border-danger-500 @enderror" wire:model.defer="percentage"> --}}
                                            </div>
                                            @error('percentage')
                                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror

                                            <div class="input-area mt-3">
                                                <label for="from" class="form-label">From</label>
                                                <select name="from" class="form-control w-full mt-2 @error('from') !border-danger-500 @enderror" wire:model.defer="from">
                                                    <option value="">None</option>
                                                    @foreach ($FROMS as $FROM)
                                                        <option value="{{ $FROM }}">{{ ucwords(str_replace('_', ' ', $FROM)) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('from')
                                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror

                                            <div class="input-area mt-3">
                                                <label for="line_of_business" class="form-label">Line of business</label>
                                                <select name="line_of_business" class="form-control w-full mt-2 @error('line_of_business') !border-danger-500 @enderror" wire:model="line_of_business">
                                                    <option value="">None</option>
                                                    @foreach ($LOBs as $LOB)
                                                        <option value="{{ $LOB }}">{{ ucwords(str_replace('_', ' ', $LOB)) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('line_of_business')
                                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror

                                            @if (!$line_of_business)

                                                @if ($condition)
                                                    <p class="mt-3"><iconify-icon icon="mdi:shield-tick"></iconify-icon>
                                                        {{ $condition->company ? $condition->company->name . ' | ' : '' }} {{ $condition->name }}
                                                    </p>
                                                @else

                                                    <div class="input-area mt-3">
                                                        <label for="conditionType" class="form-label">Condition Type</label>
                                                        <select name="conditionType" class="form-control w-full mt-2" wire:model="conditionType">
                                                            <option value="policy">Policy</option>
                                                            <option value="company">Company</option>
                                                        </select>
                                                    </div>

                                                    <div class="input-area mt-3">
                                                        <label for="searchCon" class="form-label">Serach {{ $conditionType }}</label>
                                                        <input id="searchCon" type="text" class="form-control" wire:model="searchCon">
                                                    </div>

                                                    <div class="text-sm">
                                                        @if ($searchlist)
                                                            @if ($conditionType == 'company')
                                                                @foreach ($searchlist as $result)
                                                                    <p class="mt-3"><iconify-icon icon="heroicons:building-storefront"></iconify-icon>
                                                                        {{ $result->name }} <Span wire:click="selectResult({{ $result->id }})" class="cursor-pointer text-primary-500">Select Company</Span>
                                                                    </p>
                                                                @endforeach
                                                            @elseif ($conditionType == 'policy')
                                                                @foreach ($searchlist as $result)
                                                                    <p class="mt-3"><iconify-icon icon="material-symbols:policy-outline-rounded"></iconify-icon>
                                                                        {{ $result->company->name }} | {{ $result->name }} <Span wire:click="selectResult({{ $result->id }})" class="cursor-pointer text-primary-500">Select Policy</Span>
                                                                    </p>
                                                                @endforeach
                                                            @endif
                                                        @endif
                                                    </div>

                                                @endif
                                            @endif


                                            {{-- <div class="input-area mt-3">
                                                <div class="flex items-center space-x-2">
                                                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                        <input wire:model="updatedPerPolicy" type="checkbox" value="" class="sr-only peer">
                                                        <div
                                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500">
                                                        </div>
                                                    </label>
                                                    <span class="text-sm text-slate-600 font-Inter font-normal">Per Policy</span>

                                                </div>
                                            </div>

                                            <div class="input-area mt-3">
                                                <label for="updatedTitle" class="form-label">Title</label>
                                                <input id="updatedTitle" type="text" class="form-control @error('updatedTitle') !border-danger-500 @enderror" wire:model.defer="updatedTitle">
                                            </div>

                                            <div class="from-group mt-3">
                                                <label for="updatedDesc" class="form-label">Description</label>
                                                <textarea class="form-control mt-2 w-full" wire:model.defer="updatedDesc"></textarea>
                                                @error('updatedDesc')
                                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                                @enderror
                                            </div> --}}

                                        </div>

                                    </div>
                                    <!-- Modal footer -->
                                    <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                        <button wire:click="addConf" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                            <span wire:loading.remove wire:target="addConf">Submit</span>
                                            <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="addConf" icon="line-md:loading-twotone-loop"></iconify-icon>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif


                @if ($updatedCommSec)
                    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
                        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                                <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                                    <!-- Modal header -->
                                    <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                                        <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                            Add Commission Profile
                                        </h3>

                                        <button wire:click="closeUpdateSec" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="p-6 space-y-4">
                                        <div class="from-group">

                                            <div class="input-area mt-3">
                                                <label for="updatedType" class="form-label">Type</label>
                                                <select name="updatedType" class="form-control w-full mt-2 @error('updatedType') !border-danger-500 @enderror" wire:model.defer="updatedType">
                                                    <option>None</option>
                                                    @foreach ($profileTypes as $type)
                                                        <option value="{{ $type }}">{{ ucwords(str_replace('_', ' ', $type)) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('updatedType')
                                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror

                                            <div class="input-area mt-3">
                                                <div class="flex items-center space-x-2">
                                                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                        <input wire:model="updatedPerPolicy" type="checkbox" value="" class="sr-only peer">
                                                        <div
                                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500">
                                                        </div>
                                                    </label>
                                                    <span class="text-sm text-slate-600 font-Inter font-normal">Per Policy</span>

                                                </div>
                                            </div>

                                            {{-- <div class="input-area mt-3">
                                    <label for="updatedUserId" class="form-label">User</label>
                                    <select name="updatedUserId" id="updatedUserId" class="form-control w-full mt-2 @error('updatedUserId') !border-danger-500 @enderror" wire:model="updatedUserId">
                                        <option value="">None</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('updatedUserId')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror --}}

                                            <div class="input-area mt-3">
                                                <label for="updatedTitle" class="form-label">Title</label>
                                                <input id="updatedTitle" type="text" class="form-control @error('updatedTitle') !border-danger-500 @enderror" wire:model.defer="updatedTitle">
                                            </div>

                                            <div class="from-group mt-3">
                                                <label for="updatedDesc" class="form-label">Description</label>
                                                <textarea class="form-control mt-2 w-full" wire:model.defer="updatedDesc"></textarea>
                                                @error('updatedDesc')
                                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                                @enderror
                                            </div>

                                        </div>

                                    </div>
                                    <!-- Modal footer -->
                                    <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                        <button wire:click="updateComm" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                            <span wire:loading.remove wire:target="updateComm">Submit</span>
                                            <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="updateComm" icon="line-md:loading-twotone-loop"></iconify-icon>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
