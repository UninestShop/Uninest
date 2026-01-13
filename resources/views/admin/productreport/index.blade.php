@extends('admin.layouts.app', ['title' => 'Product Reports'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4>Report</h4>

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
                                <th>Report User</th>
                                <th>Product Name</th>
                                <th>Reason</th>
                                <th>Date</th>
                                <th>Product Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            @foreach ($data as $key=> $page)
                           
                            <tr>
                                <td>{!! $key+1 !!}</td>
                                <td>{{ $page->user->name }}</td>
                                <td>{{ $page->product->name }}</td>
                                <td>{{ $page->reason }}</td>
                                <td>{{ $page->created_at }}</td>
                                <td>{{ $page->product->status }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#statusModal{{ $page->product->id }}">Change Status</button>

                                    <!-- Status Change Modal -->
                                    <div class="modal fade" id="statusModal{{ $page->product->id }}" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Change Product Status</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.products.status', $page->product->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="modal-body">
                                                        <p>Please select the status for this product:</p>
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="radio" name="status" 
                                                                   id="statusApproved{{ $page->product->id }}" value="approved" 
                                                                   {{ $page->product->status == 'approved' ? 'checked' : '' }} required>
                                                            <label class="form-check-label" for="statusApproved{{ $page->product->id }}">
                                                                Approved
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="status" 
                                                                   id="statusRejected{{ $page->product->id }}" value="rejected"
                                                                   {{ $page->product->status == 'rejected' ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="statusRejected{{ $page->product->id }}" required>
                                                                Rejected
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Update Status</button>
                                                    </div>
                                                </form>
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
            // "order": [[0, "desc"]]
        });
    });
</script>
@endpush


@endsection
