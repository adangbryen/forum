@extends('layouts.app')

@section('content')
<thread-view :count="{{ $thread->replies_count }}" :data-thread="{{ $thread }}" inline-template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="level">
                            <img scr="{{ $thread->creator->avatar_path }}"
                                alt=""
                                width="50" height="50"
                                class="mr-1">

                            <span class="flex">
                            <a href="{{ '/profiles/' . $thread->creator->name }}">
                                {{ $thread->creator->name }}
                            </a>
                                posted: {{ $thread->title }}
                            </span>
                            @can('update', $thread)
                                <form method="POST" action="{{ $thread->path() }}">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button class="btn btn-link" type="submit">Delete</button>
                                </form>
                            @endcan
                        </div>
                    </div>

                    <div class="panel-body">
                        {{ $thread->body }}
                    </div>
                </div>

                <replies @removed="repliesCount--" @added="repliesCount++"></replies>

            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <p>
                            This thread was published {{ $thread->created_at->diffForHumans() }} by
                            <a href="#">{{ $thread->creator->name }}</a>, and currently has
                            <span v-text="repliesCount"></span> comments.
                        </p>
                        <p>
                            <subscribe-button :active="{{ json_encode($thread->isSubscribeTo) }}" v-if="signedIn"></subscribe-button>
                            <button v-show="authorize('isAdmin')" @click="toggleLock" v-text="locked ? 'Unlock' : 'Lock'" class="btn btn-default"></button>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
 </thread-view>
@endsection
