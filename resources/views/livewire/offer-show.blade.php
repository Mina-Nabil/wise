<div>
    <div class="flex justify-center">
        <div class="max-w-screen-lg grid grid-cols-1 md:grid-cols-4 gap-5 mb-5">
            <div class="grid grid-cols-1 gap-5 mb-5 col-span-3">
                <div>
                    <p class="text-sm text-slate-400  font-light">
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
                            <iconify-icon icon="mdi:category"></iconify-icon>&nbsp;   {{ ucwords(str_replace('_', ' ', $offer->type)) }}
                        </span></h5>
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
                                    <li>
                                        <a href="#" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                                    dark:hover:text-white">
                                            Action</a>
                                    </li>
                                    <li>
                                        <a href="#" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                                    dark:hover:text-white">
                                            Another Action</a>
                                    </li>
                                    <li>
                                        <a href="#" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                                    dark:hover:text-white">
                                            Something else here</a>
                                    </li>
                                    <li>
                                        <a href="#"
                                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                                    dark:hover:text-white border-t border-slate-100 dark:border-slate-800">
                                            Sign out</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-slate-400 font-light">
                        Created at {{ $offer->created_at->format('l d-m-Y') }}
                    </p>
                    <p class="text-sm text-slate-400 font-light">
                        {{-- Due {{ $offer->due->format('l d-m-Y') }} --}}
                    </p>
                </div>
                <div class="flex-1 rounded-md overlay" style="min-width: 400px;">
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
                                                                <button wire:click="editThisCar({{ $offer->item->id }})"
                                                                    class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                                dark:hover:text-white">
                                                                    Edit</button>
                                                            </li>
                                                            <li>
                                                                <button wire:click="deleteThisCar({{ $offer->item->id }})"
                                                                    class="text-slate-600 dark:text-white block font-Inter text-left font-normal w-full px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                                dark:hover:text-white">
                                                                    Delete</button>
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
                                                    <span class="float-right font-light">
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
            </div>
            <div>

                <div class="flex-1 rounded-md overlay" style="min-width: 400px;">
                    <div class="card-body flex flex-col justify-center  bg-no-repeat bg-center bg-cover card p-4 active">
                        <div class="card-text flex flex-col justify-between h-full menu-open">
                            <p class="mb-2">
                                <b>Notes</b><br>
                                <span class="mt-2">{{ $offer->note ?? 'No notes for this offer.' }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card mt-5">
                    <div class="card-body flex flex-col p-6">
                        <header class="flex mb-5 items-center border-b border-slate-100 dark:border-slate-700 pb-5 -mx-6 px-6">
                            <div class="flex-1">
                                <div class="card-title text-slate-900 dark:text-white">
                                    <h6>files</h6>
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
                                                    <span class="block text-slate-600 text-sm dark:text-slate-300 ">
                                                        {{ mb_strimwidth($file->name, 0, 30, '...') }}
                                                    </span>
                                                    <span class="block font-normal text-xs text-slate-500 mt-1">
                                                        uploaded by
                                                        {{ $file->user->first_name . ' ' . $file->user->last_name }} / <span class="cursor-pointer" onclick="confirm('Are you sure ?')" wire:click="removeFile({{ $file->id }})">remove</span>
                                                    </span>
                                                </div>
                                            </div>
    
                                            <div class="flex-none">
                                                @if ($view)
                                                    <button type="button" wire:click="previewFile({{ $file->id }})" class="font-normal text-xs text-slate-500 mt-1">
                                                        Preview |
                                                    </button>
                                                @endif
                                                <span class="font-normal text-xs text-slate-500 mt-1"></span>
                                                <button type="button" wire:click="downloadFile({{ $file->id }})" class="text-xs text-slate-900 dark:text-white">
                                                    Download
                                                </button>
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
                                                        {{ $comment->user?->username ?? 'System' }}
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
            </div>
        </div>
    </div>
</div>
