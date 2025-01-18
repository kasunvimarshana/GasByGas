<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Toastr Notification
        @if(session('toast'))
            toastr.{{ session('toast.type') }}("{{ session('toast.message') }}");
        @endif

        // SweetAlert Notification
        @if(session('swal'))
            Swal.fire({
                title: "{{ session('swal.title') }}",
                text: "{{ session('swal.text') }}",
                icon: "{{ session('swal.icon') }}",
                confirmButtonText: "{{ session('swal.confirmButtonText') }}"
            });
        @endif
    });
</script>
