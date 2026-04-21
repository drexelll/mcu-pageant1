@extends('layouts.appLayout')

@section('title', 'Request Contestant')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Request New Contestant</h1>
        <div class="gold-line"></div>
    </div>
    <a href="{{ route('sas.contestants') }}" class="btn btn--outline">← Back</a>
</div>

<form action="{{ route('sas.contestants.store') }}" method="POST">
    @csrf

    <div class="card">
        <div class="form-group">
            <label class="form-label">Number</label>
            <input type="number" name="number" class="form-input" required>
        </div>

        <div class="form-group">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-input" required>
        </div>

        <div class="form-group">
            <label class="form-label">Course</label>
            <input type="text" name="course" class="form-input" required>
        </div>

        <div class="form-group">
            <label class="form-label">Photo Path</label>
            <input type="text" name="photo" class="form-input" placeholder="e.g. contestants/contestant1_pic.jpg">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn--gold">Create Contestant</button>
        </div>
    </div>
</form>
@endsection
