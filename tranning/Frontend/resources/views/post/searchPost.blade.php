@extends('layouts.master')
@section('content')

<div id="result-search">
    <input type="hidden" id="searchQuery" value="{{$searchQuery}}">
    <div id="list-result-search">
    </div>
    <div id="more-posts">
        <button id="btn-more-post">Load more</button>
    </div>
</div>


<script src="{{asset('js/search-page.js')}}"></script>
@endsection
