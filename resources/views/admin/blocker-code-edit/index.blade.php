@extends('layouts.app')

@push('datatable-styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/codemirror.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/mode/sql/sql.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/addon/hint/show-hint.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/addon/hint/sql-hint.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/addon/hint/show-hint.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/theme/monokai.min.css">
@endpush

@section('content')
    <textarea id="myTextarea"></textarea>
    <button type="button" class="btn btn-primary mt-2 ml-2">Save</button>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const myTextarea = document.getElementById('myTextarea');
            const editor = CodeMirror.fromTextArea(myTextarea, {
                lineNumbers: true,
                mode: 'text/x-mysql',
                theme: 'monokai',
                extraKeys: {
                    "Ctrl-Space": "autocomplete"
                },
                hintOptions: {
                    tables: {
                        users: {name: null, score: null, birthDate: null},
                        countries: {name: null, population: null, size: null}
                    }
                }

            });

            $('.btn').click(function () {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to save this code?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Save'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.block-code-edit.store') }}",
                            type: "POST",
                            data: {
                                code: editor.getValue(),
                                _token: "{{ csrf_token() }}"
                            },
                            success: function (response) {
                                if (response.status === 'success') {
                                    renderAlert(response.data, response.status);
                                } else {
                                    renderAlert(response.message, response.status);
                                }
                            },
                            error: function (response) {
                                renderAlert(response.message, 'error');
                            }
                        });
                    }
                })
            });

            function renderAlert(content, status) {
                $('.alert').remove();
                if (typeof content === 'object' && content !== '') {
                    content = JSON.stringify(content);
                }
                const statusClass = status === 'success' ? 'alert-success' : 'alert-danger';
                const html = `<div class="alert ${statusClass}" role="alert">
                    <h5 class="alert-heading">${status === 'success' ? 'Code executed successfully' : 'Code executed failly'}</h5>
                        ${content}
                    </div>`;
                $('#myTextarea').after(html);
            }
        })
  </script>
@endpush
