@extends('site.layout')

@section('title', $page['title'])

@section('content')

<div class="container my-4">
    <div class="col-md-12 my-auto showcase-text">
        <h2>{{$page['title']}}</h2>
        <p class="lead mb-0">
            {!! $page['body'] !!}
        </p>
    </div>
    
</div>

@endsection
