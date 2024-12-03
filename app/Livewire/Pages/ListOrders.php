<?php

namespace App\Livewire\Pages;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ListOrders extends Component
{
    public function render()
    {
        $orders = Auth::user()->ads;

        return view('livewire.pages.list-orders', [ 'orders' => $orders ]);
    }
}
