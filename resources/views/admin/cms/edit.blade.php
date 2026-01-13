@extends('admin.layouts.app', ['title' => 'Edit CMS Pages'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4>Edit CMS Page</h4>
                        <a href="{{ route('admin.cms.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.cms.update', $data->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $data->title) }}" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $data->description) }}</textarea required>
                        </div>
                        
                        {{-- <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">Select Status</option required>
                                <option value="1" @if(old('status', $data->status) == 1) selected @endif>Active</option>
                                <option value="0" @if(old('status', $data->status) == 0) selected @endif>Inactive</option>
                            </select>
                        </div> --}}
                        
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('description', {
        height: 100,
        // Dis
        removePlugins: 'exportpdf',
        allowedContent: true
    });
    
    CKEDITOR.disableAutoInline = true;
    CKEDITOR.config.versionCheck = false;
</script>
@endpush
@endsection
