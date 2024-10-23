<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Contacts
            </h4>
        </div>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
            <button wire:click="toggleAddSection"
                class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                New contact
            </button>
        </div>
    </div>


    <div class="card">
        <header class="card-header cust-card-header noborder">
            <iconify-icon wire:loading class="loading-icon text-lg" icon="line-md:loading-twotone-loop"></iconify-icon>
            <input type="text" class="form-control !pl-9 mr-1 basis-1/4"
                placeholder="Search by name, email or phone number" wire:model="search">
        </header>


        {{-- <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 no-wrap">
                            <thead class=" border-t border-slate-100 dark:border-slate-800 bg-slate-200 dark:bg-slate-700">
                                <tr>

                                    <th scope="col" class=" table-th ">
                                        Name
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Job title
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Email
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Phone 1
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Phone 2
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Home 1
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Home 2
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Work 1
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Work 2
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Street
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        District
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Governate
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Country
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Url
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Image
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                @foreach ($contacts as $contact)
                                    <tr wire:click="editThisContact({{ $contact->id }})" class="hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer">

                                        <td class="table-td ">
                                            <b>{{ $contact->first_name }} {{ $contact->last_name }}</b>
                                        </td>

                                        <td class="table-td ">
                                            {{ $contact->job_title }}
                                        </td>

                                        <td class="table-td ">
                                            {{ $contact->email }}
                                        </td>

                                        <td class="table-td ">
                                            {{ $contact->mob_number1 }}
                                        </td>

                                        <td class="table-td ">
                                            {{ $contact->mob_number2 }}
                                        </td>

                                        <td class="table-td ">
                                            {{ $contact->home_number1 }}
                                        </td>

                                        <td class="table-td ">
                                            {{ $contact->home_number2 }}
                                        </td>

                                        <td class="table-td ">
                                            {{ $contact->work_number1 }}
                                        </td>

                                        <td class="table-td ">
                                            {{ $contact->work_number2 }}
                                        </td>

                                        <td class="table-td ">
                                            {{ $contact->address_street }}
                                        </td>

                                        <td class="table-td ">
                                            {{ $contact->address_district }}
                                        </td>

                                        <td class="table-td ">
                                            {{ $contact->address_governate }}
                                        </td>

                                        <td class="table-td ">
                                            {{ $contact->address_country }}
                                        </td>

                                        <td class="table-td ">
                                            {{ $contact->url }}
                                        </td>

                                        <td class="table-td ">
                                            {{ $contact->image }}
                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                        @if ($contacts->isEmpty())
                            <div class="card m-5 p-5">
                                <div class="card-body rounded-md bg-white dark:bg-slate-800">
                                    <div class="items-center text-center p-5">
                                        <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon></h2>
                                        <h2 class="card-title text-slate-900 dark:text-white mb-3">No Contacts info with the
                                            applied
                                            filters</h2>
                                        <p class="card-text">Try changing the filters or search terms for this view.
                                        </p>
                                        <a href="{{ url('/contacts') }}" class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">View
                                            all contacts info</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    {{ $contacts->links('vendor.livewire.bootstrap') }}

                </div>
            </div>
        </div> --}}
    </div>

    <div class="vcard-container grid grid-cols-3 md:grid-cols-3 lg:grid-cols-3 gap-6 mt-5">
        @foreach ($contacts as $contact)
            <div class="card p-5" style="text-align: -webkit-center;">
                <div class="dropstart relative float-right">
                    <button class="inline-flex justify-center items-center" type="button"  
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2"
                            icon="heroicons-outline:dots-vertical"></iconify-icon>
                    </button>
                    <ul
                        class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                        <li wire:click="editThisContact({{ $contact->id }})">
                            <a
                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                <iconify-icon icon="bx:edit-alt"></iconify-icon>
                                <span>Edit</span></a>
                        </li>
                        <li wire:click="generateQR({{ $contact->id }})">
                            <a
                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                <iconify-icon icon="bx:qr"></iconify-icon>
                                <span>Generate QR Code</span></a>
                        </li>
                        <li wire:click="downloadVcard({{ $contact->id }})">
                            <a
                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                <iconify-icon icon="material-symbols:download"></iconify-icon>
                                <span>Download Vcard</span></a>
                        </li>
                    </ul>
                </div>
                @if ($contact->image)
                    <img src="https://wiseins.s3.eu-north-1.amazonaws.com/{{ preg_replace('/\//', '', $contact->image, 1) }}"
                        alt="First Name Last Name" width="150px" style="border-radius: 50%">
                @else
                    <img src="{{ asset('assets\images\avatar\user.png') }}" alt="First Name Last Name" width="150px">
                @endif

                <div class="text-center">
                    <h6 class="mt-2 mb-2">{{ ucwords($contact->first_name) }} {{ ucwords($contact->last_name) }}</h6>

                    @if ($contact->job_title || $contact->email || $contact->company)
                        <hr>
                        @if ($contact->job_title)
                            <p class="mt-2 text-slate-900 dark:text-slate-300"><iconify-icon
                                    icon="mdi:user"></iconify-icon>{{ $contact->job_title }}</p>
                        @endif
                        @if ($contact->email)
                            <p class="mb-2 text-slate-900 dark:text-slate-300"><iconify-icon
                                    icon="mdi:envelope"></iconify-icon> {{ $contact->email }}</p>
                        @endif
                        @if ($contact->company)
                            <p class="mb-2 text-slate-900 dark:text-slate-300"><iconify-icon
                                    icon="mdi:building"></iconify-icon> {{ $contact->company }}</p>
                        @endif
                    @endif
                    <hr>
                    <div class="grid grid-cols-3 md:grid-cols-3 lg:grid-cols-3 gap-6 m-4">
                        <!-- Container for phone numbers of type 1 -->
                        @if ($contact->mob_number1 || $contact->mob_number2)
                            <div class="text-center">
                                <p class="text-slate-900 dark:text-slate-300"><iconify-icon
                                        icon="ic:baseline-phone"></iconify-icon></p>
                                <p class="text-slate-900 dark:text-slate-300">{{ $contact->mob_number1 }}</p>
                                <p class="text-slate-900 dark:text-slate-300">{{ $contact->mob_number2 }}</p>
                            </div>
                        @endif


                        <!-- Container for phone numbers of type 2 -->
                        @if ($contact->home_number1 || $contact->home_number2)
                            <div>
                                <p class="text-slate-900 dark:text-slate-300"><iconify-icon
                                        icon="mdi:phone-classic"></iconify-icon></p>
                                <p class="text-slate-900 dark:text-slate-300">{{ $contact->home_number1 }}</p>
                                <p class="text-slate-900 dark:text-slate-300">{{ $contact->home_number2 }}</p>
                            </div>
                        @endif

                        <!-- Container for phone numbers of type 3 -->
                        @if ($contact->work_number1 || $contact->work_number2)
                            <div>
                                <p class="text-slate-900 dark:text-slate-300"><iconify-icon
                                        icon="emojione-monotone:telephone-receiver"></iconify-icon></p>
                                <p class="text-slate-900 dark:text-slate-300">{{ $contact->work_number1 }}</p>
                                <p class="text-slate-900 dark:text-slate-300">{{ $contact->work_number2 }}</p>
                            </div>
                        @endif
                    </div>
                    <hr>

                    <p class="text-slate-900 dark:text-slate-300 mt-2"><iconify-icon icon="mdi:location"></iconify-icon>
                        @if ($contact->address_street)
                            {{ $contact->address_street }}
                        @endif
                        @if ($contact->address_district)
                            , {{ $contact->address_district }}
                        @endif
                        <iconify-icon icon="mdi:dot" width="1.2em" height="1.2em"></iconify-icon>
                        {{ $contact->address_governate }}
                        @if ($contact->address_district)
                            ,{{ $contact->address_country }}
                        @endif
                    </p>
                    <!-- Include icons and other contact information -->
                </div>
            </div>
        @endforeach
    </div>

    @if ($contacts->isEmpty())
        <div class="card mt-5">
            <div class="card-body rounded-md bg-white dark:bg-slate-800">
                <div class="items-center text-center p-5">
                    <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon></h2>
                    <h2 class="card-title text-slate-900 dark:text-white mb-3">No Contacts info with the
                        applied
                        filters</h2>
                    <p class="card-text">Try changing the filters or search terms for this view.
                    </p>
                    <a href="{{ url('/contacts') }}"
                        class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">View
                        all contacts info</a>
                </div>
            </div>
        </div>
    @endif

    @if ($addContactSec)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none" style="max-width: 800px;">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Add Contact
                            </h3>
                            <button wire:click="toggleAddSection" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
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
                            <div class="from-group">

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                    <div class="input-area">
                                        <label for="first_name" class="form-label">First name</label>
                                        <input id="first_name" type="text"
                                            class="form-control @error('first_name') !border-danger-500 @enderror"
                                            wire:model.defer="first_name">
                                        @error('first_name')
                                            <span
                                                class="font-Inter text-sm text-danger-500 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="input-area">
                                        <label for="last_name" class="form-label">Last name</label>
                                        <input id="last_name" type="text"
                                            class="form-control @error('last_name') !border-danger-500 @enderror"
                                            wire:model.defer="last_name">
                                        @error('last_name')
                                            <span
                                                class="font-Inter text-sm text-danger-500 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                    <div class="input-area  mt-3">
                                        <label for="job_title" class="form-label">Job title</label>
                                        <input id="job_title" type="text"
                                            class="form-control @error('job_title') !border-danger-500 @enderror"
                                            wire:model.defer="job_title">
                                        @error('job_title')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="input-area  mt-3">
                                        <label for="company" class="form-label">Company</label>
                                        <input id="company" type="text"
                                            class="form-control @error('company') !border-danger-500 @enderror"
                                            wire:model.defer="company">
                                        @error('company')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="input-area mt-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input id="email" type="text"
                                        class="form-control @error('email') !border-danger-500 @enderror"
                                        wire:model.defer="email">
                                    @error('email')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                    <div class="input-area">
                                        <label for="mob_number1" class="form-label">Mobile 1</label>
                                        <input id="mob_number1" type="text"
                                            class="form-control @error('mob_number1') !border-danger-500 @enderror"
                                            wire:model.defer="mob_number1">
                                        @error('mob_number1')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="input-area">
                                        <label for="mob_number2" class="form-label">Mobile 2</label>
                                        <input id="mob_number2" type="text"
                                            class="form-control @error('mob_number2') !border-danger-500 @enderror"
                                            wire:model.defer="mob_number2">
                                        @error('mob_number2')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                    <div class="input-area">
                                        <label for="home_number1" class="form-label">Home number 1</label>
                                        <input id="home_number1" type="text"
                                            class="form-control @error('home_number1') !border-danger-500 @enderror"
                                            wire:model.defer="home_number1">
                                        @error('home_number1')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="input-area">
                                        <label for="home_number2" class="form-label">Hotline</label>
                                        <input id="home_number2" type="text"
                                            class="form-control @error('home_number2') !border-danger-500 @enderror"
                                            wire:model.defer="home_number2">
                                        @error('home_number2')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                    <div class="input-area">
                                        <label for="work_number1" class="form-label">Work number 1</label>
                                        <input id="work_number1" type="text"
                                            class="form-control @error('work_number1') !border-danger-500 @enderror"
                                            wire:model.defer="work_number1">
                                        @error('work_number1')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="input-area">
                                        <label for="work_number2" class="form-label">Work number 2</label>
                                        <input id="work_number2" type="text"
                                            class="form-control @error('work_number2') !border-danger-500 @enderror"
                                            wire:model.defer="work_number2">
                                        @error('work_number2')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="input-area mt-3">
                                    <label for="address_street" class="form-label">Address street</label>
                                    <input id="address_street" type="text"
                                        class="form-control @error('address_street') !border-danger-500 @enderror"
                                        wire:model.defer="address_street">
                                    @error('address_street')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 mt-3">
                                    <div class="input-area">
                                        <label for="address_district" class="form-label">District</label>
                                        <input id="address_district" type="text"
                                            class="form-control @error('address_district') !border-danger-500 @enderror"
                                            wire:model.defer="address_district">
                                        @error('address_district')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="input-area">
                                        <label for="address_governate" class="form-label">Governerate</label>
                                        <input id="address_governate" type="text"
                                            class="form-control @error('address_governate') !border-danger-500 @enderror"
                                            wire:model.defer="address_governate">
                                        @error('address_governate')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="input-area">
                                        <label for="address_country" class="form-label">Country</label>
                                        <input id="address_country" type="text"
                                            class="form-control @error('address_country') !border-danger-500 @enderror"
                                            wire:model.defer="address_country">
                                        @error('address_country')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="input-area mt-3">
                                    <label for="url" class="form-label">Url</label>
                                    <input id="url" type="text"
                                        class="form-control @error('url') !border-danger-500 @enderror"
                                        wire:model.defer="url">
                                    @error('url')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="input-area mt-3">
                                    <label for="image" class="form-label">Image</label>
                                    <input id="image" type="file"
                                        class="form-control @error('image') !border-danger-500 @enderror"
                                        wire:model.defer="image">
                                    @error('image')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>


                            </div>
                        </div>


                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addContact" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($contactId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none" style="max-width: 800px;">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Edit Contact
                            </h3>
                            <button wire:click="closeEditSec" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
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
                            <div class="from-group">

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                    <div class="input-area">
                                        <label for="first_name" class="form-label">First name</label>
                                        <input id="first_name" type="text"
                                            class="form-control @error('first_name') !border-danger-500 @enderror"
                                            wire:model.defer="first_name">
                                        @error('first_name')
                                            <span
                                                class="font-Inter text-sm text-danger-500 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="input-area">
                                        <label for="last_name" class="form-label">Last name</label>
                                        <input id="last_name" type="text"
                                            class="form-control @error('last_name') !border-danger-500 @enderror"
                                            wire:model.defer="last_name">
                                        @error('last_name')
                                            <span
                                                class="font-Inter text-sm text-danger-500 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">

                                    <div class="input-area  mt-3">
                                        <label for="job_title" class="form-label">Job title</label>
                                        <input id="job_title" type="text"
                                            class="form-control @error('job_title') !border-danger-500 @enderror"
                                            wire:model.defer="job_title">
                                        @error('job_title')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="input-area  mt-3">
                                        <label for="company" class="form-label">Company</label>
                                        <input id="company" type="text"
                                            class="form-control @error('company') !border-danger-500 @enderror"
                                            wire:model.defer="company">
                                        @error('company')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="input-area mt-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input id="email" type="text"
                                        class="form-control @error('email') !border-danger-500 @enderror"
                                        wire:model.defer="email">
                                    @error('email')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                    <div class="input-area">
                                        <label for="mob_number1" class="form-label">Mobile 1</label>
                                        <input id="mob_number1" type="text"
                                            class="form-control @error('mob_number1') !border-danger-500 @enderror"
                                            wire:model.defer="mob_number1">
                                        @error('mob_number1')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="input-area">
                                        <label for="mob_number2" class="form-label">Mobile 2</label>
                                        <input id="mob_number2" type="text"
                                            class="form-control @error('mob_number2') !border-danger-500 @enderror"
                                            wire:model.defer="mob_number2">
                                        @error('mob_number2')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                    <div class="input-area">
                                        <label for="home_number1" class="form-label">Home number 1</label>
                                        <input id="home_number1" type="text"
                                            class="form-control @error('home_number1') !border-danger-500 @enderror"
                                            wire:model.defer="home_number1">
                                        @error('home_number1')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="input-area">
                                        <label for="home_number2" class="form-label">Hotline</label>
                                        <input id="home_number2" type="text"
                                            class="form-control @error('home_number2') !border-danger-500 @enderror"
                                            wire:model.defer="home_number2">
                                        @error('home_number2')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                    <div class="input-area">
                                        <label for="work_number1" class="form-label">Work number 1</label>
                                        <input id="work_number1" type="text"
                                            class="form-control @error('work_number1') !border-danger-500 @enderror"
                                            wire:model.defer="work_number1">
                                        @error('work_number1')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="input-area">
                                        <label for="work_number2" class="form-label">Work number 2</label>
                                        <input id="work_number2" type="text"
                                            class="form-control @error('work_number2') !border-danger-500 @enderror"
                                            wire:model.defer="work_number2">
                                        @error('work_number2')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="input-area mt-3">
                                    <label for="address_street" class="form-label">Address street</label>
                                    <input id="address_street" type="text"
                                        class="form-control @error('address_street') !border-danger-500 @enderror"
                                        wire:model.defer="address_street">
                                    @error('address_street')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 mt-3">
                                    <div class="input-area">
                                        <label for="address_district" class="form-label">District</label>
                                        <input id="address_district" type="text"
                                            class="form-control @error('address_district') !border-danger-500 @enderror"
                                            wire:model.defer="address_district">
                                        @error('address_district')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="input-area">
                                        <label for="address_governate" class="form-label">Governerate</label>
                                        <input id="address_governate" type="text"
                                            class="form-control @error('address_governate') !border-danger-500 @enderror"
                                            wire:model.defer="address_governate">
                                        @error('address_governate')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="input-area">
                                        <label for="address_country" class="form-label">Country</label>
                                        <input id="address_country" type="text"
                                            class="form-control @error('address_country') !border-danger-500 @enderror"
                                            wire:model.defer="address_country">
                                        @error('address_country')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="input-area mt-3">
                                    <label for="url" class="form-label">Url</label>
                                    <input id="url" type="text"
                                        class="form-control @error('url') !border-danger-500 @enderror"
                                        wire:model.defer="url">
                                    @error('url')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Image</label>
                                    @if (!$image)
                                        <input wire:model="image" type="file" class="form-control w-full "
                                            name="basic" />
                                    @else
                                        <span class="block min-w-[140px] text-left">
                                            <span class="inline-block text-center text-sm mx-auto py-1">
                                                <span class="flex items-center space-x-3 rtl:space-x-reverse">
                                                    <span
                                                        class="h-[6px] w-[6px] bg-success-500 rounded-full inline-block ring-4 ring-opacity-30 ring-success-500"></span>
                                                    <span>
                                                        Document added
                                                        <span wire:click="clearImage"
                                                            class="text-xs text-slate-500 dark:text-slate-400 mt-1 cursor-pointer">|
                                                            remove</span>
                                                    </span>
                                                </span>
                                            </span>
                                        </span>
                                    @endif

                                </div>
                                @error('image')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror


                                {{-- <div class="input-area mt-3">
                                    <label for="image" class="form-label">Image</label>
                                    <input id="image" type="file" class="form-control @error('image') !border-danger-500 @enderror" wire:model.defer="image">
                                    @error('image')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div> --}}


                            </div>
                        </div>


                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editInfo" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


</div>
