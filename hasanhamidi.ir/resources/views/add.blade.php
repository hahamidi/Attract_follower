@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add a page</h2>
    <p>Set username and password:</p>
    <form action="" method="POST">
        {{ csrf_field() }}
      <div class="form-group">
        <label for="usr">Username:</label>
        <input type="text" class="form-control" id="usr" name="username">
      </div>
      <div class="form-group">
        <label for="pwd">Password:</label>
        <input type="password" class="form-control" id="pwd" name="password">
      </div>
      <button type="submit" class="btn btn-primary">Add page</button>
    </form>
  </div>

@endsection 
@section('add')
{{'active'}}

@endsection 

