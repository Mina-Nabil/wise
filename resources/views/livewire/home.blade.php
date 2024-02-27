<div>
    <div>
        <div class="flex justify-between flex-wrap items-center mb-6">
            <h4
                class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
                Dashboard</h4>
            {{-- <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
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
            </div> --}}
        </div>
        <div class="grid grid-cols-12 gap-5 mb-5">

            {{--             
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
            </div> --}}




            {{-- created offers --}}
            {{-- <div class="lg:col-span-6 col-span-12">
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
                                                Show all offers</a>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                            <!-- END: Card Droopdown -->
                        </div>
                    </div>
                    <div class="card-body p-6">

                        <!-- BEGIN: Activity Card -->
 --}}
            <div>
                <ul class="list-item space-y-3 h-full overflow-x-auto">

                    @if ($homeCreatedOffers->isEmpty())
                        <li class="text-center text-xs">
                            <h2><iconify-icon icon="mdi:tick-circle" class="text-success-500"></iconify-icon><br></h2>
                            You have no recent offers!
                        </li>
                    @endif
                    @foreach ($homeCreatedOffers as $offer)
                        <li
                            class="flex items-center space-x-3 rtl:space-x-reverse border-b border-slate-100 dark:border-slate-700 last:border-b-0 pb-3 last:pb-0">
                            <div class="text-start overflow-hidden text-ellipsis whitespace-nowrap max-w-[63%]">
                                <p class="text-sm text-slate-400  font-light" wire:click="setStatus">
                                    {{ ucwords($offer->client_type) }}
                                </p>
                                <a href="{{ route('offers.show', $offer->id) }}">
                                    <div
                                        class="text-sm text-slate-600 dark:text-slate-300 overflow-hidden text-ellipsis whitespace-nowrap hover:underline cursor-pointer">
                                        <b>{{ $offer->client->name }}</b>
                                    </div>
                                </a>
                                <p class="text-sm">{{ ucwords(str_replace('_', ' ', $offer->type)) }}</p>
                            </div>
                            <div class="flex-1 ltr:text-right rtl:text-left">
                                <div class="text-sm font-light  text-slate-900 dark:text-slate-900">
                                    @if ($offer->status === 'new')
                                        <span class="badge bg-info-500 h-auto">
                                            <iconify-icon
                                                icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
                                        </span>
                                    @elseif(str_contains($offer->status, 'pending'))
                                        <span class="badge bg-warning-500 h-auto">
                                            <iconify-icon
                                                icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
                                        </span>
                                    @elseif(str_contains($offer->status, 'declined') || str_contains($offer->status, 'cancelled'))
                                        <span class="badge bg-danger-500 h-auto">
                                            <iconify-icon
                                                icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
                                        </span>
                                    @elseif($offer->status === 'approved')
                                        <span class="badge bg-success-500 h-auto">
                                            <iconify-icon
                                                icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
                                        </span>
                                    @endif

                                    @if ($offer->is_renewal)
                                        <span
                                            class="badge bg-success-500 text-success-900 bg-opacity-30 capitalize rounded-3xl"
                                            style="vertical-align: top;">Renewal</span>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach


                </ul>
                <div class="card pb-4">
                    {{ $homeCreatedOffers->links('vendor.livewire.bootstrap') }}
                </div>
            </div>
            <!-- END: Activity Card -->
            {{--

                    </div>
                </div>
            </div> --}}

            {{-- Assigned Offers --}}
            <div class="lg:col-span-4 col-span-12">
                <div class="card ">
                    <div class="card-header ">
                        <h4 class="card-title">Assigned Offers</h4>
                        <div>
                            <!-- BEGIN: Card Dropdown -->
                            <div class="relative">
                                <div class="dropdown relative">
                                    <button class="text-xl text-center block w-full " type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span
                                            class="text-lg inline-flex h-6 w-6 flex-col items-center justify-center border border-slate-200 dark:border-slate-700 rounded dark:text-slate-400">
                                            <iconify-icon icon="heroicons-outline:dots-horizontal"></iconify-icon>
                                        </span>
                                    </button>
                                    <ul
                                        class=" dropdown-menu min-w-[120px] absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700  shadow z-[2] overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                        <li>
                                            <a href="{{ route('offers.index') }}"
                                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                Show all offers</a>
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

                                @if ($homeAssignedOffers->isEmpty())
                                    <li class="text-center text-xs">
                                        <h2><iconify-icon icon="mdi:tick-circle"
                                                class="text-success-500"></iconify-icon><br></h2>
                                        You don't have assigned offers!
                                    </li>
                                @endif
                                @foreach ($homeAssignedOffers as $offer)
                                    <li
                                        class="flex items-center space-x-3 rtl:space-x-reverse border-b border-slate-100 dark:border-slate-700 last:border-b-0 pb-3 last:pb-0">

                                        <div
                                            class="text-start overflow-hidden text-ellipsis whitespace-nowrap max-w-[63%]">
                                            <p class="text-sm text-slate-400  font-light" wire:click="setStatus">
                                                {{ ucwords($offer->client_type) }}
                                            </p>
                                            <a href="{{ route('offers.show', $offer->id) }}">
                                                <div
                                                    class="text-sm text-slate-600 dark:text-slate-300 overflow-hidden text-ellipsis whitespace-nowrap  hover:underline cursor-pointer">
                                                    <b>{{ $offer->client->name }}</b>
                                                </div>
                                            </a>
                                            <p class="text-sm">{{ ucwords(str_replace('_', ' ', $offer->type)) }}
                                            </p>
                                        </div>
                                        <a href="{{ route('offers.show', $offer->id) }}">
                                            <div class="flex-1 ltr:text-right rtl:text-left">
                                                <div class="text-sm font-light text-slate-900 dark:text-slate-900">
                                                    @if ($offer->status === 'new')
                                                        <span class="badge bg-info-500 h-auto ">
                                                            <iconify-icon
                                                                icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
                                                        </span>
                                                    @elseif(str_contains($offer->status, 'pending'))
                                                        <span class="badge bg-warning-500 h-auto">
                                                            <iconify-icon
                                                                icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
                                                        </span>
                                                    @elseif(str_contains($offer->status, 'declined') || str_contains($offer->status, 'cancelled'))
                                                        <span class="badge bg-danger-500 h-auto">
                                                            <iconify-icon
                                                                icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
                                                        </span>
                                                    @elseif($offer->status === 'approved')
                                                        <span class="badge bg-success-500 h-auto">
                                                            <iconify-icon
                                                                icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
                                                        </span>
                                                    @endif

                                                    @if ($offer->is_renewal)
                                                        <span
                                                            class="badge bg-success-500 text-slate-900  bg-opacity-30 capitalize rounded-3xl"
                                                            style="vertical-align: top;">Renewal</span>
                                                    @endif
                                                </div>
                                        </a>
                        </div>
                        </li>
                        @endforeach


                        </ul>
                        <div class="card pb-4">
                            {{ $homeAssignedOffers->links('vendor.livewire.bootstrap') }}
                        </div>
                    </div>
                    <!-- END: Activity Card -->


                </div>
            </div>
        </div>

        {{-- Tasks --}}
        <div class="lg:col-span-4 col-span-12">
            <div class="card ">
                <div class="card-header ">
                    <h4 class="card-title">Recent Tasks</h4>
                    <div>
                        <!-- BEGIN: Card Dropdown -->
                        <div class="relative">
                            <div class="dropdown relative">
                                <button class="text-xl text-center block w-full " type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <span
                                        class="text-lg inline-flex h-6 w-6 flex-col items-center justify-center border border-slate-200 dark:border-slate-700 rounded dark:text-slate-400">
                                        <iconify-icon icon="heroicons-outline:dots-horizontal"></iconify-icon>
                                    </span>
                                </button>
                                <ul
                                    class=" dropdown-menu min-w-[120px] absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700  shadow z-[2] overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                    <li>
                                        <a href="{{ route('tasks.index') }}"
                                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
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
                                    <h2><iconify-icon icon="mdi:tick-circle"
                                            class="text-success-500"></iconify-icon><br></h2>
                                    Well done! No tasks for you today. Enjoy your free time!
                                </li>
                            @endif
                            @foreach ($recentTasks as $task)
                                <li
                                    class="flex items-center space-x-3 rtl:space-x-reverse border-b border-slate-100 dark:border-slate-700 last:border-b-0 pb-3 last:pb-0">
                                    <div>
                                        <div class="w-8 h-8 rounded-[100%]">
                                            <div class="h-8 w-8 rounded-full text-white bg-blue-500">
                                                <!-- Customize the background color -->
                                                <span
                                                    class="block w-full h-full object-cover text-center text-lg leading-8">
                                                    {{ strtoupper(substr($task->open_by?->username, 0, 2)) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-start overflow-hidden text-ellipsis whitespace-nowrap max-w-[63%]">
                                        <a href="{{ route('tasks.show', $task->id) }}">
                                            <div
                                                class="text-sm text-slate-600 dark:text-slate-300  hover:underline cursor-pointer">

                                                <P class=" overflow-hidden text-ellipsis whitespace-nowrap "
                                                    style="min-width: 150px">{{ $task->title }}</P>
                                                @php
                                                    $currentDate = now();
                                                    $startDate = $task->created_at;
                                                    $dueDate = $task->due;
                                                    $totalDuration = $startDate->diffInSeconds($dueDate);
                                                    $elapsedDuration = $startDate->diffInSeconds($currentDate);
                                                    $percentagePassed = ($elapsedDuration / $totalDuration) * 100;
                                                @endphp

                                                <div class="w-full bg-slate-200 h-2 m-1 rounded-xl overflow-hidden"
                                                    style="max-width: 100px">
                                                    <div class="@if ($percentagePassed >= 0 && $percentagePassed < 30) bg-success-500 @elseif($percentagePassed >= 30 && $percentagePassed < 70) bg-warning-500 @else bg-danger-500 @endif h-full rounded-xl"
                                                        style="width: {{ number_format($percentagePassed, 0) }}%">
                                                    </div>
                                                </div>

                                            </div>
                                        </a>
                                    </div>

                                    <div class="flex-1 ltr:text-right rtl:text-left">
                                        <div>
                                            @if ($task->status === 'new')
                                                <div
                                                    class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-primary-500 bg-primary-500 text-xs">
                                                    New
                                                </div>
                                            @elseif($task->status === 'assigned')
                                                <div
                                                    class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-info-500 bg-info-500 text-xs">
                                                    Assigned
                                                </div>
                                            @elseif($task->status === 'in_progress')
                                                <div
                                                    class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-secondary-500 bg-secondary-500 text-xs">
                                                    in Progress
                                                </div>
                                            @elseif($task->status === 'pending')
                                                <div
                                                    class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-warning-500 bg-warning-500 text-xs">
                                                    Pending
                                                </div>
                                            @elseif($task->status === 'completed')
                                                <div
                                                    class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-success-500 bg-success-500 text-xs">
                                                    Completed
                                                </div>
                                            @elseif($task->status === 'closed')
                                                <div
                                                    class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-black-500 bg-black-500 text-xs">
                                                    Closed
                                                </div>
                                            @endif
                                        </div>
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

        @if (!Auth::user()->isOperations)
            {{-- Folowups --}}
            <div class="lg:col-span-4 col-span-12">
                <div class="card ">
                    <div class="card-header ">
                        <h4 class="card-title">Follow Ups</h4>
                        <div>
                            <!-- BEGIN: Card Dropdown -->
                            <div class="relative">
                                <div class="dropdown relative">
                                    <button class="text-xl text-center block w-full " type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span
                                            class="text-lg inline-flex h-6 w-6 flex-col items-center justify-center border border-slate-200 dark:border-slate-700 rounded dark:text-slate-400">
                                            <iconify-icon icon="heroicons-outline:dots-horizontal"></iconify-icon>
                                        </span>
                                    </button>
                                    <ul
                                        class=" dropdown-menu min-w-[120px] absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700  shadow z-[2] overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                        <li>
                                            <a href="{{ route('followups.index') }}"
                                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
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

                                @if ($homeFollowups->isEmpty())
                                    <li class="text-center text-xs">
                                        <h2><iconify-icon icon="mdi:tick-circle"
                                                class="text-success-500"></iconify-icon><br></h2>
                                        No Followups in the recent moment!
                                    </li>
                                @endif
                                @foreach ($homeFollowups as $followup)
                                    <li
                                        class="flex items-center space-x-3 rtl:space-x-reverse border-b border-slate-100 dark:border-slate-700 last:border-b-0 pb-3 last:pb-0">
                                        <div
                                            class="text-start overflow-hidden text-ellipsis whitespace-nowrap max-w-[63%]">
                                            <p class="text-sm">
                                                {{ ucwords(str_replace('_', ' ', $followup->called->name)) }}</p>
                                            <a
                                                href="{{ route($followup->called_type . 's.show', $followup->called_id) }}">
                                                <div
                                                    class="text-sm text-slate-600 dark:text-slate-300 overflow-hidden text-ellipsis whitespace-nowrap  hover:underline cursor-pointer">
                                                    <b>{{ $followup->title }}</b>
                                                </div>
                                            </a>
                                            <p class="text-sm text-slate-400  font-light" wire:click="setStatus">
                                                {{ ucwords($followup->call_time) }}
                                            </p>
                                        </div>
                                        <div class="flex-1 ltr:text-right rtl:text-left">
                                            <div class="text-sm font-light text-slate-400 dark:text-slate-400">
                                                @if ($followup->status === 'new')
                                                    <span class="badge bg-info-500 h-auto">
                                                        <iconify-icon
                                                            icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $followup->status)) }}
                                                    </span>
                                                @elseif(str_contains($followup->status, 'pending'))
                                                    <span class="badge bg-warning-500 h-auto">
                                                        <iconify-icon
                                                            icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $followup->status)) }}
                                                    </span>
                                                @elseif(str_contains($followup->status, 'declined') || str_contains($followup->status, 'cancelled'))
                                                    <span class="badge bg-danger-500 h-auto">
                                                        <iconify-icon
                                                            icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $followup->status)) }}
                                                    </span>
                                                @elseif($followup->status === 'approved')
                                                    <span class="badge bg-success-500 h-auto">
                                                        <iconify-icon
                                                            icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $followup->status)) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @endforeach


                            </ul>
                            <div class="card pb-4">
                                {{ $homeFollowups->links('vendor.livewire.bootstrap') }}
                            </div>
                        </div>
                        <!-- END: Activity Card -->


                    </div>
                </div>
            </div>

            {{-- Customers --}}
            <div class="lg:col-span-6 col-span-12 rounded overflow-x-auto">
                <div class="card-body px-6">
                    <div class=" -mx-6">
                        <div class="inline-block min-w-full align-middle">
                            <div class="rounded " data-simplebar="data-simplebar">
                                {{-- overflow-hidden --}}
                                <table
                                    class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 rounded whitespace-nowrap w-full">
                                    <thead
                                        class=" border-t border-slate-100 dark:border-slate-800 bg-slate-200 dark:bg-slate-700">
                                        <tr>

                                            <th scope="col" class=" table-th ">
                                                Customer Name
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


                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                        @foreach ($homeCustomers as $customer)
                                            <tr wire:click="redirectToCustomerShowPage({{ $customer }})"
                                                class=" hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer">

                                                <td class="table-td">
                                                    <b>{{ $customer->first_name }}
                                                        {{ $customer->last_name }}</b>
                                                </td>

                                                <td class="table-td ">
                                                    {{ $customer->type }}
                                                </td>

                                                <td class="table-td ">
                                                    @foreach ($customer->phones->take(1) as $phones)
                                                        {{ $phones->number }}
                                                    @endforeach

                                                </td>

                                                <td class="table-td ">
                                                    {{ $customer->email ?? 'N/A' }}
                                                </td>


                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>

                                @if ($homeCustomers->isEmpty())
                                    {{-- START: empty filter result --}}
                                    <div class="card m-5 p-5">
                                        <div class="card-body rounded-md bg-white dark:bg-slate-800">
                                            <div class="items-center text-center p-5">
                                                <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon>
                                                </h2>
                                                <h2 class="card-title text-slate-900 dark:text-white mb-3">No
                                                    Customers with the
                                                    applied
                                                    filters</h2>
                                                <p class="card-text">Try changing the filters or search terms for
                                                    this view.
                                                </p>
                                                <a href="{{ url('/customers') }}"
                                                    class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">View
                                                    all Customers</a>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- END: empty filter result --}}
                                @endif
                                <div class="card pb-4">
                                    {{ $homeCustomers->links('vendor.livewire.bootstrap') }}
                                </div>

                            </div>





                        </div>
                    </div>
                </div>
            </div>

            {{-- Corporate --}}
            <div class="lg:col-span-6 col-span-12 rounded overflow-x-auto">
                <div class="card-body px-6">
                    <div class=" -mx-6">
                        <div class="inline-block min-w-full align-middle">
                            <div class="rounded " data-simplebar="data-simplebar">
                                {{-- overflow-hidden --}}
                                <table
                                    class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 rounded whitespace-nowrap">
                                    <thead
                                        class=" border-t border-slate-100 dark:border-slate-800 bg-slate-200 dark:bg-slate-700">
                                        <tr>

                                            <th scope="col" class=" table-th ">
                                                Corporate Name
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


                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                        @foreach ($homeCorporates as $corporate)
                                            <tr wire:click="redirectToCorporateShowPage({{ $corporate }})"
                                                class=" hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer">

                                                <td class="table-td">
                                                    <b>{{ $corporate->name }}</b>
                                                </td>

                                                <td class="table-td ">
                                                    {{ $corporate->type }}
                                                </td>

                                                <td class="table-td ">
                                                    @foreach ($corporate->phones->take(1) as $phones)
                                                        {{ $phones->number }}
                                                    @endforeach

                                                </td>

                                                <td class="table-td ">
                                                    {{ $corporate->email ?? 'N/A' }}
                                                </td>


                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>


                                <div class="card pb-4">
                                    {{ $homeCorporates->links('vendor.livewire.bootstrap') }}
                                </div>

                            </div>





                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
</div>
