<script>
    $(document).on("change", ".persian-calendar", function(e) {
        let targetValue = e.target.value;
        if(targetValue) {
            window.livewire.find('<?php echo e($_instance->id); ?>').setHijriDate(targetValue)
        }
    })
</script>
<?php /**PATH C:\Users\MA\Desktop\StockManagement\resources\views/livewire/components/hijri-date.blade.php ENDPATH**/ ?>