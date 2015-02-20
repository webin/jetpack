(function($) {

	///////////////////////////////////////
	// INIT
	///////////////////////////////////////

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
		})

		// Adding an active class to the benefits nav items
		$( '.benefit-bucket a').click(function(){
			$('.benefit-bucket').removeClass('active');
			$(this).parent().addClass('active');
		})

		// Fix the benefits nav to the top of the page on scroll
		function j_fix_benefits_nav() {
			if ($(window).scrollTop() > 200)
				$('#j-settings-nav').addClass('fixed');
			else
				$('#j-settings-nav').removeClass('fixed');
		}
		$(window).scroll(j_fix_benefits_nav);
		j_fix_benefits_nav();

		// Benefits enable/disable toggle
		$('.j-enable-feature').click(function(){
			$(this).parent().toggleClass('j-feature-enabled');
			$(this).find('.j-toggle-wrap').toggleClass('j-toggle-enabled');
			$(this).prev('.j-title').children().toggle();
		})
	}



})(jQuery);
