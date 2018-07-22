<?php

namespace App\Services;
/**
 * 
 */
class MessageUdhService 
{ 
    private $udh, $msgPart ; //msgPart array of couple udh + msg 

    /**
     * 
     */
    function __construct() //throw mysql resource as argument 
    { 
        $this->udh=array( 
        'udh_length'=>'05', //sms udh lenth 05 for 8bit udh, 06 for 16 bit udh 
        'identifier'=>'00', //use 00 for 8bit udh, use 08 for 16bit udh 
        'header_length'=>'03', //length of header including udh_length & identifier 
        'reference'=>'00', //use 2bit 00-ff if 8bit udh, use 4bit 0000-ffff if 16bit udh 
        'msg_count'=>1, //sms count 
        'msg_part'=>1 //sms part number 
        ); 
        $this->msgPart=array(); 
    } 
    

    
    
    
   /**
    * 
    * @param type $msg
    * @return type
    */
   public function createMsg($msg) 
    { 
        $x=1; 
        if(strlen($msg)<=160) //if single sms, send without udh 
        { 
            $this->msgPart[$x]['udh']=''; 
            $this->msgPart[$x]['msg']=$msg; 
        } 
        else //if multipart sms, split into 153 character each part 
        { 
            $msg=str_split($msg,153); 
            $ref=mt_rand(1,255); 
            $this->udh['msg_count']=$this->dechexStr(count($msg)); 
            $this->udh['reference']=$this->dechexStr($ref); 
            foreach($msg as $part) 
            { 
                $this->udh['msgPart']=$this->dechexStr($x); 
                $this->msgPart[$x]['udh']=implode('',$this->udh); 
                $this->msgPart[$x]['msg']=$part; 
                $x++; 
            } 
        } 
        
        return $this->msgPart;
    } 
    
    
    /**
     * 
     * @param type $ref
     * @return type
     */
    private function dechexStr($ref) 
    { 
        return ($ref <= 15 )?'0'.dechex($ref):dechex($ref); 
    } 
} 
