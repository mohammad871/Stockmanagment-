<?php

namespace App\Http\Livewire;

use App\Models\Items;
use App\Models\Stock;
use App\Models\StockItem;
use App\Models\StockOperation;
use App\Rules\ValidateFormat;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

class ReturnOperation extends Component
{
    public $formNumber = 1;
    public $operation = [0=>1];

    public $return;
    public $data = [];
    public $operationType = "list";

    public $TYPE = "RETURN";

    public function render()
    {
        $items = Items::all();
        $stocks = Stock::all();
        $records = StockOperation::where('stock_operation_type', "=", $this->TYPE)
            ->join("items", "stock_operation.item_id", "=", "items.id")
            ->join("stock", "stock_operation.stock_id", "=", "stock.id")
            ->select("stock_operation.id as id", "stock_operation.hijri_date as hijri_date", "stock_operation.quantity as quantity", "items.name as item_name", "stock.name as stock_name")
            ->get();

        return view('livewire.return-operation', compact('records', 'items', 'stocks'));
    }

    public function newForm($formNumber) {
        $this->formNumber = ++$formNumber;
        $this->operation[] = $this->formNumber;
        $this->data[] = (new \App\Models\StockItem())->toArray();

    }

    public function removeForm($formNumber) {
        if(count($this->operation) == 2 && $formNumber  >= 2) {
            $this->formNumber = 1;
        }
        unset($this->operation[$formNumber]);
        if(isset($this->data[$formNumber+1])) {
            unset($this->data[$formNumber+1]);
        }
    }

    public function list() {
        $this->operationType = "list";
    }

    public function create() {
        $this->operationType = "add";
        $this->resetForm();
    }

    public function store() {
        $this->validation();

        $billNumber = $this->data[0]['bill_number'];
        foreach ($this->data as $key=> $data) {

            $itemId = $this->checkItem($data['item_id']);
            if (!$itemId) {
                $this->customError[$key]['notFound'] = "
                    جنس با این نام موجود نیست.
                ";
            } else {
                $this->customError = [];
                $data['item_id'] = $itemId;
            }

            if(empty($this->customError)) {
                $dbData = $this->stockItem(null, $data['stock_id'], $data['item_id']);
                $dbData->update([
                    'quantity' => $dbData->quantity + $data['quantity']
                ]);

                $data['stock_operation_type'] = $this->TYPE;
                $data['bill_number'] = $billNumber;
                StockOperation::create($data);

                flash()->addSuccess(
                    'برگشتی موفقانه انجام شد.',
                    'برگشتی',
                );
            }
        }
        if(empty($this->customError)) {
            $this->resetForm();
        }
    }

    public function edit(\App\Models\StockOperation $return) {
        $this->return = $return;
        $itemId = $return->item_id;
        $return->item_id = $this->checkItem($itemId, "name");

        $this->data = [$return->toArray()];
        $this->operationType = "edit";
    }

    public function update() {
        $this->validation();

        $data = $this->data[0];

        $data['item_id'] = $itemId = $this->checkItem($data['item_id']);
        if (!$itemId) {
            $this->customError[0]['notFound'] = "
                    جنس با این نام موجود نیست.
                ";
        } else {
            $this->customError = [];
            $data['item_id'] = $itemId;
        }

        if(empty($this->customError)) {
            $oldStockItem = $this->stockItem(null, $this->return->stock_id, $this->return->item_id);
            $newStockItem = $this->stockItem(null, $data['stock_id'], $data['item_id']);

            if ($data['quantity'] >= $this->return->quantity) {
                $add = (int)$data['quantity'] - (int)$this->return->quantity;

                if (
                    $this->return->stock_id == $data['stock_id'] &&
                    $this->return->item_id == $data['item_id']
                ) {
                    // increase return
                    $this->return->quantity += $add;

                    // increase stock also
                    $oldStockItem->quantity = $add;
                } else {
                    // decrease stock also (old item)
                    $oldStockItem->quantity -= $this->return->quantity;

                    // increase by new quantity (new item)
                    $newStockItem->quantity += $data['quantity'];

                    $this->return->stock_id = $data['stock_id'];
                    $this->return->item_id = $data['item_id'];
                }
            } else {
                // subtract from return
                $subtract = $this->return->quantity - $data['quantity'];

                if (
                    $this->return->stock_id == $data['stock_id'] &&
                    $this->return->item_id == $data['item_id']
                ) {
                    // increase return
                    $this->return->quantity -= $subtract;

                    // increase stock also
                    $oldStockItem->quantity -= $subtract;
                } else {
                    // decrease stock also (old item)
                    $oldStockItem->quantity -= $this->return->quantity;

                    // increase by new quantity (new item)
                    $newStockItem->quantity += $data['quantity'];

                    // update return
                    $this->return->quantity = $data['quantity'];

                    $this->return->stock_id = $data['stock_id'];
                    $this->return->item_id = $data['item_id'];
                }

            }

            $this->return->save();
            $oldStockItem->save();
            $newStockItem->save();

            $this->checkStockItem($newStockItem);
            $this->checkStockItem($oldStockItem);

            flash()->addSuccess(
                'برگشتی موفقانه تغیر یافت.',
                'برگشتی',
            );
            $this->list();
        }
    }

    public function delete(\App\Models\StockOperation $return) {
        $toDelete = (Object) $return->toArray();
        $this->return = $return;

        if($toDelete->stock_operation_type == $this->TYPE) {
            $stockItem = $this->stockItem(null, $toDelete->stock_id, $toDelete->item_id);
            $stockItem->quantity -= $toDelete->quantity;
            $stockItem->save();
            $this->return->delete();

            $this->checkStockItem($stockItem->first());

            flash()->addError(
                "
                برگشتی موفقانه حذف گردید.
                ",
                'برگشتی',
            );
        } else {
            flash()->addWarning(
                "در حذف برگشتی مشکل رخ داد دوباره کوشش نماید.",
                'برگشتی',
            );
        }
    }

    public function getIDFromValue($value) {
        $parts = explode("-#-", $value);
        if($parts) {
            $result = (int) $parts[1];
            if ($result) {
                return $result;
            }
        }
        return null;
    }

    public function resetForm() {
        $this->operation = [0=> 1];
        $this->formNumber = 1;
        $this->data = [];
    }

    private function validation() {
        Validator::make($this->data, [
            '0.bill_number' => 'required',
        ], [
            '0.bill_number.required'=> "بیل نمبر را وارد کنید.",
        ])->validate();

        foreach ($this->operation as $key=> $value) {
            Validator::make($this->data, [
                $key.'.item_id' => ['required'],
                $key.'.stock_id' =>['required'],
                $key.'.quantity' => 'required',
                $key.'.hijri_date' => 'required',
            ], [
                $key.'.item_id.required'=> "جنس را انتخاب کنید.",
                $key.'.stock_id.required'=> "انبار / گدام را انتخاب کنید.",
                $key.'.quantity.required'=> "مقدار را وارد کنید.",
                $key.'.hijri_date.required' => 'تاریخ ضرور است.',
            ])->validate();
        }
    }


    public function stockItem($id, $stock_id, $item_id) {
        if($id) {
            $stockItem = StockItem::find($id);
            return $stockItem ? $stockItem->first() : null;
        } else {
            $stockItem = StockItem::where([
                ['stock_id', "=", $stock_id],
                ['item_id', "=", $item_id],
            ])->get();
            if (count($stockItem) >= 1) {
                $stockItem = $stockItem->first();
            } else {
                $stockItem = StockItem::create([
                    'stock_id' => $stock_id,
                    'item_id' => $item_id,
                    'hijri_date'=> str_replace("/", "-", Jalalian::now()->toDateString()),
                    "quantity" => 0,
                ]);
            }
            return $stockItem;
        }
    }


    public function checkStockItem($stock_item) {
        if($stock_item) {
            if($stock_item->quantity == 0) {
                $stock_item->delete();
            }
        }
    }

    public function setHijriDate($value) {
        $this->data[$this->formNumber - 1]['hijri_date'] = $value;
    }

    public function checkItem($itemSelector, $returnType = "id") {

        $items = Items::all();
        foreach ($items as $item) {
            if(is_numeric($itemSelector)) {
                if($item->id == $itemSelector) {
                    if($returnType == "id") {
                        return $item->id;
                    } else {
                        return $item->name;
                    }
                }
            } else {
                if($item->name == $itemSelector) {
                    if($returnType == "id") {
                        return $item->id;
                    } else {
                        return $item->name;
                    }
                }
            }

        }
        return null;
    }

}

