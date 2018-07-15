/*
* TipTip
* Copyright 2010 Drew Wilson
* www.drewwilson.com
* code.drewwilson.com/entry/tiptip-jquery-plugin
*
* Version 1.3   -   Updated: Mar. 23, 2010
*
* This Plug-In will create a custom tooltip to replace the default
* browser tooltip. It is extremely lightweight and very smart in
* that it detects the edges of the browser window and will make sure
* the tooltip stays within the current window size. As a result the
* tooltip will adjust itself to be displayed above, below, to the left
* or to the right depending on what is necessary to stay within the
* browser window. It is completely customizable as well via CSS.
*
* This TipTip jQuery plug-in is dual licensed under the MIT and GPL licenses:
*   http://www.opensource.org/licenses/mit-license.php
*   http://www.gnu.org/licenses/gpl.html
*/
(function ($) {

	"use strict";

	$.fn.tipTip = function (options) {
		var defaults = {
			activation: "hover",
			keepAlive: false,
			maxWidth: "200px",
			edgeOffset: 3,
			defaultPosition: "bottom",
			delay: 400,
			fadeIn: 200,
			fadeOut: 200,
			attribute: "title",
			content: false,
			enter: function () {
			},
			exit: function () {
			}
		};
		var opts = $.extend(defaults, options);
		if ($("#tiptip_holder").length <= 0) {
			var tiptip_holder = $('<div id="tiptip_holder" style="max-width:' + opts.maxWidth + ';"></div>');
			var tiptip_content = $('<div id="tiptip_content"></div>');
			var tiptip_arrow = $('<div id="tiptip_arrow"></div>');
			$("body").append(tiptip_holder.html(tiptip_content).prepend(tiptip_arrow.html('<div id="tiptip_arrow_inner"></div>')))
		} else {
			var tiptip_holder = $("#tiptip_holder");
			var tiptip_content = $("#tiptip_content");
			var tiptip_arrow = $("#tiptip_arrow")
		}
		return this.each(function () {
			var org_elem = $(this);
			if (opts.content) {
				var org_title = opts.content
			} else {
				var org_title = org_elem.attr(opts.attribute)
			}
			if (org_title != "") {
				if (!opts.content) {
					org_elem.removeAttr(opts.attribute)
				}
				var timeout = false;
				if (opts.activation == "hover") {
					org_elem.hover(function () {
						active_tiptip()
					}, function () {
						if (!opts.keepAlive) {
							deactive_tiptip()
						}
					});
					if (opts.keepAlive) {
						tiptip_holder.hover(function () {
						}, function () {
							deactive_tiptip()
						})
					}
				} else if (opts.activation == "focus") {
					org_elem.focus(function () {
						active_tiptip()
					}).blur(function () {
						deactive_tiptip()
					})
				} else if (opts.activation == "click") {
					org_elem.click(function () {
						active_tiptip();
						return false
					}).hover(function () {
					}, function () {
						if (!opts.keepAlive) {
							deactive_tiptip()
						}
					});
					if (opts.keepAlive) {
						tiptip_holder.hover(function () {
						}, function () {
							deactive_tiptip()
						})
					}
				}

				function active_tiptip() {
					opts.enter.call(this);
					tiptip_content.html(org_title);
					tiptip_holder.hide().removeAttr("class").css("margin", "0");
					tiptip_arrow.removeAttr("style");
					var top = parseInt(org_elem.offset()['top']);
					var left = parseInt(org_elem.offset()['left']);
					var org_width = parseInt(org_elem.outerWidth());
					var org_height = parseInt(org_elem.outerHeight());
					var tip_w = tiptip_holder.outerWidth();
					var tip_h = tiptip_holder.outerHeight();
					var w_compare = Math.round((org_width - tip_w) / 2);
					var h_compare = Math.round((org_height - tip_h) / 2);
					var marg_left = Math.round(left + w_compare);
					var marg_top = Math.round(top + org_height + opts.edgeOffset);
					var t_class = "";
					var arrow_top = "";
					var arrow_left = Math.round(tip_w - 12) / 2;
					if (opts.defaultPosition == "bottom") {
						t_class = "_bottom"
					} else if (opts.defaultPosition == "top") {
						t_class = "_top"
					} else if (opts.defaultPosition == "left") {
						t_class = "_left"
					} else if (opts.defaultPosition == "right") {
						t_class = "_right"
					}
					var right_compare = (w_compare + left) < parseInt($(window).scrollLeft());
					var left_compare = (tip_w + left) > parseInt($(window).width());
					if ((right_compare && w_compare < 0) || (t_class == "_right" && !left_compare) || (t_class == "_left" && left < (tip_w + opts.edgeOffset + 5))) {
						t_class = "_right";
						arrow_top = Math.round(tip_h - 13) / 2;
						arrow_left = -12;
						marg_left = Math.round(left + org_width + opts.edgeOffset);
						marg_top = Math.round(top + h_compare)
					} else if ((left_compare && w_compare < 0) || (t_class == "_left" && !right_compare)) {
						t_class = "_left";
						arrow_top = Math.round(tip_h - 13) / 2;
						arrow_left = Math.round(tip_w);
						marg_left = Math.round(left - (tip_w + opts.edgeOffset + 5));
						marg_top = Math.round(top + h_compare)
					}
					var top_compare = (top + org_height + opts.edgeOffset + tip_h + 8) > parseInt($(window).height() + $(window).scrollTop());
					var bottom_compare = ((top + org_height) - (opts.edgeOffset + tip_h + 8)) < 0;
					if (top_compare || (t_class == "_bottom" && top_compare) || (t_class == "_top" && !bottom_compare)) {
						if (t_class == "_top" || t_class == "_bottom") {
							t_class = "_top"
						} else {
							t_class = t_class + "_top"
						}
						arrow_top = tip_h;
						marg_top = Math.round(top - (tip_h + 5 + opts.edgeOffset))
					} else if (bottom_compare | (t_class == "_top" && bottom_compare) || (t_class == "_bottom" && !top_compare)) {
						if (t_class == "_top" || t_class == "_bottom") {
							t_class = "_bottom"
						} else {
							t_class = t_class + "_bottom"
						}
						arrow_top = -12;
						marg_top = Math.round(top + org_height + opts.edgeOffset)
					}
					if (t_class == "_right_top" || t_class == "_left_top") {
						marg_top = marg_top + 5
					} else if (t_class == "_right_bottom" || t_class == "_left_bottom") {
						marg_top = marg_top - 5
					}
					if (t_class == "_left_top" || t_class == "_left_bottom") {
						marg_left = marg_left + 5
					}
					tiptip_arrow.css({"margin-left": arrow_left + "px", "margin-top": arrow_top + "px"});
					tiptip_holder.css({
						"margin-left": marg_left + "px",
						"margin-top": marg_top + "px"
					}).attr("class", "tip" + t_class);
					if (timeout) {
						clearTimeout(timeout)
					}
					timeout = setTimeout(function () {
						tiptip_holder.stop(true, true).fadeIn(opts.fadeIn)
					}, opts.delay)
				}

				function deactive_tiptip() {
					opts.exit.call(this);
					if (timeout) {
						clearTimeout(timeout)
					}
					tiptip_holder.fadeOut(opts.fadeOut)
				}
			}
		})
	}
})(jQuery);

// JavaScript Document
jQuery(document).ready(function (e) {

	/* MegaMenu in Appearance > Menu*/
	jQuery(".menu-item.menu-item-depth-0").each(function (index, item) {
		jQuery(item).find(".edit-menu-item-menu_style").change(function () {
			var mega_num_items = jQuery(item).find('.wpmega-num_items');
			var mega_orderby = jQuery(item).find('.wpmega-orderby');
			if (jQuery(item).find(".edit-menu-item-menu_style").val() == "preview-2") {
				mega_num_items.show();
				mega_orderby.show();
			} else {
				mega_num_items.hide();
				mega_orderby.hide();
			}
		}).change();

	});

	jQuery('.help_tip').tipTip({
		attribute: 'data-tip'
	});

	jQuery('a.help_tip').click(function () {
		return false;
	});

	var page_tpl_obj = jQuery('select[name=page_template]');
	var page_tpl = jQuery('select[name=page_template]').val();
	var front_page_meta_boxes = jQuery('#frontpage_meta_box');

	setTimeout(function () {
		var fullpage_meta_boxes = jQuery('#setting_fullpage_settings.tab-content');
		var fullpage_meta_tab = jQuery('.ot-metabox-nav li[aria-controls="setting_fullpage_settings"]');

		if (page_tpl == 'page-templates/front-page.php' || page_tpl == 'page-templates/fullpage.php') {
			// do nothing
		} else {
			front_page_meta_boxes.addClass('hidden');
		}

		if (page_tpl == 'page-templates/fullpage.php') {

		} else {
			fullpage_meta_boxes.addClass('hidden');
			fullpage_meta_tab.addClass('hidden');
		}

	}, 1000);


	page_tpl_obj.change(function (event) {
		var front_page_meta_boxes = jQuery('#frontpage_meta_box');

		if (jQuery(this).val() == 'page-templates/front-page.php' || jQuery(this).val() == 'page-templates/fullpage.php') {
			front_page_meta_boxes.removeClass('hidden');
		} else {
			front_page_meta_boxes.addClass('hidden');
		}

		var fullpage_meta_boxes = jQuery('#setting_fullpage_settings.tab-content');
		var fullpage_meta_tab = jQuery('.ot-metabox-nav li[aria-controls="setting_fullpage_settings"]');

		var page_content_box = jQuery('#setting_page_content_tab.tab-content');
		var page_content_tab = jQuery('.ot-metabox-nav li[aria-controls="setting_page_content_tab"]');

		if (jQuery(this).val() == 'page-templates/fullpage.php') {
			fullpage_meta_boxes.removeClass('hidden');
			fullpage_meta_tab.removeClass('hidden');

			page_content_box.addClass('hidden');
			page_content_tab.addClass('hidden');
		} else {
			fullpage_meta_boxes.addClass('hidden');
			fullpage_meta_tab.addClass('hidden');

			page_content_box.removeClass('hidden');
			page_content_tab.removeClass('hidden');
		}

	});


	/* custom usermetadata */
	var usermeta_count = jQuery('.madara-account .metadata').length - 1;
	jQuery(".cactua_add_account").click(function () {
		jQuery('.madara-account-header').removeClass('hidden');
		usermeta_count = usermeta_count + 1;

		jQuery('.madara-account').append('\
		<tr>\
			<td><input type="text" name="madara_account[' + usermeta_count + '][title]" id="title" value="" class="" /></td>\
			<td><input type="text" name="madara_account[' + usermeta_count + '][icon]" id="icon" value="" class="regular-text" /></td>\
			<td><input type="text" name="madara_account[' + usermeta_count + '][url]" id="url" value="" class="regular-text" /></td>\
			<td valign="top"><button class="custom-acc-remove button"><i class="fa fa-times"></i> Remove</button></td>\
		</tr>\
		');
		return false;
	});
	jQuery(".custom-acc-remove").live('click', function () {
		jQuery(this).parent().parent().remove();
	});

	jQuery('.btn-widget_appearance').on('click', function () {
		jQuery('.widget_appearance', jQuery(this).closest('form')).toggle();
	});

	jQuery(document).on('widget-updated widget-added', function (widget) {
		// remove previous click handler
		jQuery('.btn-widget_appearance').unbind('click');

		jQuery('.btn-widget_appearance').on('click', function () {
			jQuery('.widget_appearance', jQuery(this).closest('form')).toggle();
		});
	});

	/**
	 * Customizer
	 */
	jQuery('.customize-controls-close').on('click', function () {
		// admin is leaving customizer, we return Theme Options settings
		jQuery.ajax({
			url: ajaxurl, data: {'action': 'customizer_cancel'}, async: false, success: function (response) {
				// do nothing
			}
		});
	});

	jQuery('#customize-controls #save').on('click', function () {
		// admin is leaving customizer, we return Theme Options settings
		jQuery.ajax({
			url: ajaxurl, data: {'action': 'customizer_save'}, success: function (response) {
				// do nothing
			}
		});
	});

	/**
	 * init Color Picker for Widget Member-Author
	 */
	jQuery('.c-widget-member-bg-color').colorpicker({
		colorFormat: ('#HEX'),
	});
	jQuery('.c-widget-member-text-color').colorpicker({
		colorFormat: ('#HEX'),
	});

	jQuery(document).on('widget-updated widget-added', function () {
		jQuery('.c-widget-member-bg-color').colorpicker({
			colorFormat: ('#HEX'),
		});
		jQuery('.c-widget-member-text-color').colorpicker({
			colorFormat: ('#HEX'),
		});
	});

});
