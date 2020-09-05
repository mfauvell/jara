@extends('layouts.app')

@section('content')
    <ingredient-form
        :ingredient="{{$ingredient}}"
        :image="{{$image }}"
    />
@endsection
