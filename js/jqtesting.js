$(function() {
  		$("#draggable").draggable();
  		$("#droppable").droppable({
  			drop: function(event, ui) {
  				$(this).addClass('ui-state-highlight').find('p').html('Dropped!');
  			}
  		});
  
  	});
  if ($)
  {
    $(document).ready(
  	
      function() 
      {
        $('p#para1').addClass('tmpFrameworkLoaded');
        $('p#para2').addClass('tmpFrameworkLoaded');
        $('p#para1').text('jQuery successfully loaded and running.');
        $('div#divUploadPopUp').draggable();
        
        var $drag = $('div.ghostDiv');
        var $drop = $('div.accept');
        
        $drag.draggable({
					containment: $('div.containerHolder').length ? 'div.containerHolder' : 'document', // stick to containers if present
					revert: 'invalid' // when not dropped, the item will revert back to its initial position
        })
        
        $drop.droppable({
          activeClass: 'activeHighlight',
          accept: 'div.ghostDiv',
          drop: function(e, ui) {
            var $path = ui.draggable.attr('accept');
            // Do something with the path
  
            // Remove the element that was dropped.
            //ui.draggable.remove();
          }
        });
        
        $drop.draggable({
					containment: $('div.containerHolder').length ? 'div.containerHolder' : 'document', // stick to containers if present
					revert: 'invalid' // when not dropped, the item will revert back to its initial position
        })
        
        $('div#divGhosting').droppable({
          activeClass: 'activeHighlight',
          accept: 'div.ghostDiv',
          drop: function(e, ui) {
            var $path = ui.draggable.attr('accept');
            // Do something with the path
  
            // Remove the element that was dropped.
            //ui.draggable.remove();
          }
        });
        
        $('ul#managePicsList').sortable();
      }
    );
  }
  
  function uploadPopUpOpen()
  {
   document.getElementById("divUploadPopUp").style.visibility = "visible";
  }
  
  function uploadPopUpClose() 
  {
   document.getElementById("divUploadPopUp").style.visibility = "hidden";
  }