@extends('layouts.master')

@section('content')
    <div id="category-page">
        <span>{{$category->category_name}}</span>
    </div>

<div id="block-post">
  <input type="hidden" id="categoryId" value="{{$category->id}}">
  <div id="block-post-items"></div>
  <div id="more-posts">
    <button id="btn-more-post">Load more</button>
  </div>
</div>

<script src="{{asset('js/category-page.js')}}">
</script>
@endsection
