<!-- script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>

    async function callSwall(e){
        
        e.preventDefault()
        const result = await Swal.fire({
          title: 'Apakah anda yakin?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Iya',
          cancelButtonText: 'Batal'
        })
        
        if (result.value) {
            e.target.querySelector('form').submit()
        }
    }

</script>