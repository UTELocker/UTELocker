<script src="{{ asset('vendor/jquery/date-range-picker/daterangepicker.min.js') }}"></script>

<script type="text/javascript">
    $(function() {

        var start = moment().subtract(89, 'days');
        var end = moment();

        // $('#datatableRange').daterangepicker({
        //     autoUpdateInput: false,
        //     linkedCalendars: false,
        //     startDate: start,
        //     endDate: end,
        //     showDropdowns: true,
        // }, cb);


        $('#datatableRange').on('apply.daterangepicker', function(ev, picker) {
            showTable();
        });

    });

</script>
