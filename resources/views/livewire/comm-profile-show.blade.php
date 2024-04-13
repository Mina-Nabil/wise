<div>

    <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base">
        <div class="card-body flex flex-col p-6 active">
            <div class="order-2 card-text h-full menu-open active">
                <div class="flex justify-between mb-4">
                    <div>
                        <div class="text-xl text-slate-900 dark:text-white text-wrap">
                            {{ $profile->title }}
                            @if ($profile->per_policy)
                                <span class="badge bg-primary-500 text-primary-500 bg-opacity-30 capitalize">Per Policy</span>
                            @endif
                        </div>
                        <div class="text-base">
                            {{ ucwords(str_replace('_', ' ', $profile->type)) }}
                        </div>
                    </div>
                    <div>
                        @if ($profile->user)
                            <a href="card.html" class="inline-flex leading-5 text-slate-500 dark:text-slate-400 text-sm font-normal active">
                                <iconify-icon class="text-secondary-500 ltr:mr-2 rtl:ml-2 text-lg" icon="lucide:user"></iconify-icon>
                                {{ $profile->user->first_name }} {{ $profile->user->last_name }}
                            </a>
                        @endif

                    </div>

                </div>
                <div class="card-text mt-4 menu-open">
                    <p>{{ $profile->desc }}</p>
                    <div class="mt-4 space-x-4 rtl:space-x-reverse">
                        <button wire:click="openUpdateSec" class="btn inline-flex justify-center btn-light btn-sm">Edit info</button>
                    </div>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 float-right">Created {{ \Carbon\Carbon::parse($profile->created_at)->format('l d/m/Y') }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 float-right">Updated {{ \Carbon\Carbon::parse($profile->updated_at)->format('l d/m/Y h:m') }} - &nbsp;</p>
            </div>
        </div>
    </div>
    <div>

    </div>

    @if ($updatedCommSec)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Add Commission Profile
                            </h3>

                            <button wire:click="closeUpdateSec" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
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

                                <div class="input-area mt-3">
                                    <label forupdatedType" class="form-label">Type</label>
                                    <select name="updatedType" class="form-control w-full mt-2 @error('updatedType') !border-danger-500 @enderror" wire:model.defer="updatedType">
                                        <option>None</option>
                                        @foreach ($profileTypes as $type)
                                            <option value="{{ $type }}">{{ ucwords(str_replace('_', ' ', $type)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('updatedType')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <div class="flex items-center space-x-2">
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input wire:model="updatedPerPolicy" type="checkbox" value="" class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></div>
                                        </label>
                                        <span class="text-sm text-slate-600 font-Inter font-normal">Per Policy</span>

                                    </div>
                                </div>

                                {{-- <div class="input-area mt-3">
                                    <label for="updatedUserId" class="form-label">User</label>
                                    <select name="updatedUserId" id="updatedUserId" class="form-control w-full mt-2 @error('updatedUserId') !border-danger-500 @enderror" wire:model="updatedUserId">
                                        <option value="">None</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('updatedUserId')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror --}}

                                <div class="input-area mt-3">
                                    <label for="updatedTitle" class="form-label">Title</label>
                                    <input id="updatedTitle" type="text" class="form-control @error('updatedTitle') !border-danger-500 @enderror" wire:model.defer="updatedTitle">
                                </div>

                                <div class="from-group mt-3">
                                    <label for="updatedDesc" class="form-label">Description</label>
                                    <textarea class="form-control mt-2 w-full" wire:model.defer="updatedDesc"></textarea>
                                    @error('updatedDesc')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="updateComm" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="updateComm">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="updateComm" icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
