<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

class Stock extends Component
{
    public $stock;
    public $showModal = false;
    public $typeModal = "add";
    public $data = [];

    public function render()
    {
        $records = \App\Models\Stock::all();
        return view('livewire.stock', compact('records'));
    }

    public function create() {
        $this->typeModal = "add";
        $this->toggleModal(true);
        $this->data = [];
    }

    /**
     * @throws ValidationException
     */
    public function store() {
        Validator::make($this->data, [
            'name'=> 'required|unique:stock',
        ])->validate();

        $hijri_date = str_replace("/", "-", Jalalian::now()->toDateString());
        $this->data['hijri_date'] = $hijri_date;

        \App\Models\Stock::create($this->data);
        flash()->addSuccess(
            'انبار / گدام موفقانه اضافه گردید.',
            'گدام / انبار',
        );
        $this->data = [];
    }

    public function edit(\App\Models\Stock $stock) {
        $this->typeModal = "edit";
        $this->stock = $stock;
        $this->data = $stock->toArray();
        $this->toggleModal(true);
    }

    public function update() {
        Validator::make($this->data, [
            'name'=> 'required|unique:stock,name,'.$this->stock->id,
        ])->validate();

        $this->stock->update($this->data);
        flash()->addSuccess(
            'گدام / انبار موفقانه تغیر یافت.',
            'گدام / انبار',
        );
    }

    public function delete(\App\Models\Stock $stock) {
        $this->stock = $stock;
        $this->stock->delete();
        flash()->addError(
            "گدام / انبار
            ({$this->stock->name})
            موفقانه حذف گردید.",
            'گدام / انبار',
        );
    }

    public function toggleModal($value) {
        $this->showModal = $value;
        $this->dispatchBrowserEvent('toggle-modal', ['data'=> $value]);
    }

}
