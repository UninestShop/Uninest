<head>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<div class="d-flex">
    <!-- Product status actions -->
    <div class="btn-group me-2 mb-2">
        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-exchange-alt me-1"></i> Change Status
        </button>
        <ul class="dropdown-menu">
            <li>
                <button class="dropdown-item status-action {{ $product->status == 'approved' ? 'active' : '' }}" 
                        onclick="changeProductStatus('{{ $product->id }}', 'approve')">
                    <i class="fas fa-check-circle text-success me-1"></i> Approve
                </button>
            </li>
            <li>
                <button class="dropdown-item status-action {{ $product->status == 'rejected' ? 'active' : '' }}" 
                        onclick="changeProductStatus('{{ $product->id }}', 'reject')">
                    <i class="fas fa-times-circle text-danger me-1"></i> Reject
                </button>
            </li>
            <li>
                <button class="dropdown-item status-action {{ $product->status == 'flagged' ? 'active' : '' }}" 
                        onclick="changeProductStatus('{{ $product->id }}', 'flag')">
                    <i class="fas fa-flag text-warning me-1"></i> Flag
                </button>
            </li>
        </ul>
    </div>

    <!-- Standard edit/delete actions -->
    <div class="btn-group btn-group-sm mb-2">
        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-primary">
            <i class="fas fa-edit"></i>
        </a>
        <button type="button" class="btn btn-outline-danger" 
                onclick="confirmDelete('{{ $product->slug }}')">
            <i class="fas fa-trash"></i>
        </button>
    </div>
    
    <form id="delete-form-{{ $product->slug }}" 
          action="{{ route('admin.products.destroy', $product) }}" 
          method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
</div>

@once
@push('scripts')
<script>
function handleProduct(id, action) {
    if(confirm(`Are you sure you want to ${action} this product?`)) {
        $.post(`/admin/products/${id}/${action}`, { 
            _token: '{{ csrf_token() }}' 
        }).done(() => {
            $('#productsTable').DataTable().ajax.reload();
        });
    }
}
</script>
@endpush
@endonce
