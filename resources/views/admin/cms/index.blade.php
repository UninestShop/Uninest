@extends('admin.layouts.app', ['title' => 'CMS Pages'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4>CMS Pages</h4>
                        {{-- <a href="{{ route('admin.cms.create') }}" class="btn btn-primary">Add New Page</a>  --}}
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-hover" id="categoriesTable">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Title</th>
                                {{-- <th>Status</th> --}}
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pages as $k=>$page)
                            <tr>
                                <td scope="row" class="sr-no">{!! $k+1 !!}</td>
                                <td>{{ $page->title }}</td>
                                {{-- <td>{{ $page->status ? 'Active' : 'Inactive' }}</td> --}}
                                <td>
                                    <a href="{{ route('admin.cms.edit', $page->id) }}" class="btn btn-sm btn-info">Edit</a>
                                    {{-- <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $page->id }}">Delete</button> --}}

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $page->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirm Delete</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this page?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('admin.cms.destroy', $page->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#categoriesTable').DataTable({
            // "order": [[0, "asc"]]
        });
    });
</script>
@endpush

@endsection
