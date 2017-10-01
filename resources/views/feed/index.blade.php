@extends('layouts.app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('ui.feed.feeds')
        </div>
        <table class="table">
            <tr>
                <th>{{ trans('ui.feed.created_at') }}</th>
                <th>
                    {{ trans('ui.feed.packages_count') }}
                    ({{ trans('ui.feed.parsed') }})
                </th>
                <th>{{ trans('ui.feed.status') }}</th>
            </tr>
            @foreach($feeds as $feed)
                <tr>
                    <td>
                        @if($feed->status === \App\Feed::DONE)
                            <a href="{{ route('feed.show', $feed->id) }}">
                                {{ $feed->created_at }}
                            </a>
                        @else
                            {{ $feed->created_at }}
                        @endif
                    </td>
                    <td>{{ $feed->packages()->count() }} ({{ $feed->packages()->where('is_parsed', 1)->count() }})</td>
                    <td>
                        @include('feed._status', ['feed' => $feed])
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    <div class="text-center">
        {{ $feeds->links() }}
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('ui.feed.add_feed')
        </div>
        <div class="panel-body">
            {{ Form::open(['route' => 'feed.store', 'method' => 'post', 'files' => true]) }}

            <div class="form-group">
                {{ Form::label('file', trans('ui.feed.file')) }}
                {{ Form::file('file[]', ['multiple' => 'multiple']) }}
            </div>

            <button type="submit" class="btn btn-primary">
                @lang('ui.submit')
            </button>

            {{ Form::close() }}
        </div>
    </div>
@endsection