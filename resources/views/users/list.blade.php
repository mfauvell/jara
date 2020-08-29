@extends('layouts.app')

@section('content')
<user-list
    :roles ="{{$roles}}"
>
</user-list>
@endsection
