<?php

namespace App\Http\Livewire;

use Livewire\Component;

class MasterPage extends Component
{
    public $pageName;


    public function render()
    {
        return view('livewire.master-page');
    }


    public function index() {
        $this->pageName = "main";
    }
}
