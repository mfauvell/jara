@extends('layouts.app')

@section('content')
    <recipe-form
        :recipe="{{$recipe}}"
        :images="{{$images}}"
        :ingredients="{{$ingredients}}"
        :visibilities="{{$visibilities}}"
    />
@endsection
