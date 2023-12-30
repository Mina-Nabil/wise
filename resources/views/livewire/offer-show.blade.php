<div>
    <div>
        <div class="max-w-screen-lg grid grid-cols-1 md:grid-cols-4 gap-5 mb-5">
            <div class="grid-cols-1 gap-5 mb-5 col-span-3">

                <div>
                    <p class="text-sm text-slate-400  font-light" wire:click="setStatus">
                        {{ ucwords($offer->client_type) }}
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <h5><b>{{ $offer->client->name }}</b>
                            @if ($offer->status === 'new')
                                <span class="badge bg-info-500 h-auto">
                                    <iconify-icon icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
                                </span>
                            @elseif(str_contains($offer->status, 'pending'))
                                <span class="badge bg-warning-500 h-auto">
                                    <iconify-icon icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
                                </span>
                            @elseif(str_contains($offer->status, 'declined') || str_contains($offer->status, 'cancelled'))
                                <span class="badge bg-danger-500 h-auto">
                                    <iconify-icon icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
                                </span>
                            @elseif($offer->status === 'approved')
                                <span class="badge bg-success-500 h-auto">
                                    <iconify-icon icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
                                </span>
                            @endif
                            <span class="badge bg-secondary-500 h-auto">
                                <iconify-icon icon="mdi:category"></iconify-icon>&nbsp; {{ ucwords(str_replace('_', ' ', $offer->type)) }}
                            </span>
                        </h5>
                        <div>
                            <div class="dropdown relative float-right">
                                <button class="btn btn-sm inline-flex justify-center btn-secondary items-center cursor-default relative !pr-14" type="button" id="secondarysplitDropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                    <span class="cursor-pointer absolute  h-full ltr:right-0 rtl:left-0 px-2 flex
                                                items-center justify-center leading-none">
                                        <iconify-icon class="leading-none text-xl" icon="ic:round-keyboard-arrow-down"></iconify-icon>
                                    </span>
                                </button>
                                <ul
                                    class=" dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow
                                            z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                    @foreach ($STATUSES as $status)
                                        @if (!($status === $offer->status))
                                            <li wire:click="setStatus('{{ $status }}')">
                                                <p class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                                                    Set As {{ ucwords(str_replace('_', ' ', $status)) }}
                                                </p>
                                            </li>
                                        @endif
                                    @endforeach

                                    <li>
                                        <a href="#"
                                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                                    dark:hover:text-white border-t border-slate-100 dark:border-slate-800">
                                            Delete Offer</a>
                                    </li>
                                </ul>
                            </div>
                            <a href="{{ route($offer->client_type . 's.show', $offer->client_id) }}" target="_blank">
                                <button wire:click="toggleEditInfo" class="btn inline-flex justify-center btn-secondary shadow-base2 float-right btn-sm mr-2">View {{ ucwords($offer->client_type) }}</button>
                            </a>
                        </div>
                    </div>
                    <p class="text-sm text-slate-400 font-light">
                        Created at {{ $offer->created_at->format('l d-m-Y') }}
                    </p>
                </div>

                <div class="rounded-md overlay mt-5">
                    <div class="card-body flex flex-col justify-center  bg-no-repeat bg-center bg-cover card p-4 active">
                        <div class="card-text flex flex-col justify-between h-full menu-open">
                            <p class="mb-2">
                                <b>Offered Item ( Car )</b>

                            </p>
                            <div class="card-body flex flex-col justify-between border rounded-lg h-full menu-open p-0 mb-5" style="border-color:rgb(224, 224, 224)">
                                <div class="break-words flex items-center my-1 m-4">
                                    <h3 class="text-base capitalize py-3">
                                        <ul class="m-0 p-0 list-none">
                                            <li class="inline-block relative top-[3px] text-base font-Inter ">
                                                {{ $offer->item->car_model->brand->name }}
                                                <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                                            </li>
                                            <li class="inline-block relative top-[3px] text-base font-Inter ">
                                                {{ $offer->item->car_model->name }}
                                                <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                                            </li>
                                            <li class="inline-block relative text-sm top-[3px] text-slate-500 font-Inter dark:text-white mr-5">
                                                {{ $offer->item->category }}
                                            </li>
                                        </ul>
                                    </h3>
                                    {{-- @if ($car->payment_frequency)
                                                <span class="badge bg-primary-500 text-primary-500 bg-opacity-30 capitalize rounded-3xl float-right">{{ $car->payment_frequency }} Payment</span>
                                            @endif --}}

                                    <div class="ml-auto">
                                        <div class="relative">
                                            <div class="dropdown relative">
                                                <button class="text-xl text-center block w-full " type="button" id="tableDropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <iconify-icon icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                </button>
                                                <ul
                                                    class=" dropdown-menu min-w-[120px] absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700
                                            shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                                    <li>
                                                        <button wire:click="toggleEditItem"
                                                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                                dark:hover:text-white">
                                                            Edit</button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <hr><br>
                                <div class="grid grid-cols-2 mb-4">
                                    <div class="ml-5">
                                        <p>
                                            <b>{{ $offer->item_title }}</b>
                                            <span class="float-right font-light text-lg">
                                                {{ number_format($offer->item_value, 0, '.', ',') }} EGP
                                            </span>
                                        </p>
                                        <p>
                                            {{ $offer->item_desc }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-md overlay mt-5">
                    <div class="card-body flex flex-col justify-center  bg-no-repeat bg-center bg-cover card p-4 active">
                        <div class="card-text flex flex-col justify-between h-full menu-open">
                            <p class="mb-2">
                                <b>Options ({{ $offer->options->count() }})</b>

                            </p>

                            @if ($offer->options->isEmpty())
                                <div class="text-center">
                                    <p class="text-center m-5 text-primary">No options added to this offer.</p>
                                    <button wire:click="toggleAddOption" class="btn inline-flex justify-center btn-dark btn-sm">Create option</button>
                                </div>
                            @else
                                @foreach ($offer->options as $option)
                                    {{-- card-body flex flex-col justify-between border rounded-lg h-full menu-open p-0 mb-5" style="border-color:rgb(224, 224, 224)" --}}
                                    <div class="card-body rounded-md bg-[#E5F9FF] dark:bg-slate-700 shadow-base mb-5">
                                        <div class="break-words flex items-center my-1 m-4">
                                            <h3 class="text-base capitalize py-3">
                                                {{ $option->policy->company->name }} - {{ $option->policy->name }} - {{ $option->policy->business }}

                                                @if ($option->payment_frequency)
                                                    <span class="badge bg-primary-500 text-primary-500 bg-opacity-30 capitalize rounded-3xl float-right">{{ $option->payment_frequency }} Payment</span>
                                                @endif

                                                @if ($option->status === 'new')
                                                    <span class="badge bg-info-500 mr-2  bg-opacity-30 h-auto">
                                                        <iconify-icon icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $option->status)) }}
                                                    </span>
                                                @elseif(str_contains($option->status, 'declined'))
                                                    <span class="badge bg-danger-500  mr-2 bg-opacity-30 h-auto">
                                                        <iconify-icon icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $option->status)) }}
                                                    </span>
                                                @elseif($option->status === 'approved')
                                                    <span class="badge bg-success-500  mr-2 bg-opacity-30 h-auto">
                                                        <iconify-icon icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $option->status)) }}
                                                    </span>
                                                @endif

                                            </h3>
                                            

                                            <div class="ml-auto">
                                                <div class="relative">
                                                    <div class="dropdown relative">
                                                        <button class="text-xl text-center block w-full " type="button" id="tableDropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <iconify-icon icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                        </button>
                                                        <ul class=" dropdown-menu min-w-[120px] absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                                            <li>
                                                                <button wire:click="openAddFieldSec({{ $option->id }})" class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                                    Add Field</button>
                                                            </li>
                                                            <li>
                                                                <label for="myFile" wire:click="uploadDocOptionId({{ $option->id }})"
                                                                    class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white cursor-pointer">
                                                                    Add Doc
                                                                </label>
                                                                <input type="file" id="myFile" name="filename" style="display: none;" wire:model="uploadedOptionFile">
                                                            </li>
                                                            <li>
                                                                <button wire:click="editThisOption({{ $option->id }})" class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                                    Edit</button>
                                                            </li>
                                                            <li>
                                                                <button wire:click="" class="text-slate-600 dark:text-white block font-Inter text-left font-normal w-full px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                                    Delete</button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="break-words flex items-center m-4 mt-0">
                                            <p class="">
                                                Insured Value: <span class="text-lg">{{ $option->insured_value }}</span>
                                            </p>
                                        </div>
                                        
                                        <hr><br>
                                        <div class="grid sm:gridcols-1 md:grid-cols-2 mb-4">
                                            <div class="border-r ml-5">
                                                <p><b>Fields ({{ $option->fields->count() }})</b></p>
                                                @if ($option->fields->isEmpty())
                                                    <p>No Fields added to this option.</p>
                                                @endif
                                                <ul class=" rounded p-4 min-w-[184px] space-y-5">

                                                    @foreach ($option->fields as $field)
                                                        <li class="flex justify-between text-xs text-slate-600 dark:text-slate-300">
                                                            <span class="flex space-x-2 rtl:space-x-reverse items-center">
                                                                <span class="inline-flex h-[6px] w-[6px] bg-primary-500 ring-opacity-25 rounded-full ring-4 bg-primary-500 ring-{{ ['info', 'secondary', 'success', 'primary'][array_rand(['info', 'secondary', 'success', 'primary'])] }}-500 "></span>
                                                                <span>{{ $field->name }}

                                                                </span>
                                                            </span>
                                                            <span>
                                                                <span class="text-lg">{{ number_format($field->value, 0, '.', ',') }}</span>
                                                                | <button type="button" wire:click="deleteOptionField({{ $field->id }})" class="font-normal text-xs text-slate-500 mt-1">
                                                                    Delete
                                                                </button>
                                                            </span>
                                                        </li>
                                                    @endforeach
                                                </ul>


                                            </div>
                                            <div class="ml-5">
                                                <p>
                                                    <b>Documents</b>
                                                    @if ($option->docs->isEmpty())
                                                        <p>No Documents added to this option.</p>
                                                    @endif
                                                    <br><br>

                                                    @if ($optionId === $option->id)
                                                        <span style="display: inline-block; align-items: center;" wire:loading wire:target="uploadedOptionFile"><iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" icon="line-md:loading-twotone-loop"></iconify-icon></span>
                                                    @endif
                                                </p>



                                                @foreach ($option->docs as $file)
                                                    <div class="flex space-x-2 rtl:space-x-reverse mb-3">
                                                        <div class="flex-1 flex space-x-2 rtl:space-x-reverse">
                                                            <div class="flex-none">
                                                                <div class="h-8 w-8">
                                                                    @php
                                                                        $extension = pathinfo($file->name, PATHINFO_EXTENSION);
                                                                        $icon = '';
                                                                        $view = false;

                                                                        switch ($extension) {
                                                                            case 'doc':
                                                                            case 'docx':
                                                                            case 'xls':
                                                                            case 'xlsx':
                                                                                $icon = 'pdf-2';

                                                                                break;

                                                                            case 'jpg':
                                                                            case 'jpeg':
                                                                            case 'png':
                                                                                $icon = 'scr-1';
                                                                                $view = true;
                                                                                break;

                                                                            case 'bmp':
                                                                            case 'gif':
                                                                            case 'svg':
                                                                            case 'webp':
                                                                                $icon = 'zip-1';
                                                                                break;

                                                                            case 'pdf':
                                                                                $icon = 'pdf-1';
                                                                                $view = true;
                                                                                break;
                                                                        }
                                                                    @endphp

                                                                    <img src="{{ asset('assets/images/icon/' . $icon . '.svg') }}" alt="" class="block w-full h-full object-cover rounded-full border hover:border-white border-transparent">
                                                                </div>

                                                            </div>
                                                            <div class="flex-1">
                                                                <span class="block text-slate-600 text-sm dark:text-slate-300" style="overflow-wrap: anywhere">
                                                                    {{ mb_strimwidth($file->name, 0, 30, '...') }}
                                                                </span>
                                                                <span class="block font-normal text-xs text-slate-500 mt-1">
                                                                    uploaded by
                                                                    {{ $file->user->first_name . ' ' . $file->user->last_name }} / <span class="cursor-pointer" wire:click="removeOptionFile({{ $file->id }})">remove</span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <button wire:click="downloadOptionDoc({{ $file->id }})" class="action-btn float-right text-xs border-blue-600" type="button" style="border-color: darkgrey;margin-right:10px">
                                                            <iconify-icon icon="ic:baseline-download"></iconify-icon>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div>
                                    <button wire:click="toggleAddOption" class="btn inline-flex justify-center btn-dark btn-sm">Add option</button>
                                </div>

                            @endif

                            {{-- <button wire:click="" class="btn inline-flex justify-center btn-light rounded-[25px] btn-sm float-right">Add car</button> --}}

                        </div>
                    </div>
                </div>

            </div>
            <div>
                <span class="badge bg-primary-500 h-auto w-full mb-5 text-white" style="padding: 10px">
                    <iconify-icon icon="mingcute:time-line"></iconify-icon>&nbsp;Due: {{ \Carbon\Carbon::parse($offer->due)->format('l d-m-Y h:ia') }}
                    <span class="ml-5 cursor-pointer" wire:click="toggleEditDue">
                        <iconify-icon icon="carbon:edit"></iconify-icon>
                    </span>
                </span>
                {{-- assignee --}}
                <div class="flex-1 rounded-md overlay mb-5">
                    <div class="card-body flex flex-col justify-center  bg-no-repeat bg-center bg-cover card p-4 active">
                        <div class="card-text flex flex-col justify-between h-full menu-open">
                            <p class="mb-2 text-wrap">
                                <b><iconify-icon icon="mdi:user"></iconify-icon> Assigned To</b><br>
                                <span class="mt-2">{{ $offer->assignee ? ucwords($offer->assignee->first_name) . ' ' . ucwords($offer->assignee->last_name) : 'No notes for this offer.' }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                {{-- End assignee --}}
                {{-- Notes --}}
                <div class="flex-1 rounded-md overlay">
                    <div class="card-body flex flex-col justify-center  bg-no-repeat bg-center bg-cover card p-4 active">
                        <div class="card-text flex flex-col justify-between h-full menu-open">
                            <p class="mb-2 text-wrap">
                                <b><iconify-icon icon="material-symbols:note"></iconify-icon> Notes</b><br>
                                <span class="mt-2">{{ $offer->note ?? 'No notes for this offer.' }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                {{-- End Notes --}}

                {{-- Files --}}
                <div class="card mt-5">
                    <div class="card-body flex flex-col p-6">
                        <header class="flex mb-5 items-center border-b border-slate-100 dark:border-slate-700 pb-5 -mx-6 px-6">
                            <div class="flex-1">
                                <div class="card-title text-slate-900 dark:text-white">
                                    <h6>files <iconify-icon wire:loading wire:target="downloadOfferFile" icon="svg-spinners:3-dots-move"></iconify-icon></h6>
                                </div>
                            </div>
                            <label for="myFile" class="custom-file-label cursor-pointer">
                                <span class="btn inline-flex justify-center btn-sm btn-outline-dark float-right">
                                    <span style="display: flex; align-items: center;"><iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="uploadedFile" icon="line-md:loading-twotone-loop"></iconify-icon></span>
                                    <span style="display: flex; align-items: center;"><iconify-icon wire:loading.remove wire:target="uploadedFile" icon="ic:baseline-upload"></iconify-icon>&nbsp;upload File</span>
                                </span>

                            </label>
                            <input type="file" id="myFile" name="filename" style="display: none;" wire:model="uploadedFile"><br>

                        </header>
                        <div class="loader" wire:loading wire:target="downloadFile">
                            <div class="loaderBar"></div>
                        </div>
                        @error('uploadedFile')
                            <span class="font-Inter text-danger-500 pt-2 inline-block text-xs">* {{ $message }}</span>
                        @enderror
                        <div class="card-body">
                            <!-- BEGIN: Files Card -->
                            <ul class="divide-y divide-slate-100 dark:divide-slate-700">

                                @if ($offer->files->isEmpty())
                                    <div class="text-center text-xs text-slate-500 dark:text-slate-400 mt-1">
                                        No files added to this offer.
                                    </div>
                                @endif

                                @foreach ($offer->files as $file)
                                    <li class="block py-[8px]">

                                        <div class="flex space-x-2 rtl:space-x-reverse">
                                            <div class="flex-1 flex space-x-2 rtl:space-x-reverse">
                                                <div class="flex-none">
                                                    <div class="h-8 w-8">
                                                        @php
                                                            $extension = pathinfo($file->name, PATHINFO_EXTENSION);
                                                            $icon = '';
                                                            $view = false;

                                                            switch ($extension) {
                                                                case 'doc':
                                                                case 'docx':
                                                                case 'xls':
                                                                case 'xlsx':
                                                                    $icon = 'pdf-2';

                                                                    break;

                                                                case 'jpg':
                                                                case 'jpeg':
                                                                case 'png':
                                                                    $icon = 'scr-1';
                                                                    $view = true;
                                                                    break;

                                                                case 'bmp':
                                                                case 'gif':
                                                                case 'svg':
                                                                case 'webp':
                                                                    $icon = 'zip-1';
                                                                    break;

                                                                case 'pdf':
                                                                    $icon = 'pdf-1';
                                                                    $view = true;
                                                                    break;
                                                            }
                                                        @endphp

                                                        <img src="{{ asset('assets/images/icon/' . $icon . '.svg') }}" alt="" class="block w-full h-full object-cover rounded-full border hover:border-white border-transparent">
                                                    </div>

                                                </div>
                                                <div class="flex-1">
                                                    <span class="block text-slate-600 text-sm dark:text-slate-300" style="overflow-wrap: anywhere">
                                                        {{ mb_strimwidth($file->name, 0, 30, '...') }}
                                                    </span>
                                                    <span class="block font-normal text-xs text-slate-500 mt-1">
                                                        uploaded by
                                                        {{ $file->user->first_name . ' ' . $file->user->last_name }} / <span class="cursor-pointer" onclick="confirm('Are you sure ?')" wire:click="removeOfferFile({{ $file->id }})">remove</span>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="flex-none">
                                                <span class="font-normal text-xs text-slate-500 mt-1"></span>
                                                <button wire:click="downloadOfferFile({{ $file->id }})" class="action-btn float-right mr-1 text-xs" type="button">
                                                    <iconify-icon icon="ic:baseline-download"></iconify-icon>
                                                </button>
                                                {{-- <button type="button" wire:click="downloadFile({{ $file->id }})" class="text-xs text-slate-900 dark:text-white">
                                                    Download
                                                </button> --}}
                                            </div>
                                        </div>
                                    </li>
                                @endforeach

                            </ul>
                            <!-- END: FIles Card -->
                        </div>
                        <div class="loader" wire:loading wire:target="previewFile">
                            <div class="loaderBar"></div>
                        </div>
                        @if ($preview)
                            <iframe src='{{ $preview }}' height='400px' frameborder='0'></iframe>
                        @endif
                        {{-- <iframe src='https://wiseins.s3.eu-north-1.amazonaws.com/tasks/GGxyo5OihDGEJnn6dW51XyQ2x9544vNDGBqCMMVj.pdf' height='400px' frameborder='0'></iframe> --}}
                    </div>
                </div>
                {{-- End Files --}}

                <br><br>

                {{-- Comments --}}
                <div>
                    <div>
                        Timeline
                    </div>
                    <div class="card mb-5" style="margin-bottom:50px">
                        <div class="card-body">
                            <div class="card-text h-full">
                                <div class="mt-5">
                                    <div class="text-slate-600 dark:text-slate-300 block w-full px-4 py-3 text-sm mb-2 last:mb-0">
                                        <div class="flex ltr:text-left rtl:text-right">
                                            <div class="flex-none ltr:mr-3 rtl:ml-3">
                                                <div class="h-8 w-8 rounded-full relative text-white bg-blue-500">

                                                    <span class="block w-full h-full object-cover text-center text-lg leading-8">
                                                        {{ strtoupper(substr('michael', 0, 1)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <input type="text" class="form-control border-0" placeholder="Leave a comment..." wire:model="newComment" wire:keydown.enter="addComment" style="border: none; box-shadow: 0 0 0px rgba(0, 0, 0, 0.5);">
                                            </div>
                                            <div class="">
                                                <button class="btn inline-flex justify-center btn-primary btn-sm" wire:click="addComment">
                                                    <span class="flex items-center">
                                                        <span>Post</span>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @foreach ($offer->comments as $comment)
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="card-text h-full">
                                    <div class="mt-5">
                                        <div class="text-slate-600 dark:text-slate-300 block w-full px-4 py-3 text-sm mb-2 last:mb-0">
                                            <div class="flex ltr:text-left rtl:text-right">
                                                <div class="flex-none ltr:mr-3 rtl:ml-3">
                                                    <div class="h-8 w-8 rounded-full relative text-white bg-blue-500">

                                                        <span class="block w-full h-full object-cover text-center text-lg leading-8">
                                                            {{ strtoupper(substr($comment->user?->username ?? 'System', 0, 1)) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <div class="text-slate-800 dark:text-slate-300 text-sm font-medium mb-1`">
                                                        {{ ucwords($comment->user->username) ?? 'System' }}
                                                    </div>
                                                    <div class="text-xs hover:text-[#68768A] font-normal text-slate-600 dark:text-slate-300">
                                                        {{ $comment->comment }}
                                                    </div>
                                                </div>
                                                <div class="">
                                                    <span class="flex items-center justify-center text-sm">
                                                        {{ $comment->created_at->format('Y-m-d H:i') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{-- End Comments --}}
            </div>
        </div>
    </div>


    @if ($editItemSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Edit Item
                            </h3>
                            <button wire:click="toggleEditItem" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
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
                                <label for="lastName" class="form-label">Car</label>
                                <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2" wire:model="carId">

                                    @foreach ($offer->client->cars as $car)
                                        <option value="{{ $car->car->id }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ $car->car->category }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="from-group">
                                <label for="lastName" class="form-label">Item title</label>
                                <input type="text" class="form-control mt-2 w-full" wire:model.defer="item_title">
                                @error('item_title')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="lastName" class="form-label">Item value</label>
                                <input type="number" class="form-control mt-2 w-full" wire:model.defer="item_value">
                                @error('item_value')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="lastName" class="form-label">Item Description</label>
                                <textarea class="form-control mt-2 w-full" wire:model.defer="item_desc"></textarea>
                                @error('item_desc')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editItem" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($addOptionSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Create Option
                            </h3>
                            <button wire:click="toggleAddOption" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6">
                            @error('conditionId')
                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @if ($policyId)
                                <label for="lastName" class="form-label" style="margin: 0">Policy</label>
                                <p>{{ $policyData->company->name }} | {{ $policyData->company->name }}</p><br>
                            @else
                                <div class="from-group">
                                    <label for="lastName" class="form-label">
                                        Search Policy
                                        <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="searchPolicy" icon="line-md:loading-twotone-loop"></iconify-icon>
                                    </label>
                                    <input type="text" class="form-control mt-2 w-full" wire:model="searchPolicy">
                                </div>
                                <div class="text-sm mt-0">
                                    @if ($policiesData)
                                        @foreach ($policiesData as $policy)
                                            <p><iconify-icon icon="material-symbols:policy"></iconify-icon> {{ $policy->company->name }} | {{ $policy->name }} | <Span wire:click="selectPolicy({{ $policy->id }})" class="cursor-pointer text-primary-500">Select Policy</Span></p>
                                        @endforeach

                                    @endif
                                </div>
                            @endif


                            @if ($policyConditions)
                                @if ($conditionId)
                                    <label for="lastName" class="form-label" style="margin:0">Condition</label>
                                    <p>{{ ucwords(str_replace('_', ' ', $conditionData->scope)) }}
                                        <b>
                                            {{ $conditionData->operator == 'gte' ? '>=' : ($conditionData->operator == 'gt' ? '>' : ($conditionData->operator == 'lte' ? '<=' : ($conditionData->operator == 'lt' ? '<' : ($conditionData->operator == 'e' ? '=' : '')))) }}
                                        </b>
                                        {{ $conditionData->value }} | Rate:{{ $conditionData->value }}
                                    </p>
                                    <br>
                                @else
                                    <div class="text-sm mt-0">
                                        @foreach ($policyConditions as $condition)
                                            <p><iconify-icon icon="material-symbols:policy"></iconify-icon>
                                                {{ ucwords(str_replace('_', ' ', $condition->scope)) }}
                                                <b>
                                                    {{ $condition->operator == 'gte' ? '>=' : ($condition->operator == 'gt' ? '>' : ($condition->operator == 'lte' ? '<=' : ($condition->operator == 'lt' ? '<' : ($condition->operator == 'e' ? '=' : '')))) }}
                                                </b>

                                                {{ $condition->value }} | Rate:{{ $condition->value }}

                                                <Span wire:click="selectCondition({{ $condition->id }})" class="cursor-pointer text-primary-500">Select Condition</Span>
                                            </p>
                                        @endforeach
                                    </div>
                                @endif
                            @endif


                            @if ($policyId || $conditionId)
                                <p class="text-sm m-3"><Span wire:click="clearPolicy" class="cursor-pointer text-primary-500">Clear policy</Span></p>
                            @endif


                            <div class="from-group">
                                <label for="lastName" class="form-label">Insured Value</label>
                                <input type="text" class="form-control mt-2 w-full" wire:model.defer="insured_value">
                                @error('insured_value')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="from-group">
                                <label for="lastName" class="form-label">Payment Frequency</label>
                                <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2" wire:model="payment_frequency">

                                    @foreach ($PAYMENT_FREQS as $freqs)
                                        <option value="{{ $freqs }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ $freqs }}
                                        </option>
                                    @endforeach

                                </select>
                                @error('payment_frequency')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addOption" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($editDueSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Edit Due Date
                            </h3>
                            <button wire:click="toggleEditDue" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                <div class="input-area mb-3">
                                    <label for="time-date-picker" class="form-label">Due Date</label>
                                    <input class="form-control py-2 flatpickr cursor-pointer flatpickr-input active @error('dueDate') !border-danger-500 @enderror" id="default-picker" type="date" wire:model.defer="dueDate" autocomplete="off">
                                    @error('dueDate')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="input-area mb-3">
                                    <label for="time-date-picker" class="form-label">Time </label>
                                    <input type="time" class="form-control  @error('dueTime') !border-danger-500 @enderror" id="appt" name="appt" min="09:00" max="18:00" wire:model.defer="dueTime" autocomplete="off" />
                                    {{-- <input class="form-control cursor-pointer py-2 flatpickr time flatpickr-input active @error('dueTime') !border-danger-500 @enderror" id="time-picker" data-enable-time="true" value="" type="text" wire:model.defer="dueTime" autocomplete="off"> --}}
                                    @error('dueTime')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror

                                </div>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editDue" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($addFieldSection_id)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Add Field
                            </h3>
                            <button wire:click="closeAddField" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">

                            <div class="input-area mb-3">
                                <label class="form-label">Field Name</label>
                                <input class="form-control py-2 @error('newFieldName') !border-danger-500 @enderror" id="default-picker" type="text" wire:model.defer="newFieldName" autocomplete="off">
                                @error('newFieldName')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-area mb-3">
                                <label class="form-label">Value </label>
                                <input type="text" class="form-control  @error('newFieldValue') !border-danger-500 @enderror" id="appt" name="appt" min="09:00" max="18:00" wire:model.defer="newFieldValue" autocomplete="off" />
                                {{-- <input class="form-control cursor-pointer py-2 flatpickr time flatpickr-input active @error('dueTime') !border-danger-500 @enderror" id="time-picker" data-enable-time="true" value="" type="text" wire:model.defer="dueTime" autocomplete="off"> --}}
                                @error('newFieldValue')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addField" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($editInfoSection)
    @endif

    @if ($editOptionId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Create Option
                            </h3>
                            <button wire:click="closeEditOption" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6">
                            <label for="lastName" class="form-label" style="margin: 0">Policy</label>
                            <p>{{ $policyData->company->name }} | {{ $policyData->company->name }}</p><br>

                            <label for="lastName" class="form-label" style="margin:0">Condition</label>
                            <p>{{ ucwords(str_replace('_', ' ', $conditionData->scope)) }}
                                <b>
                                    {{ $conditionData->operator == 'gte' ? '>=' : ($conditionData->operator == 'gt' ? '>' : ($conditionData->operator == 'lte' ? '<=' : ($conditionData->operator == 'lt' ? '<' : ($conditionData->operator == 'e' ? '=' : '')))) }}
                                </b>
                                {{ $conditionData->value }} | Rate:{{ $conditionData->value }}
                            </p>
                            <br>

                            <div class="from-group">
                                <label for="lastName" class="form-label">Insured Value</label>
                                <input type="text" class="form-control mt-2 w-full" wire:model.defer="insured_value">
                                @error('insured_value')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="from-group">
                                <label for="lastName" class="form-label">Payment Frequency</label>
                                <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2" wire:model="payment_frequency">

                                    @foreach ($PAYMENT_FREQS as $freqs)
                                        <option value="{{ $freqs }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ $freqs }}
                                        </option>
                                    @endforeach

                                </select>
                                @error('payment_frequency')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editOption" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


</div>
