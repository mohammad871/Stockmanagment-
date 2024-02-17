<?php

namespace App\Http\Livewire;

use App\Models\Items;
use App\Models\Stock;
use App\Models\StockItem;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

class Transfer extends Component
{
    public $customError = [];
    public $transfer;
    public $data = [];
    public $operationType = "list";
    public $formNumber = 1;
    public $operation = [0=>1];

    public function render()
    {
        $items = Items::all();
        $stocks = Stock::all();
        $records = \App\Models\Transfer::join("items", "items.id", "=", "transfer.item_id")
            ->join("stock as from_stock", "from_stock.id", "=", "transfer.from_stock")
            ->join("stock as to_stock", "to_stock.id", "=", "transfer.to_stock")
            ->select(
                'transfer.id as id',
                'transfer.bill_number as bill_number',
                'transfer.quantity as quantity',
                'transfer.hijri_date as hijri_date',
                'from_stock.name as from_stock',
                'to_stock.name as to_stock',
                'items.name as item_name',
            )->get();
        return view('livewire.transfer', compact('records', 'items', 'stocks'));
    }

    public function newForm($formNumber) {
        $this->formNumber = ++$formNumber;
        $this->operation[] = $this->formNumber;
        $this->data[] = (new \App\Models\StockItem())->toArray();
        $this->customError = [];
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
        $this->resetForm();
    }

    public function create() {
        $this->operationType = "add";
        $this->data = [];
    }

    /**
     * @throws ValidationException
     */
    public function store() {
        $data = [];
        $this->validation();

        $billNumber = $this->data[0]['bill_number'];
        foreach ($this->data as $index=> $data) {
            $itemId = $this->checkItem($data['item_id']);
            if (!$itemId) {
                $this->customError[$index]['notFound'] = "
                    جنس با این نام موجود نیست.
                ";
            } else {
                $this->customError = [];
                $data['item_id'] = $itemId;
            }

            if(empty($this->customError)) {
                if($data) {
                    $data['bill_number'] = $billNumber;
                    if(isset($data['from_stock']) && isset($data['to_stock']) && isset($data['item_id'])) {
                        $from = $this->stockItem(null, $data['from_stock'], $data['item_id']);
                        $to = $this->stockItem(null, $data['to_stock'], $data['item_id']);

                        if($from->id != $to->id) {
                            $checkInStock = $this->isItemInStock($data['item_id'], $data['from_stock'], $data['quantity']);
                            if (!$checkInStock) {
                                $this->customError[$index]['moreThanExist'] = "
                                مقدار که شما وارد کرده اید در گدام موجود نیست.
                                موجودی گدام:
                                ($from->quantity)
                                ---
                                خواسته شده:
                                ({$data['quantity']})
                                ";
                            } else {
                                \App\Models\Transfer::create($data);

                                $from->quantity -= $data['quantity'];
                                $to->quantity += $data['quantity'];

                                $from->save();
                                $to->save();

                                flash()->addSuccess(
                                    'انتقال موفقانه انجام گردید.',
                                    'انتقال',
                                );
                                if(count($this->operation) > 1) {
                                    unset($this->operation[$index]);
                                }
                                unset($this->data[$index]);
                            }
                        } else {
                            $this->customError = ['error'];
                            flash()->addWarning(
                                "لطفاً گدام متفاوت را انتخاب کنید.",
                                "عملیه ناکام شد",
                            );
                        }
                    } else {
                        flash()->addWarning(
                            "با معذرت دوباره کوشش نماید.",
                            "عملیه ناکام شد",
                        );
                    }
                }
            }
        }

        if(!$this->customError) {
            $this->checkStockItem();
            $this->resetForm();
        }
    }

    public function edit(\App\Models\Transfer $transfer) {
        $this->transfer = $transfer;
        $itemId = $transfer->item_id;
        $transfer->item_id = $this->checkItem($itemId, "name");

        $this->data = [$transfer->toArray()];
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
            $from = $this->stockItem(null, $data['from_stock'], $data['item_id']);
            $to = $this->stockItem(null, $data['to_stock'], $data['item_id']);

            if($from->id != $to->id) {
                if (
                    $from->stock_id == $this->transfer->from_stock &&
                    $to->stock_id == $this->transfer->to_stock
                ) {
                    if ($this->transfer->quantity > $data['quantity']) {
                        $subtract = $this->transfer->quantity - $data['quantity'];
                        $from->quantity += $subtract;
                        $to->quantity -= $subtract;
                    } else {
                        $add = $data['quantity'] - $this->transfer->quantity;
                        if ($add > 0) {
                            $from->quantity -= $add;
                            $to->quantity += $add;
                        }
                    }
                } else {
                    $oldFrom = $this->stockItem(null, $this->transfer->item_id, $this->transfer->from_stock);
                    $oldTo = $this->stockItem(null, $this->transfer->item_id, $this->transfer->to_stock);

                    if (
                        $from->stock_id != $this->transfer->from_stock ||
                        $to->stock_id != $this->transfer->to_stock
                    ) {
                        if ($from->stock_id != $this->transfer->from_stock) {

                            // old stock item should be increase by old quantity
                            $oldFrom->quantity += $this->transfer->quantity;
                            $from->quantity -= $data['quantity'];

                        }

                        if ($to->stock_id != $this->transfer->to_stock) {

                            // old stock item should be decrease by old quantity
                            $oldTo->quantity -= $this->transfer->quantity;
                            $to->quantity += $data['quantity'];

                        }
                    }
                }

                if (!$this->customError) {
                    $this->transfer->update($data);
                    $from->save();
                    $to->save();
                    $this->checkStockItem();
                    flash()->addSuccess(
                        'انتقال موفقانه تغیر یافت.',
                        'انتقال',
                    );
                }
            }
        } else {
            flash()->addWarning(
                "لطفاً گدام متفاوت را انتخاب کنید.",
                "عملیه بی معنی",
            );
        }
    }

    public function delete(\App\Models\Items $transfer) {
        $this->transfer = $transfer;
        $this->transfer->delete();
        flash()->addError(
            "انتقال
             ({$this->transfer->name})
             موفقانه حذف گردید. ",
            'انتقال',
        );
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
                $key.'.from_stock' => ['required'],
                $key.'.to_stock' => ['required'],
                $key.'.quantity' => 'required',
                $key.'.hijri_date' => 'required',
            ], [
                $key.'.item_id.required'=> "جنس را انتخاب کنید.",
                $key.'.from_stock.required'=> "انتخاب گدام ضرور است.",
                $key.'.to_stock.required'=> "انتخاب گدام ضرور است.",
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

    public function checkStockItem() {
        StockItem::where("quantity", "=", 0)->delete();
    }

    public function isItemInStock($item_id, $stock_id, $quantity): bool
    {
        if($item_id && $stock_id) {
            $stockItem = StockItem::where([
                ['stock_id', "=", $stock_id],
                ['item_id', "=", $item_id],
            ])->get();

            if(count($stockItem) == 1) {
                $stockItem = $stockItem->first();
                if($stockItem->quantity >= $quantity) {
                    return true;
                }
            }
        }
        return false;
    }

    public function resetForm() {
        $this->customError = [];
        $this->operation = [0=> 1];
        $this->data = [];
        $this->formNumber = 1;
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
