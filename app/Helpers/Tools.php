<?php

namespace App\Helpers;

class Tools
{
//    public $showModal = false;
//    public function toggleModal($obj) {
//        $this->showModal = !$this->showModal;
//        $obj->dispatchBrowserEvent('toggle-modal', ['data'=> $this->showModal]);
//    }

    public static function toggleModal($isShow) {
        return !$isShow;
    }
}
