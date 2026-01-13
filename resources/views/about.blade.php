@extends('layouts.app')

@section('content')
<div class="container py-5">
  <div class="row">
    <div class="col-12">
      <h1 class="mb-4 cms-heading">{{$data->title ?? ''}}</h1>
      <div class="card cms-main">
        <div class="card-body">
           {!! $data->description ?? '' !!}
          
        </div>
      </div>
    </div>
  </div>
</div>

@endsection