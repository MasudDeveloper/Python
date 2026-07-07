@extends('layouts.app')

@section('title', $title . ' - Python Learning Hub')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-10 markdown-body">
    {!! $htmlContent !!}
</div>
@endsection
