<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LOL</title>
<style type="text/css">
    @import 'resources/css/reset.css';
    @import 'resources/css/style.css';
</style>
<script type="text/javascript" src="resources/js/jquery.js"></script>
<script type="text/javascript" src="resources/js/jquery.ba-dotimeout.min.js"></script>

<script type="text/javascript" src="resources/js/swfobject.js"></script>
<script type="text/javascript" src="resources/js/web_socket.js"></script>
<script type="text/javascript">

function ttt() {
    this.ws;
    this.onopen = function(e) {
        ttt.ws.send('helo');
        console.log("onopen");
        $.doTimeout( 'init', 900, function(){
            console.log('init');
            if(ttt.ws.send(JSON.stringify({'action':'init'}, null, 2))) {
                return false;
            }
            return true;
        });
            
        $.doTimeout( 'hearbeat', 1000, function(){
            console.log('hearbeat');
            ttt.heartbeat = new Date().valueOf();
            ttt.ws.send(JSON.stringify({'action':'heartbeat'}, null, 2));
            return true;
        });
    }
    this.onmessage = function(e) {
        data = $.parseJSON(e.data);
        switch(data.action) {
            case 'heartbeat':
                $('#ping').empty().append("Ping:"+(new Date().valueOf() - ttt.heartbeat)+'ms');
                ttt.heartbeat = null;
            break;
        
            case 'activate':
                console.log('activate');
                $('.field').click(function(e) {
                    var field = $(this).attr('id').substr(1);
                    ttt.ws.send(JSON.stringify({'action':'mark', 'field':field}, null, 2));
                });
            break;
        
            case 'status':
                $('#status').empty().append(data.status);
            break;
        
            case 'mark':
                $('#f'+data.field).css('background-color', data.color);
            break;
        
        }
    }
    
    this.onclose = function(e) {
      console.log('close')
    }
    
    this.onerror = function(e) {
      console.log('error');
    }
    this.init = function() {
        WEB_SOCKET_SWF_LOCATION = "resources/js/WebSocketMain.swf";
        WEB_SOCKET_DEBUG = true;
        this.ws = new WebSocket("ws://hacks:12345/");
        console.log("initalisation");
        this.ws.onopen = this.onopen;
        this.ws.onmessage = this.onmessage;
        this.ws.onclose = this.onclose;
        this.ws.onerror = this.onerror;

    }
}

$(document).ready(function() {
    ttt = new ttt();
   ttt.init(); 
});
</script>
</head>
<body>
    <div id="status"></div>
    <div id="ping"></div>
    <div id="game">
        <div id="board">
            <div id="f0x0" class="field"></div>
            <div id="f0x1" class="field"></div>
            <div id="f0x2" class="field"></div>
            <div id="f1x0" class="field"></div>
            <div id="f1x1" class="field"></div>
            <div id="f1x2" class="field"></div>
            <div id="f2x0" class="field"></div>
            <div id="f2x1" class="field"></div>
            <div id="f2x2" class="field"></div>
        </div>
    </div>
</body>
</html>