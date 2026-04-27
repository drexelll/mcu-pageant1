@extends('layouts.appLayout')

@section('title', 'Edit Contestant')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Edit Contestant</h1>
        <div class="gold-line"></div>
    </div>
    <a href="{{ route('sas.contestants') }}" class="btn btn--outline">← Back</a>
</div>

<form action="{{ route('sas.contestants.update', $contestant->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card">
        <div class="contestant-form">
            {{-- Left side: photo upload --}}
            <div class="image-upload">
                <label for="photo" class="image-placeholder" style="background-image:url('{{ $contestant->photo ? asset('storage/'.$contestant->photo) : '' }}'); background-size:cover; background-position:center;">
                    @if(!$contestant->photo)
                        <span class="image-text">+ Add Image</span>
                    @endif
                </label>
                <input type="file" id="photo" name="photo" accept="image/*" hidden>
            </div>

            {{-- Right side: fields --}}
            <div class="form-fields">
                <div class="form-group">
                    <label class="form-label">Number</label>
                    <input type="number" name="number" class="form-input" value="{{ $contestant->number }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-input" value="{{ $contestant->name }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Course</label>
                    <input type="text" name="course" class="form-input" value="{{ $contestant->course }}" required>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn--gold">Save Changes</button>
        </div>
    </div>
</form>
@endsection
