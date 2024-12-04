<?php

namespace App\Livewire\Pages;

use App\Models\Ad;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Checkout extends Component
{
    public Ad $ad;

    public function mount(Ad $ad)
    {
        $this->ad = $ad;
        $this->ad->load('service', 'radioStation', 'timeSlots');
    }

    public function render()
    {
        $checkout = Auth::user()->checkout([
            'pri_01jas35te8naek9r2zswkh46zp' => $this->ad->timeSlots->count(),
        ])
        ->customData([
            'ad_id' => $this->ad->id
        ])
        ->returnTo(route('ads'));

        return view('livewire.pages.checkout', ['checkout' => $checkout]);
    }
}
