<?php $__env->startSection("title", "جنس انبار / گرام"); ?>
<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 min-vh-85">
                <div class="card-header pb-0">
                    <h4 class="border-bottom mb-4 pb-3">
                        جنس انبار / گدام
                    </h4>
                </div>

                <div class="card-body px-md-4 pt-0 pb-2">

                    <div class="table-responsive p-0">
                        <?php if(count($records) > 0): ?>
                            <table class="table align-items-center mb-0 data-table">
                                <thead>
                                <tr>
                                    <th class="w-10 text-center text-secondary text-xs font-weight-bolder opacity-7">شماره</th>
                                    <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">نام انبار</th>
                                    <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">نام جنس</th>
                                    <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">مقدار</th>
                                    <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">تاریخ ایجاد</th>
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
                                            <?php echo e($record->stock_name); ?>

                                        </td>
                                        <td class="text-center">
                                            <?php echo e($record->item_name); ?>

                                        </td>
                                        <td class="text-center">
                                            <?php echo e($record->quantity); ?>

                                        </td>
                                        <td class="align-middle text-center text-sm">
                                    <span class="text-secondary text-xs font-weight-bold">
                                        <?php echo e(date('d / m / Y', strtotime(str_replace("-", "/", $record->created_at)))); ?>

                                        <br>
                                        <?php echo e(date('H:i:s A', strtotime(str_replace("-", "/", $record->created_at)))); ?>

                                    </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            
                            <div class="h6 alert bg-gray-100 text-danger text-center mx-3">
                                جنس در انبار / گدام وجود ندارد.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\jawed\OneDrive\Desktop\NewProject\StockManagement\resources\views/livewire/stock-item.blade.php ENDPATH**/ ?>