<?php


require 'vendor/autoload.php';
use Google\Cloud\Translate\V2\TranslateClient;
$parameter_one='ઉત્પાદન જ્ઞાન વ્યૂહરચના (HCP ક્રિયા સાથે દરેક HCP ક્રિયાપ્રતિક્રિયા માટે સ્માર્ટ ઉદ્દેશો સેટ કરે છે)';
echo "<pre>";
$api_key = 'AIzaSyBFbgPZh0xg8rcH_vkWEXtWufNxwVvU750';
                $translate = new TranslateClient(['key' => 'AIzaSyBFbgPZh0xg8rcH_vkWEXtWufNxwVvU750']);

                $this->db->select('ml_short');
                $this->db->from('ai_multi_language');
                $language_array = $this->db->get()->result();
                if (count((array)$language_array) > 0) {
                    foreach ($language_array as $lg) {
                        $lang_key[] = $lg->ml_short;
                    }
                }

               // if( $suffix && strpos($class, $suffix) === FALSE)

           
                if (count((array)$lang_key) > 0) {
                    foreach ($lang_key as $lk) {
                        $parameter_one = strtolower(str_replace(" ", "", $parameter_one)); //strtolower($new_text);

                        $result_PK = $translate->translate("Product Knowledge ", ['target' => $lk]);
                        $new_textPK = $result_PK['text'];
                        $final_textPK[] = strtolower(str_replace(" ", "", $new_textPK));


                        // if(strpos($parameter_one,$final_textPK) !== FALSE){
                        //     // we have to save our post data to the db
                        //     echo '--1---';
                        // }else{
                        //     echo '== else ===<br/>';
                        // }
                    }
                } 

               // print_r($final_textPK);
               echo $parameter_one; echo "<br/>";

                foreach($final_textPK as $letter)
                {
                   // echo '-=-=-'.$letter; echo "<br/>";
                    //strpos returns false if the needle wasn't found
                    // if(strpos($parameter_one, $letter) == true) 
                    // {
                    //     echo "------".$letter."======"; echo "<br/>";echo "<br/>";

                    //     $msg="please start with PN";
                    
                    // }else{
                    //     echo "<br/>"; echo "-=-=".$parameter_one."===== MATCH -=-=-=".$letter; echo "<br/>";echo "<br/>";
                    //     $msg="match";
                    // }
                    echo "<br/>";

                    if(strpos($parameter_one,$letter) === 0) {
                        echo "------".$letter."===match==================="; echo "<br/>";echo "<br/>";

                   }else{
                        echo "------".$parameter_one."====no match=="; echo "<br/>";echo "<br/>";
                        $Message .= "Name should start with Product Knowledge..<br/>";
                                    $SuccessFlag = 0;

                 } 


                    // if (strstr($parameter_one, $letter)) {
                    //     echo 'found a zero'; echo $parameter_one; echo "<br/>";
                    // } else {
                    //     echo 'did not find a zero'; echo $parameter_one; echo "<br/>";
                    // }
                }

               

                //if (strpos($content, $list) == true)



                ?>