<?php
require_once "websocket.php";

class ttt extends WebSocket {
    public function process($user, $msg) {
        $m = new Mongo();
        if($msg == 'helo') {
            $this->send($user->socket, '1');
            return;
        }
        $msg = json_decode($msg);
        switch($msg->action) {
            case 'init':
                $game = $m->ttt->game->findOne(array('open'=>1));
                
                if($game) {
                    $game['user'][] = array('id'=>$user->id, 'color'=>'blue', 'lasthearbeat'=>time());
                    $m->ttt->game->save($game);
                    $this->send($user->socket, json_encode(array('action'=>'status', 'status'=>'Welcome Player 2...')));
                } else {
                    $userInfo = array('id'=>$user->id, 'color'=>'red', 'lasthearbeat'=>time());
                    array_push($users, $user);
                    $m->ttt->game->insert(
                        array(
                            'user'=>array($userInfo),
                            'open'=>1,
                        )
                    );
                    $this->send($user->socket, json_encode(array('action'=>'status', 'status'=>'Welcome Player 1, awaiting for Player 2...')));
                }
                
                $this->send($user->socket, json_encode(array('action'=>'activate')));
                
                
                
            break;
            case 'heartbeat':
                $this->send($user->socket, json_encode(array('action'=>'heartbeat')));
                $game = $m->ttt->game->update(array('user.id'=>$user->id), array('$set'=>array('user.$.lasthearbeat'=>time())));
            break;
        
            case 'mark':
                $ownerid = $user->id;
                $ownercolor;
                $game = $m->ttt->game->findOne(array('user.id'=>$user->id));
                
                foreach($game['user'] as $user) {
                    if($user['id'] == $ownerid) {
                        $ownercolor = $user['color'];
                    }
                }
                
                foreach($game['user'] as $user) {
                    $userConnection = $this->getUserById($user['id']);
                    if(isset($userConnection)) {
                        $this->send($userConnection->socket, json_encode(array('action'=>'mark', 'color'=>$ownercolor, 'field'=>$msg->field)));
                    }
                }
                
            break;
        }
        //$m->ttt->game->remove(array('lasthearbeat'=>array('$lte'=>time()-10)));
    }
    
    protected function getUserById($userId) {
        $found=null;
        foreach($this->users as $user){
            if($user->id==$userId){ $found=$user; break; }
        }
        return $found;
    }
}
?>