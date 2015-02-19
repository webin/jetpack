
this.jetpackModules = this.jetpackModules || {};

window.jetpackModules.models = (function( window, $, _, Backbone ) {
		'use strict';

		var models = {};

		models.Modules = Backbone.Model.extend({
			visibles : {},

			/**
			* Updates modules.items dataset to be a reflection of both the current
			* modules.raw data, as well as any filters or sorting that may be in effect.
			*/
			filter_and_sort : function() {
				var subsubsub = $('.subsubsub .current'),
					items     = this.get( 'raw' ),
					m_filter  = $('.button-group.filter-active .active'),
					m_sort    = $('.button-group.sort .active'),
					m_search  = $('#srch-term-search-input').val().toLowerCase(),
					show_benefit_headings = true,
					groups;

				// If a module filter has been selected, filter it!
				if ( ! subsubsub.closest('li').hasClass( 'all' ) ) {
					show_benefit_headings = false;
					items = _.filter( items, function( item ) {
						return _.contains( item.module_tags, subsubsub.data( 'title' ) );
					} );
				}

				if ( m_filter.data('filter-by') ) {
					items = _.filter( items, function( item ) {
						return item[ m_filter.data('filter-by') ] === m_filter.data('filter-value');
					} );
				}

				if ( m_search.length ) {
					show_benefit_headings = false;
					items = _.filter( items, function( item ) {
						var search_text = item.name + ' ' + item.description;
						return ( -1 !== search_text.toLowerCase().indexOf( m_search ) );
					} );
				}

				if ( m_sort.data('sort-by') ) {

					items = _.sortBy( items, m_sort.data('sort-by') );

					if ( m_sort.data('sort-by') == 'benefit_tag' && show_benefit_headings ) {
						var benefits = this.getBenefits();
						_.each( benefits, function( item ) {
							var heading = {
								name : item,
								type : 'heading',
								benefit_tag : [ item ],
								activated: true,
								available: true,
							};
							items.unshift( heading );
						});
					}

					items = _.sortBy( items, m_sort.data('sort-by') );

					if ( 'reverse' === m_sort.data('sort-order') ) {
						items.reverse();
					}
				}

				// Sort unavailable modules to the end if the user is running in local mode.
				groups = _.groupBy( items, 'available' );
				if ( _.has( groups, 'false' ) ) {
					items = [].concat( groups[true], groups[false] );
				}

				// Now shove it back in.
				this.set( 'items', items );

				console.log( items );

				return this;
			},

			getBenefits: function( ) {
				var items = this.get( 'raw'),
					benefits = [];
				_.each( items, function( item ) {
					benefits = benefits.concat( item.benefit_tag );
				});
				return _.uniq( benefits );
			},

			initialize : function() {
				var items = this.get( 'items' );
				this.set( 'raw', items );
			}

		});

		return models;

})( this, jQuery, _, Backbone );
