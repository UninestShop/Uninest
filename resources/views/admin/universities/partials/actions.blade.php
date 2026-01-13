<head>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<div class="d-flex">
    <a href="{{ route('admin.universities.edit', $university->id) }}" class="btn btn-sm btn-primary me-2">Edit</a>
    <form action="{{ route('admin.universities.destroy', $university->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this university?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
    </form>
</div>
