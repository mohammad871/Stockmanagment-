<?php $__env->startSection("title", "مشتری"); ?>

<div>
        <div wire:ignore.self data-bs-backdrop="static" class="modal modal-lg fade" id="operationModal" tabindex="-1" role="dialog" aria-labelledby="operationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form wire:submit.prevent="<?php echo e($typeModal == "add" ?  "store" : "update"); ?>" class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            <?php echo e($typeModal == "add" ? "مشتری جدید" : " تغیر مشتری "."(".$customer->name.")"); ?>

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
                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="small text-danger">
                                        <?php echo e($message); ?>

                                    </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="small text-danger">
                                        <?php echo e($message); ?>

                                    </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                    <?php $__errorArgs = ['tazkira'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="small text-danger">
                                        <?php echo e($message); ?>

                                    </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                    <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="small text-danger">
                                        <?php echo e($message); ?>

                                    </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                        <?php if(count($records) > 0): ?>
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
                                <?php $genratedID = 00 ?>
                                <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $genratedID++ ?>
                                    <tr>
                                        <td class="pe-3 text-center">
                                            <?php echo e($genratedID); ?>

                                        </td>
                                        <td class="text-center">
                                            <?php echo e($record->name); ?>

                                        </td>
                                        <td class="text-center">
                                            <?php echo e(trim($record->phone) ?? "---"); ?>

                                        </td>
                                        <td class="text-center">
                                            <?php echo e($record->tazkira ?? "---"); ?>

                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                <?php echo e($record->hijri_date); ?>

                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <a href="javascript:void(0);" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
                                        <span class="badge bg-gradient-success" wire:click="edit(<?php echo e($record->id); ?>)">
                                            تصحیح
                                        </span>
                                                <span class="badge bg-gradient-danger"  wire:click="delete(<?php echo e($record->id); ?>)">
                                            حذف
                                        </span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            
                            <div class="h6 alert bg-gray-100 text-danger text-center mx-3">
                                مشتری وجود ندارد.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->startPush("scripts"); ?>
    <script>
        let modal = new bootstrap.Modal(document.querySelector('#operationModal'));
        window.addEventListener("closeModal", ()=> {
            document.querySelector(".modal-backdrop").remove();
            modal._hideModal();
        })

        <?php if(session()->has('addCustomer')): ?>
            modal.toggle('show');
        <?php endif; ?>
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\jawed\OneDrive\Desktop\NewProject\StockManagement\resources\views/livewire/customer.blade.php ENDPATH**/ ?>