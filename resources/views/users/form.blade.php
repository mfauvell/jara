@extends('layouts.app')

@section('content')
    <user-form
        :user="{{$user}}"
        :roles="{{$roles}}"
    />
@endsection
