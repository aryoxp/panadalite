<?php

class notification {
    const all = -1;
    const info = 0;
    const warning = 1;
    const error = 2;
    const success = 3;

    private $panada;

    public function __construct( $panada ) {
        $panada->session = new session($panada->config->session);
        $this->panada = $panada;
    }

    public function add($message, $type = 0){
        $notification = $this->panada->session->get('notification');
        if(!$notification) $notification = array();
        array_push($notification, array($type,$message));
        $this->panada->session->set('notification',$notification);
    }

    public function flush($type = self::all){
        $notification = $this->panada->session->get('notification');

        if($type == self::all) {
            $this->panada->session->remove('notification');
            return $notification;
        }

        $maintained = array();
        $flushed = array();
        foreach($notification as $n) {
            if($n[0] == $type)
                $flushed[] = $n;
            else $maintained[] = $n;
        }

        $this->panada->session->set('notification',$maintained);
        return $flushed;
    }
}