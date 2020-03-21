@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{__('messages.periods')}}</div>
                <div class="card-body">
                    <form action="{{ route('period.save') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="name">{{ __('messages.name') }}</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="{{__('messages.enter-name')}}" value="{{ old('name') }}">
                        </div>
                        <button type="submit" class="btn btn-primary">{{__('messages.create')}}</button>
                    </form>    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection