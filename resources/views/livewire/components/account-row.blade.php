<tr>
    <td class="table-td"><b>{{ $account->full_code }}</b></td>
    <td class="table-td"><b>{{ str_repeat('• ', $level) . $account->name }}</b></td>
    <td class="table-td">{{ $account->desc }}</td>
    <td class="table-td">{{ number_format($account->balance, 2) }}</td>
    <td class="table-td">{{ number_format($account->foreign_balance ?? 0, 2) }}</td>
    <td class="table-td">
        <span class="badge bg-secondary-500 text-white capitalize inline-flex items-center">
            @if ($account->nature === 'credit')
                <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:arrow-circle-up"></iconify-icon>
            @elseif ($account->nature === 'debit')
                <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:arrow-circle-down"></iconify-icon>
            @else
                <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:question-mark-circle"></iconify-icon>
            @endif
            {{ ucfirst($account->nature) }}
        </span>
    </td>
    <td class="table-td">
        <span class="badge bg-primary-500 text-white capitalize inline-flex items-center">
            @switch($account->main_account->type)
                @case('expense')
                    <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:cash"></iconify-icon>
                @break

                @case('revenue')
                    <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:currency-dollar"></iconify-icon>
                @break

                @case('asset')
                    <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:home"></iconify-icon>
                @break

                @case('liability')
                    <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:document-text"></iconify-icon>
                @break

                @default
                    <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:badge-check"></iconify-icon>
            @endswitch
            {{ ucfirst($account->main_account->name) }}
        </span>
    </td>
    <td class="table-td">{{ $account->default_currency }}</td>

    <td class="table-td">
        @if($account->children_accounts->isNotEmpty())
            @if (in_array($account->id, $showChildAccounts))
                <button class="action-btn" type="button" wire:click="hideThisChildAccount({{ $account->id }})">
                    <iconify-icon icon="mingcute:up-fill"></iconify-icon>
                </button>
            @else
                <button class="action-btn" type="button" wire:click="showThisChildAccount({{ $account->id }})">
                    <iconify-icon icon="mingcute:down-fill"></iconify-icon>
                </button>
            @endif
        @endif
    </td>
</tr>

<!-- Recursive call to display child accounts if any -->
@if (in_array($account->id, $showChildAccounts))
    @foreach ($account->children_accounts as $childAccount)
        @include('livewire.components.account-row', ['account' => $childAccount, 'level' => $level + 1])
    @endforeach
@endif
