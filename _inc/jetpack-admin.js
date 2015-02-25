(function($) {

	///////////////////////////////////////
	// INIT
	///////////////////////////////////////

	var debug = false;

	$(document).ready(function () {
		initEvents();
		configFixedElements();
	});

	///////////////////////////////////////
	// FUNCTIONS
	///////////////////////////////////////

	function configFixedElements() {
		var jpTopFrame = $('.frame.top'),
			jpBottomFrame = $('.frame.bottom'),
			$body = $('body');

		$body.scroll(function(){
			if ( 33 > jpTopFrame.offset().top ) {
				jpTopFrame.addClass('fixed');
				$body.addClass('jp-frame-top-fixed');
			}
			if ( 120 <= jpBottomFrame.offset().top ) {
				jpTopFrame.removeClass('fixed');
				$body.removeClass('jp-frame-top-fixed');
			}
		});
	}

	function initEvents() {
		// toggle search and filters at mobile resolution
		$('.filter-search').on('click', function () {
			$(this).toggleClass('active');
			$('.manage-right').toggleClass('show');
			$('.shade').toggle();
		});

		// Toggle all checkboxes
		$('.checkall').on('click', function () {
			$('.table-bordered').find(':checkbox').prop('checked', this.checked);
		});

		// Clicking outside modal, or close X closes modal
		$('.shade, .modal .close').on('click', function ( event ) {
			$('.shade, .modal').hide();
			$('.manage-right').removeClass('show');
			event.preventDefault();
		});

		// Manual switch between classic and new settings view
		$('.settings-view').click(function() {
			$('#classic_settings, #benefits, .classic-button, .benefits-button').toggle();
		});

		// Adding an active class to the benefits nav items
		$( '.benefit-bucket a').click(function(){
			var scroll_target = '#' + $(this).attr('class');
			console.log(scroll_target);
			$('html, body').animate({
				scrollTop: $(scroll_target).offset().top-90
			}, 1000);
			$('.benefit-bucket').removeClass('active');
			$(this).parent().addClass('active');
		});

		// Benefits enable/disable toggle
		$('.j-enable-feature').click(function(){
			$(this).parent().toggleClass('j-feature-enabled');
			$(this).find('.j-toggle-wrap').toggleClass('j-toggle-enabled');
			$(this).prev('.j-title').children().toggle();
			flyMiguel();
		});

		// Make a random Miguel fly!
		function flyMiguel() {
			var miguels = ['.miguel:first-child', '.miguel:nth-child(2)', '.miguel:nth-child(3)'];
			var randomMiguel = miguels[Math.floor(Math.random() * miguels.length)];

			try {
				$( randomMiguel ).css( 'display', 'block' );
				setTimeout(function () {
					$( randomMiguel ).css( 'display', 'none' );
				}, 4500);
			} catch (e) {
				if( debug ) {
					console.log( 'flyMiguel(): ' + e );
				}
			}
		}
	}

})(jQuery);
