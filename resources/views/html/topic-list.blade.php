{{-- topic-listing --}}

<md-grid-list
        md-cols-xs="1" md-cols-sm="2" md-cols-md="4" md-cols-gt-md="6"
        md-row-height-gt-md="1:1"
        md-row-height="2:2"
        md-gutter="12px" md-gutter-gt-sm="8px" >
    @foreach($topics as $topic)
    <md-grid-tile class="gray"
                  {{--style="background: url('http://assets.fodors.com/destinations/21/grand-palace-night-bangkok-thailand_main.jpg');
                        background-size: cover"--}}
                  md-rowspan="auto" md-colspan="2" md-colspan-sm="1">

        {{$topic->topic}}

        <md-grid-tile-footer>
            <h3>{{$topic->topic}}</h3>
        </md-grid-tile-footer>
    </md-grid-tile>
    @endforeach
</md-grid-list>