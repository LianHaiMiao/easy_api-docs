<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.js"></script>
</head>
<body>
    <div class="container">
        <div class="text-center" style="height:60px;line-height:60px;">{{ $apiConfig->title }}</div>
            @foreach($apiRest as $apiItem)
            <div class="pannel panel-info">
                <div class="panel-heading">{{ $apiItem->tag }} <span style="margin:0 10px 0 10px;"> : </span>  {{ $apiItem->name }} </div>
                <div class="pannel-body">
                    <div class="panel-group" id="accordion">
                        @foreach($apiItem->apilist as $apidetail) 
                            @include('api-docs/post-section',array('api'=>array('basePath'=>$apiConfig->basePath,'method'=>$apidetail->method,'path'=>$apidetail->path,'summary'=>$apidetail->summary,'parameter'=>$apidetail->parameter,'description'=>$apidetail->description)))
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!--Modal-->
    <div class="modal fade" id="myModal" style="background-color:transparent;" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	    <div class="modal-dialog">
		    <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">
                        响应结果
                    </h4>
                </div>
                <div class="modal-body">
                    <p>Response:</p>
                    <div style="width:100%;min-height:200px;white-space:normal;">
                        <pre class="text-info" id="response-pad" style="word-wrap:break-word;">
                        </pre>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
		    </div><!-- /.modal-content -->
	    </div><!-- /.modal -->
    </div>

</body>
</html>
<script>
    
    // 通过Api接口获取数据,并显示
    function TryApi(bathPath, url, method, dom){
        console.log(dom);
        // 获取当前节点父节点的全部input标签
        var inputlist=dom.parentNode.getElementsByTagName('input');
        //用于保存
        var json={};
        // 遍历父节点的input去除里面的内容
        for(var i=0;i<inputlist.length;i++){
            var name=inputlist[i].getAttribute('name');
            var value=inputlist[i].value;
            json[name]=value;
        }
        url = bathPath + url;
        //$MyJS.Http.init(method,url,json).callback(ShowResponse).send();
        sendRequest(method,url,json);
    }

    // 发送请求函数
    function sendRequest(method,url,json){
        var request = new XMLHttpRequest(); 
        // 状态发生变化时，函数被回调
        request.onreadystatechange = function() {
            //成功完成
            if (request.readyState === 4) {
                //判断响应结果
                if(request.status === 200) {
                    // 响应成功,通过这个获取响应的内容
                    console.log(request.response);
                    ShowResponse(request.response);
                }
                else {
                    // 响应失败查看状态码
                    alert("请求失败");
                    console.log(request);
                    return request;
                }
            }
            else {
                //HTTP请求还在持续中
                console.log("HTTP请求还在持续中");
            }
        }
        //根据method来选择不同的方式发送HTTP请求
        if (method == "GET") {
            request.open('GET', buildGetOrPost(url, json));
            request.send();
        } 
        else if (method == "POST") {
            //构建FormData对象
            var formData = new FormData();
            for (var key in json) {
                formData.append(key, json[key]);
            }
            request.open("POST", url);
            request.send(formData);
        }
        else {
            alert('暂不支持GET和POST之外的请求');
        }
        return true;
    }

    // GET请求初始化
    function buildGetOrPost(url,json){
        url += '?myfunc=myfunc';
        for (var key in json) {
            url += ('&' + key + '=' + json[key]);
        }
        return url;
    }


    //查看响应response
    function ShowResponse(response){
        try{
            var dom=Parse_Dom('response-pad');
            json=JSON.parse(response);
            dom.sethtml(syntaxHighlight(json));
            $('#myModal').modal('show');
        }
        catch(e){
            var dom=$MyJS.Dom('lg-pad');
            dom.sethtml(response);
            $('.bd-example-modal-lg').modal('show');
        }
    }
    // 解析响应
    function Parse_Dom(domid){
        var dom;
        if (typeof domid == 'object') {
            dom = domid;
        } else {
            dom = document.getElementById(domid);
        }
        this.sethtml = function(str) {
            dom.innerHTML = str;
        }
        this.appendhtml = function(str) {
            dom.innerHTML += str;
        }
        this.hide = function() {
            dom.style.display = "none";
        }
        this.copy = function() {
            dom.parentNode.innerHTML += dom.outerHTML;
        }
        this.distory = function() {
            dom.parentNode.removeChild(dom);
        }
        this.attr = function(key, value) {
            if (value == undefined) {
                return dom.getAttribute(key);
            } else {
                dom.setAttribute(key, value);
            }
        }
        this.node = dom;
        return this;
    }

    //语法高亮
    function syntaxHighlight(json) {
    if (typeof json != 'string') {
        json = JSON.stringify(json, undefined, 2);
    }
    json = json.replace(/&/g, '&').replace(/</g, '<').replace(/>/g, '>');
    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function(match) {
        var cls = 'number';
        if (/^"/.test(match)) {
            if (/:$/.test(match)) {
                cls = 'key';
            } else {
                cls = 'string';
            }
        } else if (/true|false/.test(match)) {
            cls = 'boolean';
        } else if (/null/.test(match)) {
            cls = 'null';
        }
        return '<span class="' + cls + '">' + match + '</span>';
    });
}
</script>
