<div class="container">
    <div class="alert alert-info row m-1 mb-3 d-flex align-items-center">
        <div class="col-auto">
            <i class="bi bi-exclamation-lg fs-4"></i>
        </div>
        <div class="col">
            {{ __("If you lost or found something, please reach out to Con Ops (Leitstelle)!") }}
        </div>
    </div>

    <div class="row g-3">
        <form class="input-group">
            <span class="input-group-text">
                <i class="bi bi-search" wire:loading.remove wire:target="search"></i>
                <div class="spinner-border spinner-border-sm" role="status" wire:loading wire:target="search"></div>
            </span>
            <x-form.input-floating
                type="search"
                wire:model.live.debounce.500ms="search"
                :placeholder="__('Search')"
            />
            <button
                type="reset"
                class="btn btn-outline-secondary"
                aria-label="{{ __('Clear search') }}"
                title="{{ __('Clear search') }}"
            >
                <i class="bi bi-x-lg"></i>
            </button>
        </form>
    </div>

    <div class="sticky-top container-fluid p-0 mt-3">
        <ul class="nav nav-underline navbar-nav-scroll d-flex bg-body flex-nowrap pt-2" role="tablist">
            <li class="nav-item flex-fill" role="presentation">
                <button
                    @class(["nav-link w-100 h-100", "active" => $tab === 'lost'])
                    type="button"
                    wire:click="setTab('lost')"
                >
                    <h4 class="mb-0">
                        {{ __("Lost") }}
                        <span class="badge text-bg-secondary">{{ $this->lostCount }}</span>
                    </h4>
                </button>
            </li>
            <li class="nav-item flex-fill" role="presentation">
                <button
                    @class(["nav-link w-100 h-100", "active" => $tab === 'found'])
                    type="button"
                    wire:click="setTab('found')"
                >
                    <h4 class="mb-0">
                        {{ __("Found") }}
                        <span class="badge text-bg-secondary">{{ $this->foundCount }}</span>
                    </h4>
                </button>
            </li>
        </ul>
    </div>

    <div class="mt-3" wire:loading.remove wire:target="search">
        @forelse($this->itemsPaginated as $item)
            <x-lostfound.card :item="$item" wire:key="{{ $item->id }}" />
        @empty
            <div class="p-3">
                @if($search !== '')
                    {{ __("Nothing found") }}
                @elseif($tab === 'lost')
                    {{ __("Nothing has been lost yet") }}
                @else
                    {{ __("Nothing has been found yet") }}
                @endif
            </div>
        @endforelse
    </div>

    <div class="mt-3" wire:loading.remove wire:target="search">
        {{ $this->itemsPaginated->links() }}
    </div>
</div>
