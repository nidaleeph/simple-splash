
<?php
  $json = '{
      "title": "PHP",
      "site": "GeeksforGeeks"
  }';
  $data = json_decode($json);
  echo $data->title;
?>