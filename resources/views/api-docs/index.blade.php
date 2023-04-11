<?php
// include('/api-integrations/AonMart.postman_collection.json');
$json_file = file_get_contents('what.txt', FILE_USE_INCLUDE_PATH);
// $json_file = '';
// $json_data = json_decode($json_file, true);
// print_r($json_file);

?>
<html>
<head>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"> </script>

<script>
$(function() {
  var people = [];
  $.getJSON('AonMart.postman_collection.json', function(data) {
    $.each(data.person, function(i, f) {
      var tblRow = "<tr>" + "<td>" + f.name + "</td>" +
        "<td>" + f.name + "</td>" + "<td>" + f.name + "</td>" + "<td>" + f.name + "</td>" + "</tr>" 
        $(tblRow).appendTo("#userdata tbody");
     });
    });
  });
</script>
</head>

<body>

<div class="wrapper">
<div class="profile">
   <table id= "userdata" border="2">
  <thead>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email Address</th>
            <th>City</th>
        </thead>
      <tbody>

       </tbody>
   </table>

</div>
</div>

</body>
</html>
