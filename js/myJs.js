/**
 * @Author Florian Stettler & Adrian Locher
 * @Version 2
 * Create Date:   19.03.2013  create of the file
 * 
 * all in this event is triggered if the content of the document is ready
 */

jQuery(document).ready(function() {
  // variable for the mouse x position
  var mouseX;
  // variable for the mouse y position
  var mouseY;
  // sets the mouse x and y position of the mouse
  jQuery(document).mousemove(function(e) {
    mouseX = e.pageX;
    mouseY = e.pageY;
  });

  // adds a class to the div
  jQuery('.variation .physical').addClass('chosen');
  // adds a class to the div
  jQuery('.skills .physical').addClass('show');

  /*
   * if a link is clicked
   * It will remove a clas of a div with the class .variation.
   * Adds a class to the link in the div with the class .variation.
   * sets the attribut of the current clicked element into a variable.
   * Removes a class of a div with the class .selectable.
   * Adds a class with the class which is saved in the variable
   * prevents the default of a link == link is wont redirect
   */
  jQuery('.variation a').click(function(e) {
    jQuery('.variation .variation').removeClass('chosen');
    jQuery(this).addClass('chosen');
    attrClass = jQuery(this).attr('rel');
    jQuery('.skills .selectable').removeClass('show');
    jQuery('.skills .' + attrClass).addClass('show');
    e.preventDefault();
  });

  // every skill is draggable and creates a clone if dragged
  jQuery('.skills a').draggable({
    helper : 'clone'
  });

  // neccessary variable to store the attack numbers
  var atkNr = '';

  // this function checks the numbers of the array.
  // If the new number is already in there a Error alert will pop up
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

  // counts the skill in a div
  function countSkills() {
    count = jQuery('.charbottom .skill').length;
    return count;
  }

  /*
   * sets some parameters which change if the users challenged an opponent
   */
  function isChallenging(battleId) {
    renewDash = false;
    challangeable = true;
    clearInterval(dashboardTime);
    checkRequest = true;
    $(".right").html('<pre>You have challenged an opponent</pre>');
    requestCheck(battleId);
    checkRequestTime = setInterval("requestCheck(battleId)", 3000);
  }

  /*
   * creates a zone where a draggable object can be dropped.
   * Uses the count function to add a class to every 5th skill.
   */
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

  // replaces a text in the div if there is no skill in there
  jQuery('#character .charbottom p').replaceWith('<p style="height:50px">Drag your Skills to this place</p>');

  // defines what happens when the button is clicked
  jQuery('a.delAtk').click(function(e) {
    delCharAtk();
    e.preventDefault();
  });

  // shows a little notice over a skill if the mouse is over it
  jQuery('.charbottom a').mouseover(function() {
    jQuery(this).next('span').css({
      'top' : mouseY - 100,
      'left' : mouseX
    }).show().stop();
  });
  // hides the notice over the skill if the mouse leaves the skill
  jQuery('.charbottom a').mouseleave(function() {
    jQuery(this).next('span').css({
      'top' : mouseY - 100,
      'left' : mouseX
    }).show().hide();
  });
  /*
   * Locher
   */
  jQuery(".right").on("click", ".challenge", function() {
    if (confirm("Are you sure, you want to challenge this character?")) {
      clearInterval(dashboardTime);
      clearInterval(receiveChallengeTime);
      renewDash = false;
      challengeable = false;
      var challengee = $(this).attr("id");
      var challenger = $("#myCharId").val();
      battleRequest(challenger, challengee);
    }
  });

  // gets the rel of the Attack and triggers the Ajax function attack
  jQuery(".myChar a.skill").click(function(e) {
    e.preventDefault();
    var attackId = $(this).attr("rel");
    attack(attackId);
  });

  // triggers the ajax event Reareat if the button is clicked
  jQuery(".myChar a.retreat").click(function(e) {
    e.preventDefault();
    if (confirm("Are you sure you want to flee?")) {
      retreat();
    }
  });

  // fadesOut the messages after 5 seconds
  jQuery('.success').delay(5000).fadeOut('3000');

  // creates a navigation out of divs which shows the skills or the attributes
  jQuery('.partnavi div').click(function() {
    var rel = jQuery(this).attr('rel');
    jQuery('.innerdiv').addClass('hidden');
    jQuery('.' + rel).removeClass('hidden');
    jQuery('.partnavipoint').removeClass('active');
    jQuery(this).addClass('active');
  });

  // every skillpoint is draggable
  jQuery('.skillpoint').draggable();

  /*
   * creates a droppable div
   * changes the attributes when the value changes
   */
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
          var newLife = (newValueDurability * 10) + 60;
          jQuery('.progresstext').html(newLife + ' / ' + newLife);
        }
      }
    }
  });

});
