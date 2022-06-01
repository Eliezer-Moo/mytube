@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            @if(session('message'))
                <div class="alert alert-success">
                    {{session('message')}}
                </div>
            @endif
        </div>
        <div class="row">
            <h2>Lista de videos</h2>
            <hr>
        </div>
    </div>
@endsection
