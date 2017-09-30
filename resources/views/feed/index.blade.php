@extends('layouts.app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('ui.feed.feeds')
        </div>
        <table class="table">
            <tr>
                <th>{{ trans('ui.feed.created_at') }}</th>
                <th>{{ trans('ui.feed.packages_count') }}</th>
                <th>{{ trans('ui.feed.status') }}</th>
            </tr>
            @foreach($feeds as $feed)
                <tr>
                    <td>{{ $feed->created_at }}</td>
                    <td>{{ $feed->packages()->count() }}</td>
                    <td>{{ $feed->status }}</td>
                </tr>
            @endforeach
        </table>
    </div>

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