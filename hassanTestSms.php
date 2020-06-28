<?php

$url="http://180.92.157.107:14809/mobimatic/sendbroadcast.php?username=softoo&password=softoo@123&from_id=9143&message=message-from-ISI-report-to-junaid&msisdn=923345087790";
  
			try{
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
					curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
					//  curl_setopt($ch, CURLOPT_POSTFIELDS,  urlencode($xmlRequest));
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_VERBOSE, 0);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					$data = curl_exec($ch);
					curl_close($ch);
					
					var_dump($data);
					
						   
				
				}catch(Exception  $e)
				{
					
						echo $error=$e->getMessage();
			    }
