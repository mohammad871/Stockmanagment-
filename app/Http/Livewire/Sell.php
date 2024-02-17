<?php

namespace App\Http\Livewire;

use \App\Models\Customer;
use App\Models\Items;
use App\Models\Stock;
use App\Models\StockItem;
use App\Models\StockOperation;
use App\Rules\ValidateFormat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Sell extends Component
{
    public $customError = [];
    public $formNumber = 1;
    public $operation = [0=>1];

    public $sell;
    public $data = [];
    public $operationType = "list";

    public $TYPE = "SELL";

    public $isSub = false;
    public $records = [];

    public function render()
    {
        if(session()->has('addCustomer')) {
            $this->data[0]['customer'] = session()->get('addCustomer');
            $this->operationType = "add";
        }
        $customers = Customer::all();
        $items = Items::all();
        $stocks = Stock::all();

        if(!$this->isSub) {
            $this->records = DB::select("
             select  customers.id as customer,
                     customers.name as customer_name,
                     count(*) as items,
                     sum(quantity) as quantity,
                     bill_number
             from stock_operation
             join customers on customers.id = stock_operation.customer
             where stock_operation_type = '{$this->TYPE}' group by stock_operation.customer, bill_number
            ");
        }

        return view('livewire.sell', compact('items', 'stocks', 'customers'));
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

        $customer = $this->data[0]['customer'];
        $billNumber = $this->data[0]['bill_number'];

        foreach ($this->data as $index=>$data) { 
            if(!isset($data['stock_id']) || !isset($data['item_id'])) {
                continue;
            }

            $itemId = $this->checkItem($data['item_id']);
            if (!$itemId) {
                $this->customError[$index]['notFound'] = "
                    جنس با این نام موجود نیست.
                ";
                break;
            } else {
                $this->customError = [];
                $data['item_id'] = $itemId;
            }

            if(empty($this->customError)) {
                $dbData = $this->stockItem(null, $data['stock_id'], $data['item_id'], $data['hijri_date']); 

                if ($this->isItemInStock($dbData->item_id, $dbData->stock_id, $data['quantity'])) {
                    $dbData->update([
                        'quantity' => $dbData->quantity - $data['quantity']
                    ]);
                    $result = $dbData;
                    $this->customError = [];
                } else {
                    $this->customError[$index]['moreThanExist'] = "
                        مقدار که شما وارد کرده اید در گدام موجود نیست.
                        موجودی گدام:
                        {$dbData->quantity}
                        ---
                        خواسته شده:
                        {$data['quantity']}
                    ";
                }
                if ($result && !$this->customError) { 
                    $data['stock_operation_type'] = $this->TYPE;
                    $data['customer'] = $customer;
                    $data['bill_number'] = $billNumber;
                    StockOperation::create($data);

                    unset($this->operation[$index]);
                    unset($this->data[$index]);

                    $this->data[0]['customer'] = $customer;
                    $this->data[0]['bill_number'] = $billNumber;

                    flash()->addSuccess(
                        'فروش موفقانه انجام شد.',
                        'فروش',
                    );

                }
            }
        }

        if(!$this->customError) {
            $this->resetForm();
        }
    }

    public function edit(\App\Models\StockOperation $sell) {
        $this->sell = $sell;
        $itemId = $sell->item_id;
        $sell->item_id = $this->checkItem($itemId, "name");
        $this->data = [$sell->toArray()];
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

                $oldStockItem = $this->stockItem(null, $this->sell->stock_id, $this->sell->item_id);
                $newStockItem = $this->stockItem(null, $data['stock_id'], $data['item_id']);

                if ($data['quantity'] >= $this->sell->quantity) {
                    $add = $data['quantity'] - $this->sell->quantity;

                    if (
                        $this->sell->stock_id == $data['stock_id'] &&
                        $this->sell->item_id == $data['item_id']
                    ) {
                        if ($this->isItemInStock($this->sell->item_id, $this->sell->stock_id, $add)) {
                            // increase sell
                            $this->sell->quantity += $add;

                            // decrease stock also
                            $oldStockItem->quantity -= $add;
                            $this->customError = [];
                        } else {
                            $this->customError['moreThanExist'] = "
                            مقدار که شما وارد کرده اید در گدام موجود نیست.
                            موجودی گدام:
                            ({$oldStockItem->quantity})
                            ---
                            خواسته شده:
                            ({$data['quantity']})
                            ";
                        }
                    } else {
                        if ($this->isItemInStock($newStockItem->item_id, $newStockItem->stock_id, $data['quantity'])) {
                            // decrease stock also (old item)
                            $oldStockItem->quantity += $this->sell->quantity;

                            // decrease by new quantity (new item)
                            $newStockItem->quantity -= $data['quantity'];

                            $this->sell->stock_id = $data['stock_id'];
                            $this->sell->item_id = $data['item_id'];
                            $this->customError = [];
                        } else {
                            $this->customError['moreThanExist'] = "
                            مقدار که شما وارد کرده اید در گدام موجود نیست.
                            موجودی گدام:
                            ({$newStockItem->quantity})
                            ---
                            خواسته شده:
                            ({$data['quantity']})
                            ";
                        }
                    }
                } else {
                    // subtract from sell
                    $subtract = $this->sell->quantity - $data['quantity'];

                    if (
                        $this->sell->stock_id == $data['stock_id'] &&
                        $this->sell->item_id == $data['item_id']
                    ) {
                        // decrease sell
                        $this->sell->quantity -= $subtract;

                        // increase stock also
                        $oldStockItem->quantity += $subtract;
                    } else {
                        if ($this->isItemInStock($newStockItem->item_id, $newStockItem->stock_id, $data['quantity'])) {
                            // increase stock also (old item)
                            $oldStockItem->quantity += $this->sell->quantity;

                            // decrease by new quantity (new item)
                            $newStockItem->quantity -= $data['quantity'];

                            // update sell
                            $this->sell->quantity = $data['quantity'];

                            $this->sell->stock_id = $data['stock_id'];
                            $this->sell->item_id = $data['item_id'];
                            $this->customError = [];
                        } else {
                            $this->customError['moreThanExist'] = "
                            مقدار که شما وارد کرده اید در گدام موجود نیست.
                            موجودی گدام:
                            ({$oldStockItem->quantity})
                            ---
                            خواسته شده:
                            ({$data['quantity']})
                            ";
                        }
                    }
                }


                if (!$this->customError) {
                    $this->sell->bill_number = $this->sell->bill_number != $data['bill_number'] ? $data['bill_number'] : $this->sell->bill_number;
                    $this->sell->customer = $this->sell->customer != $data['customer'] ? $data['customer'] : $this->sell->customer;

                    $this->sell->save();
                    $oldStockItem->save();
                    $newStockItem->save();

                    $this->checkStockItem($newStockItem);
                    $this->checkStockItem($oldStockItem);

                    $this->listSub();

                    flash()->addSuccess(
                        'فروش موفقانه تغیر یافت.',
                        'فروش',
                    );
                }
            }
        }
    }

    public function delete(\App\Models\StockOperation $sell) {
        $toDelete = (Object) $sell->toArray();
        $this->sell = $sell;

        if($toDelete->stock_operation_type == $this->TYPE) {
            $stockItem = $this->stockItem(null, $toDelete->stock_id, $toDelete->item_id, $toDelete->hijri_date);
            $stockItem->quantity += $toDelete->quantity;
            $stockItem->save();
            $this->sell->delete();

            $this->checkStockItem($stockItem);

            $this->listSub(null, null, $sell);

            flash()->addError(
                "
                فروش موفقانه حذف گردید.
                ",
                'فروش',
            );
        } else {
            flash()->addWarning(
                "در حذف فروش مشکل رخ داد دوباره کوشش نماید.",
                'فروش',
            );
        }
    }

    public function resetForm() {
        $this->customError = [];
        $this->operation = [0=> 1];
        $this->formNumber = 1;
        $this->data = [];
        session()->forget('addCustomer');
    }

    private function validation() {
        Validator::make($this->data, [
            '0.bill_number' => 'required',
            '0.customer' => 'required',
        ], [
            '0.bill_number.required'=> "بیل نمبر را وارد کنید.",
            '0.customer.required'=> "مشتری را انتخاب کنید.",
        ])->validate();

        foreach ($this->operation as $key=> $value) {
            Validator::make($this->data, [
                $key.'.item_id' => ['required'],
                $key.'.quantity' => 'required',
            ], [
                $key.'.item_id.required'=> "جنس را انتخاب کنید.",
                $key.'.quantity.required'=> "مقدار را وارد کنید.",
            ])->validate();
        }
    }

    public function stockItem($id, $stock_id, $item_id, $date) {
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
                    'hijri_date' => $date,
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

    public function setHijriDate($value) {
        $this->data[$this->formNumber - 1]['hijri_date'] = $value;
    }

    public function addNewCustomer() {
        session()->put('addCustomer', "");
        $this->redirect('/customer');
    }

    public function listSub($customer = null, $bill_number = null, $obj = null) {
        if(!$customer && !$bill_number) {
            if(isset($this->data[0]['customer']) && isset($this->data[0]['bill_number'])) {
                $customer = $this->data[0]['customer'];
                $bill_number = $this->data[0]['bill_number'];
            } elseif($obj) {
                $customer = $obj->customer;
                $bill_number = $obj->bill_number;
            }
            $this->list();
        }

        if($customer && $bill_number) {
            $this->records = StockOperation::where([
                ['stock_operation_type', "=", $this->TYPE],
                ['customer', "=", $customer],
                ['stock_operation.bill_number', "=", $bill_number],
            ])
                ->join("items", "stock_operation.item_id", "=", "items.id")
                ->join("stock", "stock_operation.stock_id", "=", "stock.id")
                ->join("customers", "stock_operation.customer", "=", "customers.id")
                ->select(
                    "stock_operation.id as id",
                    "stock_operation.hijri_date as hijri_date",
                    "stock_operation.quantity as quantity",
                    "items.name as item_name",
                    "stock.name as stock_name",
                    "customers.name as customer_name",
                    "bill_number",
                )->get();
            $this->isSub = true;
        } else {
            $this->setIsSub(false);
            session()->forget('addCustomer');
        }
    }


    public function setIsSub($value) {
        $this->isSub = $value;
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
