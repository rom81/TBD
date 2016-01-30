<?php
$page_title ="Display Facebook Page Events on Website";
?>

<!DOCTYPE html>
<html lang="en">
<head>
 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
 
    <title><?php echo $page_title; ?></title>
 
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
 
</head>
<body>
     
<div class="container">
 
<!-- events will be here -->
 
</div>
 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
 
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
 
</body>
</html>

<div class="page-header">
    <h1><?php echo $page_title; ?></h1>
</div>

<?php
$fb_page_id = "221167777906963";
?>

$year_range = 2;
 
// automatically adjust date range
// human readable years
$since_date = date('Y-01-01', strtotime('-' . $year_range . ' years'));
$until_date = date('Y-01-01', strtotime('+' . $year_range . ' years'));
 
// unix timestamp years
$since_unix_timestamp = strtotime($since_date);
$until_unix_timestamp = strtotime($until_date);
 
// or you can set a fix date range:
// $since_unix_timestamp = strtotime("2012-01-08");
// $until_unix_timestamp = strtotime("2018-06-28");

https://graph.facebook.com/oauth/access_token?client_id=YOUR_APP_ID&client_secret=YOUR_APP_SECRET&grant_type=client_credentials

$access_token = "YOUR_ACCESS_TOKEN";

$fields="id,name,description,place,timezone,start_time,cover";
 
$json_link = "https://graph.facebook.com/{$fb_page_id}/events/attending/?fields={$fields}&access_token={$access_token}&since={$since_unix_timestamp}&until={$until_unix_timestamp}";
 
$json = file_get_contents($json_link);

$obj = json_decode($json, true, 512, JSON_BIGINT_AS_STRING);
 
// for those using PHP version older than 5.4, use this instead:
// $obj = json_decode(preg_replace('/("\w+"):(\d+)/', '\\1:"\\2"', $json), true);

echo "<table class='table table-hover table-responsive table-bordered'>";
 
    // count the number of events
    $event_count = count($obj['data']);
 
    for($x=0; $x<$event_count; $x++){
        // set timezone
        date_default_timezone_set($obj['data'][$x]['timezone']);
 
        $start_date = date( 'l, F d, Y', strtotime($obj['data'][$x]['start_time']));
        $start_time = date('g:i a', strtotime($obj['data'][$x]['start_time']));
  
        $pic_big = isset($obj['data'][$x]['cover']['source']) ? $obj['data'][$x]['cover']['source'] : "https://graph.facebook.com/{$fb_page_id}/picture?type=large";
 
        $eid = $obj['data'][$x]['id'];
        $name = $obj['data'][$x]['name'];
        $description = isset($obj['data'][$x]['description']) ? $obj['data'][$x]['description'] : "";
 
        // place
        $place_name = isset($obj['data'][$x]['place']['name']) ? $obj['data'][$x]['place']['name'] : "";
        $city = isset($obj['data'][$x]['place']['location']['city']) ? $obj['data'][$x]['place']['location']['city'] : "";
        $country = isset($obj['data'][$x]['place']['location']['country']) ? $obj['data'][$x]['place']['location']['country'] : "";
        $zip = isset($obj['data'][$x]['place']['location']['zip']) ? $obj['data'][$x]['place']['location']['zip'] : "";
 
        $location="";
 
        if($place_name && $city && $country && $zip){
           $location="{$place_name}, {$city}, {$country}, {$zip}";
        }else{
           $location="Location not set or event data is too old.";
        }
        echo "<tr>";
           echo "<td rowspan='6' style='width:20em;'>";
        echo "<img src='{$pic_big}' width='200px' />";
        echo "</td>";
        echo "</tr>";
  
        echo "<tr>";
         echo "<td style='width:15em;'>What:</td>";
        echo "<td><b>{$name}</b></td>";
        echo "</tr>";
  
        echo "<tr>";
       echo "<td>When:</td>";
      echo "<td>{$start_date} at {$start_time}</td>";
      echo "</tr>";
  
echo "<tr>";
    echo "<td>Where:</td>";
    echo "<td>{$location}</td>";
echo "</tr>";
  
echo "<tr>";
    echo "<td>Description:</td>";
    echo "<td>{$description}</td>";
echo "</tr>";
  
echo "<tr>";
    echo "<td>Facebook Link:</td>";
    echo "<td>";
        echo "<a href='https://www.facebook.com/events/{$eid}/' target='_blank'>View on Facebook</a>";
    echo "</td>";
echo "</tr>";
    }


// get events for the next x years
$year_range = 1;
 
// automatically adjust date range
// human readable years
$since_date = date('2016-01-31');
$until_date = date('2016-12-31', strtotime('+' . $year_range . ' years'));
 
// unix timestamp years
$since_unix_timestamp = strtotime($since_date);
$until_unix_timestamp = strtotime($until_date);
