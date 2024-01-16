<div>
    <div>
        <div class="flex justify-between flex-wrap items-center mb-6">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">Dashboard</h4>
            <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                <button class="btn leading-0 inline-flex justify-center bg-white text-slate-700 dark:bg-slate-800 dark:text-slate-300 !font-normal">
                    <span class="flex items-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="heroicons-outline:calendar"></iconify-icon>
                        <span>Weekly</span>
                    </span>
                </button>
                <button class="btn leading-0 inline-flex justify-center bg-white text-slate-700 dark:bg-slate-800 dark:text-slate-300 !font-normal">
                    <span class="flex items-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="heroicons-outline:filter"></iconify-icon>
                        <span>Select Date</span>
                    </span>
                </button>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-5 mb-5">
            <div class="2xl:col-span-3 lg:col-span-4 col-span-12">
                <div class="bg-no-repeat bg-cover bg-center p-4 rounded-[6px] relative" style="background-image: url(assets/images/all-img/widget-bg-1.png); height:100%;">
                    <div class="max-w-[180px]">
                        <div class="text-xl font-medium text-slate-900 mb-2">
                            Welcome,
                            {{ auth()->user()->first_name }}
                        </div>
                        <p class="text-sm text-slate-800">
                            You are doing great, check you least tasks
                        </p>
                    </div>
                    <a href="{{ route('tasks.index') }}">

                        <div class="absolute top-1/2 -translate-y-1/2 ltr:right-6 rtl:left-6 mt-2 h-12 w-12 bg-white rounded-full text-xs font-medium
                  flex flex-col items-center justify-center">
                            here
                        </div>
                    </a>
                </div>
            </div>
            <div class="2xl:col-span-9 lg:col-span-8 col-span-12">
                <div class="card p-6">
                    <div class="grid xl:grid-cols-4 lg:grid-cols-2 col-span-1 gap-3">

                        <!-- BEGIN: Group Chart4 -->


                        <div class="bg-warning-500 rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-25 relative z-[1]">
                            <div class="overlay absolute left-0 top-0 w-full h-full z-[-1]">
                                <img src="assets/images/all-img/shade-1.png" alt="" draggable="false" class="w-full h-full object-contain">
                            </div>
                            <span class="block mb-1 text-sm text-slate-900 dark:text-white font-medium">
                                Tasks
                            </span>
                            <span class="block mb- text-2xl text-slate-900 dark:text-white font-medium">
                                {{ $countTasks }}
                            </span>
                            <div class="flex space-x-2 rtl:space-x-reverse">
                                <div class="flex-none text-xl  text-primary-500">
                                    <iconify-icon icon="heroicons:arrow-trending-up"></iconify-icon>
                                </div>
                                <div class="flex-1 text-sm">
                                    <span class="block mb-[2px] text-primary-500">
                                        25.67%
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-info-500 rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-25 relative z-[1]">
                            <div class="overlay absolute left-0 top-0 w-full h-full z-[-1]">
                                <img src="assets/images/all-img/shade-2.png" alt="" draggable="false" class="w-full h-full object-contain">
                            </div>
                            <span class="block mb-1 text-sm text-slate-900 dark:text-white font-medium">
                                Compled This Month
                            </span>
                            <span class="block mb- text-2xl text-slate-900 dark:text-white font-medium">
                                {{ $compTasks }}
                            </span>
                            <div class="flex space-x-2 rtl:space-x-reverse">
                                <div class="flex-none text-xl  text-primary-500">
                                    <iconify-icon icon="heroicons:arrow-trending-up"></iconify-icon>
                                </div>
                                <div class="flex-1 text-sm">
                                    <span class="block mb-[2px] text-primary-500">
                                        8.67%
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-primary-500 rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-25 relative z-[1]">
                            <div class="overlay absolute left-0 top-0 w-full h-full z-[-1]">
                                <img src="assets/images/all-img/shade-3.png" alt="" draggable="false" class="w-full h-full object-contain">
                            </div>
                            <span class="block mb-1 text-sm text-slate-900 dark:text-white font-medium">
                                Cars
                            </span>
                            <span class="block mb- text-2xl text-slate-900 dark:text-white font-medium">
                                {{ $countCars }}
                            </span>
                            <div class="flex space-x-2 rtl:space-x-reverse">
                                <div class="flex-none text-xl  text-danger-500">
                                    <iconify-icon icon="heroicons:arrow-trending-down"></iconify-icon>
                                </div>
                                <div class="flex-1 text-sm">
                                    <span class="block mb-[2px] text-danger-500">
                                        1.67%
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-success-500 rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-25 relative z-[1]">
                            <div class="overlay absolute left-0 top-0 w-full h-full z-[-1]">
                                <img src="assets/images/all-img/shade-4.png" alt="" draggable="false" class="w-full h-full object-contain">
                            </div>
                            <span class="block mb-1 text-sm text-slate-900 dark:text-white font-medium">
                                Active Policy
                            </span>
                            <span class="block mb- text-2xl text-slate-900 dark:text-white font-medium">
                                654
                            </span>
                            <div class="flex space-x-2 rtl:space-x-reverse">
                                <div class="flex-none text-xl  text-primary-500">
                                    <iconify-icon icon="heroicons:arrow-trending-up"></iconify-icon>
                                </div>
                                <div class="flex-1 text-sm">
                                    <span class="block mb-[2px] text-primary-500">
                                        11.67%
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- END: Group Chart3 -->
                    </div>
                </div>
            </div>



            <div class="lg:col-span-4 col-span-12">
                <div class="card ">
                    <div class="card-header ">
                        <h4 class="card-title">Recent Tasks</h4>
                        <div>
                            <!-- BEGIN: Card Dropdown -->
                            <div class="relative">
                                <div class="dropdown relative">
                                    <button class="text-xl text-center block w-full " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="text-lg inline-flex h-6 w-6 flex-col items-center justify-center border border-slate-200 dark:border-slate-700 rounded dark:text-slate-400">
                                            <iconify-icon icon="heroicons-outline:dots-horizontal"></iconify-icon>
                                        </span>
                                    </button>
                                    <ul class=" dropdown-menu min-w-[120px] absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700  shadow z-[2] overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                        <li>
                                            <a href="{{ route('tasks.index') }}" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                Show all</a>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                            <!-- END: Card Droopdown -->
                        </div>
                    </div>
                    <div class="card-body p-6">

                        <!-- BEGIN: Activity Card -->

                        <div>
                            <ul class="list-item space-y-3 h-full overflow-x-auto">

                                @if ($recentTasks->isEmpty())
                                    <li class="text-center text-xs">
                                        <h2><iconify-icon icon="mdi:tick-circle" class="text-success-500"></iconify-icon><br></h2>
                                        Well done! No tasks for you today. Enjoy your free time!
                                    </li>
                                @endif
                                @foreach ($recentTasks as $task)
                                    <li class="flex items-center space-x-3 rtl:space-x-reverse border-b border-slate-100 dark:border-slate-700 last:border-b-0 pb-3 last:pb-0">
                                        <div>
                                            <div class="w-8 h-8 rounded-[100%]">
                                                <div class="h-8 w-8 rounded-full text-white bg-blue-500">
                                                    <!-- Customize the background color -->
                                                    <span class="block w-full h-full object-cover text-center text-lg leading-8">
                                                        {{ strtoupper(substr($task->open_by?->username, 0, 2)) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-start overflow-hidden text-ellipsis whitespace-nowrap max-w-[63%]">
                                            <div class="text-sm text-slate-600 dark:text-slate-300 overflow-hidden text-ellipsis whitespace-nowrap">
                                                {{ $task->title }}
                                            </div>
                                        </div>
                                        <div class="flex-1 ltr:text-right rtl:text-left">
                                            <div class="text-sm font-light text-slate-400 dark:text-slate-400">
                                                {{ \Carbon\Carbon::parse($task->due)->diffForHumans() }}
                                            </div>
                                        </div>
                                    </li>
                                @endforeach


                            </ul>
                        </div>
                        <!-- END: Activity Card -->



                    </div>
                </div>
            </div>


            <div class="lg:col-span-4 col-span-12">
                <div class="card ">
                    <div class="card-header ">
                        <h4 class="card-title">Created Offers</h4>
                        <div>
                            <!-- BEGIN: Card Dropdown -->
                            <div class="relative">
                                <div class="dropdown relative">
                                    <button class="text-xl text-center block w-full " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="text-lg inline-flex h-6 w-6 flex-col items-center justify-center border border-slate-200 dark:border-slate-700 rounded dark:text-slate-400">
                                            <iconify-icon icon="heroicons-outline:dots-horizontal"></iconify-icon>
                                        </span>
                                    </button>
                                    <ul class=" dropdown-menu min-w-[120px] absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700  shadow z-[2] overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                        <li>
                                            <a href="{{ route('offers.index') }}" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                Show all</a>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                            <!-- END: Card Droopdown -->
                        </div>
                    </div>
                    <div class="card-body p-6">

                        <!-- BEGIN: Activity Card -->

                        <div>
                            <ul class="list-item space-y-3 h-full overflow-x-auto">

                                @if ($homeCreatedOffers->isEmpty())
                                    <li class="text-center text-xs">
                                        <h2><iconify-icon icon="mdi:tick-circle" class="text-success-500"></iconify-icon><br></h2>
                                        You have no recent offers!
                                    </li>
                                @endif
                                @foreach ($homeCreatedOffers as $offer)
                                    <li class="flex items-center space-x-3 rtl:space-x-reverse border-b border-slate-100 dark:border-slate-700 last:border-b-0 pb-3 last:pb-0">
                                        <div class="text-start overflow-hidden text-ellipsis whitespace-nowrap max-w-[63%]">
                                            <p class="text-sm text-slate-400  font-light" wire:click="setStatus">
                                                {{ ucwords($offer->client_type) }}
                                            </p>
                                            <div class="text-sm text-slate-600 dark:text-slate-300 overflow-hidden text-ellipsis whitespace-nowrap">
                                                <b>{{ $offer->client->name }}</b>
                                            </div>
                                            <p class="text-sm">{{ ucwords(str_replace('_', ' ', $offer->type)) }}</p>
                                        </div>
                                        <div class="flex-1 ltr:text-right rtl:text-left">
                                            <div class="text-sm font-light text-slate-400 dark:text-slate-400">
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

                                                @if ($offer->is_renewal)
                                                    <span class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize rounded-3xl" style="vertical-align: top;">Renewal</span>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @endforeach


                            </ul>
                        </div>
                        <!-- END: Activity Card -->


                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
