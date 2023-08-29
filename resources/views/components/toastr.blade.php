@if($toastr = session('toastr'))
<script>
    window.onload = function(){
        document.querySelector('script[src="{{ asset("js/jquery-3.6.0.min.js") }}"]');
        toastr.{{ $toastr['type'] }}('{{ $toastr["text"] }}');
    }
</script>
@endif
