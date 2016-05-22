(function($) {
	(function( jQuery, window, undefined ) {
	"use strict";
	 
	var matched, browser;
	 
	jQuery.uaMatch = function( ua ) {
	  ua = ua.toLowerCase();
	 
		var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
			/(webkit)[ \/]([\w.]+)/.exec( ua ) ||
			/(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
			/(msie) ([\w.]+)/.exec( ua ) ||
			ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
			[];
	 
		return {
			browser: match[ 1 ] || "",
			version: match[ 2 ] || "0"
		};
	};
	 
	matched = jQuery.uaMatch( window.navigator.userAgent );
	browser = {};
	 
	if ( matched.browser ) {
		browser[ matched.browser ] = true;
		browser.version = matched.version;
	}
	 
	// Chrome is Webkit, but Webkit is also Safari.
	if ( browser.chrome ) {
		browser.webkit = true;
	} else if ( browser.webkit ) {
		browser.safari = true;
	}
	 
	jQuery.browser = browser;
	 
	})( jQuery, window );

	$(document).ready(function() {
		var userAgent = navigator.userAgent.toLowerCase();
        $.browser = {
            version: (userAgent.match(/.+(?:rv|it|ra|ie|me)[\/: ]([\d.]+)/) || [])[1],
            chrome: /chrome/.test(userAgent),
            safari: /webkit/.test(userAgent) && !/chrome/.test(userAgent),
            opera: /opera/.test(userAgent),
            msie: /msie/.test(userAgent) && !/opera/.test(userAgent),
            mozilla: /mozilla/.test(userAgent) && !/(compatible|webkit)/.test(userAgent),
            iPad: /iPad/i.test(userAgent),
            iPhone: /iPhone/i.test(userAgent),
            iPod: /iPod/i.test(userAgent)
        };
		
		/*
		$('#images-slider').slides({ 
			container : 'slider-container',
			start : 1,
			effect : 'fade', 
			pagination : true,
			crossfade : true,
			fadeSpeed : 1000,
			play : 8000,
			pause : 4000,
			animationStart : sliderHideCaption,
			animationComplete : sliderSetCaption,
			slidesLoaded: function() {
				sliderSetCaption();
			}
		});*/
        
		paginate_slider();
		main_menu();
		// search_form_mail_button();
		shadow_border();
		search_tabs();
		tabbed_content();
		readmore();
		inquire_form();
		
		if ((location.href != "http://www.hghue.com/" && location.href != "http://www.hghtravel.com/" && location.href != "http://www.huevietnamtravel.com/") && (location.href.indexOf('/destinations') == -1) && (location.href.indexOf('/home') == -1)) {
			if (window.location.hash) {
				jQuery('html,body').animate({scrollTop: jQuery(window.location.hash.replace('#', '.')).offset().top}, 'slow');	
			} else {
				jQuery('html,body').animate({scrollTop: jQuery("#system-main").offset().top - 100}, 'slow');	
			}
		}
		
		if (location.href == "http://www.hghue.com/tours") {
			 
			$(function() { 
				$(".tours-list").tablesorter({}); 
			});              	
		}

		// Cruise - remove after this menu has a page
		$('.menu-1565').removeClass('active');
		$('.menu-1565 a').removeClass('active');
		// Visas - remove after this menu has a page
		$('.menu-1567').removeClass('active');
		$('.menu-1567 a').removeClass('active');
		$('.menu-1571 a').attr('href', '/blog');

		// remove active class for unuse menu
		$('body.i18n-fr .menu-1566, body.i18n-fr .menu-1568, body.i18n-fr .menu-1571').removeClass('active');
	});
	
	function inquire_form() {
		$('#edit-language-other').click(function() {
			if ($(this).is(':checked')) {
				$('#edit-language-other--2').show();
			} else {
				$('#edit-language-other--2').hide();
			}
		});
		
		try {
			$("#edit-arrival-date").datepicker({ dateFormat: "M dd, yy" });
		} catch (e) {}
	}
	
	function readmore() {
		$('.page-tour .node-tour .tour-day .read-more a').click(function() {
			$(this).parent().parent().find('.summary').show().parent().find('.teaser').show();
			$(this).parent().hide();
		});
	}
	
	function sliderSetCaption() {
		$('.slides_control img').each(function() {
			if ($(this).css('display') != 'none') {
				// home page only
				if ($('#page-wrapper').attr('class') == 'page-home') {
					if ($(this).attr('title') != "") {
						$('#captions-wrapper .caption').html('<a href="'+$(this).attr('path')+'">'+$(this).attr('title')+'</a>');
						$('#captions-wrapper .caption').fadeIn();
					}
					
					if ($(this).attr('path') != "") {
						$('#captions-wrapper .find-out-more').html('<a href="'+$(this).attr('path')+'">More</a>');
						$('#captions-wrapper .find-out-more').fadeIn();
					}
				}
			}
		});
		
		var $html = $('#images-slider .pagination').html();
		if (typeof $html != 'undefined') $('.pagination-slider').html('<ul class="page-navigator">'+$html+'</ul>');
	}
	
	function sliderHideCaption() {
		if ($('#page-wrapper').attr('class') == 'page-home') {
			$('#captions-wrapper .caption').fadeOut();
			$('#captions-wrapper .find-out-more').fadeOut();
		}
	}
	
	function tabbed_content() {
		// =====================================
		// home tabbed content (footer bottom)
		$('.tabbed-content-wrapper #destinations-tab').click(function() {
			$('.tabbed-content-wrapper .content').removeClass('tab-second');	
			
			$('.tabbed-content-wrapper .text').each(function() {
				$(this).attr('class', 'text tab');
			});
			
			$(this).attr('class', 'text tab-selected');
			$('#foot-destinations-content').show();
			$('#foot-last-viewed-content').hide();
		});
		
		$('.tabbed-content-wrapper #last-viewed-tab').click(function() {
			$('.tabbed-content-wrapper .content').addClass('tab-second');
			
			$('.tabbed-content-wrapper .text').each(function() {
				$(this).attr('class', 'text tab');
			});
			$(this).attr('class', 'text tab-selected');
			$('#foot-destinations-content').hide();
			$('#foot-last-viewed-content').show();
			$(document).contentSlide();
		});
		
		// =====================================
		// left search panel tour detail
		$('.page-tour #tours-tab, .page-destinations #tours-tab, .page-node #tours-tab, .page-inquire #tours-tab, .page-hotels #tours-tab, .page-search #tours-tab, .page-user #tours-tab, .page-views  #tours-tab, .page-news  #tours-tab').click(function() {
			$('.page-tour .search-control, .page-destinations .search-control, .page-node .search-control, .page-inquire .search-control, .page-hotels .search-control, .page-search .search-control, .page-user .search-control, .page-views  .search-control, .page-news  .search-control').removeClass('tab-second');
			
			$('.page-tour #tours-tab .text, .page-destinations #tours-tab .text, .page-node #tours-tab .text, .page-inquire #tours-tab .text, .page-hotels #tours-tab .text, .page-search #tours-tab .text, .page-user #tours-tab .text, .page-views  #tours-tab .text, .page-news  #tours-tab .text').each(function() {
				$(this).attr('class', 'text tab');
			});
			
			$(this).attr('class', 'text tab-selected');
		});
		
		$('.page-tour #hotels-tab, .page-destinations #hotels-tab, .page-node #hotels-tab, .page-inquire #hotels-tab, .page-hotels #hotels-tab, .page-search #hotels-tab, .page-user #hotels-tab, .page-views  #hotels-tab, .page-news  #hotels-tab').click(function() {
			$('.page-tour .search-control, .page-destinations .search-control, .page-node .search-control, .page-inquire .search-control, .page-hotels .search-control, .page-search .search-control, .page-user .search-control, .page-views  .search-control, .page-news  .search-control').addClass('tab-second');	
			
			$('.page-tour #hotels-tab .text, .page-destinations #hotels-tab .text, .page-node #hotels-tab .text, .page-inquire #hotels-tab .text, .page-hotels #hotels-tab .text, .page-search #hotels-tab .text, .page-user #hotels-tab .text, .page-views  #hotels-tab .text, .page-news  #hotels-tab .text').each(function() {
				$(this).attr('class', 'text tab');
			});
			
			$(this).attr('class', 'text tab-selected');
		});
		// =====================================
		
		// =====================================
		// News
		$('.page-news .news-list .tabs .text').click(function() {
			// deslect all
			$('.page-news .news-list .tabs .text').each(function() {
				$(this).attr('class', 'text');
			});
			
			// select this tab
			$(this).attr('class', 'text tab-selected');
			
			// set class for content
			if ($(this).attr('id') == 'general-tab-content') {
				$('.page-news .news-list .content').removeClass('content-second');
			} else {
				$('.page-news .news-list .content').addClass('content-second')
			}
			
			$current = $(this).attr('id');
			$('.page-news .news-list .content > div').each(function() {
				$(this).hide();
			});
			$('.page-news .news-list .'+$current).show();
		});
		
		// =====================================
		// Tour detail
		$('.page-tour .tour-content .tabs .text').click(function() {
			// deslect all
			$('.page-tour .tour-content .tabs .text').each(function() {
				$(this).attr('class', 'text');
			});
			
			// select this tab
			$(this).attr('class', 'text tab-selected');
			
			// set class for content
			if ($(this).attr('id') == 'overview-tab-content') {
				$('.page-tour .tour-content .content').removeClass('content-second');
			} else {
				$('.page-tour .tour-content .content').addClass('content-second')
			}
			
			$current = $(this).attr('id');
			$('.page-tour .tour-content .content > div').each(function() {
				$(this).hide();
			});
			$('.page-tour .tour-content .'+$current).show();
		});
		
		// similar tours
		$('.page-tour #similar-tours .tabs .text').click(function() {
			$('.page-tour #similar-tours .tabs .text').each(function() {
				$(this).attr('class', 'text');
			});
			
			// select this tab
			$(this).attr('class', 'text tab-selected');
			$current = $(this).attr('id');
			$('.page-tour #similar-tours .contentSlidecontainer').each(function() {
				$(this).hide();
			});
			$('.page-tour #similar-tours .'+$current).show();
			
			if ($current == 'last-viewed-content') {
				$(document).contentSlide();
				$('.page-tour #similar-tours .content').addClass('tab-second');
			} else {
				$('.page-tour #similar-tours .content').removeClass('tab-second');
			}
		});
		
		// destinations tours
		$('.page-destinations .tours-list .tabs .text').click(function() {
			$('.page-destinations .tours-list .tabs .text').each(function() {
				$(this).attr('class', 'text');
			});
			
			// select this tab
			$(this).attr('class', 'text tab-selected');
			$current = $(this).attr('id');
			$('.page-destinations .tours-list .content > div').each(function() {
				$(this).hide();
			});
			$('.page-destinations .tours-list .'+$current).show();
			
			if ($current == 'all-tours-tab-content') {
				$('.page-destinations .tours-list .content').addClass('tab-second');
				$(document).contentSlide();
			} else {
				$('.page-destinations .tours-list .content').removeClass('tab-second');
			}
		});
	}
	
	// =====================
	var cities_options = new Array();
	function search_tabs() {
		$('#tours-tab').click(function() {
			$('#tours-tab').attr('class', 'text tab-selected');
			$('#hotels-tab').attr('class', 'text tab');
			// hide hotel type, show duration and style
			$('.search-control .hotel-type').hide();
			$('.search-control .duration').show();
			$('.search-control .style').show();
			$('.search-content #search-type').val('tour');
		});
		
		$('#hotels-tab').click(function() {
			$('#tours-tab').attr('class', 'text tab');
			$('#hotels-tab').attr('class', 'text tab-selected');
			// show hotel type, hide duration and style
			$('.search-control .hotel-type').show();
			$('.search-control .duration').hide();
			$('.search-control .style').hide();
			$('.search-content #search-type').val('hotel');
		});
		
		$('#search-country').change(function() {
			if ($('#search-country').val() == 'all') {
				// reset cities value
				$('#search-city').html('<option value="all">All Cities</option>');
			} else {
				if (!cities_options[$('#search-country').val()]) {
					$.ajax({
						url: Drupal.settings.basePath + "ajax/get-cities/"+$('#search-country').val(),
						dataType: 'json',
						async: false,
						success: function(result) {
							html = '<option value="all">All Cities</option>';
							$('#search-city').html(html);
							if (result.length > 0) {
								for(i = 0; i < result.length; i++) {
									html += '<option value="'+result[i].tid+'">'+result[i].name+'</option>'
								}
								
								cities_options[$('#search-country').val()] = html;
								$('#search-city').html(html);
							}
						}
					});
				}
				
				$('#search-city').html(cities_options[$('#search-country').val()]);
				
				// prefill for city if url exist
				if (location.href.indexOf('/search/') > -1) {
					var params = location.href.split('search/');
					if (typeof params[1] != 'undefined') {
						params = params[1].split('/');
						$('#search-city').val(params[2]);
					}
				}
			}
		});
		
		if (location.href.indexOf('/search/') > -1) {
			var params = location.href.split('search/');
			if (typeof params[1] != 'undefined') {				
				params = params[1].split('/');
				$('#search-country').val(params[1]);
				$('#search-country').change();

				if (params[0] == 'tour') {
					$('#search-duration').val(params[3]);
					$('#search-style').val(params[4]);
				} else {
					$('#hotels-tab').click();
					$('.search-content .search-control').addClass('tab-second');
					$('#search-hotel-type').val(params[3]);	
				}
			}
		}
		
		$('#block-search-form input, #block-multiblock-1 input').bind('keypress', function(e) {
			if(e.keyCode == 13) {
				location.href = Drupal.settings.basePath+'search/google/'+$('#edit-search-block-form--2').val()+'?query='+$('#edit-search-block-form--2').val()+'&cx=001174141285309249268%3Aeds0rfje3vg&cof=FORID%3A11&sitesearch=';
			}
		});

		$('#search-content-submit').click(function() {
			search_submit();
		});
	}
	
	function search_submit() {
		$type 			= $('.search-content #search-type').val();
		$country 		= $('.search-content #search-country').val();
		$city		 	= $('.search-content #search-city').val().replace(' ', '-').toLowerCase();
		$duration	 	= $('.search-content #search-duration').val();
		$style	 		= $('.search-content #search-style').val().replace(' ', '-').toLowerCase();
		$hotel	 		= $('.search-content #search-hotel-type').val();
		
		if ($type == 'tour') {
			location.href = Drupal.settings.basePath+'search/'+$type+'/'+$country+'/'+$city+'/'+$duration+'/'+$style;
		} else {
			location.href = Drupal.settings.basePath+'search/'+$type+'/'+$country+'/'+$city+'/'+$hotel;
		}
	}
	
	function shadow_border() {
		$('#header').shadow();
		$('.site-logo').shadow();
		$('.shadow').shadow();
		// $('.links-list').shadow();
		// $('#content-wrapper').shadow();
		
		settings = {
			tl: { radius: 0 },
			tr: { radius: 0 },
			bl: { radius: 14 },
			br: { radius: 14 },
			antiAlias: true
			//autoPad: true,
			//validTags: ["div"]
		}

		//$('#images-slider .slides_control img').corner(settings);
		//$('#images-slider .slides_control img').corner('bottom round 14px');
		/*
		if (($.browser.msie && $.browser.version > 8) || !$.browser.msie) {
			$('#header').corner('bottom round 14px');
			$('#captions-wrapper').corner('top round 14px');
			$('#captions-wrapper-footer').corner('bottom round 14px');
			// $('#desinations-list').corner('bottom 14px');
			$('.link-item').corner('left round 14px');
		}
		*/
	}
		
	function main_menu() {
		$('#primary-menu-bar li').each(function() {
			$hyperlink_text = $(this).find('a').html();
			if ($hyperlink_text == 'Destinations') {
				$(this).find('a').first().attr('id', 'detinations-link');
				$(this).find('a').first().append('<span> </span>');
			} else if ($hyperlink_text == "Tours" || $hyperlink_text == "Tour") {
				$(this).find('a').attr('id', 'tours-link');
				$(this).find('a').append('<span> </span>');
			} else if ($hyperlink_text == "Hotels &amp; Resorts") {
				$(this).find('a').attr('id', 'hotels-link');
				// $(this).find('a').append('<span> </span>');
			} else if ($hyperlink_text == "About Us") {
				$(this).find('a').attr('id', 'about-link');
				// $(this).find('a').append('<span> </span>');
			}
		});
		
		if (location.href.indexOf('destinations/') > -1) {
			$('.menu-659').addClass('active');	
		}
		
		if (location.href.indexOf('/hotel/') > -1) {
			$('.menu-661').addClass('active');	
		}
		
		$('#primary-menu-bar li a').each(function() {
			$element_id = $(this).attr('id');
			switch($element_id) {
				case 'detinations-link' : {
					$('#detinations-link').hover(function() {
						// hide others
						$('#tours-list').hide();
						$('#desinations-list').fadeIn();
					});
				
					$('#desinations-list').mouseleave(function() {
						$('#desinations-list').delay(1000).fadeOut();
					});
				} break;
				
				case "tours-link": {
					$('#tours-link').hover(function() {
						// hide others
						$('#desinations-list').hide();
						$('#tours-list').fadeIn();
					});
				
					$('#tours-list').mouseleave(function() {
						$('#tours-list').delay(1000).fadeOut();
					});
				} break;

			}
		});
	}
	
	/*
	// for default search block
	function search_form_mail_button() {
		$('#header #block-search-form #edit-actions input').val("");
		$('#header #block-search-form #edit-actions').append('<a href="" class="mail-button"></a>');
	}
	*/
	
	function paginate_slider() {
		$html = $('#images-slider .pagination').html();
		if ($html) {
			$('.pagination-slider').html('<ul class="pagination">'+$html+'</ul>');
	
			$(document).on('click', '.pagination-slider ul li a', function() {
				$('.pagination-slider ul li').each(function(index) {
					$(this).removeClass('current');
				});
				
				$('.pagination-slider ul li:eq('+$(this).parent().index()+')').addClass('current');
				$(this).addClass('current');
				
				var link = $(this).attr('href');
				$('#images-slider .pagination li a[href="' + link + '"]').click();
			});
		}
	}
})(jQuery);

/* ============================= */
(function($) {
	$.fn.contentSlide = function(options) {
		var settings = {
			'container': '.contentSlidecontainer:visible',
			'leftbutton': '.prevcontainer',
			'rightbutton': '.nextcontainer',
			'window': '.contentSlidewindow:visible',
			'wrapper': '.contentSlidewrapper',
			'slide': '.contentSlide',
			'slides_per_page': 2,
			'pagingcontainer': '.pagingcontainer'
		};

		var methods = {
			contentSlidemove: function($pager_wrapper, direction, $paging_container) {
				var pages = parseInt($pager_wrapper.data('pages'), 10);
				var currentpage = parseInt($pager_wrapper.data('currentpage'), 10);
				var windowwidth = parseInt($pager_wrapper.data('windowwidth'), 10);
				var left = parseInt($pager_wrapper.css('left'), 10);
				var newleft = 0;
				var animate = false;
				if (direction == 'left') {
					if (currentpage > 1) {
						currentpage--;
						$pager_wrapper.data('currentpage', currentpage);
						newleft = 0 - ((windowwidth) * (currentpage - 1));
						animate = true;
					}
				} else if (direction == 'right') {
					if (currentpage < pages) {
						currentpage++;
						$pager_wrapper.data('currentpage', currentpage);
						newleft = 0 - ((windowwidth) * (currentpage - 1));
						animate = true;
					}
				}

				if (animate) {
					$pager_wrapper.animate({
						left: newleft + 'px'
					}, {
						"duration": "slow"
					});
					$paging_container.find('.current').removeClass();
					$paging_container.find('span[data-pagenum="' + currentpage + '"]').addClass('current');
				}
			},
			contentSlidejump: function($pager_wrapper, page_num) {

				var pages = parseInt($pager_wrapper.data('pages'), 10);
				var currentpage = parseInt($pager_wrapper.data('currentpage'), 10);
				var windowwidth = parseInt($pager_wrapper.data('windowwidth'), 10);
				var left = parseInt($pager_wrapper.css('left'), 10);
				var newleft = 0;
				page_num = parseInt(page_num, 10);

				if (page_num < pages + 1 && page_num > 0 && page_num != currentpage) {
					newleft = 0 - ((windowwidth) * (page_num - 1));
					$pager_wrapper.data('currentpage', page_num);
					$pager_wrapper.animate({
						left: newleft + 'px'
					}, {
						"duration": "slow"
					});
				}
			}
		}

		return this.each(function() {
			if (options) {
				$.extend(settings, options);
			}

			var $contentSlide_container = $(settings.container);
			$contentSlide_container.each(function() {
				$this = $(this);
				var $pager_window = $this.find(settings.window);
				var $pager_wrapper = $pager_window.find(settings.wrapper);
				var $paging_container = $this.find(settings.pagingcontainer);

				$pager_wrapper.height($pager_window.innerHeight());
				var $slides = $pager_wrapper.find(settings.slide);
				var slides_per_page = $pager_wrapper.attr('data-slidesperpage');
				if (typeof slides_per_page !== 'undefined' && typeof slides_per_page !== false && !isNaN(slides_per_page)) {
					settings.slides_per_page = parseInt(slides_per_page, 10);
					// console.log(settings.slides_per_page);
				}

				var pages = Math.ceil($slides.length / settings.slides_per_page);

				var containerWidth = 0;
				$slides.each(function() {
					$this = $(this);
					containerWidth += $this.outerWidth();
				});

				$pager_wrapper.width(containerWidth);
				$pager_wrapper.data('pages', pages);
				if (typeof $pager_wrapper.data('currentpage') == 'undefined') {
					$pager_wrapper.data('currentpage', 1);
				} else if ($pager_wrapper.data('currentpage') > pages || $pager_wrapper.data('currentpage') < 1) {
					$pager_wrapper.data('currentpage', 1);
				}

				$pager_wrapper.data('windowwidth', $pager_window.innerWidth());
				$pager_window.siblings(settings.leftbutton).unbind().click(function() {
					methods['contentSlidemove'].apply(this, [$pager_wrapper, 'left', $paging_container]);
				});

				$pager_window.siblings(settings.rightbutton).unbind().click(function() {
					methods['contentSlidemove'].apply(this, [$pager_wrapper, 'right', $paging_container]);
				});

				if ($paging_container.length == 1) {
					$paging_container.empty();
					var i = 1;
					var str = '';
					for (i; i < pages + 1; i++) {
						if ($pager_wrapper.data('currentpage') == i) {
							str = '<span class="current" data-pagenum="' + i + '">' + i + '</span>';
						} else {
							str = '<span data-pagenum="' + i + '">' + i + '</span>';
						}

						$paging_container.append(str);
					}

					$paging_container.find('span').unbind().click(function() {
						$this = $(this);
						if (!$this.hasClass('current')) {
							$this.siblings('.current').removeClass('current');
							$this.addClass('current');
							methods['contentSlidejump'].apply(this, [$pager_wrapper, $(this).attr('data-pagenum')]);
						}
					});
				}
			});
		});
	};

	$(document).ready(function() {
		if (typeof Drupal.settings.hgh_travel != 'undefined') {
			if (Drupal.settings.hgh_travel.nid > 0) {
				setTimeout(function () {
					$(document).contentSlide({
						slides_per_page: 3	
					});
				}, 1000);
			} else {
				setTimeout(function () {
					$(document).contentSlide();
				}, 1000);
			}
		} else {
			setTimeout(function () {
				$(document).contentSlide();
			}, 1000);
			
		}
	});

	$(document).ready(function() {
		var _maxHeight = 0;
		var view_more = 'view more';
		if (Drupal.settings.hgh_travel.language == 'fr') {
			view_more = 'voir plus';	
		}

		jQuery('#block-views-feedback-block .block-title').append('<span class="view-more"><a href="/testimonials" class="more">'+view_more+'</a></span');

		jQuery('#block-views-feedback-block .views-row').each(function() {
			jQuery(this).css({
		        position:   'absolute',
		        visibility: 'hidden',
		        display:    'block'
		    });
			if (jQuery(this).height() > _maxHeight) _maxHeight = jQuery(this).height();
		});

		jQuery('#block-views-feedback-block .views-row').each(function() {
			jQuery(this).css({
		        position:   'relative',
		        visibility: 'visible',
		        display: 'none'
		    })
		});

		jQuery('#block-views-feedback-block .views-row-1').css({display:'block'});

		if (_maxHeight > 0) jQuery('#block-views-feedback-block .view-feedback .view-content').height(_maxHeight + 20);
		// testimonials
		setInterval(function() {
			var random = Math.floor(Math.random() * (20 - 10 + 1)) + 10;
			jQuery('#block-views-feedback-block .views-row').hide();
			jQuery('#block-views-feedback-block .views-row-'+random).fadeIn();
		}, 7000);

		jQuery('#tabs').tab();

		/*
		jQuery('.collectonme').hide();
	    jQuery(document).snowfall();          
	    jQuery('body').addClass('lightBg');
		jQuery('.collectonme').hide();
	    jQuery(document).snowfall('clear');
	    jQuery(document).snowfall({shadow : true, round : true,  minSize: 5, maxSize:8}); // add shadows
	    */

	    jQuery('.mobile-menu').click(function(e) {
	    	if (jQuery('#primary-menu-bar').css('display') == "none") {
	    		jQuery('#primary-menu-bar').slideDown();
	    	} else {
	    		jQuery('#primary-menu-bar').slideUp();
	    	}
	    });

	    // close menu when click outside
	    jQuery('.mobile-menu').click(function(event) {
			jQuery('html').one('click',function() {
				jQuery('#primary-menu-bar').slideUp();	
			});

			event.stopPropagation();
		});
	});
})(jQuery);

