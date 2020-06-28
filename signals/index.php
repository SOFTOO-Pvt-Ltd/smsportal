<?PHP
function sendMessage() {
    $content      = array(
        "en" => 'I am ali usman testing this API'
    );
    $hashes_array = array();
    array_push($hashes_array, array(
        "id" => "like-button",
        "text" => "Like",
        "icon" => "http://i.imgur.com/N8SN8ZS.png",
        "url" => "https://yoursite.com"
    ));
    $fields = array(
        'app_id' => "589fbf08-37d7-4a46-8609-6fc291d72b42",
        'included_segments' => array(
            'All'
        ),
        // 'include_player_ids' => array (
        //     '42bee59c-f6fd-41ef-a0ec-486016a675cc',
        // 	// '80d5901b-a590-49e3-a498-daa90d6efcbf',
        // 	// '085ffed9-16d6-4336-90e8-cf9640ad39c0'
        // ),
        'data' => array(
            "foo" => "bar"
        ),
        'contents' => $content,
        'web_buttons' => $hashes_array
    );
    
    $fields = json_encode($fields);
    print("\nJSON sent:\n");
    print($fields);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Authorization: Basic YzMyZjcxMzMtMmY0MC00ZmVhLWI5NGUtYjc5MDM2OTJlZmM2'
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
}

$response = sendMessage();
$return["allresponses"] = $response;
$return = json_encode($return);

$data = json_decode($response, true);
print_r($data);
$id = $data['id'];
print_r($id);

print("\n\nJSON received:\n");
print($return);
print("\n");
?>


<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
<script>
  var OneSignal = window.OneSignal || [];
  OneSignal.push(function() {
    OneSignal.init({
      appId: "e9085f27-9e5f-4ce3-bc7f-2a2f236442ad",
    });
     OneSignal.getUserId( function(userId) {
      alert('player_id of the subscribed user is : ' + userId);
      // Make a POST call to your server with the user ID        
    });
  });
</script>