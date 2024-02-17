<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

class User extends Component
{
    public $user;
    public $showModal = false;
    public $typeModal = "add";

    public function render()
    {
        $records = \App\Models\User::all();
        return view('livewire.user', compact('records'));
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
            'name'=> 'required|unique:users',
            'password'=> 'required|confirmed',
        ], [
            'name.unique'=> 'نام کاربر تکرار  بوده نمی تواند.',
            'password.required'=> 'پسورد ضرور است.',
            'password.confirmed'=> 'لطفا تکرار پسورد را درست وارد کنید.',
        ])->validate();

        $this->data['hijri_date'] = str_replace("/", "-", Jalalian::now()->toDateString());
        $this->data['password'] = Hash::make($this->data['password']);

        \App\Models\User::create($this->data);
        flash()->addSuccess(
            'کاربر موفقانه اضافه گردید.',
            'کاربر',
        );
        $this->data = [];
    }

    public function edit(\App\Models\User $user) {
        $this->typeModal = "edit";
        $this->user = $user;
        $this->data = $user->toArray();
        $this->toggleModal(true);
    }

    public function update() {
        Validator::make($this->data, [
            'name'=> 'required|unique:users,name,'.$this->user->id,
        ])->validate();

        $this->user->update($this->data);
        flash()->addSuccess(
            'کاربر موفقانه تغیر یافت.',
            'کاربر',
        );
    }

    public function delete(\App\Models\User $user) {
        $this->user = $user;
        $this->user->delete();
        flash()->addError(
            "کاربر
            ({$this->user->name})
            موفقانه حذف گردید.",
            'کاربر',
        );
    }

    public function toggleModal($value) {
        $this->showModal = $value;
        $this->dispatchBrowserEvent('toggle-modal', ['data'=> $value]);
    }
}
