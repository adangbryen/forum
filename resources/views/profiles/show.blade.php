@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="page-header">
                    <avatar-form :user="{{ $profileUser }}"></avatar-form>

                    @foreach($activities as $date => $activity)
                        <h3 class="page-header">{{ $date }}</h3>
                        @foreach($activity as $data)
                            @if (view()->exists("profiles.activities.{$data->type}"))
                                @include("profiles.activities.{$data->type}", ['activity' => $data])
                            @endif
                        @endforeach
                    @endforeach
                </div>

            </div>
        </div>
    </div>
@endsection
