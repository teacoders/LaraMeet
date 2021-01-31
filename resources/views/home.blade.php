@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{asset('css/video.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
@endsection

@section('content')
<main-component :loggedin_user="{{ auth()->user() }}" pusher-key="{{ config('broadcasting.connections.pusher.key') }}" pusher-cluster="{{ config('broadcasting.connections.pusher.options.cluster') }}"></main-component>
@endsection
