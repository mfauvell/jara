@extends('layouts.app')

@section('content')
    <dashboard
        :lastPublicRecipes="{{$lastPublicRecipes}}"
        :lastPrivateRecipes="{{$lastPrivateRecipes}}"
    />
@endsection
