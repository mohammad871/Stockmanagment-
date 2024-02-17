<?php

namespace App\Http\Livewire; 
use Livewire\Component;
use App\Models\Items;
use App\Models\Customer;
use App\Models\Stock; 
use App\Models\StockOperation; 

class Report extends Component
{
    public $type = [];
    public $label = "";
    public $records = [];

    public $data = [
        "startDate"=> "",
        "endDate"=> "",
        "item"=> "",
        "customer"=> "",
        'stock'=> "",
        "type"=> "stock",
    ];

    
    public function dehydrate() {
        $this->type = [
            "PURCHASE"=> "دخولی",
            "SELL"=> "خروجی",
            "RETURN"=> "برگشتی",
        ];
    }

    public function render()
    {
        $items = Items::all();  
        $stocks = Stock::all(); 
        $customers = Customer::all(); 
        return view('livewire.report', compact('items', 'customers', 'stocks'));
    } 


    public function search() {
        $data = $this->data;
        $type = $data['type']; 

        if($type == "customer") {
            $item = Items::where("name","=", $data['item'])->get()->first(); 

            if($item) {
                $this->records = StockOperation::where('stock_operation.item_id', "=", $item->id) 
                ->join("stock", "stock.id", "=", "stock_operation.stock_id")
                ->join("items", "items.id", "=", "stock_operation.item_id")
                ->where("stock_operation.hijri_date", ">=", $data['startDate'])
                ->where("stock_operation.hijri_date", "<=", $data['endDate'])
                ->select("stock_operation.stock_operation_type as operationType", "items.name as itemName", \DB::raw("sum(stock_operation.quantity) as itemQuantity"))
                ->groupBy("stock_operation.stock_operation_type", "items.name")
                ->get();
    
                if(count($this->records)) { 
                    $firstRecords = $this->records[0];
                    $this->label = "
                        <h3 class='text-center mb-4'>
                            <span>
                                راپور جنس
                            </span>
                            &nbsp;({$data['item']})&nbsp;
                            <span>
                                از 
                            </span>
                            &nbsp;({$data['startDate']})&nbsp;
                            <span>
                                تا
                            </span>
                            &nbsp;({$data['endDate']})&nbsp;
                        </h3>
                    "; 
                }
            }

            
        } else {
            $this->records = Stock::where("stock.id",">=", "0")
            ->join("stock_operation", "stock_operation.stock_id", "=", "stock.id")
            ->join("items", "items.id", "=", "stock_operation.item_id")
            ->where("stock_operation.hijri_date", ">=", $data['startDate'])
            ->where("stock_operation.hijri_date", "<=", $data['endDate'])
            ->select("stock.name as stockName", "items.name as itemName", \DB::raw("sum(stock_operation.quantity) as itemQuantity"))
            ->groupBy("stock.name", "items.name")
            ->get();   

            $this->label = "
                <h3 class='text-center mb-4'>
                    <span>
                        راپور گدام ها
                    </span>
                    <span>
                        از 
                    </span>
                    &nbsp;({$data['startDate']})&nbsp;
                    <span>
                        تا
                    </span>
                    &nbsp;({$data['endDate']})&nbsp;
                </h3>
            "; 
        }
        return;
    } 

    public function setDate($type, $value) {  
        $this->data[$type] = $value;    
    }

    public function setType($type) {
        $this->records = [];
        $this->data['type'] = $type;
    }
}
