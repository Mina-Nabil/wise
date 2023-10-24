<div>
    <div class="space-y-5 profile-page">
        <div class="profiel-wrap px-[35px] pb-10 md:pt-[60px] pt-10 rounded-lg bg-white dark:bg-slate-800 lg:flex lg:space-y-0 space-y-6 justify-between items-end relative z-[1]">

            <div class="profile-box flex-none md:text-start text-center">
                <div class="md:flex items-end md:space-x-6 rtl:space-x-reverse">
                    <div class="flex-1">
                        <div class="text-2xl font-medium text-slate-900 dark:text-slate-200 mb-[3px]">
                            {{ auth()->user()->username }}
                        </div>
                        <div class="text-sm font-light text-slate-600 dark:text-slate-400">
                            {{ auth()->user()->type }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- end profile box -->
            <!-- profile info-500 -->
        </div>
        <div class="grid md:grid-cols-6 lg:grid-cols-2 gap-5">
            <div>
                <div class="card h-full">
                    <header class="card-header flex justify-between">
                        <h4 class="card-title">Info</h4>
                        @if ($changes)
                            <button type="submit" wire:click="saveInfo" class="btn inline-flex justify-center btn-success rounded-[25px] btn-sm mr-3">Save</button>
                        @endif
                    </header>
                    <div class="card-body p-6">
                        <ul class="list space-y-8">
                            <li class="flex space-x-3 rtl:space-x-reverse">
                                <div class="flex-none text-2xl text-slate-600 dark:text-slate-300">
                                    <iconify-icon icon="solar:user-broken"></iconify-icon>
                                </div>
                                <div class="flex-1">
                                    <div class="uppercase text-xs text-slate-500 dark:text-slate-300 mb-1 leading-[12px]">
                                        Username
                                    </div>
                                    <input id="name" type="text" class="form-control @error('username') !border-danger-500 @enderror" wire:model="username">
                                    @error('username')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </li>
                            <li class="flex space-x-3 rtl:space-x-reverse">
                                <div class="flex-none text-2xl text-slate-600 dark:text-slate-300">
                                    <iconify-icon icon="icon-park-outline:edit-name"></iconify-icon>
                                </div>
                                <div class="flex-1">
                                    <div class="uppercase text-xs text-slate-500 dark:text-slate-300 mb-1 leading-[12px]">
                                        First Name
                                    </div>
                                    <input id="first_name" type="text" class="form-control @error('firstName') !border-danger-500 @enderror" wire:model="firstName">
                                    @error('firstName')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="flex-1">
                                    <div class="uppercase text-xs text-slate-500 dark:text-slate-300 mb-1 leading-[12px]">
                                        Last Name
                                    </div>
                                    <input id="last_name" type="text" class="form-control @error('lastName') !border-danger-500 @enderror" wire:model="lastName">
                                    @error('lastName')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </li>

                            <li class="flex space-x-3 rtl:space-x-reverse">
                                <div class="flex-none text-2xl text-slate-600 dark:text-slate-300">
                                    <iconify-icon icon="heroicons:phone-arrow-up-right"></iconify-icon>
                                </div>
                                <div class="flex-1">
                                    <div class="uppercase text-xs text-slate-500 dark:text-slate-300 mb-1 leading-[12px]">
                                        PHONE
                                    </div>
                                    <input id="name" type="text" class="form-control @error('phone') !border-danger-500 @enderror" wire:model="phone">
                                    @error('phone')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </li>
                            <!-- end single list -->
                            <li class="flex space-x-3 rtl:space-x-reverse">
                                <div class="flex-none text-2xl text-slate-600 dark:text-slate-300">
                                    <iconify-icon icon="heroicons:envelope"></iconify-icon>
                                </div>
                                <div class="flex-1">
                                    <div class="uppercase text-xs text-slate-500 dark:text-slate-300 mb-1 leading-[12px]">
                                        Email
                                    </div>
                                    <input id="email" type="email" class="form-control @error('email') !border-danger-500 @enderror" wire:model="email">
                                    @error('email')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </li>
                            <!-- end single list -->
                        </ul>
                    </div>
                </div>
            </div>
            <div>
                <div class="card h-full">
                    <header class="card-header">
                        <h4 class="card-title">Security</h4>
                    </header>
                    <form wire:submit.prevent="changePassword">
                        <div class="card-body p-6">
                            <ul class="list space-y-8">
                                <li class="flex space-x-3 rtl:space-x-reverse">
                                    <div class="flex-none text-2xl text-slate-600 dark:text-slate-300">
                                        <iconify-icon icon="solar:lock-password-broken"></iconify-icon>
                                    </div>
                                    <div class="flex-1">
                                        <div class="uppercase text-xs text-slate-500 dark:text-slate-300 mb-1 leading-[12px]">
                                            Current Password
                                        </div>
                                        <input id="name" type="password" class="form-control @error('currentPassword') !border-danger-500 @enderror" wire:model="currentPassword">
                                        @error('currentPassword')
                                            <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </li>
                                <li class="flex space-x-3 rtl:space-x-reverse">
                                    <div class="flex-none text-2xl text-slate-600 dark:text-slate-300">
                                        <iconify-icon icon="solar:lock-password-bold-duotone"></iconify-icon>
                                    </div>
                                    <div class="flex-1">
                                        <div class="uppercase text-xs text-slate-500 dark:text-slate-300 mb-1 leading-[12px]">
                                            New Password
                                        </div>
                                        <input id="first_name" type="password" class="form-control @error('newPassword') !border-danger-500 @enderror" wire:model="newPassword">
                                        @error('newPassword')
                                            <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror

                                    </div>
                                </li>
                                {{-- <li class="flex space-x-3 rtl:space-x-reverse">
                                    <div class="flex-none text-2xl text-slate-600 dark:text-slate-300">
                                        <iconify-icon icon="solar:lock-password-bold-duotone"></iconify-icon>
                                    </div>
                                    <div class="flex-1">
                                        <div class="uppercase text-xs text-slate-500 dark:text-slate-300 mb-1 leading-[12px]">
                                            Confirm Password
                                        </div>
                                        <input id="name" type="password" class="form-control" wire:model="password_confirmation">
                                    </div>
                                </li> --}}
                                <!-- end single list -->
                                <li class="flex space-x-3 rtl:space-x-reverse float-right">
                                    <button class="btn inline-flex justify-center btn-success" type="submit">Change Password</button>
                                </li>
                                <!-- end single list -->
                            </ul>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        @can('create', App\Models\Users\User::class)
            <div class="md:grid-col-12">
                <div class="card h-full">
                    <header class="card-header">
                        <h4 class="card-title">Users</h4>
                    </header>


                    <div class="card-body px-6 pb-6">
                        <div class="overflow-x-auto -mx-6">
                            <div class="inline-block min-w-full align-middle">
                                <div class="overflow-hidden ">
                                    <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                        <thead class="bg-slate-200 dark:bg-slate-700">
                                            <tr>

                                                <th scope="col" class=" table-th ">
                                                    Username
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    Name
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
                                                    Action
                                                </th>

                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                            @foreach ($users as $user)
                                                <tr class="even:bg-slate-50 dark:even:bg-slate-700">
                                                    <td class="table-td">{{ $user->username }}</td>
                                                    <td class="table-td">{{ $user->first_name }} {{ $user->last_name }}</td>
                                                    <td class="table-td "><span class="badge bg-success-500 text-white capitalize">{{ $user->type }}</span></td>
                                                    <td class="table-td">{{ $user->phone }}</td>
                                                    <td class="table-td">{{ $user->email }}</td>
                                                    <td class="table-td ">

                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        @endcan
    </div>
</div>
