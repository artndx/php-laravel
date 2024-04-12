@extends('layout')
@section('content')
<table class="table">
  <thead>
    <tr>
      <th scope="col">Id</th>
      <th scope="col">Title</th>
      <th scope="col">Text</th>
      <th scope="col">Article</th>
      <th scope="col">User</th>
      <th scope="col">Accept / Reject</th>=
    </tr>
  </thead>
  <tbody>
    @foreach($comments as $comment)
    <tr>
      <th scope="row">{{$comment->id}}</th>
      <th scope="row">{{$comment->title}}</th>
      <th scope="row">{{$comment->text}}</th>
      <th scope="row">{{$comment->article_name}}</th>
      <th scope="row">{{$comment->user_name}}</th>
      <td>
        @if(!$comment->accept)
          <a href="comments/{{$comment->id}}/accept"><button type="submit" class="btn btn-success">Accept</button></a>
        @else
          <a href="comments/{{$comment->id}}/reject"><button type="submit" class="btn btn-danger">Reject</button></a>
        @endif
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection