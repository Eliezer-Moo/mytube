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
            <h1>Editar videos</h1>
        </div>
    </div>
@endsection
