@extends('layouts.app')

@section('content')
    <recipe
        :recipe="{{$recipe}}"
        :editable="{{$editable ? 1 : 0}}"
        :deletable="{{$deletable ? 1 : 0}}"
    />
@endsection
