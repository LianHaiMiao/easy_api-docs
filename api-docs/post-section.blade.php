<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{$api['method']}}-{{$api['summary']}}">
                @if($api['method']=="POST")
                <span class="label label-success">POST</span>
                @else
                <span class="label label-info">GET</span>
                @endif
                <span style="margin-left:20px;">{{$api['path']}}</span>
                <small><span class="text-primary"style="float:right;">{{$api['summary']}}</span></small>
            </a>
        </h4>
    </div>
    <div id="collapse-{{$api['method']}}-{{$api['summary']}}" class="panel-collapse collapse">
        <div class="panel-body">
            <div style="width:50%;float:left;">
            @foreach($api['parameter'] as $item)
                <div class="input-group">
                    <span class="input-group-addon input-sm">{{$item->name}}</span>
                    <input name="{{$item->name}}" type="text" class="form-control input-sm"/>
                    <span class="input-group-addon input-sm">{{$item->description}}</span>
                </div>
                <br>
            @endforeach
                <button class="btn btn-default btn-sm" onclick="TryApi('{{$api['basePath']}}','{{$api['path']}}','{{$api['method']}}',this);">测试接口</button>
            </div>
            <div style="width:50%;float:right;padding-left:20px;">
                <p class="text-info" style="font-size:12px;text-indent:24px;">{{$api['description']}}</p>
            </div>
        </div>
    </div>
</div>