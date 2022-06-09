@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <h2>Editar video</h2>
            <hr>
            <form action="{{route('videos.update', $video->id)}}" method="POST" enctype="multipart/form-data" class="col-lg-7">
                {!! csrf_field() !!}  <!-- Protección contra ataques ya implementado en laravel  https://www.welivesecurity.com/la-es/2015/04/21/vulnerabilidad-cross-site-request-forgery-csrf/-->
                @method('PUT')
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <label for="title">Título</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{$video->title}}"
                    />
                </div>
                <div class="form-group">
                    <label for="description">Descripción</label>
                    <textarea class="form-control" id="description" name="description">{{$video->description}}</textarea>
                </div>
                <div class="form-group">
                    <label for="image">Miniaturas</label>
                    <input type="file" class="form-control" id="image" name="image"/>
                </div>
                <div class="form-group">
                    <label for="video">Archivo de Vídeo</label>
                    <input type="file" class="form-control" id="video" name="video"/>
                </div>
                <hr>
                <button type="submit" class="btn btn-success">Actualizar Vídeo</button>
            </form>
        </div>
    </div>

@endsection
