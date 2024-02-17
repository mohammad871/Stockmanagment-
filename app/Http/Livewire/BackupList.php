<?php

namespace App\Http\Livewire;

use Livewire\Component;

class BackupList extends Component
{
    public function render()
    {
        $records = glob(storage_path('app\Laravel\*'));
        foreach ($records as $index=>$record) {
            $record = explode("\\", $record);
            $records[$index] = array_pop($record);
        }
        return view('livewire.backup-list', compact('records'));
    }



    public function delete($record) {
        $record = storage_path('app\Laravel\\'.$record);
        if(file_exists($record)) {
            unlink($record);

            flash()->addSuccess("
                فایل بک آپ موفقانه حذف گردید.
            ");
        }
    }


    public function download($record) {
        $record = storage_path('app\Laravel\\'.$record);
        if(file_exists($record)) {
            flash()->addSuccess("
                فایل بک آپ موفقانه دانلود گردید.
            ");
            return response()->download($record);
        }
    }
}
