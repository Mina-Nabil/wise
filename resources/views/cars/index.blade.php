@extends('layouts.app')

@section('cars')
    active
@endsection

@section('Breadcrumb')
    <!-- BEGIN: Breadcrumb -->
    {{-- <div class="mb-5">
        <ul class="m-0 p-0 list-none">
            <li class="inline-block relative top-[3px] text-base text-primary-500 font-Inter ">
                <a href="index.html">
                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                    <iconify-icon icon="heroicons-outline:chevron-right"
                        class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                </a>
            </li>
            <li class="inline-block relative text-sm text-primary-500 font-Inter ">
                Home
                <iconify-icon icon="heroicons-outline:chevron-right"
                    class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
            </li>
            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                Cars</li>
        </ul>
    </div> --}}
    <!-- END: BreadCrumb -->
@endsection

@section('content')
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
        id="blackModal" tabindex="-1" aria-labelledby="blackModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog relative w-auto pointer-events-none">
            <div
                class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                        <h3 class="text-base text-white dark:text-white capitalize">
                            <ul class="m-0 p-0 list-none">
                                <li class="inline-block relative top-[3px] text-base font-Inter ">
                                    ALFA ROMIO
                                    <iconify-icon icon="heroicons-outline:chevron-right"
                                        class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                                </li>
                                <li class="inline-block relative top-[3px] text-base font-Inter ">
                                    Giulia
                                    <iconify-icon icon="heroicons-outline:chevron-right"
                                        class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                                </li>
                                <li
                                    class="inline-block relative text-sm top-[3px] text-slate-500 font-Inter dark:text-white">
                                    2.0 A/T H/L Turbo</li>
                            </ul>
                        </h3>
                        <button type="button"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
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
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="bg-slate-200 dark:bg-slate-700">
                                <tr>

                                    <th scope="col" class=" table-th ">
                                        Year
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Price
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Action
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                <tr>
                                    <td class="table-td">2017</td>
                                    <td class="table-td text-success-500">563,000</td>
                                    <td class="table-td ">
                                        <span
                                            class="flex-none space-x-2 text-base text-secondary-500 flex rtl:space-x-reverse">
                                            <button type="button" class="text-slate-400">
                                                <iconify-icon icon="heroicons-outline:pencil-alt"></iconify-icon>
                                            </button>
                                            <button type="button"
                                                class="transition duration-150 hover:text-danger-500 text-slate-400  delete-button">
                                                <iconify-icon icon="heroicons-outline:trash"></iconify-icon>
                                            </button>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-td">2017</td>
                                    <td class="table-td text-success-500">563,000</td>
                                    <td class="table-td ">
                                        <span
                                            class="flex-none space-x-2 text-base text-secondary-500 flex rtl:space-x-reverse">
                                            <button type="button" class="text-slate-400">
                                                <iconify-icon icon="heroicons-outline:pencil-alt"></iconify-icon>
                                            </button>
                                            <button type="button"
                                                class="transition duration-150 hover:text-danger-500 text-slate-400  delete-button">
                                                <iconify-icon icon="heroicons-outline:trash"></iconify-icon>
                                            </button>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-td">2014</td>
                                    <td class="table-td text-success-500">267,000</td>
                                    <td class="table-td ">
                                        <span
                                            class="flex-none space-x-2 text-base text-secondary-500 flex rtl:space-x-reverse">
                                            <button type="button" class="text-slate-400">
                                                <iconify-icon icon="heroicons-outline:pencil-alt"></iconify-icon>
                                            </button>
                                            <button type="button"
                                                class="transition duration-150 hover:text-danger-500 text-slate-400  delete-button">
                                                <iconify-icon icon="heroicons-outline:trash"></iconify-icon>
                                            </button>
                                        </span>
                                    </td>
                                </tr>



                            </tbody>
                        </table>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button data-bs-dismiss="modal"
                            class="btn inline-flex justify-center text-white bg-black-500">Accept</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">Cars</h4>
            <!---->
        </div>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">

            <button data-bs-toggle="modal" data-bs-target="#successModal"
                class="btn inline-flex justify-center btn-outline-success capitalize">Add new Car</button>



            <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
                id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                <div class="modal-dialog relative w-auto pointer-events-none">
                    <div
                        class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                                rounded-md outline-none text-current">
                        <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                            <!-- Modal header -->
                            <div
                                class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-success-500">
                                <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                    Add new Car


                                </h3>
                                <button type="button"
                                    class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                            dark:hover:bg-slate-600 dark:hover:text-white"
                                    data-bs-dismiss="modal">
                                    <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewbox="0 0 20 20"
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

                                <div>
                                    <label for="basicSelect" class="form-label">Mark Name</label>
                                    <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2">
                                        <option selected="Selected" disabled="disabled" value="none"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">Select
                                            an option</option>
                                        <option value="option1"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">Option
                                            1
                                        </option>
                                        <option value="option2"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">Option
                                            2
                                        </option>
                                        <option value="option3"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">Option
                                            3
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label for="basicSelect" class="form-label">Model Name</label>
                                    <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2"
                                        disabled>
                                        <option selected="Selected" disabled="disabled" value="none"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">Select
                                            an option</option>
                                        <option value="option1"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">Option
                                            1
                                        </option>
                                        <option value="option2"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">Option
                                            2
                                        </option>
                                        <option value="option3"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">Option
                                            3
                                        </option>
                                    </select>
                                </div>
                                <div class="input-area">
                                    <label for="name" class="form-label">Project Name*</label>
                                    <input id="name" type="text" class="form-control" placeholder="Project Name"
                                        disabled>
                                </div>
                            </div>
                            <!-- Modal footer -->
                            <div
                                class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                <button data-bs-dismiss="modal"
                                    class="btn inline-flex justify-center text-white bg-success-500">Accept</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="card">
        <header class="card-header cust-card-header noborder">
            <input type="text" class="form-control !pl-9 mr-1 basis-1/4" placeholder="Search">
            <div data-select2-id="select2-data-17-14zv py-1 text-sm">
                <select name="select2basic" id="select2basic"
                    class="select2 form-control w-full mt-2 py-2 select2-hidden-accessible"
                    data-select2-id="select2-data-select2basic" tabindex="-1" aria-hidden="true">
                    <option value="All" class=" inline-block font-Inter font-normal text-sm text-slate-600"
                        data-select2-id="select2-data-2-yt7a">All
                    </option>
                    <option value="option2" class=" inline-block font-Inter font-normal text-sm text-slate-600"
                        data-select2-id="select2-data-19-qp84">Option 2</option>
                    <option value="option3" class=" inline-block font-Inter font-normal text-sm text-slate-600"
                        data-select2-id="select2-data-20-ylak">Option 3</option>

                </select>
            </div>
        </header>

        {{-- <header class="card-header cust-card-header filter-padges">
            <button class="btn inline-flex justify-center btn-primary btn-sm">
                <span class="flex items-center">
                    <span>Aston Martin</span>
                    <iconify-icon class="ltr:mr-1 rtl:ml-1 ml-2" icon="zondicons:close-solid"></iconify-icon>
                </span>
            </button>
            <button class="btn inline-flex justify-center btn-primary btn-sm">
                <span class="flex items-center">
                    <span>Vantage Roadster</span>
                    <iconify-icon class="ltr:mr-1 rtl:ml-1 ml-2" icon="zondicons:close-solid"></iconify-icon>
                </span>
            </button>
            <!-- Rest of your content goes here if needed -->
        </header> --}}


        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class=" border-t border-slate-100 dark:border-slate-800 bg-slate-200 dark:bg-slate-700">
                                <tr>

                                    <th scope="col" class=" table-th ">
                                        Mark Name
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Model Name
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Category
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Prices
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                <tr>
                                    <td class="table-td">ALFA ROMIO</td>
                                    <td class="table-td">Giulia</td>
                                    <td class="table-td ">2.0 A/T H/L Turbo</td>
                                    <td class="table-td "><button data-bs-toggle="modal" data-bs-target="#blackModal"
                                            class="btn inline-flex justify-center btn-outline-dark capitalize btn-sm">Show
                                            Prices</button>

                                    </td>
                                </tr>



                            </tbody>

                        </table>

                    </div>
                    <div class="flex justify-center items-center border-t border-slate-100 dark:border-slate-700">
                        <div class="card-text h-full space-y-10 pt-4">
                            <ul class="list-none">
                                <li class="inline-block">
                                    <a href="#"
                                        class="flex items-center justify-center w-6 h-6 bg-slate-100 dark:bg-slate-700 dark:hover:bg-black-500 text-slate-800
                                        dark:text-white rounded mx-[3px] sm:mx-1 hover:bg-black-500 hover:text-white text-sm font-Inter font-medium transition-all
                                        duration-300 relative top-[2px] pl-2">
                                        <iconify-icon icon="material-symbols:arrow-back-ios-rounded"></iconify-icon>
                                    </a>
                                </li>
                                <li class="inline-block">
                                    <a href="#"
                                        class="flex items-center justify-center w-6 h-6 bg-slate-100 text-slate-800
                                        dark:text-white rounded mx-[3px] sm:mx-1 hover:bg-black-500 hover:text-white text-sm font-Inter font-medium transition-all
                                        duration-300 p-active">
                                        1</a>
                                </li>
                                <li class="inline-block">
                                    <a href="#"
                                        class="flex items-center justify-center w-6 h-6 bg-slate-100 dark:bg-slate-700 dark:hover:bg-black-500 text-slate-800
                                        dark:text-white rounded mx-[3px] sm:mx-1 hover:bg-black-500 hover:text-white text-sm font-Inter font-medium transition-all
                                        duration-300 ">
                                        2</a>
                                </li>
                                <li class="inline-block">
                                    <a href="#"
                                        class="flex items-center justify-center w-6 h-6 bg-slate-100 dark:bg-slate-700 dark:hover:bg-black-500 text-slate-800
                                        dark:text-white rounded mx-[3px] sm:mx-1 hover:bg-black-500 hover:text-white text-sm font-Inter font-medium transition-all
                                        duration-300 ">
                                        3</a>
                                </li>
                                <li class="inline-block">
                                    <a href="#"
                                        class="flex items-center justify-center w-6 h-6 bg-slate-100 dark:bg-slate-700 dark:hover:bg-black-500 text-slate-800
                                        dark:text-white rounded mx-[3px] sm:mx-1 hover:bg-black-500 hover:text-white text-sm font-Inter font-medium transition-all
                                        duration-300 ">
                                        4</a>
                                </li>
                                <li class="inline-block">
                                    <a href="#"
                                        class="flex items-center justify-center w-6 h-6 bg-slate-100 dark:bg-slate-700 dark:hover:bg-black-500 text-slate-800
                                        dark:text-white rounded mx-[3px] sm:mx-1 hover:bg-black-500 hover:text-white text-sm font-Inter font-medium transition-all
                                        duration-300 ">
                                        5</a>
                                </li>
                                <li class="inline-block">
                                    <a href="#"
                                        class="flex items-center justify-center w-6 h-6 bg-slate-100 dark:bg-slate-700 dark:hover:bg-black-500 text-slate-800
                                        dark:text-white rounded mx-[3px] sm:mx-1 hover:bg-black-500 hover:text-white text-sm font-Inter font-medium transition-all
                                        duration-300 relative top-[2px]">
                                        <iconify-icon icon="material-symbols:arrow-forward-ios-rounded"></iconify-icon>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
