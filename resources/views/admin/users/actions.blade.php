<head>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<div class="btn-group">
    <a href="{{ route('admin.users.edit', $user->slug) }}" class="btn btn-sm btn-primary">
        <i class="fas fa-edit"></i>
    </a>
    <form action="{{ route('admin.users.destroy', $user->slug) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>
