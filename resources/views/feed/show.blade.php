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
@endsection

@section('javascript')
    <script>
        function getRandomColor() {
            return '#'+(Math.random()*0xFFFFFF<<0).toString(16);
        }

        $(document).ready(function () {
            var $ctx = $('#feedChart');
            var feedChart = new Chart($ctx, {
                type: 'line'
            });

            $.ajax({
                url: '/chart-data',
                data: {
                    feed_id: $ctx.data('id')
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
        });
    </script>
@stop
