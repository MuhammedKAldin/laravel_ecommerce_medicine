@extends('admin.layout.layout')

@section('content')
<div class="container-fluid p-0">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="row mb-2 mb-xl-3">
                    <div class="col-auto d-none d-sm-block">
                        <h3>Product Log Details</h3>
                    </div>
                    <div class="col-auto ms-auto text-end mt-n1">
                        <a href="{{ route('admin.products.logs') }}" class="btn btn-secondary">
                            Back to Logs
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Basic Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <th width="150">Log ID</th>
                                    <td>{{ $log->id }}</td>
                                </tr>
                                <tr>
                                    <th>Product</th>
                                    <td>{{ $log->product->name }}</td>
                                </tr>
                                <tr>
                                    <th>Action</th>
                                    <td>
                                        <span class="badge bg-{{ $log->action == 'created' ? 'success' : ($log->action == 'updated' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($log->action) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Changed By</th>
                                    <td>{{ $log->admin->name }}</td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h5>Changes</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Field</th>
                                            <th>Old Value</th>
                                            <th>New Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($log->action == 'created')
                                            @foreach($log->changes['new'] as $field => $value)
                                                @if(!in_array($field, ['id', 'created_at', 'updated_at']))
                                                    <tr>
                                                        <td>{{ $field == 'category_id' ? 'Category' : ucfirst($field) }}</td>
                                                        <td>-</td>
                                                        <td>
                                                            @if($field == 'category_id')
                                                                {{ \App\Models\Category::find($value)->name ?? $value }}
                                                            @else
                                                                {{ is_array($value) ? json_encode($value) : $value }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @elseif($log->action == 'updated')
                                            @foreach($log->changes['new'] as $field => $value)
                                                <tr>
                                                    <td>{{ $field == 'category_id' ? 'Category' : ucfirst($field) }}</td>
                                                    <td>
                                                        @if($field == 'category_id')
                                                            {{ \App\Models\Category::find($log->changes['old'][$field])->name ?? $log->changes['old'][$field] }}
                                                        @else
                                                            {{ $log->changes['old'][$field] ?? '-' }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($field == 'category_id')
                                                            {{ \App\Models\Category::find($value)->name ?? $value }}
                                                        @else
                                                            {{ is_array($value) ? json_encode($value) : $value }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            @foreach($log->changes['old'] as $field => $value)
                                                @if(!in_array($field, ['id', 'created_at', 'updated_at']))
                                                    <tr>
                                                        <td>{{ $field == 'category_id' ? 'Category' : ucfirst($field) }}</td>
                                                        <td>
                                                            @if($field == 'category_id')
                                                                {{ \App\Models\Category::find($value)->name ?? $value }}
                                                            @else
                                                                {{ is_array($value) ? json_encode($value) : $value }}
                                                            @endif
                                                        </td>
                                                        <td>-</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 