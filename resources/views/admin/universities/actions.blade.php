<head>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<div class="d-flex gap-2">
    <button type="button" class="btn btn-sm btn-primary edit-btn" data-id="{{ $university->id }}">
        <i class="fas fa-edit"></i>
    </button>
    
    <form action="{{ route('admin.universities.destroy', $university) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="button" class="btn btn-sm btn-danger delete-btn">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>
