<!-- Numero Field -->
<div class="form-group col-sm-6">
    {!! Form::label('numero', 'Numero:') !!}
    {!! Form::text('numero', isset($viatico) ? null : $proximoNumero, ['class' => 'form-control', 'readonly' => 'readonly', 'required' => 'required']) !!}
</div>

<!-- Fecha Field -->
<div class="form-group col-sm-6">
    {!! Form::label('fecha', 'Fecha:') !!}
    {!! Form::text('fecha', isset($viatico) ? null : now()->format('Y-m-d'), ['class' => 'form-control', 'readonly' => 'readonly', 'required' => 'required']) !!}
</div>

<!-- Id Chofer Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_chofer', 'Chofer:') !!}
    {!! Form::select('id_chofer', $choferes, null, ['class' => 'form-control', 'id' => 'id_chofer', 'placeholder' => 'Seleccione un chofer', 'required' => 'required']) !!}
</div>

<!-- Descripcion Field -->
<div class="form-group col-sm-6">
    {!! Form::label('descripcion', 'Descripcion:') !!}
    {!! Form::select('descripcion', ['Transferencia' => 'Transferencia', 'Giro' => 'Giro', 'Efectivo' => 'Efectivo'], null, ['class' => 'form-control', 'placeholder' => 'Seleccione una opción', 'required' => 'required']) !!}
</div>

<!-- Monto Field -->
<div class="form-group col-sm-6">
    {!! Form::label('monto', 'Monto:') !!}
    {!! Form::text('monto', null, ['class' => 'form-control', 'id' => 'monto', 'required' => 'required']) !!}
</div>

<!-- Id Orden Carga Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_orden_carga', 'Orden de Carga:') !!}
    @php $ordenCargaSeleccionada = old('id_orden_carga', isset($viatico) ? $viatico->id_orden_carga : null); @endphp
    <select name="id_orden_carga" id="id_orden_carga" class="form-control select2" style="width: 100%" required>
        <option value="">Seleccione una orden de carga</option>
        @foreach($ordenCargas as $id => $ordenCarga)
            <option value="{{ $id }}" data-chofer="{{ $ordenCarga['id_chofer'] }}" {{ (string) $ordenCargaSeleccionada === (string) $id ? 'selected' : '' }}>
                {{ $ordenCarga['texto'] }}
            </option>
        @endforeach
    </select>
</div>

<!-- Cargado Por Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cargado_por', 'Cargado Por:') !!}
    {!! Form::text('cargado_por', isset($viatico) ? null : Auth::user()->name, ['class' => 'form-control', 'readonly' => 'readonly', 'required' => 'required']) !!}
</div>

<!-- Documentos Field -->
<div class="form-group col-sm-12">
    {!! Form::label('documentos', 'Documentos:') !!}

    <div id="documentos-dropzone" class="documentos-dropzone">
        <p class="mb-0">Arrastra los documentos aquí o haz clic para seleccionar</p>
    </div>
    <input type="file" id="documentos-input" name="documentos[]" multiple accept="image/*,.pdf" class="d-none">

    <div id="documentos-preview" class="documentos-preview"></div>

    @isset($viatico)
        @if($viatico->documentos && $viatico->documentos->count())
            <div class="mt-3">
                <label>Documentos ya cargados:</label>
                <div class="documentos-preview">
                    @foreach($viatico->documentos as $documento)
                        <div class="documento-card">
                            <a href="{{ asset('documento_viatico/' . $documento->nombre_archivo) }}" target="_blank">
                                <div class="documento-icon">
                                    @if(Str::endsWith(strtolower($documento->nombre_archivo), ['.jpg', '.jpeg', '.png', '.gif']))
                                        <i class="fas fa-file-image"></i>
                                    @elseif(Str::endsWith(strtolower($documento->nombre_archivo), ['.pdf']))
                                        <i class="fas fa-file-pdf"></i>
                                    @else
                                        <i class="fas fa-file"></i>
                                    @endif
                                </div>
                                <small class="d-block text-truncate">{{ $documento->nombre_archivo }}</small>
                            </a>
                            <button type="button" class="documento-remove documento-remove-existing"
                                    data-url="{{ route('viaticos.documentos.destroy', $documento->id) }}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endisset
</div>

<style>
    #id_orden_carga + .select2-container .select2-selection--single {
        height: calc(1.5em + .75rem + 2px);
        border: 1px solid #ced4da;
        border-radius: .25rem;
    }
    #id_orden_carga + .select2-container .select2-selection--single .select2-selection__rendered {
        line-height: calc(1.5em + .75rem);
        padding-left: .75rem;
        color: #495057;
    }
    #id_orden_carga + .select2-container .select2-selection--single .select2-selection__arrow {
        height: calc(1.5em + .75rem);
        right: 6px;
    }
    #id_orden_carga + .select2-container--default.select2-container--focus .select2-selection--single,
    #id_orden_carga + .select2-container--default .select2-selection--single:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 .2rem rgba(0,123,255,.25);
    }

    .documentos-dropzone {
        border: 2px dashed #ced4da;
        border-radius: .25rem;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        background: #f8f9fa;
        transition: background .2s, border-color .2s;
    }
    .documentos-dropzone.dragover {
        background: #e9ecef;
        border-color: #6c757d;
    }
    .documentos-preview {
        display: flex;
        flex-wrap: wrap;
        margin-top: .75rem;
    }
    .documento-card {
        width: 100px;
        border: 1px solid #dee2e6;
        border-radius: .25rem;
        padding: .25rem;
        margin: .25rem;
        text-align: center;
        color: inherit;
        position: relative;
    }
    .documento-icon {
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #6c757d;
    }
    .documento-remove {
        position: absolute;
        top: -8px;
        right: -8px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #dc3545;
        color: #fff;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .7rem;
        line-height: 1;
        cursor: pointer;
        padding: 0;
    }
    .documento-remove:hover {
        background: #b02a37;
    }
</style>

<script>
    (function () {
        var dropzone = document.getElementById('documentos-dropzone');
        var input = document.getElementById('documentos-input');
        var preview = document.getElementById('documentos-preview');
        var selectedFiles = [];

        function iconClassFor(filename) {
            var ext = filename.split('.').pop().toLowerCase();

            if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].indexOf(ext) !== -1) {
                return 'fa-file-image';
            }

            if (ext === 'pdf') {
                return 'fa-file-pdf';
            }

            return 'fa-file';
        }

        function syncInput() {
            var dataTransfer = new DataTransfer();
            selectedFiles.forEach(function (file) {
                dataTransfer.items.add(file);
            });
            input.files = dataTransfer.files;
        }

        function renderPreview() {
            preview.innerHTML = '';

            selectedFiles.forEach(function (file, index) {
                var card = document.createElement('div');
                card.className = 'documento-card';

                var icon = document.createElement('div');
                icon.className = 'documento-icon';
                icon.innerHTML = '<i class="fas ' + iconClassFor(file.name) + '"></i>';
                card.appendChild(icon);

                var name = document.createElement('small');
                name.className = 'd-block text-truncate';
                name.textContent = file.name;
                card.appendChild(name);

                var remove = document.createElement('button');
                remove.type = 'button';
                remove.className = 'documento-remove';
                remove.innerHTML = '<i class="fas fa-times"></i>';
                remove.addEventListener('click', function (e) {
                    e.stopPropagation();
                    selectedFiles.splice(index, 1);
                    syncInput();
                    renderPreview();
                });
                card.appendChild(remove);

                preview.appendChild(card);
            });
        }

        function addFiles(fileList) {
            Array.from(fileList).forEach(function (file) {
                selectedFiles.push(file);
            });
            syncInput();
            renderPreview();
        }

        dropzone.addEventListener('click', function () {
            input.click();
        });

        dropzone.addEventListener('dragover', function (e) {
            e.preventDefault();
            dropzone.classList.add('dragover');
        });

        dropzone.addEventListener('dragleave', function () {
            dropzone.classList.remove('dragover');
        });

        dropzone.addEventListener('drop', function (e) {
            e.preventDefault();
            dropzone.classList.remove('dragover');
            addFiles(e.dataTransfer.files);
        });

        input.addEventListener('change', function () {
            addFiles(input.files);
        });

        document.querySelectorAll('.documento-remove-existing').forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (!confirm('¿Eliminar este documento?')) {
                    return;
                }

                var tokenField = document.querySelector('input[name="_token"]');
                var card = btn.closest('.documento-card');

                fetch(btn.dataset.url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': tokenField ? tokenField.value : '',
                        'Accept': 'application/json',
                    },
                }).then(function (response) {
                    if (response.ok) {
                        card.remove();
                    } else {
                        alert('No se pudo eliminar el documento.');
                    }
                });
            });
        });
    })();
</script>

<script>
    (function () {
        var montoInput = document.getElementById('monto');
        if (!montoInput) {
            return;
        }

        function formatearMiles(valor) {
            var digitos = valor.replace(/\D/g, '');
            return digitos ? new Intl.NumberFormat('es-PY').format(digitos) : '';
        }

        montoInput.value = formatearMiles(montoInput.value);

        montoInput.addEventListener('input', function () {
            var posicionDesdeFinal = montoInput.value.length - montoInput.selectionStart;
            montoInput.value = formatearMiles(montoInput.value);
            var posicion = montoInput.value.length - posicionDesdeFinal;
            montoInput.setSelectionRange(posicion, posicion);
        });

        if (montoInput.form) {
            montoInput.form.addEventListener('submit', function () {
                montoInput.value = montoInput.value.replace(/\D/g, '');
            });
        }
    })();
</script>

{{-- jQuery/Select2 solo estan disponibles despues de @yield('content'), asi que este
     script se registra via @push('third_party_scripts') para ejecutarse recien al final
     del body, cuando esas librerias ya cargaron. --}}
@push('third_party_scripts')
    <script>
        $(function () {
            var $choferSelect = $('#id_chofer');
            var $ordenCargaSelect = $('#id_orden_carga');

            if (!$choferSelect.length || !$ordenCargaSelect.length) {
                return;
            }

            $ordenCargaSelect.select2({
                width: '100%',
                placeholder: 'Seleccione una orden de carga',
                allowClear: true,
                matcher: function (params, data) {
                    if (!data.id) {
                        return data;
                    }

                    var choferId = $choferSelect.val();
                    if (choferId && String($(data.element).data('chofer')) !== String(choferId)) {
                        return null;
                    }

                    if (!params.term) {
                        return data;
                    }

                    if (data.text.toUpperCase().indexOf(params.term.toUpperCase()) > -1) {
                        return data;
                    }

                    return null;
                }
            });

            function limpiarSiInvalida() {
                var choferId = $choferSelect.val();
                var $opcionSeleccionada = $ordenCargaSelect.find('option:selected');

                if (choferId && $opcionSeleccionada.val() && String($opcionSeleccionada.data('chofer')) !== String(choferId)) {
                    $ordenCargaSelect.val('').trigger('change');
                }
            }

            $choferSelect.on('change', limpiarSiInvalida);
        });
    </script>
@endpush