<h2>{{ trans('stats.this_week') }}</h2>

<div class="screen-stats">
    
    <div class="metrics row">
        <div class="col-sm-3">
            <div class="metric">
                <div class="metric-number">{{ $stats['summary_week']['sessions'] }}</div>
                <div class="metric-label">{{ trans('stats.sessions') }}</div>
                <div class="metric-total">
                {!! trans(
                    'stats.total_nb',
                    ['nb' => $stats['summary_total']['sessions']]
                ) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="metric">
                <div class="metric-number">{{ $stats['summary_week']['pageviews'] }}</div>
                <div class="metric-label">{{ trans('stats.page_views') }}</div>
                <div class="metric-total">
                {!! trans(
                    'stats.total_nb',
                    ['nb' => $stats['summary_total']['pageviews']]
                ) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="metric">
                <div class="metric-number">{{ $weekDuration }}</div>
                <div class="metric-label">{{ trans('stats.avg_time') }}</div>
                <div class="metric-total">
                {!! trans(
                    'stats.total_nb',
                    ['nb' => $totalDuration]
                ) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="metric">
                <div class="metric-number">{{ $stats['summary_week']['events'] }}</div>
                <div class="metric-label">{{ trans('stats.interactions') }}</div>
                <div class="metric-total">
                {!! trans(
                    'stats.total_nb',
                    ['nb' => $stats['summary_total']['events']]
                ) !!}
                </div>
            </div>
        </div>
    </div>
    
    <hr/>
    
    <div class="row">
        
        <div class="col-sm-6">
            
            <h3>{{ trans('stats.interactions') }}</h3>
            
            <table class="table">
                <thead>
                    <tr>
                        <th align="left">{{ trans('stats.interaction') }}</th>
                        <th align="center" width="100">{{ trans('stats.number') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stats['events_week'] as $label => $value)
                    <tr>
                        <td align="left">{{ $label }}</td>
                        <td align="center" width="100">{{ $value }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
        </div>
        
        <div class="col-sm-6">
            
            <h3>{{ trans('stats.page_views') }}</h3>
            
            <table class="table data">
                <thead>
                    <tr>
                        <th align="left">{{ trans('stats.page') }}</th>
                        <th align="center" width="100">{{ trans('stats.number') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stats['pageviews_week'] as $label => $statGroupOrValue)
                        @if(is_array($statGroupOrValue))
                        <tr class="data-group">
                            <td align="left">{{ $label }}</td>
                            <td align="center" width="100">{{ $statGroupOrValue['value'] }}</td>
                        </tr>
                            @foreach($statGroupOrValue['children'] as $label => $value)
                            <tr class="data-group-children">
                                <td align="left">{{ $label }}</td>
                                <td align="center" width="100">{{ $value }}</td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td align="left">{{ $label }}</td>
                            <td align="center" width="100">{{ $statGroupOrValue }}</td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            
        </div>
    
    </div>
    
</div>
