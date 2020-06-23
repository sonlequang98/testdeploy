@extends('layouts.master')

@section('content')
    @isset($post)
    <input type="hidden" id="post-id" value="{{$post->id}}">
    <div id="page-post-content">
    <div id="post-detail-title">
        <span>{{$post->title}}</span>
    </div>
    <div id="page-content">

    </div>
    <div id="post-info">
        <p><strong>Author：</strong>{{$post->user->name}}&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<strong>
        Release date：
        </strong>{{$post->release_number->release_number_name}}</p>
    </div>
    <!-- <div>
        {!!$post->content!!}
    </div> -->
    </div>
    <script src="{{asset('js/post-page.js')}}"></script>
    @endisset
@endsection