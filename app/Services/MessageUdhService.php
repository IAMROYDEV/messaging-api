<?php

namespace App\Services;

/**
 * 
 */
class MessageUdhService {

    private $udh, $msgPart;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->udh = array(
            'udh_length' => '05',
            'identifier' => '00',
            'header_length' => '03',
            'reference' => '00',
            'msg_count' => 1,
            'msg_part' => 1
        );
        $this->msgPart = array();
    }

    /**
     * Create message part with udh
     * 
     * @param string $msg
     * @return array
     */
    public function createMsg($msg) {
        $x = 1;
        if (strlen($msg) <= 160) {
            $this->msgPart[$x]['udh'] = '';
            $this->msgPart[$x]['msg'] = $msg;
        } else {
            $msg = str_split($msg, 153);
            $ref = mt_rand(1, 255);
            $this->udh['msg_count'] = $this->dechexStr(count($msg));
            $this->udh['reference'] = $this->dechexStr($ref);
            foreach ($msg as $part) {
                $this->udh['msgPart'] = $this->dechexStr($x);
                $this->msgPart[$x]['udh'] = implode('', $this->udh);
                $this->msgPart[$x]['msg'] = $part;
                $x++;
            }
        }

        return $this->msgPart;
    }

    /**
     * 
     * @param integer $ref
     * @return type
     */
    private function dechexStr($ref) {
        return ($ref <= 15 ) ? '0' . dechex($ref) : dechex($ref);
    }

}
