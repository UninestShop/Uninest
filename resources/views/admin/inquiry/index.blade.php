@extends('admin.layouts.app',['title' => 'Inquiries'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4>Inquiry</h4>

                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-hover" id="contact">
                        <thead>
                            <tr>
                                <th>S.NO</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            @foreach ($data as $key=> $page)
                           
                            <tr>
                                <td>{!! $key+1 !!}</td>
                                <td>{{ $page->name }}</td>
                                <td>{{ $page->email }}</td>
                                <td>{{ $page->subject }}</td>
                                <td>{{ $page->message }}</td>
                                <td>{{ $page->created_at }}</td>
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
        $('#contact').DataTable({
            responsive: true,
            processing: true,
            ordering: true,
            paging: true,
            language: {
                emptyTable: "No inquiries found"
            }
        });
    });
</script>
@endpush


@endsection
