/**
 * @Author Florian Stettler, Adrian Locher
 * @Version 9
 * Create Date:   03.04.2013  creation of the file
 * 
 * This file contains all the functions that make a AJAX request and process the information returned. 
 */
jQuery(document).ready(function() {
  //onlineCheckAll();
  //setInterval('onlineCheckAll()', 10000);
});
var waitingNotOver = true;
var onDashboard = false;

// This set the user as online and sets users that have sent this request for over a minute
function onlineCheck(val) {
  //console.debug(onDashboard);
  jQuery.ajax({
    type : 'POST',
    url : 'pages/ajax.php',
    data : {
      'event' : 'onlineCheck',
      'dashboard' : onDashboard,
      'value' : val
    }
  });
}

// Loads the dashboard new with the currently online users.
function dashboard() {
  jQuery.ajax({
    type : 'POST',
    url : 'pages/ajax.php',
    data : {
      'event' : 'dashboard'
    }
  }).done(function(html) {
    if (renewDash) {
      jQuery('.right').html(html);
    }
  });
}

function battleRequest(challenger, challengee) {
  $.ajax({
    type : 'POST',
    url : 'pages/ajax.php',
    data : {
      'event' : 'makeRequest',
      'challenger' : challenger,
      'challengee' : challengee
    }
  }).done(function(data) {
    var response = $.parseJSON(data);
    if (response.success == true) {
      $(".right").html('<pre>You have challenged an opponent</pre>');
      checkRequest = true;
      battleId = response.battleId;
      requestCheck(battleId);
      checkRequestTime = setInterval("requestCheck(battleId)", 3000);
    } else {
      alert(response.message);
    }
  });
}

function hasChallange(charId) {
  $.ajax({
    type : 'POST',
    url : 'pages/ajax.php',
    data : {
      'event' : 'hasChallange',
      'charId' : charId
    }
  }).done(function(data) {
    // alert(data);
    var obj = $.parseJSON(data);
    if (obj.has == true && challangeable) {
      challangeable = false;
      clearInterval(receiveChallengeTime);
      if (confirm(obj.message)) {
        requestResponse(obj.battle, "a");
      } else {
        requestResponse(obj.battle, "r");
        checkRequestTime = setInterval("hasChallange(thisCharId)", 3000);
      }
    }
  });
}

function requestResponse(battleId, val) {
  $.ajax({
    type : 'POST',
    url : 'pages/ajax.php',
    data : {
      'event' : 'requestResponse',
      'battleId' : battleId,
      'charId' : thisCharId,
      'status' : val
    }
  }).done(function(data) {
    if (val == "a") {
      window.location.href = "?page=Battle&fight=" + battleId;
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

function setAttribute(val) {
  jQuery.ajax({
    type : 'POST',
    url : 'pages/ajax.php',
    data : {
      'event' : 'setAttribute',
      'value' : val
    }
  });
}

function requestCheck(battleId) {
  $.ajax({
    type : 'POST',
    url : 'pages/ajax.php',
    data : {
      'event' : 'requestCheck',
      'battleId' : battleId
    }
  }).done(function(data) {
    if (checkRequest) {
      var obj = $.parseJSON(data);
      if (obj.accepted == true) {
        window.location.href = "?page=Battle&fight=" + battleId;
      } else {
        if (obj.rejected == true) {
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

// this is called while the player is waiting for his turn if its his turn the function attack is called
function waiting() {
  //console.debug("waiting");
  $(".otherChar .status").text("Attacking");
  $.ajax({
    type : 'POST',
    url : 'pages/ajax.php',
    data : {
      'event' : 'waiting',
      'battleId' : battleId,
      'charId' : thisCharId
    }
  }).done(function(data) {
    if (waitingNotOver == true) {
      //console.debug(data);
      var obj = $.parseJSON(data);
      if (obj.fled) {
        alert("you're opponent has fled");
      }
      if (obj.over) {
        waitingNotOver = false;
        alert(obj.overmessage);
        livepoints();
        hasLevelUp();
      } else {
        if (obj.attack == true) {
          attacking = true;
          clearInterval(waitingTime);
          $(".myChar .status").text("Attacking");
          $(".otherChar .status").text("Waiting");
          livepoints();
        } else {
          if (waitingTime == null) {
            waitingTime = setInterval("waiting()", 3000);
          }
          $(".myChar .status").text("Waiting");
          $(".otherChar .status").text("Attacking");
        }
        jQuery(".myChar .charmiddle").replaceWith(obj.myHp);
        jQuery(".otherChar .charmiddle").replaceWith(obj.oHp);
        var log = obj.bLog.replace(/\n/g, "<br>");
        jQuery(".battlelogtext").html(log);
      }
    }
  });
}

// This gets the current HP of both players and the battle Log it's called by Attack after the attack was successfull
function livepoints() {
  $.ajax({
    type : 'POST',
    url : 'pages/ajax.php',
    data : {
      'event' : 'livepoints',
      'battleId' : battleId,
      'charId' : thisCharId
    }
  }).done(function(data) {
    var obj = $.parseJSON(data);
    jQuery(".myChar .charmiddle").replaceWith(obj.myHp);
    jQuery(".otherChar .charmiddle").replaceWith(obj.oHp);
    var log = obj.bLog.replace(/\n/g, "<br>");
    jQuery(".battlelogtext").html(log);
  });
}

// this checks if the player has a level up and redirect the player accordingly if the character
function hasLevelUp() {
  $.ajax({
    type : 'POST',
    url : 'pages/ajax.php',
    data : {
      'event' : 'hasLevelUp',
      'charId' : thisCharId
    }
  }).done(function(data) {
    //console.debug(data);
    var obj = $.parseJSON(data);
    if (obj.levelup == true) {
      alert(obj.message);
      window.location.href = "?page=Character";
    } else {
      alert(obj.message);
      window.location.href = "index.php";
    }
  });
}

// This set the battle as over and the status as fled
function retreat() {
  $.ajax({
    type : 'POST',
    url : 'pages/ajax.php',
    data : {
      'event' : 'flee',
      'battleId' : battleId
    }
  }).done(function(data) {
    window.location.href = "index.php";
  });
}

// attacks the oponent with the chosen attack
function attack(atkId) {
  if (attacking == true) {
    attacking = false;
    $.ajax({
      type : 'POST',
      url : 'pages/ajax.php',
      data : {
        'event' : 'attack',
        'battleId' : battleId,
        'charId' : thisCharId,
        'atkId' : atkId
      }
    }).done(function(data) {
      var obj = $.parseJSON(data);
      if (obj.valid) {
        jQuery(".myChar .charmiddle").replaceWith(obj.myHp);
        jQuery(".otherChar .charmiddle").replaceWith(obj.oHp);
        var log = obj.bLog.replace(/\n/g, "<br>");
        jQuery(".battlelogtext").html(log);
        if (obj.over) {
          alert(obj.overmessage);
          livepoints();
          hasLevelUp();
        } else {
          waiting();
          waitingTime = setInterval("waiting()", 3000);
          $(".myChar .status").text("Waiting");
          $(".otherChar .status").text("Attacking");
        }
      }
    });
  };
}
