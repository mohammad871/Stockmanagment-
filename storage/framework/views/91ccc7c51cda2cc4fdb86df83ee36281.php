<?php $__env->startSection("title", "خریداری"); ?>
<div xmlns:wire="http://www.w3.org/1999/xhtml">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 min-vh-85">
                <div class="card-header pb-0">
                    <h4 class="border-bottom mb-4 pb-3">خریداری</h4>
                </div>

                <div class="card-body px-md-4 pt-0 pb-2">
                    <?php if($operationType == "list"): ?>
                        <button class="btn bg-gradient-primary " wire:click="create" >
                            خریداری جدید
                        </button>
                        <div class="table-responsive p-0">
                        <?php if(count($records) > 0): ?>
                            <table class="table align-items-center mb-0 data-table">
                                <thead>
                                <tr>
                                    <th class=" text-center text-secondary text-xs font-weight-bolder opacity-7">شماره</th>
                                    <th class=" text-center text-secondary text-xs font-weight-bolder opacity-7">جنس</th>
                                    <th class=" text-center text-secondary text-xs font-weight-bolder opacity-7">گذام</th>
                                    <th class=" text-center text-secondary text-xs font-weight-bolder opacity-7">مقدار</th>
                                    <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">تاریخ شمسی</th>
                                    <th class="w-10 text-center text-secondary text-xs font-weight-bolder opacity-7">عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $genratedID = 0 ?>
                                <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $genratedID++ ?>
                                    <tr>
                                        <td class="pe-3 text-center ">
                                            <?php echo e($genratedID); ?>

                                        </td>
                                        <td class="text-center">
                                            <?php echo e($record->item_name); ?>

                                        </td>
                                        <td class="text-center">
                                            <?php echo e($record->stock_name); ?>

                                        </td>
                                        <td class="text-center">
                                            <?php echo e($record->quantity); ?>

                                        </td>
                                        <td class="text-center">
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
                                خریداری وجود ندارد.
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php else: ?>
                        <button class="btn btn-secondary btn-sm" wire:click="list">
                            برگشت
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <form wire:submit.prevent="<?php echo e($operationType == "edit" ? "update" : "store"); ?>" class="<?php echo e($operationType != "edit" ? "mt-5" : ''); ?>">
                            <div class="col-md-4 mb-2 generate-part">
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
                                    <?php $__errorArgs = ['0.bill_number'];
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
                            <?php $__currentLoopData = $operation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="row align-items-center auto-generate-form auto-generate-form-<?php echo e($formNumber); ?>" >
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="item.<?php echo e($key); ?>">جنس</label>





                                        <input
                                            class="form-control"
                                            type="text"
                                            list="item.<?php echo e($key); ?>"
                                            wire:model.defer="data.<?php echo e($key); ?>.item_id"
                                            placeholder="انتخاب جنس"
                                        >
                                        <?php if(isset($customError[$key])): ?>
                                            <div class="small text-danger">
                                                <?php echo e(isset($customError[$key]['notFound']) ? $customError[$key]['notFound'] : ''); ?>

                                            </div>
                                        <?php endif; ?>

                                        <datalist id="item.<?php echo e($key); ?>">
                                            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option
                                                    value="<?php echo e($item->name); ?>"
                                                ></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </datalist>














                                        <?php $__errorArgs = [$key.'.item_id'];
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

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="stockName.<?php echo e($key); ?>">گدام / انبار مربوطه</label>
                                        <select
                                            class="form-select"
                                            type="text"
                                            id="stockName.<?php echo e($key); ?>"
                                            wire:model.defer="data.<?php echo e($key); ?>.stock_id">
                                        >
                                            <option value="">انتخاب گدام / انبار</option>
                                            <?php $__currentLoopData = $stocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($stock->id); ?>"><?php echo e($stock->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php $__errorArgs = [$key.'.stock_id'];
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

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="quantity.<?php echo e($key); ?>">مقدار</label>
                                        <input
                                            min="0"
                                            type="number"
                                            class="form-control form-control-alternative"
                                            id="quantity.<?php echo e($key); ?>"
                                            placeholder="مقدار را وارد کنید."
                                            name="quantity"
                                            wire:model.defer="data.<?php echo e($key); ?>.quantity">
                                        <?php $__errorArgs = [$key.'.quantity'];
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

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="hijriDate.<?php echo e($key); ?>">تاریخ</label>
                                        <input
                                            type="text"
                                            class="persian-calendar form-control form-control-alternative"
                                            id="hijriDate.<?php echo e($key); ?>"
                                            placeholder="تاریخ را انتخاب کنید."
                                            wire:model="data.<?php echo e($key); ?>.hijri_date"
                                        >
                                        <?php $__errorArgs = [$key.'.hijri_date'];
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


                            <?php if($value != 1 && $operationType != "edit"): ?>
                                    <div class="auto-generate-form-close" wire:click="removeForm(<?php echo e($key); ?>)">
                                        <button type="button" class="btn-danger btn">
                                            &times;
                                        </button>
                                    </div>
                                <?php endif; ?>

                                <?php if($operationType != "edit"): ?>
                                <div class="auto-generate-form-new" wire:click.debounce.100ms="newForm(<?php echo e($formNumber); ?>)">
                                    <button type="button" class="btn-info btn">
                                        &plus;
                                    </button>
                                </div>
                                <div class="auto-generate-form-number">
                                    <?php echo e($value); ?>

                                </div>
                                <?php endif; ?>

                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <button type="submit" class="d-inline-block btn bg-gradient-primary">ذخیره کردن</button>
                        </form>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->startPush('scripts'); ?>
    <?php echo $__env->make('livewire.components.hijri-date', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <script>


    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\jawed\OneDrive\Desktop\NewProject\StockManagement\resources\views/livewire/purchase.blade.php ENDPATH**/ ?>