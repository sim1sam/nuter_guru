@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Suppliers')}}</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{__('admin.Suppliers')}}</h1>
        </div>
        <div class="section-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header"><h4>{{__('admin.Add New')}}</h4></div>
                        <div class="card-body">
                            <form action="{{ route('admin.supplier.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>{{__('admin.Name')}} *</label>
                                    <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                                </div>
                                <div class="form-group">
                                    <label>{{__('admin.Code')}}</label>
                                    <input type="text" name="code" class="form-control" value="{{ old('code') }}">
                                </div>
                                <div class="form-group">
                                    <label>{{__('admin.Phone')}}</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                                </div>
                                <div class="form-group">
                                    <label>{{__('admin.Email')}}</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                                </div>
                                <div class="form-group">
                                    <label>{{__('admin.Address')}}</label>
                                    <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>{{__('admin.Status')}}</label>
                                    <select name="status" class="form-control">
                                        <option value="1">{{__('admin.Active')}}</option>
                                        <option value="0">{{__('admin.Inactive')}}</option>
                                    </select>
                                </div>
                                <button class="btn btn-primary btn-block">{{__('admin.Save')}}</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header"><h4>{{__('admin.All Suppliers')}} ({{ $suppliers->count() }})</h4></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>{{__('admin.Name')}}</th>
                                            <th>{{__('admin.Code')}}</th>
                                            <th>{{__('admin.Phone')}}</th>
                                            <th>{{__('admin.Email')}}</th>
                                            <th>{{__('admin.Status')}}</th>
                                            <th width="120">{{__('admin.Action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($suppliers as $s)
                                        <tr>
                                            <td>{{ $s->name }}</td>
                                            <td>{{ $s->code ?: '-' }}</td>
                                            <td>{{ $s->phone ?: '-' }}</td>
                                            <td>{{ $s->email ?: '-' }}</td>
                                            <td>
                                                @if($s->status)
                                                    <span class="badge badge-success">{{__('admin.Active')}}</span>
                                                @else
                                                    <span class="badge badge-danger">{{__('admin.Inactive')}}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button"
                                                    class="btn btn-primary btn-sm btn-edit-supplier"
                                                    data-id="{{ $s->id }}"
                                                    data-name="{{ $s->name }}"
                                                    data-code="{{ $s->code }}"
                                                    data-phone="{{ $s->phone }}"
                                                    data-email="{{ $s->email }}"
                                                    data-address="{{ $s->address }}"
                                                    data-status="{{ $s->status }}"
                                                    title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDeleteSupplier({{ $s->id }})" title="{{__('admin.Delete')}}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No suppliers yet. Add one from the left form.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- Edit modal --}}
<div class="modal fade" id="editSupplierModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" id="editSupplierForm" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>{{__('admin.Name')}} *</label>
                    <input type="text" name="name" id="edit_supplier_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>{{__('admin.Code')}}</label>
                    <input type="text" name="code" id="edit_supplier_code" class="form-control">
                </div>
                <div class="form-group">
                    <label>{{__('admin.Phone')}}</label>
                    <input type="text" name="phone" id="edit_supplier_phone" class="form-control">
                </div>
                <div class="form-group">
                    <label>{{__('admin.Email')}}</label>
                    <input type="email" name="email" id="edit_supplier_email" class="form-control">
                </div>
                <div class="form-group">
                    <label>{{__('admin.Address')}}</label>
                    <textarea name="address" id="edit_supplier_address" class="form-control" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <label>{{__('admin.Status')}}</label>
                    <select name="status" id="edit_supplier_status" class="form-control">
                        <option value="1">{{__('admin.Active')}}</option>
                        <option value="0">{{__('admin.Inactive')}}</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('admin.Close')}}</button>
                <button type="submit" class="btn btn-primary">{{__('admin.Update')}}</button>
            </div>
        </form>
    </div>
</div>

<form id="deleteSupplierForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<script>
(function ($) {
    $(document).on('click', '.btn-edit-supplier', function () {
        var $btn = $(this);
        $('#editSupplierForm').attr('action', '{{ url('admin/supplier') }}/' + $btn.data('id'));
        $('#edit_supplier_name').val($btn.data('name') || '');
        $('#edit_supplier_code').val($btn.data('code') || '');
        $('#edit_supplier_phone').val($btn.data('phone') || '');
        $('#edit_supplier_email').val($btn.data('email') || '');
        $('#edit_supplier_address').val($btn.data('address') || '');
        $('#edit_supplier_status').val(String($btn.data('status')));
        $('#editSupplierModal').modal('show');
    });
})(jQuery);

function confirmDeleteSupplier(id) {
    if (!confirm('Delete this supplier?')) {
        return;
    }
    var form = document.getElementById('deleteSupplierForm');
    form.action = '{{ url('admin/supplier') }}/' + id;
    form.submit();
}
</script>
@endsection
