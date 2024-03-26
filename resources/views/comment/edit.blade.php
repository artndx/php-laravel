@extends('layout')
@section('content')

    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
            <li>
                {{$error}}
            </li>
            @endforeach
        </ul>
    </div>
    @endif

<form action="/comment/{{$comment->id}}" method="post">
    @csrf
    @method("PUT")
    <div class="form-group">
        <label for="exampleInputTitle">Title</label>
        <input type="text" class="form-control" id="exampleInputTitle" name="title" value="{{$comment->title}}">
    </div>
    <div class="form-group">
    <label for="exampleInput>Description">Description</label>
    <input type="desc" class="form-control" id="exampleInputDescription" name="text" value="{{$comment->text}}">
  </div>
  <button type="submit" class="btn btn-primary">Update article</button>
</form>
@endsection