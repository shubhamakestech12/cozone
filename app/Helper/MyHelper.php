<?php
use Illuminate\Support\Str;
    /* FUNCTION TO CHECK GIVEN STRING IS EMAIL OR MOBILE */
    function check_string($str,$flag=''){
        $mobile_flag = false;
        $email_flag = false;
        $data = false;
        if(preg_match("/^[0-9]+$/" , trim($str))){
            $mobile_flag = true;
        }else{
            $email_flag = true;
        }
        if(preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", trim($str))){
            if(empty($flag)){
                $data = User::where(['email' => $str])->first();
            }
            if($data){
                $resp['status'] = 'error';
                $resp['message'] = "The email has already been taken!";
                return $resp; 
            }else{
                $resp['status'] = 'success';
                $resp['message'] = "email";
                return $resp;
            }
        }

        if(preg_match("/^[6-9][0-9]{9}$/" , trim($str))){
            if(empty($flag)){
                $data = User::where(['mobile' => $str])->first();
            }
            if($data){
                $resp['status'] = 'error';
                $resp['message'] = "The mobile has already been taken!";
                return $resp; 
            }else{
                $resp['status'] = 'success';
                $resp['message'] = "mobile";
                return $resp;
            }

        }

        if($email_flag == true){
            $resp['status'] = 'error';
            $resp['message'] = "Invalid email!";
            return $resp; 
        }

        if($mobile_flag == true){
            $resp['status'] = 'error';
            $resp['message'] = "Invalid Mobile!";
            return $resp;
        }
    }//END OF METHOD

    /* FUNCTION TO CHECK UNIQUE SLUG AND CREATE ALSO */
    function generateUniqueSlug($title , $resp){
        if(!empty($resp) && is_object($resp)){
            $token = openssl_random_pseudo_bytes(5);
            $token = bin2hex($token);
            return $resp->slug.'-'.$token;
        }else{
            return Str::slug($title, '-');    
        }
    }// End of Function

    /* FUNCTION TO ENCRYPT DATA */
        function encryptData($plainText,$key){
            $key = hextobin(md5($key));
            $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
            $openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
            $encryptedText = bin2hex($openMode);
            return $encryptedText;
        }

        /*
        * @param1 : Encrypted String
        * @param2 : Working key provided by CCAvenue
        * @return : Plain String
        */
        /* FUNCTION TO DECRYPT DATA */
        function decryptData($encryptedText,$key){
            $key = hextobin(md5($key));
            $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
            $encryptedText = hextobin($encryptedText);
            $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
            return $decryptedText;
        }
        /* HELPER */
        function hextobin($hexString) { 
            $length = strlen($hexString); 
            $binString = "";   
            $count = 0 ; 
            while($count<$length) 
            {       
                $subString =substr($hexString,$count,2);           
                $packedString = pack("H*",$subString); 
                if ($count==0)
                {
                    $binString=$packedString;
                }               
                else 
                {
                    $binString.=$packedString;
                }                
                $count+=2; 
            } 
            return $binString; 
        } 