<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Policies
            </h4>
        </div>
    </div>
    <div>
        <div class="input-area mb-3">
            <div class="relative">
                <input type="text" class="form-control !pr-12 mr-2" placeholder="Search" wire:model="search"> 
                <iconify-icon class="loading-icon text-lg" icon="line-md:loading-twotone-loop"></iconify-icon>
                <div
                    class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center">
                    <button class="btn-sm" data-tippy-content="Filter" type="button"
                        id="primaryOutlineDropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="true">
                        <iconify-icon icon="bx:filter"></iconify-icon>
                    </button>
                    <ul class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none pe-5"
                        style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 52px); max-height: 200px; overflow-y: auto;">
                        <li>
                            <a href="#"
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                Action
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                Action
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                Action
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                Action
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                Action
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                Action
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mb-5">
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
        </div>
        <div class="grid xl:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">

            {{-- BEGIN: Policy list --}}
            @foreach ($policies as $policy)
            
                <div class="card mb-5">
                    <div class="card-body">
                        <div class="card-text h-full">
                            <header class="border-b px-4 pt-4 pb-3 flex justify-between border-primary-500 ">
                                <div class="flex-wrap items-center">
                                    <h4 class="mb-0 text-primary-500">
                                        <iconify-icon class="text-3xl inline-block ltr:mr-2 rtl:ml-2 text-primary-500"
                                            icon="iconoir:privacy-policy"></iconify-icon>
                                        {{ $policy->name }}
                                    </h4>
                                </div>

                                <div class="flex space-x-3 rtl:space-x-reverse float-right">
                                    <button class="action-btn onClickTooltip" type="button" icon="ph:note-light"
                                        data-tippy-content="not active yet">
                                        <iconify-icon icon="ph:note-light" data-tippy-content="not active yet"
                                            data-tippy-theme="dark"></iconify-icon>
                                    </button>
                                    <a href={{ route('policies.show',$policy->id) }}>
                                        <button class="action-btn" type="button">
                                            <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                                        </button>
                                    </a>
                                    <button class="action-btn" type="button" wire:click="openDeletePolicy({{ $policy->id }})">
                                        <iconify-icon icon="heroicons:trash"></iconify-icon>
                                    </button>
                                </div>
                            </header>
                            <div class="py-3 px-5">
                                <h5 class="card-subtitle">{{ $policy->business }} - asalal;asa</h5>
                                {{-- @foreach ($policy->conditions as $condition)
                                    <h5 class="card-subtitle">
                                        <span class="badge bg-slate-900 text-white capitalize">{{ $condition->scope }}</span>
                                        <span class="badge bg-info-500 text-white capitalize">{{ $condition->operator }}</span>
                                        <span class="badge bg-slate-900 text-white capitalize">{{ $condition->value }}</span>
                                    </h5>
                                @endforeach --}}

                                <h5 class="card-subtitle">
                                    <span class="badge bg-slate-900 text-white capitalize">Model Year</span>
                                    <span class="badge bg-info-500 text-white capitalize">>=</span>
                                    <span class="badge bg-slate-900 text-white capitalize">2016</span>
                                </h5>
                                <p class="card-text mt-3">{{ $policy->note }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            {{-- END: Policy list --}}

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

@if($deleteThisPolicy)
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" id="dangerModal" tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog" style="display: block;">
    <div class="modal-dialog relative w-auto pointer-events-none">
      <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
        <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
          <!-- Modal header -->
          <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-danger-500">
            <h3 class="text-base font-medium text-white dark:text-white capitalize">
              Delete Policy
            </h3>
            <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
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
                Are you sure, to delete this Policy ?
            </h6>
            <p class="text-base text-slate-600 dark:text-slate-400 leading-6">
              Oat cake ice cream candy chocolate cake
              apple pie. Brownie carrot cake candy
              canes. Cake sweet roll cake cheesecake
              cookie chocolate cake liquorice.
            </p>
          </div>
          <!-- Modal footer -->
          <div class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
            <button data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-danger-500">
              Yes, Delete
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
@endif