<div>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>
    <div class="card">
        <header class=" card-header noborder">
            <h4 class="card-title">
                WISE Calendar
            </h4>
        </header>
        <div class="card-body px-6 pb-6">
            <div wire:ignore id='calendar'></div>

        </div>


        @if (true)
            <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
                <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                    <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                        <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                            <!-- Modal header -->
                            <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                                <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                    Add Event
                                </h3>

                                <button wire:click="toggleAddLead" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
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
                                    <p class="text-lg"><b>Lead Info</b></p>

                                    <div class="input-area">
                                        <label for="title" class="form-label">First Name</label>
                                        <input id="title" type="text" class="form-control @error('title') !border-danger-500 @enderror" wire:model.defer="title">
                                        @error('title')
                                            <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                        <div class="input-area">
                                            <label for="start_time" class="form-label">Start Time</label>
                                            <input id="start_time" type="date" class="form-control @error('start_time') !border-danger-500 @enderror" wire:model.defer="start_time">
                                        </div>
                                        <div class="input-area">
                                            <label for="end_time" class="form-label">End Time</label>
                                            <input id="end_time" type="text" class="form-control @error('end_time') !border-danger-500 @enderror" wire:model.defer="end_time">
                                        </div>
                                    </div>
                                    @error('start_time')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror

                                    @error('end_time')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                        <div class="input-area">
                                            <label for="all_day" class="form-label">All Day</label>
                                            <input id="all_day" type="checkbox" class="form-control @error('all_day') !border-danger-500 @enderror" wire:model.defer="all_day">
                                        </div>
                                        <div class="input-area">
                                            <label for="all_user" class="form-label">All User</label>
                                            <input id="all_user" type="checkbox" class="form-control @error('all_user') !border-danger-500 @enderror" wire:model.defer="all_user">
                                        </div>
                                    </div>

                                    <div class="input-area">
                                        <label for="location" class="form-label">Location</label>
                                        <input id="location" type="text" class="form-control @error('location') !border-danger-500 @enderror" wire:model.defer="location">
                                        @error('location')
                                            <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="input-area">
                                        <label for="note" class="form-label">Note</label>
                                        <textarea name="note" id="" cols="30" rows="10" wire:model.defer="note"></textarea>
                                        @error('note')
                                            <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                        <div class="input-area mt-3">
                                            <label for="user_tag" class="form-label">User Tag</label>
                                            <select name="user_tag" id="user_tag" class="form-control w-full mt-2 @error('user_tag') !border-danger-500 @enderror" wire:model.defer="user_tag">
                                                <option>None</option>
                                                @foreach ($USER_TAGS as $USER_TAG)
                                                    <option value="{{ $USER_TAG }}">{{ $USER_TAG }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="input-area mt-3">
                                            <label for="user_tag" class="form-label">User Tag</label>
                                            <select name="user_tag" id="user_tag" class="form-control w-full mt-2 @error('user_tag') !border-danger-500 @enderror" wire:model.defer="user_tag">
                                                <option>None</option>
                                                @foreach ($USER_TAGS as $USER_TAG)
                                                    <option value="{{ $USER_TAG }}">{{ $USER_TAG }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        @if ($user_tag = 'guest')
                                            <div class="input-area">
                                                <label for="location" class="form-label">Guest Name</label>
                                                <input id="location" type="text" class="form-control @error('location') !border-danger-500 @enderror" wire:model.defer="location">
                                            </div>
                                        @endif
                                    </div>



                                </div>
                                <!-- Modal footer -->
                                <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                    <button wire:click="addLead" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                        Accept
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        @endif



    </div>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            console.log(@json($events))
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'today dayGridMonth,timeGridWeek,dayGridDay'
                },
                // timeZone: 'UTC',
                selectable: true,
                eventBorderColor: 'white',
                events: @json($events),
                eventClick: function(info) {
                    info.jsEvent.preventDefault(); // don't let the browser navigate
                    if (info.event.url) {
                        window.open(info.event.url);
                    }
                }
            });
            calendar.render();
        });
    </script>
</div>
