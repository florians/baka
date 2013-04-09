jQuery(document).ready(function() {
  //onlineCheckAll();
  //setInterval('onlineCheckAll()', 10000);
});

function onlineCheck(val) {
  jQuery.ajax({
    type : 'POST',
    url : 'pages/ajax.php',
    data : {
      'event' : 'onlineCheck',
      'value' : val
    }
  });
}

function dashboard() {
  jQuery.ajax({
    type : 'POST',
    url : 'pages/ajax.php',
    data : {
      'event' : 'dashboard'
    }
  }).done(function(html) {
    jQuery('.right').html(html);
  });
}