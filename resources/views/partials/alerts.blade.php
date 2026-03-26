
<!--Alertas de exito-->
@if(session('success'))
    <div id="alert-success" class="alert alert-success alert-dismissible d-flex align-items-center fade show">
        <i class="fa-solid fa-check-circle"></i>
        <strong class="mx-2">¡Éxito!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!--Alertas de peligro-->
@if(session('danger'))
    <div id="alert-danger" class="alert alert-danger alert-dismissible d-flex align-items-center fade show">
        <i class="fa-solid fa-trash-can"></i>
        <strong class="mx-2">¡Atención!</strong> {{ session('danger') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!--Alertas de errores-->
@if($errors->any())
    <div id="alert-errors" class="alert alert-danger alert-dismissible d-flex align-items-center fade show">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <strong class="mx-2">¡Error!</strong> 
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('success') || session('danger') || $errors->any())
<script>
    setTimeout(function(){
        let successAlert = document.getElementById('alert-success');
        let dangerAlert = document.getElementById('alert-danger');
        let errorsAlert = document.getElementById('alert-errors');

        if(successAlert) {
            successAlert.classList.remove('show');
            successAlert.classList.add('fade');
            setTimeout(() => successAlert.remove(), 500);
        }

        if(dangerAlert) {
            dangerAlert.classList.remove('show');
            dangerAlert.classList.add('fade');
            setTimeout(() => dangerAlert.remove(), 500);
        }

        if(errorsAlert) {
            errorsAlert.classList.remove('show');
            errorsAlert.classList.add('fade');
            setTimeout(() => errorsAlert.remove(), 500);
        }
    }, 5000); // 5 segundos
</script>
@endif
