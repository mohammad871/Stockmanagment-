@section("title", "مشتری")

<div>
        <div wire:ignore.self data-bs-backdrop="static" class="modal modal-lg fade" id="operationModal" tabindex="-1" role="dialog" aria-labelledby="operationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form wire:submit.prevent="{{ $typeModal == "add" ?  "store" : "update" }}" class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            {{ $typeModal == "add" ? "مشتری جدید" : " تغیر مشتری "."(".$customer->name.")"  }}
                        </h5>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stockName">نام مشتری</label>
                                    <input
                                        type="text"
                                        class="form-control form-control-alternative"
                                        id="stockName"
                                        placeholder="نام مشتری را وارد کنید."
                                        name="name"
                                        wire:model.defer="data.name"
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
                                    <label for="phone">شماره تلفن</label>
                                    <input
                                        type="text"
                                        class="form-control form-control-alternative"
                                        id="phone"
                                        placeholder="شماره تلفن را وارد کنید."
                                        name="phone"
                                        wire:model.defer="data.phone">
                                    @error('phone')
                                    <div class="small text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tazkira">نمبر تذکره</label>
                                    <input
                                        type="text"
                                        class="form-control form-control-alternative"
                                        id="tazkira"
                                        placeholder=" نمبر تذکره را وارد کنید."
                                        name="tazkira"
                                        wire:model.defer="data.tazkira">
                                    @error('tazkira')
                                    <div class="small text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address">آدرس مشتری</label>
                                    <input
                                        type="text"
                                        class="form-control form-control-alternative"
                                        id="address"
                                        placeholder="آدرس را وارد کنید."
                                        name="address"
                                        wire:model.defer="data.address">
                                    @error('address')
                                    <div class="small text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-start">
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">بستن</button>
                        <button type="submit" class="btn bg-gradient-primary">ذخیره کردن</button>
                    </div>
                </form>
            </div>
        </div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 min-vh-85">
                <div class="card-header pb-0">
                    <h4 class="border-bottom mb-4 pb-3">مشتری</h4>
                </div>

                <div class="card-body px-md-4 pt-0 pb-2">
                    <button class="btn bg-gradient-primary " wire:click="create" >
                        مشتری جدید
                    </button>

                    <div class="table-responsive p-0">
                        @if(count($records) > 0)
                            <table class="table align-items-center mb-0 data-table">
                                <thead>
                                <tr>
                                    <th class="w-10 text-center text-secondary text-xs font-weight-bolder opacity-7">شماره</th>
                                    <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">نام مشتری</th>
                                    <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">شماره تلفن</th>
                                    <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">نمبر تذکره</th>
                                    <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">تاریخ ثبت</th>
                                    <th class="w-10 text-center text-secondary text-xs font-weight-bolder opacity-7">عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $genratedID = 00 @endphp
                                @foreach($records as $record)
                                    @php $genratedID++ @endphp
                                    <tr>
                                        <td class="pe-3 text-center">
                                            {{ $genratedID }}
                                        </td>
                                        <td class="text-center">
                                            {{ $record->name }}
                                        </td>
                                        <td class="text-center">
                                            {{ trim($record->phone) ?? "---" }}
                                        </td>
                                        <td class="text-center">
                                            {{ $record->tazkira ?? "---" }}
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                {{ $record->hijri_date }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <a href="javascript:void(0);" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
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
                                مشتری وجود ندارد.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push("scripts")
    <script>
        let modal = new bootstrap.Modal(document.querySelector('#operationModal'));
        window.addEventListener("closeModal", ()=> {
            document.querySelector(".modal-backdrop").remove();
            modal._hideModal();
        })

        @if(session()->has('addCustomer'))
            modal.toggle('show');
        @endif
    </script>
@endpush
