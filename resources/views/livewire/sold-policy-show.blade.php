<div>
    <div class="flex justify-end mb-5">
        <div class="dropdown relative">
            <button class="btn btn-sm inline-flex justify-center btn-secondary items-center" type="button" id="secondaryDropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                Actions
                <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="ic:round-keyboard-arrow-down"></iconify-icon>
            </button>
            <ul class=" dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow
                        z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                <li>
                    <a href="#" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                dark:hover:text-white">
                        Edit</a>
                </li>
                <li>
                    <a href="#" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                dark:hover:text-white">
                        Set as Valid</a>
                </li>
                <li>
                    <a href="#" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                dark:hover:text-white">
                        Set as Valid</a>
                </li>
                <li>
                    <a href="#" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                dark:hover:text-white border-t border-slate-100 dark:border-slate-800">
                        Sign out</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div>

            <div class="bg-no-repeat bg-cover mb-5 p-5 rounded-[6px] relative" style="background-image: url({{ asset('assets/images/all-img/policy-bg.jpg') }}); padding: 30px">
                <div class="max-w-xl">

                    <h4 class=" font-medium text-white mb-2">
                        <span class="block"><b><iconify-icon icon="iconoir:privacy-policy"></iconify-icon> Sold Policy</b></span>

                        <p class="text-sm text-white font-normal">
                            Start: {{ \Carbon\Carbon::parse($soldPolicy->start)->format('l d/m/Y') }}

                        </p>
                        <p class="text-sm text-white font-normal mb-3">
                            Expired: {{ \Carbon\Carbon::parse($soldPolicy->expiry)->format('l d/m/Y') }}
                        </p>

                        <span class="block mb-3">
                            <p class="text-sm text-slate-400  font-light" wire:click="setStatus">
                                {{ ucwords($soldPolicy->client_type) }}
                            </p>
                            <a class="hover:underline cursor-pointer" href="{{ route($soldPolicy->client_type . 's.show', $soldPolicy->client_id) }}">
                                @if ($soldPolicy->client_type === 'customer')
                                    <iconify-icon icon="raphael:customer"></iconify-icon> {{ $soldPolicy->client->first_name . ' ' . $soldPolicy->client->middle_name . ' ' . $soldPolicy->client->last_name }}
                                @elseif($soldPolicy->client_type === 'corporate')
                                    <iconify-icon icon="mdi:company"></iconify-icon>{{ $soldPolicy->client->name }}
                                @endif
                            </a>
                        </span>
                    </h4>

                    <a href="{{ route('policies.show', $soldPolicy->policy->id) }}">
                        <button class="btn btn-sm btn-dark text-left">
                            {{ $soldPolicy->policy->company->name }} - {{ $soldPolicy->policy->name }}
                        </button>
                    </a>
                </div>
            </div>

            <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base mb-5">
                <div class="card-body flex flex-col p-6 active justify-center">
                    <div>
                        <span class="text-xs text-slate-500 dark:text-slate-400 block mb-1">
                            Policy Number
                        </span>
                        <span class="text-lg font-medium text-slate-900 dark:text-white block">
                            {{ $soldPolicy->policy_number }}
                        </span>
                    </div>
                </div>
            </div>

            @if ($soldPolicy->customer_car)
                <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base mb-5">
                    <div class="card-body flex flex-col p-6 active justify-center">
                        <header class=" mb-2 items-center text-center">
                            <div class="flex-1">
                                <div class=" text-base font-Inter text-slate-900 dark:text-white">Car specifications</div>
                            </div>
                            <hr class="mb-3 w-[96px]" style="margin: 0 auto;">
                        </header>

                        <div class="text-center">
                            {{ $soldPolicy->customer_car->model_year }}
                            {{ $soldPolicy->customer_car->car->car_model->brand->name }}
                            {{ $soldPolicy->customer_car->car->car_model->name }}
                            {{ $soldPolicy->customer_car->car->category }}
                        </div>

                        <hr class="mt-3 m-5">

                        <table class=" divide-slate-100 dark:divide-slate-700">
                            <tbody class="bg-white dark:bg-slate-800 ">

                                <tr>
                                    <td class="table-td ">Car Chassis</td>
                                    <td class="table-td  !text-lg"><b>{{ $soldPolicy->car_chassis }}</b></td>
                                </tr>

                                <tr>
                                    <td class="table-td ">Car Plate No.</td>
                                    <td class="table-td  !text-lg"><b>{{ $soldPolicy->car_plate_no }}</b></td>
                                </tr>

                                <tr>
                                    <td class="table-td ">Car Engine</td>
                                    <td class="table-td  !text-lg"><b>{{ $soldPolicy->car_engine }}</b></td>
                                </tr>



                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base">
                <div class="card-body flex flex-col p-6 active text-center">
                    <header class=" mb-5 items-center">
                        <button wire:click="togglePaymentInfoSection" class="action-btn float-right text-sm" type="button">
                            <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                        </button>
                        <div class="flex-1">
                            <div class="card-title font-Inter text-slate-900 dark:text-white">{{ number_format($soldPolicy->insured_value, 0, '.', ',') }}</div>
                            <div class="card-subtitle font-Inter">Insured Value</div>
                            @if ($soldPolicy->payment_frequency)
                                <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 bg-warning-500 text-xs">
                                    {{ ucwords(str_replace('-', ' ', $soldPolicy->payment_frequency)) }}
                                    @if ($soldPolicy->installements_count)
                                        <span class="w-5 h-5 inline-flex items-center justify-center bg-danger-500 text-white rounded-full font-Inter text-xs ltr:ml-1 rtl:mr-1 relative">
                                            {{ $soldPolicy->installements_count }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <hr class="mb-3 w-[96px]" style="margin: 0 auto;margin-top:10px;">
                    </header>

                    <div class="grid grid-cols-3 gap-3 mb-4 text-base text-center">
                        <div class="border-r">
                            <h5>{{ number_format($soldPolicy->net_premium, 0, '.', ',') }}</h5>
                            <p class="text-xs">Net Premium</p>

                        </div>
                        <div class="">
                            <h5 class="text-info-500">{{ $soldPolicy->net_rate }}%</h5>
                            <p class="mr-2 text-xs">Net Rate</p>
                        </div>
                        <div class="border-l">
                            <h5>{{ number_format($soldPolicy->gross_premium, 0, '.', ',') }}</h5>
                            <p class="text-xs">Gross Premium</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>

            {{-- Tasks --}}
            <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base mb-5">
                <div class="card-body flex flex-col p-6 active">
                    <header class="flex mb-5 items-center">
                        <div class="flex-1">
                            <div class="card-title font-Inter text-slate-900 dark:text-white">
                                <iconify-icon icon="ic:round-add-task"></iconify-icon>
                                Tasks
                                <button wire:click="toggleNewTaskSection" class="btn inline-flex justify-center btn-dark shadow-base2 float-right btn-sm">Create</button>
                            </div>

                        </div>
                    </header>
                    <div>

                        @if (!$soldPolicy->endorsements->isEmpty() && $soldPolicy->claims->isEmpty())
                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                <thead class=" border-t border-slate-100 dark:border-slate-800">
                                    <tr>

                                        <th scope="col" class=" table-th ">
                                            title
                                        </th>

                                        <th scope="col" class=" table-th ">
                                            Fields/Actions
                                        </th>

                                        <th scope="col" class=" table-th ">
                                            status
                                        </th>

                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                    @if ($soldPolicy->endorsements)
                                        @foreach ($soldPolicy->endorsements as $endorsement)
                                            <tr>
                                                <td class="table-td">{{ $endorsement->title }}</td>
                                                <td class="table-td ">{{ $endorsement->actions->count() }}</td>
                                                <td class="table-td ">

                                                    @if ($endorsement->status === 'new')
                                                        <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-primary-500 bg-primary-500 text-xs">
                                                            New
                                                        </div>
                                                    @elseif($endorsement->status === 'assigned')
                                                        <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-info-500 bg-info-500 text-xs">
                                                            Assigned
                                                        </div>
                                                    @elseif($endorsement->status === 'in_progress')
                                                        <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-secondary-500 bg-secondary-500 text-xs">
                                                            in Progress
                                                        </div>
                                                    @elseif($endorsement->status === 'pending')
                                                        <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-warning-500 bg-warning-500 text-xs">
                                                            Pending
                                                        </div>
                                                    @elseif($endorsement->status === 'completed')
                                                        <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-success-500 bg-success-500 text-xs">
                                                            Completed
                                                        </div>
                                                    @elseif($endorsement->status === 'closed')
                                                        <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-black-500 bg-black-500 text-xs">
                                                            Closed
                                                        </div>
                                                    @endif

                                                </td>

                                            </tr>
                                        @endforeach
                                    @endif


                                    @if ($soldPolicy->claims)
                                        @foreach ($soldPolicy->claims as $claim)
                                            <tr>
                                                <td class="table-td">{{ $claim->title }}</td>
                                                <td class="table-td ">{{ $claim->fields->count() }}</td>
                                                <td class="table-td ">

                                                    @if ($claim->status === 'new')
                                                        <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-primary-500 bg-primary-500 text-xs">
                                                            New
                                                        </div>
                                                    @elseif($claim->status === 'assigned')
                                                        <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-info-500 bg-info-500 text-xs">
                                                            Assigned
                                                        </div>
                                                    @elseif($claim->status === 'in_progress')
                                                        <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-secondary-500 bg-secondary-500 text-xs">
                                                            in Progress
                                                        </div>
                                                    @elseif($claim->status === 'pending')
                                                        <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-warning-500 bg-warning-500 text-xs">
                                                            Pending
                                                        </div>
                                                    @elseif($claim->status === 'completed')
                                                        <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-success-500 bg-success-500 text-xs">
                                                            Completed
                                                        </div>
                                                    @elseif($claim->status === 'closed')
                                                        <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-black-500 bg-black-500 text-xs">
                                                            Closed
                                                        </div>
                                                    @endif

                                                </td>

                                            </tr>
                                        @endforeach
                                    @endif


                                </tbody>
                            </table>
                        @else
                            <div class="text-center">
                                <p class="text-center text-sm m-5 text-primary">No tasks for this sold policy!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- benefits --}}
            <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base mb-5">
                <div class="card-body flex flex-col p-6 active">
                    <header class="flex mb-5 items-center">
                        <div class="flex-1">
                            <div class="card-title font-Inter text-slate-900 dark:text-white">
                                <iconify-icon icon="subway:save"></iconify-icon>
                                benefits
                                <button wire:click="openNewBenefitSec" class="btn inline-flex justify-center btn-dark shadow-base2 float-right btn-sm">New Benefit</button>
                            </div>

                        </div>
                    </header>
                    <div>
                        @if (!$soldPolicy->benefits->isEmpty())
                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                <thead class=" border-t border-slate-100 dark:border-slate-800">
                                    <tr>

                                        <th scope="col" class=" table-th ">
                                            Benefit
                                        </th>

                                        <th scope="col" class=" table-th ">
                                            Value
                                        </th>

                                        <th scope="col" class=" table-th ">
                                            Action
                                        </th>

                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                    @foreach ($soldPolicy->benefits as $benefit)
                                        <tr>
                                            <td class="table-td">{{ $benefit->benefit }}</td>
                                            <td class="table-td ">{{ $benefit->value }}</td>
                                            <td class="table-td ">
                                                <div class="flex space-x-3 rtl:space-x-reverse">
                                                    <button wire:click="editThisBenefit({{ $benefit->id }})" class="action-btn" type="button">
                                                        <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                                                    </button>
                                                    <button wire:click="deleteThisBenefit({{ $benefit->id }})" class="action-btn" type="button">
                                                        <iconify-icon icon="heroicons:trash"></iconify-icon>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        @else
                            <div class="text-center">
                                <p class="text-center text-sm m-5 text-primary">No benefits for this sold policy!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Exclusions --}}
            <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base">
                <div class="card-body flex flex-col p-6 active">
                    <header class="flex mb-5 items-center">
                        <div class="flex-1">
                            <div class="card-title font-Inter text-slate-900 dark:text-white">
                                <iconify-icon icon="ooui:special-pages-ltr"></iconify-icon>
                                Exclusions
                                <button wire:click="openAddExcSec" class="btn inline-flex justify-center btn-dark shadow-base2 float-right btn-sm">New Exclusions</button>
                            </div>

                        </div>
                    </header>
                    <div>
                        @if (!$soldPolicy->exclusions->isEmpty())
                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                <thead class=" border-t border-slate-100 dark:border-slate-800">
                                    <tr>

                                        <th scope="col" class=" table-th ">
                                            Title
                                        </th>

                                        <th scope="col" class=" table-th ">
                                            Value
                                        </th>

                                        <th scope="col" class=" table-th ">
                                            Action
                                        </th>

                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                    @foreach ($soldPolicy->exclusions as $exclusion)
                                        <tr>
                                            <td class="table-td">{{ $exclusion->title }}</td>
                                            <td class="table-td ">{{ $exclusion->value }}</td>
                                            <td class="table-td ">
                                                <div class="flex space-x-3 rtl:space-x-reverse">
                                                    <button wire:click="editThisExc({{ $exclusion->id }})" class="action-btn" type="button">
                                                        <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                                                    </button>
                                                    <button wire:click="deleteThisExc({{ $exclusion->id }})" class="action-btn" type="button">
                                                        <iconify-icon icon="heroicons:trash"></iconify-icon>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        @else
                            <div class="text-center">
                                <p class="text-center text-sm m-5 text-primary">No exclusions for this sold policy!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($newTaskSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                New Exclusion
                            </h3>
                            <button wire:click="closeAddExcSec" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>


                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif


                        <!-- Modal body -->
                        <div class="p-6 py-1">
                            <div class="from-group col-span-3  mt-3">
                                <label for="newExcValue" class="form-label">Task Type</label>
                                <select name="basicSelect" class="form-control w-full mt-2" wire:model="newTaskType">
                                    <option value="claim">Claim</option>
                                    <option value="endorsement">Endorsement</option>
                                </select>
                            </div>

                            <div class="input-area mt-3">
                                <label for="textarea" class="form-label">Text Description</label>
                                <textarea wire:model.defer="newTaskDesc" id="textarea" class="form-control" placeholder="Text Area"></textarea>
                            </div>

                            <div class="input-area mt-3">
                                <label for="textarea" class="form-label">Due Date</label>
                                <input wire:model.defer="newTaskDue" id="textarea" type="date" class="form-control">
                            </div>
                        </div>



                        @if ($newTaskType === 'endorsement')
                            <div class="p-6 py-1">
                                <p class="text-lg"><b>Enter Actions</b></p>
                            </div>
                            @foreach ($actions as $index => $action)
                                <div class="p-6 py-1">
                                    <div class="grid grid-cols-8 md:grid-cols-8 lg:grid-cols-8 gap-2 items-center">
                                        <div class="from-group col-span-3">
                                            <label for="newExcValue" class="form-label">Title</label>
                                            <select name="basicSelect" class="form-control w-full mt-2  @error('actions.' . $index . '.column_name') !border-danger-500 @enderror" wire:model="actions.{{ $index }}.column_name">
                                                <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">Select Relation...</option>
                                                @foreach ($COLUMNS as $COLUMN)
                                                    <option value="{{ $COLUMN }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">{{ ucwords(str_replace('_', ' ', $COLUMN)) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="from-group col-span-4">
                                            <label for="newExcValue" class="form-label">Value</label>
                                            <input name="newExcValue" type="text" class="form-control mt-2 w-full @error('actions.' . $index . '.value') !border-danger-500 @enderror" wire:model="actions.{{ $index }}.value">
                                            @error('actions.{{ $index }}.value')
                                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="from-group col-span-1">
                                            <label for="newExcValue" class="form-label">remove</label>
                                            <button class="action-btn" wire:click="removeAcion({{ $index }})" type="button">
                                                <iconify-icon icon="heroicons:trash"></iconify-icon>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="p-6 space-y-4">
                                <button wire:click="addAnotherAction" class="btn btn-sm mt-2 inline-flex justify-center btn-dark">Add Action</button>
                            </div>
                        @elseif($newTaskType === 'claim')
                            <div class="p-6 py-1">
                                <p class="text-lg"><b>Enter Fields</b></p>
                            </div>
                            @foreach ($fields as $index => $field)
                                <div class="p-6 py-1">
                                    <div class="grid grid-cols-8 md:grid-cols-8 lg:grid-cols-8 gap-2 items-center">
                                        <div class="from-group col-span-3">
                                            <label for="newExcValue" class="form-label">Title</label>
                                            <input name="newExcValue" type="text" class="form-control mt-2 w-full @error('fields.' . $index . '.title') !border-danger-500 @enderror" wire:model="fields.{{ $index }}.title">
                                            @error('fields.{{ $index }}.title')
                                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="from-group col-span-4">
                                            <label for="newExcValue" class="form-label">Value</label>
                                            <input name="newExcValue" type="text" class="form-control mt-2 w-full @error('fields.' . $index . '.value') !border-danger-500 @enderror" wire:model="fields.{{ $index }}.value">
                                            @error('fields.{{ $index }}.value')
                                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="from-group col-span-1">
                                            <label for="newExcValue" class="form-label">remove</label>
                                            <button class="action-btn" wire:click="removeField({{ $index }})" type="button">
                                                <iconify-icon icon="heroicons:trash"></iconify-icon>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="p-6 space-y-4">
                                <button wire:click="addAnotherField" class="btn btn-sm mt-2 inline-flex justify-center btn-dark">Add Field</button>
                            </div>
                        @endif


                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="createTask" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="createTask">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="createTask" icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($editPaymentInfoSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                New Exclusion
                            </h3>
                            <button wire:click="togglePaymentInfoSection" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="from-group">
                                <label for="insured_value" class="form-label">Insured Value</label>
                                <input name="insured_value" type="number" class="form-control mt-2 w-full @error('insured_value') !border-danger-500 @enderror" wire:model.defer="insured_value">
                                @error('insured_value')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="net_rate" class="form-label">Net Rate</label>
                                <input name="net_rate" type="number" max="100" min="0" class="form-control mt-2 w-full @error('net_rate') !border-danger-500 @enderror" wire:model.defer="net_rate">
                                @error('net_rate')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="net_premium" class="form-label">Net Premium</label>
                                <input name="net_premium" type="number" class="form-control mt-2 w-full @error('net_premium') !border-danger-500 @enderror" wire:model.defer="net_premium">
                                @error('net_premium')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="gross_premium" class="form-label">Gross Premium</label>
                                <input name="gross_premium" type="number" class="form-control mt-2 w-full @error('gross_premium') !border-danger-500 @enderror" wire:model.defer="gross_premium">
                                @error('gross_premium')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="installements_count" class="form-label">Installements Count</label>
                                <input name="installements_count" max="12" min="1" type="number" class="form-control mt-2 w-full @error('installements_count') !border-danger-500 @enderror" wire:model.defer="installements_count">
                                @error('installements_count')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="payment_frequency" class="form-label">Payment Frequency</label>
                                <select name="payment_frequency" id="basicSelect" class="form-control w-full mt-2 @error('payment_frequency') !border-danger-500 @enderror" wire:model="payment_frequency">
                                    <option> Select an option...</option>
                                    @foreach ($PAYMENT_FREQS as $PAYMENT_FREQ)
                                        <option value="{{ $PAYMENT_FREQ }}">{{ $PAYMENT_FREQ }}</option>
                                    @endforeach
                                </select>
                                @error('payment_frequency')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editPaymentInfo" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="addExc">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="addExc" icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($newExcSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                New Exclusion
                            </h3>
                            <button wire:click="closeAddExcSec" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="from-group">
                                <label for="newExcTitle" class="form-label">Title</label>
                                <input name="newExcTitle" type="text" class="form-control mt-2 w-full @error('newExcTitle') !border-danger-500 @enderror" wire:model.defer="newExcTitle">
                                @error('newExcTitle')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="newExcValue" class="form-label">Value</label>
                                <input name="newExcValue" type="text" class="form-control mt-2 w-full @error('newExcValue') !border-danger-500 @enderror" wire:model.defer="newExcValue">
                                @error('newExcValue')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addExc" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="addExc">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="addExc" icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($newBenefitSec)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                New Benefit
                            </h3>
                            <button wire:click="closeNewBenefitSec" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="from-group">
                                <label for="newBenefit" class="form-label">Benefit</label>
                                <select name="newBenefit" class="form-control w-full mt-2 @error('newBenefit') !border-danger-500 @enderror" wire:model="newBenefit">
                                    <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                        Select an option...</option>
                                    @foreach ($BENEFITS as $BENEFIT)
                                        <option value="{{ $BENEFIT }}">
                                            {{ $BENEFIT }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('newBenefit')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="newValue" class="form-label">Value</label>
                                <input type="text" class="form-control mt-2 w-full @error('newValue') !border-danger-500 @enderror" wire:model.defer="newValue">
                                @error('newValue')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addBenefit" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="editBenefit">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="editBenefit" icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($excId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Edit Exclusion
                            </h3>
                            <button wire:click="closeEditExc" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="from-group">
                                <label for="lastName" class="form-label">Title</label>
                                <input type="text" class="form-control mt-2 w-full @error('eExcTitle') !border-danger-500 @enderror" wire:model.defer="eExcTitle">
                                @error('eExcTitle')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="lastName" class="form-label">Value</label>
                                <input type="text" class="form-control mt-2 w-full @error('eExcValue') !border-danger-500 @enderror" wire:model.defer="eExcValue">
                                @error('eExcValue')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editExc" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="editExc">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="editExc" icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($benefitId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Edit Benefit
                            </h3>
                            <button wire:click="closeEditBenefit" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="from-group">
                                <label for="eBenefit" class="form-label">Benefit</label>
                                <select name="eBenefit"eBenefit class="form-control w-full mt-2 @error('eBenefit') !border-danger-500 @enderror" wire:model="eBenefit">
                                    <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                        Select an option...</option>
                                    @foreach ($BENEFITS as $BENEFIT)
                                        <option value="{{ $BENEFIT }}">
                                            {{ $BENEFIT }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('eBenefit')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="lastName" class="form-label">Value</label>
                                <input type="text" class="form-control mt-2 w-full @error('eValue') !border-danger-500 @enderror" wire:model.defer="eValue">
                                @error('eValue')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editBenefit" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="editBenefit">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="editBenefit" icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($deleteBenefitId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                                rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-danger-500">
                            <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                Delete Benefit
                            </h3>
                            <button wire:click="dismissDeleteBenefit" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                            dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
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
                        <div class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="deleteBenefit" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-danger-500">Yes, Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($deleteExcId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                                rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-danger-500">
                            <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                Delete Exclusions
                            </h3>
                            <button wire:click="dismissDeleteExc" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                            dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <h6 class="text-base text-slate-900 dark:text-white leading-6">
                                Are you sure ! you Want to delete this Exclusions ?
                            </h6>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="deleteExc" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-danger-500">Yes, Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
