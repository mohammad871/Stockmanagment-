<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

class Customer extends Component
{
    public $customer;
    public $showModal = false;
    public $typeModal = "add";
    public $data = [];

    public function render()
    {
        $records = \App\Models\Customer::all();
        return view('livewire.customer', compact('records'));
    }

    public function create() {
        $this->data = [];
        $this->typeModal = 'add';
        $this->toggleModal(true);
    }

    public function store() {
        Validator::make($this->data, [
            'name'=> 'required',
            'phone'=> 'unique:customers',
            'tazkira'=> 'unique:customers',
        ])->validate();

        $hijri_date = str_replace("/", "-", Jalalian::now()->toDateString());
        $this->data['hijri_date'] = $hijri_date;

        $customer = \App\Models\Customer::create($this->data);

        flash()->addSuccess(
            'مشتری موفقانه اضافه گردید!',
            'مشتری',
        );

        if(session()->has('addCustomer')) {
            session()->put('addCustomer', $customer->id);
            $this->redirect('/sell');
        }

        $this->data = [];

        $this->dispatchBrowserEvent("closeModal");
    }

    public function edit(\App\Models\Customer $customer) {
        $this->customer = $customer;
        $this->data = $customer->toArray();
        $this->typeModal = "edit";
        $this->toggleModal(true);
    }

    public function update() {
        Validator::make($this->data, [
            'name'=> 'required',
            'phone'=> 'nullable|unique:customers,phone,'.$this->customer->id,
            'tazkira'=> 'nullable|unique:customers,tazkira,'.$this->customer->id,
        ])->validate();

        $this->customer->update($this->data);
        flash()->addSuccess(
            ") مشتری {$this->customer->name} موفقانه تغیر یافت گردید! (",
            'مشتری',
        );

        $this->dispatchBrowserEvent('closeModal');
    }

    public function delete(\App\Models\Customer $customer) {
        $this->customer = $customer;
        $this->customer->delete();
        flash()->addError(
            ") مشتری {$this->customer->name} موفقانه حذف گردید! (",
            'مشتری',
        );
    }

    public function toggleModal($value) {
        $this->showModal = $value;
        $this->dispatchBrowserEvent('toggle-modal', ['data'=> $value]);
    }
}
