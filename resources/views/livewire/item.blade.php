@section("title", "جنس")
<div>
    @if($showModal)
        <div wire:ignore.self data-bs-backdrop="static" class="modal fade" id="operationModal" tabindex="-1" role="dialog" aria-labelledby="operationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form wire:submit.prevent="{{ $typeModal == "add" ?  "store" : "update" }}" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            {{ $typeModal == "add" ? "جنس جدید" : " تغیر جنس "."(".$item->name.")"  }}
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="stockName">نام جنس</label>
                                    <input
                                        type="text"
                                        class="form-control form-control-alternative"
                                        id="stockName"
                                        placeholder="نام جنس را وارد کنید."
                                        name="name"
                                        wire:model.defer="data.name">
                                    @error('name')
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
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card mb-4 min-vh-85">
                <div class="card-header pb-0">
                    <h4 class="border-bottom mb-4 pb-3">جنس</h4>
                </div>

                <div class="card-body px-md-4 pt-0 pb-2">
                    <button class="btn bg-gradient-primary " wire:click="create" >
                        جنس جدید
                    </button>

                    <div class="table-responsive p-0">
                        @if(count($records) > 0)
                            <table class="table align-items-center mb-0 data-table">
                                <thead>
                                <tr>
                                    <th class="w-10 text-center text-secondary text-xs font-weight-bolder opacity-7">شماره</th>
                                    <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">نام جنس</th>
                                    <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">تاریخ ایجاد</th>
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
                                                <span class="badge bg-gradient-danger" onclick="confirm('آیا می خواهید ریکارد مربوطه حذف شود؟') || event.stopImmediatePropagation()"   wire:click="delete({{ $record->id }})">
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
                                جنس وجود ندارد.
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
        window.addEventListener("closeModal", ()=> {
            let modal = new bootstrap.Modal($('#operationModal'));
            document.querySelector('.modal-backdrop').remove();
            modal._hideModal();
        })
    </script>
@endpush
