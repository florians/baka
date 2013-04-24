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
      $(".right").html('<pre>You have challanged an opponent</pre>');
      checkRequest = true;
      battleId = response.battleId;
      requestCheck(battleId);
      checkRequestTime = setInterval("requestCheck(battleId)", 3000);
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
        requestResponse(obj.battle,"a");
        alert("you've accepted");
      } else {
        alert("you've rejected");
        requestResponse(obj.battle,"r");
        checkRequestTime = setInterval("hasChallange(thisCharId)", 3000);
	    }
	  }
	});
}

function requestResponse(battleId,val){
   $.ajax({
    type : 'POST',
    url : 'pages/ajax.php',
    data : {
      'event' : 'requestResponse',
      'battleId' : battleId,
      'status' : val
    }
  }).done(function(data){
    if(val == "a"){
      window.location.href = "?page=Battle&fight="+battleId;
    } else {
      challangeable = true;
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

function requestCheck(battleId){
  $.ajax({
    type : 'POST',
    url : 'pages/ajax.php',
    data : {
      'event' : 'requestCheck',
      'battleId' : battleId
    }
  }).done(function(data){  
    if(checkRequest){
      var obj = $.parseJSON(data);
      if(obj.accepted == true){
        window.location.href = "?page=Battle&fight="+battleId;
      } else {
        if(obj.rejected == true){
          alert(obj.message);
          checkRequest = false;
          challangeable = true;
          renewDash = true;
          clearInterval(checkRequestTime);
          receiveChallengeTime = setInterval("hasChallange(thisCharId)", 3000);
          dashboardTime = setInterval("dashboard()", 3000);
        } 
      }
    } 
  });
}

function waiting(){
  if(){
  $.ajax({
    type : 'POST',
    url : 'pages/ajax.php',
    data : {
      'event' : 'waiting',
      'battleId' : battleId,
      'attackingPlayer' : attackingPlayer
    }
  }).done(function(data){
    console.log(data);
      var obj = $.parseJSON(data);
      if(obj.over){
        alert("It's over!");
      } else {
        if(obj.change == true){
          jQuery(".myChar .charmiddle").replaceWith(obj.hp);
          waiting = false;
          attacking = true;
          clear(waitingTime);
        } 
      }
  });
  }
}

function attack(atkId){
  
}
