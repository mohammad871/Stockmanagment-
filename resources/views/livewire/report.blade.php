<div>  
    <style>
        input {
            height: 30px;
        }
        .form-group {
            margin: 0;
        }
    </style>
    <ul class="d-print-none pe-0 nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link  {{$data['type'] == 'stock' ? 'active': ''}}" id="profile-tab"  wire:mousedown="setType('stock')">
                <i class="fa fa-layer-group"></i>&nbsp;
                راپور گدام ها 
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link  {{$data['type'] == 'customer' ? 'active': ''}}"  wire:mousedown="setType('customer')">
                <i class="fa fa-users"></i>&nbsp;
                راپور اجناس
            </button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent" style='height: auto' >
        <div class="card p-3 rounded-0 tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab" style="height: auto">
            <form wire:submit.prevent="search" class='d-print-none'> 
                <div class="row" style="padding-left: 30px; padding-right: 30px">
 

                    @if($data['type'] == "customer")
                    <div class="col-md-6 form-group">  
                            <label for="stockName">جنس</label>

                        <input
                            required
                            class="form-control"
                            type="text"
                            list="item"
                            wire:model.defer="data.item"
                            placeholder="انتخاب جنس"
                        > 

                        <datalist id="item">
                            @foreach($items as $item)
                                <option
                                    value="{{ $item->name }}"
                                ></option>
                            @endforeach
                        </datalist>
                    </div> 
                    @endif

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="startDate"> تاریخ شروع</label>
                            <input
                                autocomplete="off"
                                type="text"
                                class="form-control form-control-alternative persian-calendar"
                                id="startDate"
                                placeholder="تاریخ شروع."
                                name="startDate" 
                                wire:model.defer="data.startDate"
                                required
                            >
                            @error('name')
                            <div class="small text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div> 

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="endDate"> تاریخ ختم</label>
                            <input
                                autocomplete="off"
                                type="text"
                                class="form-control form-control-alternative persian-calendar"
                                id="endDate" 
                                placeholder="تاریخ ختم."
                                name="endDate" 
                                wire:model.defer="data.endDate"
                                required
                                >
                            @error('name')
                            <div class="small text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div> 
                </div> 

                <button class="btn btn-primary mt-4 me-4" style="width: 120px;">
                    جستجو
                </button>
            </form> 
 
            @if(count($records) > 0)
            <div >
                {!! $label !!}
            </div>
            <button class="btn btn-warning d-print-none" onclick="window.print()">
                <i class="fas fa-print"></i>&nbsp;
                دریافت PDF
            </button>
            @php $quantity = 0; @endphp
            <table class="table table-borderless table-striped"> 
                <tr>
                    <th>
                        شماره
                    </th> 
                    @if($data['type'] == "stock")
                    <th>
                        گدام
                    </th> 
                    <th>
                        جنس
                    </th>
                    @endif

                    <th>
                        تعداد
                    </th>
                    @if($data['type'] == "customer")
                    <th>
                        نوعیت
                    </th>  
                    @endif
                </tr> 
                @php $id = 0; @endphp
                @foreach($records as $record)
                @php $quantity += $record->itemQuantity; @endphp
                <tr>
                    <td>
                        {{ ++$id }}
                    </td>


                    @if($data['type'] == "stock")
                    <td>
                        {{ $record['stockName'] }}
                    </td> 
                    <td>
                        {{ $record['itemName'] }}
                    </td> 
                    @endif

                    <td>
                        {{ $record['itemQuantity'] }}
                    </td>
                    @if($data['type'] == "customer")
                    <td>
                        {{ $type[$record['operationType']] }}
                    </td>                 
                    @endif
                </tr>
                @endforeach
                <tr>
                    <th colspan="{{ $data['type'] == 'customer' ? 1 : 3 }}">
                        مجموع
                    </th>
                    <th>
                        {{ $quantity }}
                    </th>
                </tr>
            </table>
            @endif
        </div> 
    </div> 
 
</div> 
 
@push('scripts')
    <script>
        $(document).on("change", ".persian-calendar", function(e) {
            let target = e.target; 
            if(target) {
                @this.setDate(target.getAttribute("name"), target.value)
            }
        })
    </script>
@endpush
