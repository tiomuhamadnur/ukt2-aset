@if (session('notify'))
    <script>
        Swal.fire({
            icon: "success",
            title: "Sukses!",
            html: @json(session('notify')) // pakai html, bukan text
        }).then(() => {
            if (window.history.replaceState) {
                window.history.replaceState(null, '', window.location.href);
            }
        });
    </script>
@elseif (session('error'))
    <script>
        Swal.fire({
            icon: "error",
            title: "Oops!",
            html: @json(session('error')) // pakai html, bukan text
        }).then(() => {
            if (window.history.replaceState) {
                window.history.replaceState(null, '', window.location.href);
            }
        });
    </script>
@elseif ($errors->any())
    <script>
        @php
            $errorsList = $errors->all();
            $messageError = collect($errorsList)
                ->map(function ($msg, $index) use ($errorsList) {
                    return count($errorsList) > 1
                        ? ($index + 1) . '. ' . e($msg)
                        : e($msg);
                })
                ->implode('<br>');
        @endphp
        Swal.fire({
            icon: "error",
            title: "Ooopss!",
            html: @json($messageError) // pakai html, bukan text
        }).then(() => {
            if (window.history.replaceState) {
                window.history.replaceState(null, '', window.location.href);
            }
        });
    </script>
@endif

<!-- BEGIN: Success Modal -->
<div id="success-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-2">
                <div class="p-2 text-center">
                    <h4 class="mt-2 fw-bolder">Success</h4>
                    <div class="text-slate-500 mt-2">{{ session('notify') ?? '-' }}</div>
                </div>
                <div class="px-5 pb-8 text-center mt-3">
                    <button type="button" data-dismiss="modal" class="btn btn-dark w-24 mr-1 me-2">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END:  Success Modal -->

<!-- BEGIN: Error Modal -->
<div id="error-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-2">
                <div class="p-2 text-center">
                    <h4 class="text-3xl mt-2 fw-bolder">Ooopps!</h4>
                    <h1 class="text-center align-middle text-danger mt-2" style="font-size: 100px">
                        <i class="mdi mdi-alert-circle-outline mx-auto"></i>
                    </h1>
                    <div class="text-slate-500 mt-2">{{ session('error') ?? '-' }}</div>
                </div>
                <div class="px-5 pb-8 text-center mt-3">
                    <button type="button" data-dismiss="modal" class="btn btn-dark w-24 mr-1 me-2">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END:  Error Modal -->
