@component('profiles.activities.activity')
    @slot('heading')
        <a href="{{ route('profile', $activity->subject->creator)  }}">
            {{ $activity->subject->creator->name }}
        </a>
        published:
        <a href="{{ $activity->subject->path() }}">{{ $activity->subject->title }}</a>
    @endslot

    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent
