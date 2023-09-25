@extends('layouts.app')

@section('content')
    <div class="space-y-5 profile-page">
        <div
            class="profiel-wrap px-[35px] pb-10 md:pt-[60px] pt-10 rounded-lg bg-white dark:bg-slate-800 lg:flex lg:space-y-0 space-y-6 justify-between items-end relative z-[1]">

            <div class="profile-box flex-none md:text-start text-center">
                <div class="md:flex items-end md:space-x-6 rtl:space-x-reverse">
                    <div class="flex-1">
                        <div class="text-2xl font-medium text-slate-900 dark:text-slate-200 mb-[3px]">
                            Michael rafaillo
                        </div>
                        <div class="text-sm font-light text-slate-600 dark:text-slate-400">
                            Admin
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
                    <header class="card-header">
                        <h4 class="card-title">Info</h4>
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
                                    <input id="name" type="text" class="form-control" value="michaelrofail">
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
                                    <input id="first_name" type="text" class="form-control" value="michael">
                                </div>
                                <div class="flex-1">
                                    <div class="uppercase text-xs text-slate-500 dark:text-slate-300 mb-1 leading-[12px]">
                                        Last Name
                                    </div>
                                    <input id="last_name" type="text" class="form-control" value="rafaillo">
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
                                    <input id="name" type="text" class="form-control" value="01282776814">
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
                                    <input id="email" type="email" class="form-control"
                                        value="michael.rafaillo@gmail.com">
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
                                    <input id="name" type="text" class="form-control">
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
                                    <input id="first_name" type="text" class="form-control">
                                </div>
                            </li>
                            <li class="flex space-x-3 rtl:space-x-reverse">
                                <div class="flex-none text-2xl text-slate-600 dark:text-slate-300">
                                    <iconify-icon icon="solar:lock-password-bold-duotone"></iconify-icon>
                                </div>
                                <div class="flex-1">
                                    <div class="uppercase text-xs text-slate-500 dark:text-slate-300 mb-1 leading-[12px]">
                                        Confirm Password
                                    </div>
                                    <input id="name" type="text" class="form-control">
                                </div>
                            </li>
                            <!-- end single list -->
                            <li class="flex space-x-3 rtl:space-x-reverse float-right">
                                <button class="btn inline-flex justify-center btn-success">Save Changes</button>
                            </li>
                            <!-- end single list -->
                        </ul>
                    </div>
                </div>
            </div>

        </div>
        @can('create', App\Models\Users\User::class)
        <div class="md:grid-col-12">
            <div class="card h-full">
                <header class="card-header">
                    <h4 class="card-title">Users and permissions</h4>
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
                                    <tbody
                                        class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                        <tr class="even:bg-slate-50 dark:even:bg-slate-700">
                                            <td class="table-td">michael</td>
                                            <td class="table-td">Michael Rafaillo</td>
                                            <td class="table-td "><span
                                                    class="badge bg-success-500 text-white capitalize">Admin</span></td>
                                            <td class="table-td">01282776814</td>
                                            <td class="table-td">Michael.Rafaillo@gmail.com</td>
                                            <td class="table-td ">
                                                <div>
                                                    <div class="relative">
                                                        <div class="dropdown relative">
                                                            <button class="text-xl text-center block w-full"
                                                                type="button" id="tableDropdownMenuButton1"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <iconify-icon
                                                                    icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                            </button>
                                                            <ul class="dropdown-menu min-w-[120px] absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none"
                                                                style="">
                                                                <li>
                                                                    <a href="#"
                                                                        class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                                  dark:hover:text-white">
                                                                        View</a>
                                                                </li>
                                                                <li>
                                                                    <a href="#"
                                                                        class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                                  dark:hover:text-white">
                                                                        Edit</a>
                                                                </li>
                                                                <li>
                                                                    <a href="#"
                                                                        class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                                  dark:hover:text-white">
                                                                        Delete</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr class="even:bg-slate-50 dark:even:bg-slate-700">

                                            <td class="table-td">Minabil</td>

                                            <td class="table-td">Mina Nabil</td>

                                            <td class="table-td "><span
                                                    class="badge bg-success-500 text-white capitalize">
                                                    Admin
                                                </span>
                                            </td>

                                            <td class="table-td">
                                                01225212014
                                            </td>

                                            <td class="table-td">
                                                mina9492@hotmail.com
                                            </td>

                                            <td class="table-td">

                                            </td>
                                        </tr>

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
@endsection
