@extends('layouts.app')

@section('content')
    <recipe-list
        :recipes="{{$recipes}}"
    />
@endsection
