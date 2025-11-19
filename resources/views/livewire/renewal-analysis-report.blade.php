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
					<label class="form-label">Month </label>
					<select class="form-control" wire:model="selectedMonth">
						<option value="-1">Select month</option>
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
						<option value="-1">All users</option>
						@foreach($users as $usr)
							<option value="{{ $usr['id'] }}">{{ $usr['title'] }}</option>
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
						<span>User: {{ collect($users)->firstWhere('id', (int) $selectedUserId)['title'] ?? $selectedUserId }}</span>
					@endif
				</div>
			</header>
			<div class="card-body px-6 pb-6">
				<div class="overflow-x-auto -mx-6">
					<div class="inline-block min-w-full align-middle">
						<div class="overflow-hidden">
							<table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
								<thead class="bg-slate-200 dark:bg-slate-700">
									<tr>
										<th scope="col" class="table-th">Metric</th>
										<th scope="col" class="table-th text-right">Count</th>
										<th scope="col" class="table-th text-right">% of Expiring</th>
										<th scope="col" class="table-th text-right">% of Offers</th>
										<th scope="col" class="table-th text-right">Net Premium</th>
										<th scope="col" class="table-th text-right">% of Expiring Net</th>
									</tr>
								</thead>
								<tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
									<tr class="even:bg-slate-50 dark:even:bg-slate-700">
										<td class="table-td">Total expiring sold policies</td>
										<td class="table-td text-right">{{ $stats['totalExpiringSoldPolicies'] ?? 0 }}</td>
										<td class="table-td text-right">100%</td>
										<td class="table-td text-right">-</td>
										<td class="table-td text-right">{{ number_format($stats['sumNetExpiring'] ?? 0, 2) }}</td>
										<td class="table-td text-right">100%</td>
									</tr>
									<tr class="even:bg-slate-50 dark:even:bg-slate-700">
										<td class="table-td">Total offers for these expiring policies</td>
										<td class="table-td text-right">{{ $stats['totalOffersForExpiring'] ?? 0 }}</td>
										<td class="table-td text-right">{{ number_format($stats['pctOffersOfExpiring'] ?? 0, 2) }}%</td>
										<td class="table-td text-right">100%</td>
										<td class="table-td text-right">-</td>
										<td class="table-td text-right">-</td>
									</tr>
									<tr class="even:bg-slate-50 dark:even:bg-slate-700">
										<td class="table-td">Total new sold policies created from these offers</td>
										<td class="table-td text-right">{{ $stats['totalNewSoldPoliciesFromOffers'] ?? 0 }}</td>
										<td class="table-td text-right">{{ number_format($stats['pctNewOfExpiring'] ?? 0, 2) }}%</td>
										<td class="table-td text-right">{{ number_format($stats['pctNewOfOffers'] ?? 0, 2) }}%</td>
										<td class="table-td text-right">{{ number_format($stats['sumNetNewFromOffers'] ?? 0, 2) }}</td>
										<td class="table-td text-right">{{ number_format($stats['pctNetNewOfExpiring'] ?? 0, 2) }}%</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	@elseif($showResults && $selectedYear && !$selectedMonth)
		<div class="card">
			<header class="card-header cust-card-header noborder justify-between">
				<h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900">
					Renewal Analysis - Yearly Summary
				</h4>
				<div class="text-sm text-slate-500">
					<span>Year: {{ $selectedYear }}</span>
					@if($selectedUserId)
						<span class="mx-2">|</span>
						<span>User: {{ collect($users)->firstWhere('id', (int) $selectedUserId)['title'] ?? $selectedUserId }}</span>
					@endif
				</div>
			</header>
			<div class="card-body px-6 pb-6">
				<div class="overflow-x-auto -mx-6">
					<div class="inline-block min-w-full align-middle">
						<div class="overflow-hidden">
							<table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
								<thead class="bg-slate-200 dark:bg-slate-700">
									<tr>
										<th scope="col" class="table-th">Month</th>
										<th scope="col" class="table-th text-right">Expiring</th>
										<th scope="col" class="table-th text-right">Offers</th>
										<th scope="col" class="table-th text-right">New Sold</th>
										<th scope="col" class="table-th text-right">% Offers of Expiring</th>
										<th scope="col" class="table-th text-right">% New of Offers</th>
										<th scope="col" class="table-th text-right">% New of Expiring</th>
										<th scope="col" class="table-th text-right">Expiring Net</th>
										<th scope="col" class="table-th text-right">New Net</th>
										<th scope="col" class="table-th text-right">% New Net of Expiring Net</th>
									</tr>
								</thead>
								<tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
									@foreach($yearlyStats as $row)
										<tr class="even:bg-slate-50 dark:even:bg-slate-700">
											<td class="table-td">{{ $row['name'] }}</td>
											<td class="table-td text-right">{{ $row['totalExpiringSoldPolicies'] }}</td>
											<td class="table-td text-right">{{ $row['totalOffersForExpiring'] }}</td>
											<td class="table-td text-right">{{ $row['totalNewSoldPoliciesFromOffers'] }}</td>
											<td class="table-td text-right">{{ number_format($row['pctOffersOfExpiring'], 2) }}%</td>
											<td class="table-td text-right">{{ number_format($row['pctNewOfOffers'], 2) }}%</td>
											<td class="table-td text-right">{{ number_format($row['pctNewOfExpiring'], 2) }}%</td>
											<td class="table-td text-right">{{ number_format($row['sumNetExpiring'], 2) }}</td>
											<td class="table-td text-right">{{ number_format($row['sumNetNewFromOffers'], 2) }}</td>
											<td class="table-td text-right">{{ number_format($row['pctNetNewOfExpiring'], 2) }}%</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endif
</div>


