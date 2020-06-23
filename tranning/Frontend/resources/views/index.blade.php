@extends('layouts.master')

@section('content')

<div class="slideshow-container">
@if(!$releaseNumbers->isEmpty())
  <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
    <div class="slide-info">
      <h4><u><i>Release number</i></u></h4>
      <h3>{{$releaseNumbers[0]->release_number_name}}</h3>
      <div class="releasenumber-description">
          <p>{{$releaseNumbers[0]->release_number_description}}</p>
        </div>
      </div>
    <div class="image-slide">
    <img src="http:\\adminsite.local\{{$releaseNumbers[0]->image->upload_path}}">
    </div>
     
    </div>
    @for($i = 1; $i < count($releaseNumbers); $i++)
     
      <div class="carousel-item">
      <div class="slide-info">
        <h4><u><i>Release number</i></u></h4>
        <h3>{{$releaseNumbers[$i]->release_number_name}}</h3>
        <div class="releasenumber-description">
          <p>{{$releaseNumbers[$i]->release_number_description}}</p>
        </div>
      </div>
        <div class="image-slide">
        <img src="http:\\adminsite.local\{{$releaseNumbers[$i]->image->upload_path}}">
        </div>
    </div>
    @endfor
    <div class="next-slide">
    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
    <span id="next-button">Next</span>
    <span class="sr-only"></span>
    </a>
    </div>
  </div>
  
</div>
@endif
</div>

<br>
<div class="title-new-post">
New
  </div>
<br>
@if(!$listPost->isEmpty())
<div id="block-new-posts">
    <div id="new-post">

      <img src="http:\\adminsite.local\{{$listPost[0]->image->upload_path}}" alt="">
      <a class="new-post-link" href="{{route('show-post',['id' =>$listPost[0]->id ])}}" title="{{$listPost[0]->title}}">{{$listPost[0]->title}}</a>
      <p class="new-post-name">{{$listPost[0]->release_number->release_number_name}}</p>
    </div>
    <div id="old-post">
        @if(isset($listPost[1]))
        <div id="old-post-above">
        <img src="http:\\adminsite.local\{{$listPost[1]->image->upload_path}}" alt="">
        <a class="old-post-link" href="{{route('show-post',['id' =>$listPost[1]->id ])}}" title="{{$listPost[1]->title}}">{{$listPost[1]->title}}</a>
        <p class="old-post-name">{{$listPost[1]->release_number->release_number_name}}</p>
        </div>
        @endif
        <div id="old-post-bellow">
            @if(isset($listPost[2]))
            <div>
            <img src="http:\\adminsite.local\{{$listPost[2]->image->upload_path}}" alt="">
            <a class="old-post-link" href="{{route('show-post',['id' =>$listPost[2]->id ])}}" title="{{$listPost[2]->title}}">{{$listPost[2]->title}}</a>
            <p class="old-post-name">{{$listPost[2]->release_number->release_number_name}}</p>
            </div>
            @endif
            @if(isset($listPost[3]))
            <div>
            <img src="http:\\adminsite.local\{{$listPost[3]->image->upload_path}}" alt="">
            <a class="old-post-link" href="{{route('show-post',['id' =>$listPost[3]->id ])}}" title="{{$listPost[3]->title}}">{{$listPost[3]->title}}</a>
            <p class="old-post-name">{{$listPost[3]->release_number->release_number_name}}</p>
            </div>
            @endif
        </div>
    </div>

</div>
@endif


<div id="block-post">
  
  <div class="title-page-post">
  Article
  </div>
  <div id="block-post-items"></div>
  <div id="more-posts">
    <button id="btn-more-post">Load more</button>
  </div>
</div>


<!-- The dots/circles -->

<script src="{{asset('js/index-page.js')}}"></script>
@endsection
