<?php

namespace App\Livewire\Pages;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ListAds extends Component
{
    use WithPagination;

    public $search;

    #[Url]
    public $sortColumn = '';

    #[Url]
    public $sortAscending = true;

    public function sortBy($column)
    {
        if ($column == $this->sortColumn) {
            $this->sortAscending = !$this->sortAscending;
        } else {
            $this->sortAscending = true;
        }

        $this->sortColumn = $column;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $sortAscending = $this->sortAscending;
        $sortColumn = match ($this->sortColumn) {
            'station' => 'radio_stations.name',
            'status' => 'ads.status',
            'service' => 'services.description',
            'total' => 'time_slots_count',
            'creation_date' => 'ads.created_at',
            default => ''
        };
        $ads = Auth::user()
            ->ads()
            ->with(['radioStation', 'service'])
            ->withCount('timeSlots')
            ->join("radio_stations", "radio_stations.id", "=", "ads.radio_station_id")
            ->join("services", "services.id", "=", "ads.service_id")
            ->when($this->search, function (Builder $query, $search) {
                $search = strtolower(str_replace(' ', '%', $search));
                $query
                    ->whereHas('radioStation', fn (Builder $query) => $query->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('service', fn (Builder $query) => $query->where('description', 'like', "%{$search}%"));
            })
            ->when($sortColumn, fn (Builder $query, $column) => $query->orderBy($column, $sortAscending ? 'asc' : 'desc'))
            ->paginate(5);

        return view('livewire.pages.list-ads', [ 'ads' => $ads ]);
    }
}
