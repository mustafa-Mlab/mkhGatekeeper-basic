jQuery(document).ready(function($) {
    if ($('.custom_logout.menu-item.menu-item-type-custom').length) { 
      $.ajax({
        url: ajaxObject.ajaxurl,
        type: 'POST',
        data: {
          action: 'get_logout', // Replace with your desired action hook
        },
        success: function(response) {
          var buttonHtml = response.data;
		  var buttonElement = $('<div>').html(buttonHtml);

		  // Extract the data-logout URL using the attr() method
		  var logoutUrl = buttonElement.find("button").attr("data-logout");
		   $('.custom_logout.menu-item.menu-item-type-custom a').attr('href', logoutUrl );
// //           $('.custom_logout.menu-item.menu-item-type-custom').html(buttonHtml);
// 		  $('.custom_logout.menu-item.menu-item-type-custom').click( function(){
// 			  $('.custom_logout.menu-item.menu-item-type-custom button').click();
// 		  });
        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.error("AJAX error: " + textStatus + ", " + errorThrown);
        }
      });
    }
  });
