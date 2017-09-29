@extends('layouts.app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('ui.feed.feeds')
        </div>
        <table class="table">
            @foreach($feeds as $feed)
                <tr>
                    <td>{{ $feed->filename }}</td>
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
                {{ Form::file('file') }}
            </div>

            <button type="submit" class="btn btn-primary">
                @lang('ui.submit')
            </button>

            {{ Form::close() }}
        </div>
    </div>
@endsection