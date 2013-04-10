jQuery(document).ready(function() {
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
  var atkArray = new Array();
  jQuery('.charbottom').droppable({
    drop : function(event, ui) {
      jQuery('.charbottom p').hide();
      var atkId = ui.helper.attr('rel');
      if (jQuery.inArray(atkId, atkArray)) {
        jQuery(this).append(jQuery(ui.draggable).clone());
        atkArray += atkId + ',';
        setCharAtk(atkId);
        countSkills = jQuery('.charbottom .skill').length;
      } else {
        alert('Skill already learned!')
      }
      if ((countSkills % 5) == 0) {
        jQuery('.charbottom').find('a').eq(countSkills - 1).addClass('skillright');
      }
    }
  });
  jQuery('#character .charbottom p').replaceWith('<p style="height:50px">Drag your Skills to this place</p>');

  jQuery('a.delAtk').click(function(e) {
    delCharAtk();
    e.preventDefault();
  });
});
