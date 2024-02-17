<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Validator;
use \App\Models\StockItem;
use Livewire\Component;

class LStockItem extends Component
{
    public $stockItem;
    public $records = [];
    public $data = [];

    public function render()
    {
        $stocks = \App\Models\Stock::all();
        $this->records = StockItem::
              join("items", "stock_item.item_id", "=", "items.id")
            ->join("stock", "stock_item.stock_id", "=", "stock.id")
            ->select(
                "stock_item.id as id",
                "stock_item.created_at as created_at",
                "stock_item.hijri_date as hijri_date",
                "stock_item.quantity as quantity",
                "items.name as item_name",
                "stock.name as stock_name",
            )->get();
        return view('livewire.stock-item', compact( 'stocks'));
    }

    public function create() {
        $this->data = [];
    }

    public function store() {
        Validator::make($this->data, [
            'name'=> 'required|unique:stock_item',
        ])->validate();

        StockItem::create($this->data);
        flash()->addSuccess(
            'انبار / گدام موفقانه اضافه گردید.',
            'جنس انبار / گرام',
        );
        $this->data = [];
    }

    public function edit(\App\Models\StockItem $stockItem) {
        $this->stockItem = $stockItem;
        $this->data = $stockItem->toArray();
    }

    public function update() {
        Validator::make($this->data, [
            'name'=> 'required|unique:stock,name,'.$this->stockItem->id,
        ])->validate();

        $this->stockItem->update($this->data);
        flash()->addSuccess(
            'جنس انبار / گرام موفقانه تغیر یافت.',
            'جنس انبار / گرام',
        );
    }

    public function delete(\App\Models\StockItem $stockItem) {
        $this->stockItem = $stockItem;
        $this->stockItem->delete();
        flash()->addError(
            "جنس انبار / گرام
            ({$this->stockItem->name})
            موفقانه حذف گردید.",
            'جنس انبار / گرام',
        );
    }
}
