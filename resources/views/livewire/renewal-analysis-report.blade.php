<div>
	<div class="card mb-6">
		<header class="card-header cust-card-header noborder justify-between">
			<h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900">
				Renewal Analysis - Filters
			</h4>
			<div class="flex items-center gap-2">
				<iconify-icon wire:loading class="loading-icon text-lg" icon="line-md:loading-twotone-loop"></iconify-icon>
			</div>
		</header>
		<div class="card-body px-6 pb-6">
			<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
				<div>
					<label class="form-label">Year <span class="text-red-500">*</span></label>
					<select class="form-control" wire:model="selectedYear">
						<option value="">Select year</option>
						@foreach($years as $year)
							<option value="{{ $year }}">{{ $year }}</option>
						@endforeach
					</select>
					@error('selectedYear')
						<div class="text-red-500 text-xs mt-1">{{ $message }}</div>
					@enderror
				</div>
				<div>
					<label class="form-label">Month <span class="text-red-500">*</span></label>
					<select class="form-control" wire:model="selectedMonth">
						<option value="">Select month</option>
						@foreach($months as $month)
							<option value="{{ $month['value'] }}">{{ $month['label'] }}</option>
						@endforeach
					</select>
					@error('selectedMonth')
						<div class="text-red-500 text-xs mt-1">{{ $message }}</div>
					@enderror
				</div>
				<div>
					<label class="form-label">User (optional)</label>
					<select class="form-control" wire:model="selectedUserId">
						<option value="">All users</option>
						@foreach($users as $usr)
							<option value="{{ $usr['id'] }}">{{ $usr['username'] }}</option>
						@endforeach
					</select>
					@error('selectedUserId')
						<div class="text-red-500 text-xs mt-1">{{ $message }}</div>
					@enderror
				</div>
			</div>
			<div class="mt-6">
				<button class="btn btn-primary" wire:click="load" wire:loading.attr="disabled">
					Load
				</button>
			</div>
		</div>
	</div>

	@if($showResults && $selectedYear && $selectedMonth)
		<div class="card">
			<header class="card-header cust-card-header noborder justify-between">
				<h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900">
					Renewal Analysis - Results
				</h4>
				<div class="text-sm text-slate-500">
					<span>Year: {{ $selectedYear }}</span>
					<span class="mx-2">|</span>
					<span>
						Month:
						{{ \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->format('F') }}
					</span>
					@if($selectedUserId)
						<span class="mx-2">|</span>
						<span>User: {{ collect($users)->firstWhere('id', (int) $selectedUserId)['username'] ?? $selectedUserId }}</span>
					@endif
				</div>
			</header>
			<div class="card-body px-6 pb-6">
				<div class="text-slate-600">
					{{-- Replace this section with actual analysis content --}}
					<p>Implement the renewal analysis query and presentation here.</p>
				</div>
			</div>
		</div>
	@endif
</div>


