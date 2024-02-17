<?php $__env->startSection("title", "بک آپ"); ?>
<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 min-vh-85">
                <div class="card-header pb-0">
                    <h4 class="border-bottom mb-4 pb-3">بک آپ</h4>
                </div>

                <div class="card-body px-md-4 pt-0 pb-2">
                    <a class="btn bg-gradient-primary "  href="/backup">
                        بک آپ جدید
                    </a>

                    <div class="table-responsive p-0">
                        <?php if(count($records) > 0): ?>
                            <table class="table align-items-center mb-0 data-table">
                                <thead>
                                <tr>
                                    <th class="w-10 text-center text-secondary text-xs font-weight-bolder opacity-7">شماره</th>
                                    <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">نام بک آپ</th>
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
                                            <?php echo e($record); ?>

                                        </td>
                                        <td class="align-middle text-center">
                                            <a href="javascript:void(0);" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
                                                <span class="badge bg-gradient-danger"  wire:click="delete('<?php echo e($record); ?>')">
                                                    <i class="fas fa-trash"></i>
                                                    حذف
                                                </span>
                                                <span class="badge bg-gradient-info"  wire:click="download('<?php echo e($record); ?>')">
                                                    <i class="fas fa-download"></i>
                                                    دانلود کردن
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            
                            <div class="h6 alert bg-gray-100 text-danger text-center mx-3">
                                بک آپ وجود ندارد.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\jawed\OneDrive\Desktop\StockManagement\resources\views/livewire/backup-list.blade.php ENDPATH**/ ?>