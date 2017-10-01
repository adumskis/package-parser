@if($feed->status === \App\Feed::DONE)
    <span class="label label-success">@lang('ui.feed.statuses.' . $feed->status)</span>
@elseif($feed->status === \App\Feed::PARSING)
    <span class="label label-primary">@lang('ui.feed.statuses.' . $feed->status)</span>
@elseif($feed->status === \App\Feed::ERROR)
    <span class="label label-danger">@lang('ui.feed.statuses.' . $feed->status)</span>
@elseif($feed->status === \App\Feed::EXTRACTING)
    <span class="label label-primary">@lang('ui.feed.statuses.' . $feed->status)</span>
@elseif($feed->status === \App\Feed::IN_QUEUE)
    <span class="label label-info">@lang('ui.feed.statuses.' . $feed->status)</span>
@endif