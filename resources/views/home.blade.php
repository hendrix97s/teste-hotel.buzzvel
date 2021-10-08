@extends('layouts.app')

@section('content')
    
    <home-component name="{{ Auth::user()->name }}"/>
@endsection
