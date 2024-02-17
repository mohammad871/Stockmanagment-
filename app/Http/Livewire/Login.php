<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use \Illuminate\Support\Facades\Validator;

class Login extends Component
{
    public $data = [];

    public function render()
    {
        return view('livewire.login');
    }

    public function login() {
        Validator::make($this->data, [
            'name'=> 'required',
            'password'=> 'required'
        ], [
            'password.required'=> 'پسورد ضرور است.'
        ])->validate();

        $user = Auth::attempt($this->data);
        if($user) {
            $this->redirect('/');
        } else {
            flash()->addError(
                "نام کاربری یا پسورد تان نادرست است.",
                "مشکل نام کاربری / پسورد"
            );
        }

    }


    public function logout() {
        Auth::logout();
        return redirect()->to('login');
    }

}
