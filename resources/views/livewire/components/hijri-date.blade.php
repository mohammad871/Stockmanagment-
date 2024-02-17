<script>
    $(document).on("change", ".persian-calendar", function(e) {
        let targetValue = e.target.value;
        if(targetValue) {
            @this.setHijriDate(targetValue)
        }
    })
</script>
