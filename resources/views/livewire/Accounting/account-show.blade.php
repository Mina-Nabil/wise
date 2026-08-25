<div>

    <div class="card dark active mb-5">
        <div class="card-body rounded-md bg-white dark:bg-slate-800 shadow-base menu-open">
            <div class="flex items-start justify-between p-5">
                <div>
                    <h3 class="card-title text-slate-900 dark:text-white">{{ $account->name }}</h3>
                    @if ($account->desc)
                        <p class="card-text my-5 break-words">{{ $account->desc }}</p>
                    @endif
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ ucwords($account->nature) }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-2 justify-end">
                    @can('setOpeningBalance', $account)
                        <button wire:click="openOpeningBalanceModal"
                            class="btn inline-flex justify-center btn-outline-primary btn-sm">
                            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:settings-2"></iconify-icon>
                            Set Opening Balance
                        </button>
                        <button wire:click="openClearBalancesModal"
                            class="btn inline-flex justify-center btn-outline-danger btn-sm">
                            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:eraser"></iconify-icon>
                            Clear Parent &amp; Children Balances
                        </button>
                    @endcan
                    @can('merge', $account)
                        <button wire:click="openMergeModal"
                            class="btn inline-flex justify-center btn-outline-warning btn-sm">
                            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:git-merge"></iconify-icon>
                            دمج الحساب
                        </button>
                    @endcan
                    @can('move', $account)
                        <button wire:click="openMoveModal"
                            class="btn inline-flex justify-center btn-outline-secondary btn-sm">
                            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:folder-tree"></iconify-icon>
                            نقل الحساب
                        </button>
                    @endcan
                    @can('deleteWithEntries', $account)
                        <button wire:click="openDeleteAccountModal"
                            class="btn inline-flex justify-center btn-danger btn-sm">
                            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:trash-2"></iconify-icon>
                            حذف الحساب
                        </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="card">



        <header class=" card-header noborder">
            <div class="flex flex-wrap gap-8">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Balance at period start</p>
                    <h4 class="card-title">{{ number_format($periodStartBalance, 2) }}</h4>
                    <p class="text-xs text-slate-400 dark:text-slate-500">As of {{ \Carbon\Carbon::parse($fromDate)->format('d/m/Y') }}</p>
                </div>
                <div class="border-l border-slate-200 dark:border-slate-600 pl-8">
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Opening balance</p>
                    <h4 class="card-title text-slate-600 dark:text-slate-300">{{ number_format($headerOpeningBalance, 2) }}</h4>
                    <p class="text-xs text-slate-400 dark:text-slate-500">Before first entry</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <label class="flex items-center gap-2 cursor-pointer text-sm text-slate-600 dark:text-slate-300 select-none">
                    <div class="relative">
                        <input type="checkbox" wire:model="includeChildren" class="sr-only peer">
                        <div class="w-9 h-5 bg-slate-200 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary-500"></div>
                    </div>
                    Include Children
                </label>
                @if ($includeChildren)
                <label class="flex items-center gap-2 cursor-pointer text-sm text-slate-600 dark:text-slate-300 select-none">
                    <div class="relative">
                        <input type="checkbox" wire:model="sameSheet" class="sr-only peer">
                        <div class="w-9 h-5 bg-slate-200 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary-500"></div>
                    </div>
                    Same Sheet
                </label>
                @endif
                <button wire:click="downloadJournalEntries"
                    class="btn inline-flex justify-center btn-outline-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="line-md:download-loop"></iconify-icon>
                    Download Journal Entries
                </button>
                <input type="text" class="form-control w-auto d-inline-block cursor-pointer" style="width:auto"
                    name="datetimes" id="reportrange" />
            </div>
        </header>

        <header class="card-header noborder">
            <iconify-icon wire:loading wire:target="searchText" class="loading-icon text-lg"
                    icon="line-md:loading-twotone-loop"></iconify-icon>
                <input type="text" class="form-control !pl-9 mr-1 basis-1/4" placeholder="Search..."
                    wire:model="searchText">
        </header>

        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class=" border-t border-slate-100 dark:border-slate-800 no-wrap">
                                <tr>
                                    <th scope="col" class="table-th">#</th>
                                    <th scope="col" class="table-th">Date</th>
                                    <th scope="col" class="table-th">Title</th>
                                    <th scope="col" class="table-th">Comment</th>
                                    <th scope="col" class="table-th">Debit</th>
                                    <th scope="col" class="table-th">Credit</th>
                                    <th scope="col" class="table-th">Balance</th>
                                    <th scope="col" class="table-th">Debit $</th>
                                    <th scope="col" class="table-th">Credit $</th>
                                    <th scope="col" class="table-th">Balance $</th>
                                    <th scope="col" class="table-th">Creator</th>

                                </tr>
                            </thead>
                            <tbody
                                class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700 no-wrap">

                                @foreach ($entries as $entry)
                                    <tr>
                                        <td class="table-td" 
                                        ><a href="{{ route('accounts.entries', $entry->id) }}" target="_blank" class="text-blue-500 hover:text-blue-700 underline">{{ $entry->id }}</a></td>
                                        <td class="table-td">{{ $entry->created_at->format('d/m/Y') }}</td>
                                        <td class="table-td"><b>{{ $entry->name }}</b> {{ $entry->is_reverted_entry ? ' (R1)' : '' }} {{ $entry->is_revert_entry ? ' (R2)' : '' }} </td>
                                        <td class="table-td">{{ $entry->cash_title }}</td>
                                        <td class="table-td">{{ number_format($entry->debit_amount, 2) }}</td>
                                        <td class="table-td">{{ number_format($entry->credit_amount, 2) }}</td>
                                        <td class="table-td">{{ number_format($entry->account_balance, 2) }}</td>
                                        <td class="table-td">{{ number_format($entry->debit_foreign_amount, 2) }}</td>
                                        <td class="table-td">{{ number_format($entry->credit_foreign_amount, 2) }}</td>
                                        <td class="table-td">{{ number_format($entry->account_foreign_balance, 2) }}
                                        </td>
                                        <td class="table-td">{{ $entry->username }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($entries->isEmpty())
                    {{-- START: empty filter result --}}
                    <div class="card m-5 p-5">
                        <div class="card-body rounded-md bg-white dark:bg-slate-800">
                            <div class="items-center text-center p-5">
                                <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon>
                                </h2>
                                <h2 class="card-title text-slate-900 dark:text-white mb-3">
                                    No entries found!</h2>
                                <p class="card-text">Try changing the filters or search terms for this view.
                                </p>
                                <a href="{{ url('/accounts') }}"
                                    class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">
                                    View Accounts</a>
                            </div>
                        </div>
                    </div>
                    {{-- END: empty filter result --}}
                @endif
            </div>
        </div>
        <header class=" card-header noborder">
            <div class="flex flex-wrap gap-8">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Balance at period end</p>
                    <h4 class="card-title">{{ number_format($periodEndBalance, 2) }}</h4>
                    <p class="text-xs text-slate-400 dark:text-slate-500">As of {{ \Carbon\Carbon::parse($toDate)->format('d/m/Y') }}</p>
                </div>
                <div class="border-l border-slate-200 dark:border-slate-600 pl-8">
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Current balance</p>
                    <h4 class="card-title {{ round($periodEndBalance, 2) != round($currentBalance, 2) ? 'text-primary-600 dark:text-primary-400' : '' }}">
                        {{ number_format($currentBalance, 2) }}
                    </h4>
                    <p class="text-xs text-slate-400 dark:text-slate-500">Latest entry</p>
                </div>
            </div>
        </header>
    </div>


    @if ($is_open_edit)
    @endif

    {{-- حذف الحساب مع كل قيوده --}}
    @if ($isDeleteAccountModalOpen)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="delete_account_modal" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none" style="max-width: 600px;">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700" dir="rtl">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white">
                                حذف الحساب مع كل قيوده
                            </h3>

                            <button wire:click="closeDeleteAccountModal" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">إغلاق</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4 text-right">
                            <div
                                class="bg-danger-50 dark:bg-danger-900/20 border border-danger-200 dark:border-danger-800 rounded-lg p-4">
                                <p class="text-sm text-danger-700 dark:text-danger-300 font-medium mb-2">
                                    سيتم حذف حساب «{{ $account->name }}» نهائيًا، وهذه العملية لا يمكن التراجع عنها.
                                </p>
                                <p class="text-sm text-danger-700 dark:text-danger-300">
                                    عدد القيود التي ستُحذف: <b>{{ $deleteImpact['entries'] ?? 0 }}</b> قيد،
                                    وتؤثر على <b>{{ $deleteImpact['accounts'] ?? 0 }}</b> حساب آخر.
                                </p>
                            </div>

                            <p class="text-sm text-slate-700 dark:text-slate-300 font-medium">ماذا سيحدث بالضبط؟</p>
                            <ul class="text-sm text-slate-600 dark:text-slate-300 space-y-2 list-disc pr-5">
                                <li>
                                    كل قيد يومية يظهر فيه هذا الحساب سيُحذف <b>بالكامل</b>، أي أن الطرف المقابل في
                                    الحسابات الأخرى سيُحذف أيضًا، حتى يظل مجموع المدين مساويًا لمجموع الدائن.
                                </li>
                                <li>
                                    لذلك <b>ستتغير أرصدة الحسابات الأخرى</b> التي شاركت في هذه القيود، لأن القيد لم يعد
                                    موجودًا.
                                </li>
                                <li>
                                    <b>الأرصدة الافتتاحية</b> لتلك الحسابات (المرحّلة من القيود المؤرشفة) لن تتغير، يتم
                                    حفظها قبل الحذف وإعادة تطبيقها بعده.
                                </li>
                                @if (($deleteImpact['pending'] ?? 0) > 0)
                                    <li>
                                        سيتم حذف <b>{{ $deleteImpact['pending'] }}</b> قيد معلّق (في انتظار الاعتماد)
                                        يخص هذا الحساب.
                                    </li>
                                @endif
                                @if (($deleteImpact['archived'] ?? 0) > 0)
                                    <li>
                                        سيتم حذف <b>{{ $deleteImpact['archived'] }}</b> سطر من القيود المؤرشفة تخص هذا
                                        الحساب فقط، وسجلات باقي الحسابات لن تتأثر.
                                    </li>
                                @endif
                                <li>
                                    أي فاتورة أو دفعة عمولة مرتبطة بقيد محذوف سيتم فك ارتباطها بالقيد.
                                </li>
                                <li>
                                    بعد الحذف يتم إعادة حساب كل الأرصدة من جديد.
                                </li>
                            </ul>

                            <div class="form-group pt-2">
                                <label for="deleteConfirmation" class="form-label">
                                    للتأكيد، اكتب اسم الحساب: <b>{{ $account->name }}</b>
                                </label>
                                <input type="text" id="deleteConfirmation"
                                    class="form-control mt-2 w-full text-right" wire:model.defer="deleteConfirmation"
                                    placeholder="اكتب اسم الحساب هنا">
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-start p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="deleteAccountWithEntries"
                                class="btn inline-flex justify-center btn-danger">
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="deleteAccountWithEntries"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                                <span wire:loading.remove wire:target="deleteAccountWithEntries">حذف نهائي</span>
                            </button>
                            <button wire:click="closeDeleteAccountModal"
                                class="btn inline-flex justify-center btn-outline-dark">
                                إلغاء
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- نقل الحساب (مع الحسابات الفرعية) تحت حساب أب جديد --}}
    @if ($isMoveModalOpen)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="move_account_modal" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none" style="max-width: 600px;">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700" dir="rtl">
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white">
                                نقل الحساب في شجرة الحسابات
                            </h3>

                            <button wire:click="closeMoveModal" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">إغلاق</span>
                            </button>
                        </div>

                        <div class="p-6 space-y-4 text-right">
                            <div class="form-group">
                                <label class="form-label">اختر الحساب الأب الجديد</label>
                                @if ($moveToRoot)
                                    <div
                                        class="flex items-center justify-between bg-slate-100 dark:bg-slate-600 rounded-md p-3 mt-2">
                                        <div>
                                            <p class="text-sm font-medium text-slate-800 dark:text-white">المستوى
                                                الأعلى (بدون حساب أب)</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-300">ضمن نفس الحساب
                                                الرئيسي</p>
                                        </div>
                                        <button wire:click="clearMoveParent"
                                            class="btn btn-sm btn-outline-dark">تغيير</button>
                                    </div>
                                @elseif ($moveParent)
                                    <div
                                        class="flex items-center justify-between bg-slate-100 dark:bg-slate-600 rounded-md p-3 mt-2">
                                        <div>
                                            <p class="text-sm font-medium text-slate-800 dark:text-white">
                                                {{ $moveParent->name }}</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-300">
                                                {{ $moveParent->full_code }}</p>
                                        </div>
                                        <button wire:click="clearMoveParent"
                                            class="btn btn-sm btn-outline-dark">تغيير</button>
                                    </div>
                                @else
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        <button wire:click="chooseMoveToRoot" type="button"
                                            class="btn btn-sm btn-outline-dark">
                                            نقل إلى المستوى الأعلى
                                        </button>
                                    </div>
                                    <input type="text" class="form-control mt-3 w-full text-right"
                                        wire:model.debounce.400ms="moveSearchText"
                                        placeholder="اسم الحساب أو الكود">
                                    @error('moveParentId')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                    <div class="mt-2 max-h-48 overflow-y-auto">
                                        @foreach ($moveSearchResults as $result)
                                            <div wire:click="selectMoveParent({{ $result->id }})"
                                                class="cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-600 rounded-md p-2 border-b border-slate-100 dark:border-slate-600">
                                                <p class="text-sm text-slate-800 dark:text-white">{{ $result->name }}
                                                </p>
                                                <p class="text-xs text-slate-500 dark:text-slate-300">
                                                    {{ $result->full_code }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div
                                class="bg-warning-50 dark:bg-warning-900/20 border border-warning-200 dark:border-warning-800 rounded-lg p-4">
                                <p class="text-sm text-warning-700 dark:text-warning-300">
                                    سيتم نقل حساب «{{ $account->name }}» ({{ $account->full_code }}) مع
                                    <b>كل الحسابات الفرعية التابعة له</b>
                                    @if ($moveToRoot)
                                        إلى المستوى الأعلى في نفس الحساب الرئيسي.
                                    @elseif ($moveParent)
                                        ليصبح تابعًا لحساب «{{ $moveParent->name }}».
                                    @else
                                        إلى الحساب الأب الذي تختاره.
                                    @endif
                                </p>
                            </div>

                            <p class="text-sm text-slate-700 dark:text-slate-300 font-medium">ماذا سيحدث بالضبط؟</p>
                            <ul class="text-sm text-slate-600 dark:text-slate-300 space-y-2 list-disc pr-5">
                                <li>
                                    الحساب وكل حساباته الفرعية ينتقلون معًا؛ <b>القيود تبقى على نفس الحسابات</b> ولا
                                    تُحذف ولا تُنقل إلى حساب آخر.
                                </li>
                                <li>
                                    <b>الأرصدة لا تتغير</b> — القيود مربوطة بمعرّف الحساب وليس بمكانه في الشجرة، لذلك
                                    لا حاجة لإعادة حساب الأرصدة.
                                </li>
                                <li>
                                    <b>كود الحساب</b> يُمواءَم مع مكانه الجديد في شجرة الحسابات.
                                </li>
                                <li>
                                    إذا كان آخر رقم في كود الحساب المنقول <b>مستخدمًا بالفعل</b> عند الحساب الأب
                                    الجديد، يُعيَّن رقم تالي تلقائيًا لتجنّب التعارض.
                                </li>
                                <li>
                                    لا يمكن اختيار حساب أب <b>له قيود يومية</b> — الحساب الذي يصبح له حسابات فرعية لا
                                    يقبل قيودًا جديدة.
                                </li>
                                <li>
                                    لا يمكن نقل الحساب تحت <b>أحد حساباته الفرعية</b> (لتجنّب حلقة في الشجرة).
                                </li>
                            </ul>
                        </div>

                        <div
                            class="flex items-center justify-start p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="moveAccount" @disabled(!$moveToRoot && !$moveParentId)
                                class="btn inline-flex justify-center btn-primary disabled:opacity-50 disabled:cursor-not-allowed">
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="moveAccount"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                                <span wire:loading.remove wire:target="moveAccount">تنفيذ النقل</span>
                            </button>
                            <button wire:click="closeMoveModal" class="btn inline-flex justify-center btn-outline-dark">
                                إلغاء
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- دمج الحساب في حساب آخر --}}
    @if ($isMergeModalOpen)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="merge_account_modal" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none" style="max-width: 600px;">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700" dir="rtl">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white">
                                دمج الحساب في حساب آخر
                            </h3>

                            <button wire:click="closeMergeModal" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">إغلاق</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4 text-right">
                            <div class="form-group">
                                <label for="mergeSearchText" class="form-label">ابحث عن الحساب الذي سيتم الدمج
                                    فيه</label>
                                @if ($mergeTarget)
                                    <div
                                        class="flex items-center justify-between bg-slate-100 dark:bg-slate-600 rounded-md p-3 mt-2">
                                        <div>
                                            <p class="text-sm font-medium text-slate-800 dark:text-white">
                                                {{ $mergeTarget->name }}</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-300">
                                                {{ $mergeTarget->full_code }}</p>
                                        </div>
                                        <button wire:click="clearMergeTarget"
                                            class="btn btn-sm btn-outline-dark">تغيير</button>
                                    </div>
                                @else
                                    <input type="text" id="mergeSearchText" class="form-control mt-2 w-full text-right"
                                        wire:model.debounce.400ms="mergeSearchText" placeholder="اسم الحساب أو الكود">
                                    @error('mergeTargetId')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                    <div class="mt-2 max-h-48 overflow-y-auto">
                                        @foreach ($mergeSearchResults as $result)
                                            <div wire:click="selectMergeTarget({{ $result->id }})"
                                                class="cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-600 rounded-md p-2 border-b border-slate-100 dark:border-slate-600">
                                                <p class="text-sm text-slate-800 dark:text-white">{{ $result->name }}
                                                </p>
                                                <p class="text-xs text-slate-500 dark:text-slate-300">
                                                    {{ $result->full_code }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div
                                class="bg-warning-50 dark:bg-warning-900/20 border border-warning-200 dark:border-warning-800 rounded-lg p-4">
                                <p class="text-sm text-warning-700 dark:text-warning-300">
                                    سيتم دمج حساب «{{ $account->name }}» في
                                    @if ($mergeTarget)
                                        حساب «{{ $mergeTarget->name }}»، ثم حذف حساب «{{ $account->name }}».
                                    @else
                                        الحساب الذي تختاره، ثم حذف هذا الحساب.
                                    @endif
                                </p>
                            </div>

                            <p class="text-sm text-slate-700 dark:text-slate-300 font-medium">ماذا سيحدث بالضبط؟</p>
                            <ul class="text-sm text-slate-600 dark:text-slate-300 space-y-2 list-disc pr-5">
                                <li>
                                    كل القيود (الحالية والمعلّقة والمؤرشفة) تنتقل إلى الحساب الآخر، ولا يُحذف أي قيد.
                                </li>
                                <li>
                                    الحساب الآخر <b>يحتفظ باسمه وكوده ومكانه</b> في شجرة الحسابات.
                                </li>
                                <li>
                                    <b>الرصيد الافتتاحي</b> للحسابين يُجمع معًا (مع عكس الإشارة إذا اختلفت طبيعة
                                    الحسابين مدين/دائن)، ثم يُعاد حساب كل الأرصدة، فلا يضيع أي رصيد.
                                </li>
                                <li>
                                    إذا وُجد قيد يحتوي على الحسابين معًا، يتم دمج الطرفين في سطر واحد، وإذا ألغى أحدهما
                                    الآخر يُحذف السطر من القيد.
                                </li>
                                <li>
                                    الحسابات الفرعية التابعة لهذا الحساب تصبح تابعة للحساب الآخر. وانتبه: الحساب الذي
                                    يصبح له حسابات فرعية لا يقبل قيودًا جديدة.
                                </li>
                                <li>
                                    الارتباطات الأخرى (بروفايلات العمولة، شركات التأمين، إعدادات الحساب، عناوين القيود)
                                    تنتقل إلى الحساب الآخر.
                                </li>
                                <li>
                                    لا يمكن الدمج إذا اختلفت عملة الحسابين.
                                </li>
                            </ul>
                        </div>

                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-start p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="mergeAccount" @disabled(!$mergeTargetId)
                                class="btn inline-flex justify-center btn-primary disabled:opacity-50 disabled:cursor-not-allowed">
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="mergeAccount"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                                <span wire:loading.remove wire:target="mergeAccount">تنفيذ الدمج</span>
                            </button>
                            <button wire:click="closeMergeModal" class="btn inline-flex justify-center btn-outline-dark">
                                إلغاء
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Clear Parent & Children Balances Modal --}}
    @if ($isClearBalancesModalOpen)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="clear_balances_modal" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none" style="max-width: 500px;">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Clear Parent &amp; Children Balances
                            </h3>

                            <button wire:click="closeClearBalancesModal" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
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
                            <div class="bg-danger-50 dark:bg-danger-900/20 border border-danger-200 dark:border-danger-800 rounded-lg p-4">
                                <div class="flex">
                                    <iconify-icon icon="lucide:alert-triangle" class="text-danger-600 dark:text-danger-400 text-xl mr-2"></iconify-icon>
                                    <div>
                                        <p class="text-sm text-danger-700 dark:text-danger-300">
                                            This will set the opening balance of <strong>{{ $account->name }}</strong> and
                                            all of its child accounts (at any level) to zero, then recalculate all entry
                                            balances for all accounts. This may take a moment and cannot be undone.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="closeClearBalancesModal"
                                class="btn inline-flex justify-center btn-outline-dark">
                                Cancel
                            </button>
                            <button wire:click="clearBalancesWithChildren"
                                class="btn inline-flex justify-center btn-danger">
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="clearBalancesWithChildren"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                                <span wire:loading.remove wire:target="clearBalancesWithChildren">Clear Balances</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Set Opening Balance Modal --}}
    @if ($isOpeningBalanceModalOpen)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="opening_balance_modal" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none" style="max-width: 500px;">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Set Opening Balance
                            </h3>

                            <button wire:click="closeOpeningBalanceModal" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
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
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-4">
                                <div class="flex">
                                    <iconify-icon icon="lucide:info" class="text-blue-600 dark:text-blue-400 text-xl mr-2"></iconify-icon>
                                    <div>
                                        <p class="text-sm text-blue-700 dark:text-blue-300">
                                            Setting the opening balance will recalculate all entry balances for all accounts. This may take a moment.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-5">
                                <label for="openingBalance" class="form-label">Balance (EGP)</label>
                                <input type="number" step="0.01" id="openingBalance"
                                    class="form-control mt-2 w-full {{ $errors->has('openingBalance') ? '!border-danger-500' : '' }}"
                                    wire:model="openingBalance" placeholder="Enter opening balance">
                                @error('openingBalance')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-5">
                                <label for="openingForeignBalance" class="form-label">Foreign Balance (Optional)</label>
                                <input type="number" step="0.01" id="openingForeignBalance"
                                    class="form-control mt-2 w-full {{ $errors->has('openingForeignBalance') ? '!border-danger-500' : '' }}"
                                    wire:model="openingForeignBalance" placeholder="Enter foreign balance">
                                @error('openingForeignBalance')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                <p class="text-sm text-slate-500 mt-1">Only needed for accounts with foreign currency</p>
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="closeOpeningBalanceModal"
                                class="btn inline-flex justify-center btn-outline-dark">
                                Cancel
                            </button>
                            <button wire:click="setOpeningBalance"
                                class="btn inline-flex justify-center btn-primary">
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setOpeningBalance"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                                <span wire:loading.remove wire:target="setOpeningBalance">Set Balance</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
