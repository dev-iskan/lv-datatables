@extends('layouts.app')

@section('content')
    <div class="container">
        <users-index endpoint="{{route('users-api')}}"></users-index>
    </div>
@endsection
