@extends('pages.layouts.app')


@php
    $apiUrl = $apiUrl;
@endphp

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mt-3">{{ $title }}</h5>
            </div>
            <div class="card-body">
                <div class="btn-list">
                    <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal"
                        data-bs-target="#modalCategoryCreate">ADD CATEGORIES</button>
                    <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal"
                        data-bs-target="#modalProductCreate">ADD PRODUCTS</button>
                </div>
                <table id="productTable" class="table table-bordered text-nowrap mt-3 w-100">
                    <thead>
                        <tr>
                            <th>NAME</th>
                            <th>PRICE</th>
                            <th>CATEGORY</th>
                            <th>STATUS</th>
                            <th width="8%">ACTION</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL --}}
    <div class="modal fade" id="modalCategoryCreate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalCategoryCreateLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="modalCategoryCreateLabel">ADD NEW CATEGORY</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formStore" action="#" data-url="{{ $apiUrl . 'categories' }}" method="POST"
                    class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control mt-1" id="name" name="name"
                                placeholder="name">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL CREATE PRODUCT --}}
    <div class="modal fade" id="modalProductCreate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalProductCreateLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="modalProductCreateLabel">ADD NEW PRODUCT</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formStoreProduct" action="#" data-url="{{ $apiUrl . 'products' }}" method="POST"
                    class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control mt-1" id="name" name="name"
                                placeholder="name">
                        </div>
                        <div class="form-group mt-3">
                            <label for="name">Price <span class="text-danger">*</span></label>
                            <input type="number" class="form-control mt-1" id="price" name="price"
                                placeholder="0.000">
                        </div>
                        <div class="form-group mt-3">
                            <label for="name">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="select mt-1 form-select">
                                <?php foreach($categories as $c) : ?>
                                <option value="{{ $c['id'] }}">{{ $c['name'] }}</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label for="name">Status <span class="text-danger">*</span></label>
                            <select name="status" class="select mt-1 form-select">
                                <option value="bagus">Bagus</option>
                                <option value="Rusak">Rusak</option>
                            </select>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- MODAL DELETE PRODUCT --}}
    <div class="modal fade" id="modalProductDelete" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalDeleteCreateLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <form id="formDelete" action="#" data-url={{ $apiUrl . 'products' }} method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <div class="text-center">
                            <span class="avatar avatar-md bg-danger-transparent svg-white avatar-rounded mb-3">
                                <i class="ri-delete-bin-6-line fs-4 text-danger p-5"></i>
                            </span>
                            <h6 class="modal-text">Are you sure to delete this data?</h6>
                            <span class="text-muted">This action can not be undone</span>
                            <input type="hidden" name="id" id="did">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" aria-label="Close">DISCARD
                            !</button>
                        <button type="submit" class="btn btn-danger">DELETE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- MODAL UPDATE PRODUCT --}}
    <div class="modal fade" id="modalProductEdit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalProductEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="modalProductEditLabel">UPDATE PRODUCT</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formUpdateProduct" action="#" data-url="" method="PATCH" class="needs-validation"
                    novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control mt-1" id="name" name="name" placeholder="name">
                        </div>
                        <div class="form-group mt-3">
                            <label for="name">Price <span class="text-danger">*</span></label>
                            <input type="number" class="form-control mt-1" id="price" name="price"
                                placeholder="0.000">
                        </div>
                        <div class="form-group mt-3">
                            <label for="name">Category <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="select mt-1 form-select">
                                <?php foreach($categories as $c) : ?>
                                <option value="{{ $c['id'] }}">{{ $c['name'] }}</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label for="name">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="select mt-1 form-select">
                                <option value="Bagus">Bagus</option>
                                <option value="Rusak">Rusak</option>
                            </select>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- /MODAL --}}
@endsection


@section('script')
    <script src="https://cdn.datatables.net/2.1.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    <script>
        $(document).ready(function() {
            var apiUrl = "{{ $apiUrl }}";
            $(document).on('click', '#btn-delete-product', function() {
                $('#did').val($(this).attr('data-idx'));
            });

            $('.btn-delete-product').click(function() {});

            $(document).on("click", "#btn-edit-product", (function() {
                let id = $(this).attr("data-idx");
                let name = $(this).attr("data-name");
                let price = $(this).attr("data-price");
                let category_id = $(this).attr("data-category_id");
                let status = $(this).attr("data-status");

                $("#modalProductEdit #id").val(id);
                $("#modalProductEdit #name").val(name);
                $("#modalProductEdit #price").val(price);
                 
                $("#modalProductEdit #category_id option").prop("selected", false);
                $("#modalProductEdit #status option").prop("selected", false);

                setTimeout(() => {
                    $("#modalProductEdit #category_id").val(category_id).prop("selected", 'selected').trigger("change");
                    $("#modalProductEdit #status").val(status).prop("selected", 'selected').trigger("change");
                }, 50);
                
                $("#modalProductEdit #formUpdateProduct").attr('data-url', apiUrl + "products/" + id);

            }));

            ajaxHandler.submit("formStore");
            ajaxHandler.submit("formStoreProduct");
            ajaxHandler.delete("formDelete", "modalProductDelete");
            ajaxHandler.submit("formUpdateProduct");


            function loadTable(perPage) {
                $('#productTable').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: apiUrl + "products",
                        type: "GET",
                        data: function(data) {
                            data.search = $('input[type="search"]').val();
                            data.length = data.length;
                            data.start = data.start;
                            data.draw = data.draw;
                        },
                        dataSrc: function(json) {
                            return json.results?.data || [];
                        }
                    },
                    columns: [{
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'price',
                            name: 'price',
                            className:'text-end',
                            render: function(data, type, row) {
                                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(data);
                            }
                        },
                        {
                            data: 'category',
                            name: 'category'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: null,
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                return `
                                        <button type="button" class="btn btn-sm btn-warning ms-auto" id="btn-edit-product" data-bs-toggle="modal" data-bs-target="#modalProductEdit"  data-idx="${row.id}" data-name="${row.name}" data-price="${row.price}" data-category_id="${row.category_id}" data-status="${row.status}" >Edit</button>
                                        <button class="btn btn-sm btn-danger" id="btn-delete-product" data-bs-toggle="modal" data-bs-target="#modalProductDelete" data-idx="${row.id}">Delete</button>
                                    `;
                            }
                        }
                    ],
                    serverMethod: "GET",
                    paging: true,
                    lengthChange: false,
                    pageLength: 10,
                    drawCallback: function(settings) {
                        var json = settings.json;
                        if (json) {
                            $('#pagination-info').text(
                                `Showing ${settings._iDisplayStart + 1} to ${settings._iDisplayStart + settings._iDisplayLength} of ${json.results.recordsTotal} entries`
                            );
                        }
                    },
                });
            }

            loadTable(10);

        });
    </script>
@endsection
