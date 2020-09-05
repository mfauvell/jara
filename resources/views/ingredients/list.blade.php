@extends('layouts.app')

@section('content')
    <ingredient-list
        :ingredients="{{$ingredients}}"
    />
@endsection
