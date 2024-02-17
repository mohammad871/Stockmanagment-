<?php

namespace App\Http\Livewire;

use App\Models\Items;
use App\Models\Stock;
use App\Models\StockItem;
use App\Models\StockOperation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

class Purchase extends Component
{
    public $customError;
    public $formNumber = 1;
    public $operation = [0=>1];

    public $purchase;

    public $data = [];
    public $operationType = "add"; 
    public $TYPE = "PURCHASE";

    public function render()
    {
        $items = Items::all();
        $stocks = Stock::all();
        $records = StockOperation::where('stock_operation_type', "=", $this->TYPE)
            ->join("items", "stock_operation.item_id", "=", "items.id")
            ->join("stock", "stock_operation.stock_id", "=", "stock.id")
            ->select("stock_operation.id as id", "stock_operation.created_at as created_at", "stock_operation.quantity as quantity", "items.name as item_name", "stock.name as stock_name", "stock_operation.hijri_date as hijri_date")
            ->get(); 

        return view('livewire.purchase', compact('records', 'items', 'stocks'));
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

    /**
     * @throws ValidationException
     */
    public function store() {
        $result = null;
        $this->validation();

        $billNumber = $this->data[0]['bill_number'];

        foreach ($this->data as $key=> $data) {
            if (!isset($data['stock_id']) || !isset($data['item_id'])) {
                continue;
            }


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

                $data['bill_number'] = $billNumber;
                StockOperation::create($data);
                flash()->addSuccess(
                    'خریداری موفقانه انجام شد.',
                    'خریداری',
                );
            }
        }

        if(!$this->customError) {
            $this->resetForm();
        }
    }

    public function edit(\App\Models\StockOperation $purchase) {
        $this->purchase = $purchase;
        $itemId = $purchase->item_id;
        $purchase->item_id = $this->checkItem($itemId, "name");

        $this->data = [$purchase->toArray()];
        $this->operationType = "edit";
    }

    public function update() {

        $this->validation();

        foreach ($this->data as $data) {

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
                $oldStockItem = $this->stockItem(null, $this->purchase->stock_id, $this->purchase->item_id);
                $newStockItem = $this->stockItem(null, $data['stock_id'] , $data['item_id']);


                if($data['quantity'] >= $this->purchase->quantity) {
                    $add = $data['quantity'] - $this->purchase->quantity;

                    if(
                        $this->purchase->stock_id == $data['stock_id'] &&
                        $this->purchase->item_id == $data['item_id']
                    ) {
                        // increase purchase
                        $this->purchase->quantity += $add;

                        // increase stock also
                        $oldStockItem->quantity += $add;

                    } else {
                        // decrease stock also (old item)
                        $oldStockItem->quantity -= $this->purchase->quantity;

                        // increase by new quantity (new item)
                        $newStockItem->quantity += $data['quantity'];

                        $this->purchase->stock_id = $data['stock_id'];
                        $this->purchase->item_id = $data['item_id'];
                    }
                } else {
                    // subtract from purchase
                    $subtract = $this->purchase->quantity - $data['quantity'];

                    if(
                        $this->purchase->stock_id == $data['stock_id'] &&
                        $this->purchase->item_id == $data['item_id']
                    ) {
                        // increase purchase
                        $this->purchase->quantity -= $subtract;

                        // increase stock also
                        $oldStockItem->quantity -= $subtract;
                    } else {
                        // decrease stock also (old item)
                        $oldStockItem->quantity -= $this->purchase->quantity;

                        // increase by new quantity (new item)
                        $newStockItem->quantity += $data['quantity'];

                        // update purchase
                        $this->purchase->quantity = $data['quantity'];

                        $this->purchase->stock_id = $data['stock_id'];
                        $this->purchase->item_id = $data['item_id'];
                    }

                }

                $this->purchase->save();
                $oldStockItem->save();
                $newStockItem->save();

                $this->checkStockItem($newStockItem);
                $this->checkStockItem($oldStockItem);

                flash()->addSuccess(
                    'خریداری موفقانه تغیر یافت.',
                    'خریداری',
                );
                $this->list();
            }
        }
    }

    public function delete(\App\Models\StockOperation $purchase) {
        $toDelete = (Object) $purchase->toArray();
        $this->purchase = $purchase;

        if($toDelete->stock_operation_type == $this->TYPE) {
            $stockItem = $this->stockItem(null, $purchase->stock_id, $purchase->item_id);

            if($stockItem->quantity >= $toDelete->quantity) {
                $stockItem->quantity -= $toDelete->quantity;
                $stockItem->save();
                $this->purchase->delete();

                $this->checkStockItem($stockItem);

                flash()->addError(
                    "
                    خریداری موفقانه حذف گردید.
                    ",
                    'خریداری',
                );
            }
        } else {
            flash()->addWarning(
                "در حذف خریداری مشکل رخ داد دوباره کوشش نماید.",
                'خریداری',
            );
        }
    }

    public function resetForm() {
        $this->operation = [0=> 1];
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
                $key.'.stock_id' =>[ 'required'],
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
                    "quantity" => 0
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
