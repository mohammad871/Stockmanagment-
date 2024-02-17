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
            <button class="nav-link  <?php echo e($data['type'] == 'stock' ? 'active': ''); ?>" id="profile-tab"  wire:mousedown="setType('stock')">
                <i class="fa fa-layer-group"></i>&nbsp;
                راپور گدام ها 
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link  <?php echo e($data['type'] == 'customer' ? 'active': ''); ?>"  wire:mousedown="setType('customer')">
                <i class="fa fa-users"></i>&nbsp;
                راپور اجناس
            </button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent" style='height: auto' >
        <div class="card p-3 rounded-0 tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab" style="height: auto">
            <form wire:submit.prevent="search" class='d-print-none'> 
                <div class="row" style="padding-left: 30px; padding-right: 30px">
 

                    <?php if($data['type'] == "customer"): ?>
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
                            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option
                                    value="<?php echo e($item->name); ?>"
                                ></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </datalist>
                    </div> 
                    <?php endif; ?>

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
                </div> 

                <button class="btn btn-primary mt-4 me-4" style="width: 120px;">
                    جستجو
                </button>
            </form> 
 
            <?php if(count($records) > 0): ?>
            <div >
                <?php echo $label; ?>

            </div>
            <button class="btn btn-warning d-print-none" onclick="window.print()">
                <i class="fas fa-print"></i>&nbsp;
                دریافت PDF
            </button>
            <?php $quantity = 0; ?>
            <table class="table table-borderless table-striped"> 
                <tr>
                    <th>
                        شماره
                    </th> 
                    <?php if($data['type'] == "stock"): ?>
                    <th>
                        گدام
                    </th> 
                    <th>
                        جنس
                    </th>
                    <?php endif; ?>

                    <th>
                        تعداد
                    </th>
                    <?php if($data['type'] == "customer"): ?>
                    <th>
                        نوعیت
                    </th>  
                    <?php endif; ?>
                </tr> 
                <?php $id = 0; ?>
                <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $quantity += $record->itemQuantity; ?>
                <tr>
                    <td>
                        <?php echo e(++$id); ?>

                    </td>


                    <?php if($data['type'] == "stock"): ?>
                    <td>
                        <?php echo e($record['stockName']); ?>

                    </td> 
                    <td>
                        <?php echo e($record['itemName']); ?>

                    </td> 
                    <?php endif; ?>

                    <td>
                        <?php echo e($record['itemQuantity']); ?>

                    </td>
                    <?php if($data['type'] == "customer"): ?>
                    <td>
                        <?php echo e($type[$record['operationType']]); ?>

                    </td>                 
                    <?php endif; ?>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <th colspan="<?php echo e($data['type'] == 'customer' ? 1 : 3); ?>">
                        مجموع
                    </th>
                    <th>
                        <?php echo e($quantity); ?>

                    </th>
                </tr>
            </table>
            <?php endif; ?>
        </div> 
    </div> 
 
</div> 
 
<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).on("change", ".persian-calendar", function(e) {
            let target = e.target; 
            if(target) {
                window.livewire.find('<?php echo e($_instance->id); ?>').setDate(target.getAttribute("name"), target.value)
            }
        })
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\MA\Desktop\StockManagement\resources\views/livewire/report.blade.php ENDPATH**/ ?>