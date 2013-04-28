jQuery(document).ready(function() {
  var mouseX;
  var mouseY;
  jQuery(document).mousemove(function(e) {
    mouseX = e.pageX;
    mouseY = e.pageY;
  });

  jQuery('.variation .physical').addClass('chosen');
  jQuery('.skills .physical').addClass('show');
  jQuery('.variation a').click(function(e) {
    jQuery('.variation .variation').removeClass('chosen');
    jQuery(this).addClass('chosen');
    attrClass = jQuery(this).attr('rel');
    jQuery('.skills .selectable').removeClass('show');
    jQuery('.skills .' + attrClass).addClass('show');
    e.preventDefault();
  });

  jQuery('.skills a').draggable({
    helper : 'clone'
  });
  var atkNr = '';

  function checkAtkNrs(atkNr) {
    var atkArray = [];
    jQuery('.charbottom a').each(function(index, value) {
      atkArray.push(jQuery(this).attr('rel'));
    });
    if (jQuery.inArray(atkNr, atkArray) === -1) {
      return true;
    } else {
      return false;
    }
  }

  function countSkills() {
    count = jQuery('.charbottom .skill').length;
    return count;
  }

  function isChallenging(battleId) {
    renewDash = false;
    challangeable = true;
    clearInterval(dashboardTime);
    checkRequest = true;
    $(".right").html('<pre>You have challanged an opponent</pre>');
    requestCheck(battleId);
    checkRequestTime = setInterval("requestCheck(battleId)", 3000);
  }


  jQuery('.charbottom').droppable({
    drop : function(event, ui) {
      jQuery('.charbottom p').hide();
      var atkId = ui.helper.attr('rel');
      if (checkAtkNrs(atkId) == true) {
        jQuery(this).append(jQuery(ui.draggable).clone());
        setCharAtk(atkId);
      } else {
        alert('Skill already learned!');
      }
      if ((countSkills() % 5) == 0) {
        jQuery('.charbottom').find('a').eq(countSkills() - 1).addClass('skillright');
      }
    }
  });

  jQuery('#character .charbottom p').replaceWith('<p style="height:50px">Drag your Skills to this place</p>');

  jQuery('a.delAtk').click(function(e) {
    delCharAtk();
    e.preventDefault();
  });

  jQuery('.charbottom a').mouseover(function() {
    jQuery(this).next('span').css({
      'top' : mouseY - 100,
      'left' : mouseX
    }).show().stop();
  });
  jQuery('.charbottom a').mouseleave(function() {
    jQuery(this).next('span').css({
      'top' : mouseY - 100,
      'left' : mouseX
    }).show().hide();
  });

  jQuery(".right").on("click", ".challenge", function() {
    clearInterval(dashboardTime);
    clearInterval(receiveChallengeTime);
    renewDash = false;
    challengeable = false;
    var challengee = $(this).attr("id");
    var challenger = $("#myCharId").val();
    battleRequest(challenger, challengee);
  });

  jQuery(".myChar a.skill").click(function(e) {
    e.preventDefault();
    var attackId = $(this).attr("rel");
    attack(attackId);
  });

  jQuery(".myChar a.retreat").click(function(e) {
    e.preventDefault();
    if (confirm("Are you sure you want to flee?")) {
      retreat();
    }
  });
  jQuery('.success').delay(5000).fadeOut('3000');

  jQuery('.partnavi div').click(function() {
    var rel = jQuery(this).attr('rel');
    jQuery('.innerdiv').addClass('hidden');
    jQuery('.' + rel).removeClass('hidden');
    jQuery('.partnavipoint').removeClass('active');
    jQuery(this).addClass('active');
  });

  jQuery('.skillpoint').draggable();

  jQuery('.apcontentleft .att').droppable({
    hoverClass : "shadow",
    drop : function(event, ui) {
      jQuery(ui.draggable).hide();
      var attRel = jQuery(this).attr('rel');
      if (attRel) {
        var attrNewContent = '.charattrtable .' + attRel;
        var attrContent = parseInt(jQuery(attrNewContent).html());
        setAttribute(attRel);
        jQuery(attrNewContent).html(attrContent + 1);
        if (attRel == 'Durability') {
          var valueDurability = jQuery('.Durability').attr('value');
          var newValueDurability = parseInt(valueDurability) + 1;
          jQuery('.Durability').val(newValueDurability);
          var newLife = (newValueDurability * 10) + 55;
          jQuery('.progresstext').html(newLife + ' / ' + newLife);
        }
      }
    }
  });

});
