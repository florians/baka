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
    if(renewDash){
      jQuery('.right').html(html);
    }
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
	}).done(function(data){
   var response = $.parseJSON(data);
   if(response.success == true){
      alert("Challenge sent");
      $(".right").html('<pre>You have challanged an opponent</pre>');
    } else {
      alert(response.message);
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
  // alert(data);
	  var obj = $.parseJSON(data);
	  if(obj.has == true && challangeable){
	    challangeable = false;
	    clearInterval(receiveChallengeTime);
	    if(confirm(obj.message)){
	      alert("you've accepted");
	    } else {
	      challangeable = true;
	      receiveChallengeTime = setInterval("hasChallange(charId)", 3000);
	    }
	  } else {
	    alert(obj.message);
	  }
	});
}

function checkRequest($battleId){
  $.ajax({
    type : 'POST',
    url : 'pages/ajax.php',
    data : {
      'event' : 'checkRequest',
      'battleId' : battleId
    }
  }).done(function(data){
    // alert(data);
    var obj = $.parseJSON(data);
    if(obj.has == true && challangeable){
      challangeable = false;
      clearInterval(receiveChallengeTime);
      if(confirm(obj.message)){
        alert("you've accepted");
      } else {
        challangeable = true;
        receiveChallengeTime = setInterval("hasChallange(charId)", 3000);
      }
    } else {
      alert(obj.message);
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