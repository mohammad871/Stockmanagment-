@section("title", "خروجی")
<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 min-vh-85">
                <div class="card-header pb-0">
                    <h4 class="border-bottom mb-4 pb-3">خروجی</h4>
                </div>

                <div class="card-body px-md-4 pt-0 pb-2">
                    @if($operationType == "list")
                        @if($isSub)
                            <button class="btn btn-secondary btn-sm" wire:click="setIsSub(false)">
                                برگشت
                                <i class="fas fa-arrow-left"></i>
                            </button>
                        @else
                            <button class="btn-sm btn bg-gradient-primary " wire:click="create" >
                                خروجی جدید
                            </button>
                        @endif

                        <div class="table-responsive p-0">

                            {{-- If user click on a specific customer --}}
                            @if($isSub)
                                @if(count($records) > 0)
                                    <table class="table align-items-center mb-0 data-table">
                                        <thead>
                                        <tr>
                                            <th class="text-secondary text-xs font-weight-bolder opacity-7">شماره</th>
                                            <th class="text-secondary text-xs font-weight-bolder opacity-7">بیل</th>
                                            <th class="text-secondary text-xs font-weight-bolder opacity-7">مشتری</th>
                                            <th class="text-secondary text-xs font-weight-bolder opacity-7">جنس</th>
                                            <th class="text-secondary text-xs font-weight-bolder opacity-7">گذام</th>
                                            <th class="text-secondary text-xs font-weight-bolder opacity-7">مقدار</th>
                                            <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">تاریخ خرید</th>
                                            <th class="w-10 text-center text-secondary text-xs font-weight-bolder opacity-7">عملیات</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $genratedID = 0 @endphp
                                        @foreach($records as $record)
                                            @php $genratedID++ @endphp
                                            <tr>
                                                <td class="pe-3">
                                                    {{ $genratedID }}
                                                </td>
                                                <td>
                                                    {{ $record->bill_number }}
                                                </td>
                                                <td>
                                                    {{ $record->customer_name }}
                                                </td>
                                                <td>
                                                    {{ $record->item_name }}
                                                </td>
                                                <td>
                                                    {{ $record->stock_name }}
                                                </td>
                                                <td>
                                                    {{ $record->quantity }}
                                                </td>
                                                <td class="align-middle text-center text-sm">
                                                    <span class="text-secondary text-xs font-weight-bold">
                                                        {{ $record->hijri_date }}
                                                    </span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <a href="javascript:void(0);" class="text-secondary font-weight-bold text-xs" >
                                                        <span class="badge bg-gradient-success" wire:click="edit({{ $record->id }})">
                                                            تصحیح
                                                        </span>
                                                        <span onclick="confirm('آیا می خواهید ریکارد مربوطه حذف شود؟') || event.stopImmediatePropagation()"  class="badge bg-gradient-danger"  wire:click="delete({{ $record->id }})">
                                                            حذف
                                                        </span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    {{-- Alert not found any records --}}
                                    <div class="h6 alert bg-gray-100 text-danger text-center mx-3">
                                        خروجی وجود ندارد.
                                    </div>
                                @endif

                            {{-- Main list of customer sell --}}
                            @else
                                @if(count($records) > 0)
                                <table class="table align-items-center mb-0 data-table">
                                    <thead>
                                    <tr>
                                        <th class="w-10 text-center text-secondary text-xs font-weight-bolder opacity-7">شماره</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7"> شماره بیل </th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">مشتری</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolPder opacity-7">تعداد خرید مشتری</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">مقدار</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">تاریخ خروجی</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $genratedID = 0 @endphp
                                    @foreach($records as $record)
                                        @php $genratedID++ @endphp
                                        <tr>
                                            <td class="pe-3 text-center">
                                                {{ $genratedID }}
                                            </td>
                                            <td class="text-center">
                                                {{ $record->bill_number }}
                                            </td>
                                            <td class="text-center">
                                                {{ $record->customer_name }}
                                            </td>
                                            <td class="text-center">
                                                {{ $record->items }}
                                            </td>
                                            <td class="text-center">
                                                {{ $record->quantity }}
                                            </td>
                                            <td class="align-middle text-center">
                                                <a href="javascript:;" wire:click="listSub({{ $record->customer }}, '{{ $record->bill_number }}')">
                                                    <span class="badge bg-gradient-info">
                                                        دیدن خرید مشتری
                                                    </span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                @else
                                    {{-- Alert not found any records --}}
                                    <div class="h6 alert bg-gray-100 text-danger text-center mx-3">
                                        خروجی وجود ندارد.
                                    </div>
                                @endif
                            @endif
                        </div>
                    @else
                        <button class="btn btn-secondary btn-sm" wire:click="listSub">
                            برگشت
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <form wire:submit.prevent="{{ $operationType == "edit" ? "update" : "store" }}" class="{{ $operationType != "edit" ? "mt-5" : ''}}">
                            <div class="row generate-part">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="bill.0">بیل نمبر</label>
                                        <input
                                            min="0"
                                            type="number"
                                            class="form-control form-control-alternative"
                                            id="bill.0"
                                            placeholder="بیل نمبر را وارد کنید."
                                            name="bill"
                                            wire:model="data.0.bill_number">
                                        @error('0.bill_number')
                                        <div class="small text-danger">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group pb-0 flex-grow-1">
                                        <label for="customer.0">مشتری</label>
                                        <div class="d-flex align-items-start">
                                            <button type="button" wire:click="addNewCustomer" class="px-3 btn btn-info btn-sm" style="border-top-left-radius: 0; border-bottom-left-radius: 0; height: 30px;">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            <select
                                                style="border-top-right-radius: 0; border-bottom-right-radius: 0; height: 30px;"
                                                type="text"
                                                class="form-select"
                                                id="customer.0"
                                                name="customer"
                                                wire:model="data.0.customer"
                                            >
                                                <option value="">انتخاب مشتری</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('0.customer')
                                        <div class="small text-danger mt-n3">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            @foreach($operation as $key=> $value)
                                <div class="row align-items-center auto-generate-form auto-generate-form-{{ $formNumber }}" >

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="item.{{ $key }}">جنس</label>
{{--                                            <select--}}
{{--                                                type="text"--}}
{{--                                                class="form-select "--}}
{{--                                                id="item.{{ $key }}"--}}
{{--                                                wire:model.defer="data.{{ $key }}.item_id"--}}
{{--                                            >--}}
{{--                                                <option value="">انتخاب جنس</option>--}}
{{--                                                @foreach($items as $item)--}}
{{--                                                    <option--}}
{{--                                                        value="{{ $item->id }}"--}}
{{--                                                    >{{ $item->name }}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}

                                            <input
                                                class="form-control"
                                                type="text"
                                                list="item.{{ $key }}"
                                                wire:model.defer="data.{{ $key }}.item_id"
                                                placeholder="انتخاب جنس"
                                            >
                                            @if(isset($customError[$key]))
                                                <div class="small text-danger">
                                                    {{ isset($customError[$key]['notFound']) ? $customError[$key]['notFound'] : '' }}
                                                </div>
                                            @endif

                                            <datalist id="item.{{ $key }}">
                                                @foreach($items as $item)
                                                    <option
                                                        value="{{ $item->name }}"
                                                    ></option>
                                                @endforeach
                                            </datalist>

                                            @error($key.'.item_id')
                                            <div class="small text-danger">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="stockName.{{ $key }}">گدام / انبار مربوطه</label>
                                            <select
                                                class="form-select"
                                                type="text"
                                                id="stockName.{{ $key }}"
                                                wire:model.defer="data.{{ $key }}.stock_id">
                                                >
                                                <option value="">انتخاب گدام / انبار</option>
                                                @foreach($stocks as $stock)
                                                    <option value="{{ $stock->id }}">{{ $stock->name }}</option>
                                                @endforeach
                                            </select>
                                            @error($key.'.stock_id')
                                            <div class="small text-danger">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>



                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="quantity.{{ $key }}">مقدار</label>
                                            <input
                                                min="0"
                                                type="number"
                                                class="form-control form-control-alternative"
                                                id="quantity.{{ $key }}"
                                                placeholder="مقدار را وارد کنید."
                                                name="quantity"
                                                wire:model.defer="data.{{ $key }}.quantity">
                                            @error($key.'.quantity')
                                            <div class="small text-danger">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                            @if(isset($customError[$key]['moreThanExist']))
                                                <div class="small text-danger">
                                                    {{ $customError[$key]['moreThanExist'] }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="hijriDate.{{ $key }}">تاریخ</label>
                                            <input
                                                autocomplete="off"
                                                type="text"
                                                class="persian-calendar form-control form-control-alternative"
                                                id="hijriDate.{{ $key }}"
                                                placeholder="تاریخ را انتخاب کنید."
                                                wire:model="data.{{ $key }}.hijri_date"
                                            >
                                            @error($key.'.hijri_date')
                                            <div class="small text-danger">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    @if($value != 1 && $operationType != "edit")
                                        <div class="auto-generate-form-close" wire:click="removeForm({{ $key }})">
                                            <button type="button" class="btn-danger btn">
                                                &times;
                                            </button>
                                        </div>
                                    @endif

                                    @if($operationType != "edit")
                                        <div class="auto-generate-form-new" wire:click.debounce.100ms="newForm({{ $formNumber }})">
                                            <button type="button" class="btn-info btn">
                                                &plus;
                                            </button>
                                        </div>
                                        <div class="auto-generate-form-number">
                                            {{ $value }}
                                        </div>
                                    @endif

                                </div>
                            @endforeach

                            @if(count($errors->all()) > 0)
                            <div class="bg-dark p-2 rounded-1 pt-3 mb-2">
                                <strong class="text-danger pe-3">مشکلات</strong>                                
                                <ul class="text-danger">
                                    @foreach($errors->all() as $error) 
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif;

                            <button type="submit" class="d-inline-block btn bg-gradient-primary">ذخیره کردن</button>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    @include('livewire.components.hijri-date')
@endpush
