@section("title", "کاربر")
<div>
    @if($showModal)
        <div wire:ignore.self data-bs-backdrop="static" class="modal fade" id="operationModal" tabindex="-1" role="dialog" aria-labelledby="operationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form wire:submit.prevent="{{ $typeModal == "add" ?  "store" : "update" }}" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            {{ $typeModal == "add" ? "ایجاد کاربر جدید" : " تغیر کاربر "."(".$user->name.")"  }}
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="userName">نام کاربر</label>
                                    <input
                                        type="text"
                                        class="form-control form-control-alternative"
                                        id="userName"
                                        placeholder="نام کاربر را وارد کنید."
                                        name="name"
                                        wire:model.defer="data.name">
                                    @error('name')
                                    <div class="small text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>


                            @if($typeModal == "add")
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="password">پسورد</label>
                                    <input
                                        type="password"
                                        class="form-control form-control-alternative"
                                        id="password"
                                        placeholder="پسورد را وارد کنید."
                                        name="password"
                                        wire:model.defer="data.password">
                                    @error('password')
                                    <div class="small text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="rpassword">تکرار پسورد</label>
                                    <input
                                        type="password"
                                        class="form-control form-control-alternative"
                                        id="rpassword"
                                        placeholder="تکرار پسورد را وارد کنید. "
                                        name="rpassword"
                                        wire:model.defer="data.password_confirmation">
                                    @error('password_confirmation')
                                    <div class="small text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            @endif

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
                    <h4 class="border-bottom mb-4 pb-3">کاربر</h4>
                </div>

                <div class="card-body px-md-4 pt-0 pb-2">
                    <button class="btn bg-gradient-primary " wire:click="create" >
                        ایجاد کاربر جدید
                    </button>
                    <div class="table-responsive p-0">
                        @if(count($records) > 0)
                            <table class="table align-items-center mb-0 data-table">
                                <thead>
                                <tr>
                                    <th class="text-secondary text-xs font-weight-bolder opacity-7">شماره</th>
                                    <th class="text-secondary text-xs font-weight-bolder opacity-7">نام کاربر</th>
                                    <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">تاریخ ایجاد</th>
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
                                کاربر وجود ندارد.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
