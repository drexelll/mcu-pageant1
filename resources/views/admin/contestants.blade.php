@extends('layouts.appLayout')

@section('title', 'Contestants')

@section('content')

<div class="page-header">
    <div>
        <div class="page-label">Admin Panel</div>
        <h1 class="page-title">Contestants</h1>
        <div class="gold-line"></div>
    </div>
</div>

{{-- This is just to test that data is coming through --}}
<p>Total contestants: {{ $contestants->count() }}</p>

@foreach($contestants as $contestant)
    <p>{{ $contestant->number }} — {{ $contestant->name }} — {{ $contestant->college }}</p>
@endforeach

@endsection
