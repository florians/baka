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


function battleRequest(challenger, challengee){
	$.ajax({
		type : 'POST',
		url : 'pages/ajax.php',
		data : {
			'event' : 'makeRequest',
			'challenger' : challenger,
			'challengee' : challengee
		}
	});
}

function hasChallange(charId){
	$.ajax({
		type : 'POST',
		url : 'pages/ajax.php',
		data : {
			'event' : 'hasChallange',
			'charId' : charId
		}
	}).done(function(data){
	  var obj = $.parseJSON(data);
	  if(obj.has == "no"){
	    hasChallange(charId+1);
	  } else if(obj.has == "yes"){
	    var c = confirm(obj.message);
	    if(c= true){
	      alert("you've accepted");
	    } else {
	      alert("you've dislined");
	    }
	  }
	});
}

function setCharAtk(val) {
  jQuery.ajax({
    type : 'POST',
    url : 'pages/ajax.php',
    data : {
      'event' : 'setCharAtk',
      'value' : val
    }
  });
}

function delCharAtk(val) {
  jQuery.ajax({
    type : 'POST',
    url : 'pages/ajax.php',
    data : {
      'event' : 'delCharAtk'
    }
  }).done(function(html) {
    jQuery('head').append(html);
  });
}