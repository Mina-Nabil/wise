<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Users Management
            </h4>
        </div>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
            @can('create', App\Models\Users\User::class)
                <button wire:click="openNewUserSec" class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                    Create user
                </button>
            @endcan

        </div>
    </div>
    <div class="card">
        <header class="card-header cust-card-header noborder">
            <iconify-icon wire:loading wire:target="search" class="loading-icon text-lg" icon="line-md:loading-twotone-loop"></iconify-icon>
            <input type="text" class="form-control !pl-9 mr-1 basis-1/4" placeholder="Search" wire:model="search">
        </header>

        <div class="card-body px-6 pb-6">
            <div class=" -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class=" border-t border-slate-100 dark:border-slate-800 bg-slate-200 dark:bg-slate-700">
                                <tr>

                                    <th scope="col" class=" table-th ">
                                        Name
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Username
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Type
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Phone
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Email
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Activated
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Action
                                    </th>


                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                @foreach ($users as $user)
                                    <tr>

                                        <td class="table-td flex items-center">
                                            <div class="rounded-full flex-shrink-0 ltr:mr-[10px] rtl:ml-[10px]">
                                                @if ($user->image)
                                                    <img src="{{ Illuminate\Support\Facades\Storage::disk('s3')->url(str_replace('//', '/', $user->image)) }}" alt="user" class="h-8 lg:h-8 w-8 lg:w-8 rounded-full object-cover">
                                                @else
                                                    <span class="block w-8 h-8 lg:w-8 lg:h-8 object-cover text-center text-lg leading-8 user-initial">
                                                        {{ strtoupper(substr($user->username, 0, 1)) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <span>{{ $user->first_name }} {{ $user->last_name }}</span>
                                        </td>



                                        <td class="table-td">
                                            {{ $user->username }}
                                        </td>

                                        <td class="table-td ">
                                            {{ ucwords(str_replace('_', ' ', $user->type)) }}
                                        </td>

                                        <td class="table-td">
                                            {{ $user->phone }}
                                        </td>

                                        <td class="table-td">
                                            {{ $user->email }}
                                        </td>

                                        <td class="table-td">
                                            @if ($user->is_active)
                                                <span class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize rounded-3xl">Active</span>
                                            @else
                                                <span class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize rounded-3xl">Deactivated</span>
                                            @endif

                                        </td>

                                        <td>
                                            <div class="dropstart relative">
                                                <button class="inline-flex justify-center items-center" type="button" id="tableDropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                </button>
                                                <ul class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">

                                                    <li wire:click="updateThisUser({{ $user->id }})">
                                                        <span class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                            <iconify-icon icon="lucide:edit"></iconify-icon>
                                                            <span>Edit</span></span>
                                                    </li>

                                                    @if ($user->is_active)
                                                        <li wire:click="toggleUserStatus({{ $user->id }})">
                                                            <span class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                <iconify-icon icon="ant-design:stop-twotone"></iconify-icon>
                                                                <span>Set As Deactivated</span></span>
                                                        </li>
                                                    @else
                                                        <li wire:click="toggleUserStatus({{ $user->id }})">
                                                            <span class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                <iconify-icon icon="teenyicons:tick-circle-outline"></iconify-icon>
                                                                <span>Set As Active</span></span>
                                                        </li>
                                                    @endif



                                                </ul>
                                            </div>
                                        </td>


                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                        @if ($users->isEmpty())
                            {{-- START: empty filter result --}}
                            <div class="card m-5 p-5">
                                <div class="card-body rounded-md bg-white dark:bg-slate-800">
                                    <div class="items-center text-center p-5">
                                        <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon></h2>
                                        <h2 class="card-title text-slate-900 dark:text-white mb-3">No users with the
                                            applied
                                            filters</h2>
                                        <p class="card-text">Try changing the filters or search terms for this view.
                                        </p>
                                        <a href="{{ url('/users') }}" class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">View
                                            all users</a>
                                    </div>
                                </div>
                            </div>
                            {{-- END: empty filter result --}}
                        @endif
                    </div>
                    {{ $users->links('vendor.livewire.bootstrap') }}
                </div>
            </div>
        </div>
    </div>

    @can('create', App\Models\Users\User::class)
        @if ($newUserSection)
            <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
                <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                    <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                        <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                            <!-- Modal header -->
                            <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                                <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                    Create new user
                                </h3>
                                <button wire:click="closeNewUserSec" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
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
                                    <div class="input-area">
                                        <label for="newUsername" class="form-label">Username</label>
                                        <input id="newUsername" type="text" class="form-control @error('newUsername') !border-danger-500 @enderror" wire:model.lazy="newUsername" autocomplete="off">
                                    </div>
                                    @error('newUsername')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="from-group">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                        <div class="input-area">
                                            <label for="newFirstName" class="form-label">First Name</label>
                                            <input id="newFirstName" type="text" class="form-control @error('newFirstName') !border-danger-500 @enderror" wire:model.defer="newFirstName" autocomplete="off">
                                        </div>
                                        <div class="input-area">
                                            <label for="newLastName" class="form-label">Last Name</label>
                                            <input id="newLastName" type="text" class="form-control @error('newLastName') !border-danger-500 @enderror" wire:model.defer="newLastName" autocomplete="off">
                                        </div>
                                    </div>
                                    @error('newFirstName')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror

                                    @error('newLastName')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="from-group">
                                    <label for="newType" class="form-label">Type</label>
                                    <select name="newType" id="newType" class="form-control w-full mt-2 @error('newType') !border-danger-500 @enderror" wire:model.defer="newType" autocomplete="off">
                                        <option>None</option>
                                        @foreach ($TYPES as $type)
                                            <option value="{{ $type }}">{{ ucwords(str_replace('_', ' ', $type)) }}</option>
                                        @endforeach
                                    </select>
                                    @error('newType')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="from-group">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                        <div class="input-area">
                                            <label for="newPassword" class="form-label">Password</label>
                                            <input id="newPassword" type="password" class="form-control @error('newPassword') !border-danger-500 @enderror" wire:model.defer="newPassword" autocomplete="off">
                                        </div>
                                        <div class="input-area">
                                            <label for="newPassword_confirmation" class="form-label">Confirm Password</label>
                                            <input id="newPassword_confirmation" type="password" class="form-control @error('newPassword_confirmation') !border-danger-500 @enderror" autocomplete="off" wire:model.defer="newPassword_confirmation">
                                        </div>
                                    </div>
                                    @error('newPassword')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror

                                    @error('newPassword_confirmation')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="from-group">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                        <div class="input-area">
                                            <label for="newPhone" class="form-label">Phone</label>
                                            <input id="newPhone" type="text" class="form-control @error('newPhone') !border-danger-500 @enderror" wire:model.defer="newPhone" autocomplete="off">
                                        </div>
                                        <div class="input-area">
                                            <label for="newEmail" class="form-label">Email</label>
                                            <input id="newEmail" type="email" class="form-control @error('newEmail') !border-danger-500 @enderror" wire:model.defer="newEmail" autocomplete="off">
                                        </div>
                                    </div>
                                    @error('newPhone')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror

                                    @error('newEmail')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="from-group">
                                    <label for="newManagerId" class="form-label">Manager</label>
                                    <select name="newManagerId" id="newManagerId" class="form-control w-full mt-2 @error('newManagerId') !border-danger-500 @enderror" wire:model.defer="newManagerId" autocomplete="off">
                                        <option>None</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('newManagerId')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>


                            </div>
                            <!-- Modal footer -->
                            <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                <button wire:click="addNewUser" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                    <span wire:loading.remove wire:target="addNewUser">Submit</span>
                                    <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="addNewUser" icon="line-md:loading-twotone-loop"></iconify-icon>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endcan

    @can('create', App\Models\Users\User::class)
        @if ($updateUserSec)
            <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
                <div class="modal-dialog relative w-auto pointer-events-none">
                    <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                        <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                            <!-- Modal header -->
                            <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                                <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                    Update user
                                </h3>
                                <button wire:click="closeUpdateThisUser" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                    <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                                                                                                                                                                                                                                                                            11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <!-- Modal body -->
                            <div class="p-6 space-y-4">
                                <div class="flex-none flex items-center justify-center">
                                    <div class="md:h-[186px] md:w-[186px] h-[140px] w-[140px] md:ml-0 md:mr-0 ml-auto mr-auto md:mb-0 mb-4 rounded-full ring-4 ring-slate-100 @error('userImage') ring-danger-500 @enderror relative">
                                    <img @if ($userImage) src="@if (!is_string($this->userImage)) {{ $userImage->temporaryUrl() }} @else {{ $this->userImage }} @endif" @else src="{{ asset('assets/images/users/user-1.png') }}" @endif alt="" class="w-full h-full object-cover rounded-full">


                                        @if (!$userImage)
                                            <label for="userImage" class="absolute right-2 h-8 w-8 bg-slate-50 text-slate-600 rounded-full shadow-sm flex flex-col items-center justify-center md:top-[140px] top-[100px] cursor-pointer">
                                                <iconify-icon wire:loading.remove wire:target="userImage,clearImage" icon="heroicons:pencil-square"></iconify-icon>
                                                <iconify-icon wire:loading wire:target="userImage,clearImage" icon="svg-spinners:ring-resize"></iconify-icon>
                                            </label>
                                            <input wire:model="userImage" type="file" name="userImage" id="userImage" style="display:none"  accept=".jpg, .jpeg, .png">

                                            
                                        @else
                                            <span wire:click="clearImage" class="absolute right-2 h-8 w-8 bg-slate-50 text-slate-600 rounded-full shadow-sm flex flex-col items-center justify-center md:top-[140px] top-[100px] cursor-pointer">
                                                <iconify-icon wire:loading.remove wire:target="userImage,clearImage" icon="mdi:remove"></iconify-icon>
                                                <iconify-icon wire:loading wire:target="userImage,clearImage" icon="svg-spinners:ring-resize"></iconify-icon>
                                            </span>
                                        @endif


                                    </div>
                                    
                                </div>
                                @error('userImage')
                                    <small class="mt-3 text-danger-500"> *Please upload an image file with JPEG, JPG, or PNG format and ensure it is under 1 MB in size.</small>
                                    @enderror


                                <div class="from-group">
                                    <div class="input-area">
                                        <label for="username" class="form-label">Username</label>
                                        <input id="username" type="text" class="form-control @error('username') !border-danger-500 @enderror" wire:model.lazy="username" autocomplete="off">
                                    </div>
                                    @error('username')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="from-group">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                        <div class="input-area">
                                            <label for="first_name" class="form-label">First Name</label>
                                            <input id="first_name" type="text" class="form-control @error('first_name') !border-danger-500 @enderror" wire:model.defer="first_name" autocomplete="off">
                                        </div>
                                        <div class="input-area">
                                            <label for="last_name" class="form-label">Last Name</label>
                                            <input id="last_name" type="text" class="form-control @error('last_name') !border-danger-500 @enderror" wire:model.defer="last_name" autocomplete="off">
                                        </div>
                                    </div>
                                    @error('first_name')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror

                                    @error('last_name')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="from-group">
                                    <label for="type" class="form-label">Type</label>
                                    <select name="type" id="type" class="form-control w-full mt-2 @error('type') !border-danger-500 @enderror" wire:model.defer="type" autocomplete="off">
                                        <option>None</option>
                                        @foreach ($TYPES as $type)
                                            <option value="{{ $type }}">{{ ucwords(str_replace('_', ' ', $type)) }}</option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="from-group">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                        <div class="input-area">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input id="phone" type="text" class="form-control @error('phone') !border-danger-500 @enderror" wire:model.defer="phone" autocomplete="off">
                                        </div>
                                        <div class="input-area">
                                            <label for="email" class="form-label">Email</label>
                                            <input id="email" type="email" class="form-control @error('email') !border-danger-500 @enderror" wire:model.defer="email" autocomplete="off">
                                        </div>
                                    </div>
                                    @error('phone')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror

                                    @error('email')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                            <!-- Modal footer -->
                            <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                <button wire:click="EditUser" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                    <span wire:loading.remove wire:target="EditUser">Submit</span>
                                    <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="EditUser" icon="line-md:loading-twotone-loop"></iconify-icon>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endcan
</div>
