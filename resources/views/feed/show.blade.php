@extends('layouts.app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('ui.feed.feed') (Etot_kWh)
        </div>
        <div class="panel-body">
            <canvas id="feedChart" width="100%" height="50px" data-id="{{$feed->id}}"></canvas>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('ui.feed.total') (Etot_kWh)
        </div>
        <div class="panel-body">
            <canvas id="totalChart" width="100%" height="50px" data-id="{{$feed->id}}"></canvas>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        function getRandomColor() {
            return '#'+(Math.random()*0xFFFFFF<<0).toString(16);
        }

        $(document).ready(function () {
            var $ctxFeed = $('#feedChart');
            var feedChart = new Chart($ctxFeed, {
                type: 'line'
            });

            var $ctxTotal = $('#totalChart');
            var totalChart = new Chart($ctxTotal, {
                type: 'line'
            });


            $.ajax({
                url: '/chart-data',
                data: {
                    feed_id: $ctxFeed.data('id')
                },
                dataType: 'json',
                success: function (response){
                    for (var i = 0; i < response.datasets.length; i++) {
                        response.datasets[i].backgroundColor = getRandomColor();
                        response.datasets[i].borderColor = response.datasets[i].backgroundColor;
                        response.datasets[i].fill = false;
                        response.datasets[i].cubicInterpolationMode = 'monotone';
                    }
                    feedChart.data = response;
                    feedChart.update();
                }
            });

            $.ajax({
                url: '/chart-data',
                data: {
                    feed_id: $ctxTotal.data('id'),
                    total: true
                },
                dataType: 'json',
                success: function (response){
                    for (var i = 0; i < response.datasets.length; i++) {
                        response.datasets[i].backgroundColor = getRandomColor();
                        response.datasets[i].borderColor = response.datasets[i].backgroundColor;
                        response.datasets[i].fill = false;
                        response.datasets[i].cubicInterpolationMode = 'monotone';
                    }
                    totalChart.data = response;
                    totalChart.update();
                }
            });
        });
    </script>
@stop
