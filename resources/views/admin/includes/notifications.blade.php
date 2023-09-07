<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
</script>

<script>

    @if(Session::has('alert-success'))
        toastr["success"]("{{ Session::get('alert-success') }}");
    @endif

    @if(Session::has('alert-info'))
        toastr["info"]("{{ Session::get('alert-info') }}");
    @endif

    @if(Session::has('alert-danger'))
        toastr["error"]("{{ Session::get('alert-danger') }}");
    @endif

</script>
