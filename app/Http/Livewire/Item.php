<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;

class Item extends Component
{
    use WithPagination;

    public $item;
    public $showModal = false;
    public $typeModal = "add";
    public $data = [];

    public function render()
    {
        $records = \App\Models\Items::all();
        return view('livewire.item', compact('records'));
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
            'name'=> 'required|unique:items',
        ])->validate();

        $hijri_date = str_replace("/", "-", Jalalian::now()->toDateString());
        $this->data['hijri_date'] = $hijri_date;

        \App\Models\Items::create($this->data);
        flash()->addSuccess(
            'جنس موفقانه اضافه گردید.',
            'جنس',
        );
        $this->data = [];

        $this->dispatchBrowserEvent('closeModal');
    }

    public function edit(\App\Models\Items $item) {
        $this->typeModal = "edit";
        $this->item = $item;
        $this->data = $item->toArray();
        $this->toggleModal(true);
    }

    public function update() {
        Validator::make($this->data, [
            'name'=> 'required|unique:items,name,'.$this->item->id,
        ])->validate();

        $this->item->update($this->data);
        flash()->addSuccess(
            'جنس موفقانه تغیر یافت.',
            'جنس',
        );

        $this->dispatchBrowserEvent('closeModal');
    }

    public function delete(\App\Models\Items $item) {
        $this->item = $item;
        $this->item->delete();
        flash()->addError(
            "جنس
             ({$this->item->name})
             موفقانه حذف گردید. ",
            'جنس',
        );
    }

    public function toggleModal($value) {
        $this->showModal = $value;
        $this->dispatchBrowserEvent('toggle-modal', ['data'=> $value]);
    }

}
