<div>
    <div class="space-y-5">
        <div class="card">
            <header class="card-header noborder">
                <h4 class="card-title">قائمة الدخل - Income Statement Report</h4>
            </header>
            <div class="card-body px-6 pb-6">
                @if(!$isConfigured)
                    <div class="alert alert-warning mb-4">
                        <div class="flex items-start space-x-3 rtl:space-x-reverse">
                            <div class="flex-1">
                                <h5 class="text-base mb-1 font-semibold text-warning-600">Configuration Required</h5>
                                <p class="text-sm text-warning-600">
                                    Please configure all account settings before generating the report.
                                </p>
                                <div class="mt-2">
                                    <a href="{{ url('accounts/settings') }}" class="btn btn-sm btn-warning">
                                        Go to Settings
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="form-group">
                        <label for="startDate" class="form-label">Start Date (التاريخ الأول)</label>
                        <input wire:model="startDate" type="date" id="startDate" class="form-control" 
                            {{ !$isConfigured ? 'disabled' : '' }}>
                        @error('startDate') 
                            <span class="text-danger-500 text-sm">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="endDate" class="form-label">End Date (التاريخ الثاني)</label>
                        <input wire:model="endDate" type="date" id="endDate" class="form-control"
                            {{ !$isConfigured ? 'disabled' : '' }}>
                        @error('endDate') 
                            <span class="text-danger-500 text-sm">{{ $message }}</span> 
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end">
                    <button wire:click="generateReport" 
                        class="btn btn-dark"
                        {{ !$isConfigured || $isGenerating ? 'disabled' : '' }}>
                        @if($isGenerating)
                            <iconify-icon icon="svg-spinners:ring-resize" class="text-xl mr-2"></iconify-icon>
                            Generating...
                        @else
                            <iconify-icon icon="heroicons:document-arrow-down" class="text-xl mr-2"></iconify-icon>
                            Generate Report
                        @endif
                    </button>
                </div>

                @if($isConfigured)
                    <div class="mt-6 p-4 bg-slate-50 dark:bg-slate-700 rounded-md">
                        <h5 class="text-sm font-semibold mb-2">Report Preview</h5>
                        <p class="text-sm text-slate-600 dark:text-slate-300 mb-3">
                            The report will compare account balances between:
                        </p>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div class="p-3 bg-white dark:bg-slate-800 rounded border border-slate-200 dark:border-slate-600">
                                <div class="text-slate-500 dark:text-slate-400 mb-1">Start Date:</div>
                                <div class="font-semibold">{{ $startDate ? \Carbon\Carbon::parse($startDate)->format('F d, Y') : 'Not set' }}</div>
                            </div>
                            <div class="p-3 bg-white dark:bg-slate-800 rounded border border-slate-200 dark:border-slate-600">
                                <div class="text-slate-500 dark:text-slate-400 mb-1">End Date:</div>
                                <div class="font-semibold">{{ $endDate ? \Carbon\Carbon::parse($endDate)->format('F d, Y') : 'Not set' }}</div>
                            </div>
                        </div>
                        
                        <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded">
                            <div class="flex items-start">
                                <iconify-icon icon="heroicons:information-circle" class="text-blue-500 text-xl mr-2 flex-shrink-0 mt-0.5"></iconify-icon>
                                <div class="text-sm text-blue-700 dark:text-blue-300">
                                    The report will include:
                                    <ul class="list-disc list-inside mt-2 space-y-1">
                                        <li>Net Revenues (صافي الإيرادات)</li>
                                        <li>Cost of Revenues (تكلفة الحصول علي الايرادات)</li>
                                        <li>Operating Expenses (مصروفات التشغيل)</li>
                                        <li>Other Income/Expenses (إيرادات ومصروفات أخرى)</li>
                                        <li>Taxes (الضرائب)</li>
                                        <li>Net Profit/Loss (صافي الربح/الخسارة)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        Livewire.on('downloadReport', (url) => {
            window.open(url, '_blank');
        });
    </script>
    @endpush
</div>
