STUDIP.i18n = {
    'edit': function () {
        if (jQuery(this).is("tr")) {
            var metadata = jQuery.parseJSON(jQuery(this).find(".metadata").text());
            STUDIP.i18n.showEditWindow(
                jQuery("#edit_window_edit_entry_title").text(), 
                metadata.string, 
                metadata.translation, 
                ""
            );
        } else {
            STUDIP.i18n.showEditWindow(
                jQuery("#edit_window_new_entry_title").text(), 
                "", 
                "", 
                ""
            );
        }
    },
    'showEditWindow': function (window_title, text, translation, origin) {
        jQuery("#text").val(text ? text : '');
        jQuery("#originaltext").val(text ? text : '');
        jQuery("#translation").val(translation ? translation : '');
        jQuery("#origin").val(origin ? origin : '');
        jQuery("#edit_window").dialog({
            'title': window_title,
            'show': "fade",
            'hide': "fade",
            'width': "80%",
            'modal': true
        });
        STUDIP.i18n.check();
    },
    'save': function () {
        jQuery.ajax({
            'url': STUDIP.ABSOLUTE_URI_STUDIP + "plugins.php/easytranslator/save_text",
            'data': {
                'originaltext': jQuery("#originaltext").val(),
                'text': jQuery("#text").val(),
                'translation': jQuery("#translation").val(),
                'language_id': jQuery('#language_id').val()
            },
            'type': "post",
            'success': function () {
                var elements = jQuery('span[data-original-string="' + jQuery("#text").val() + '"]');
                jQuery.each(elements, function (index, element) {
                    if (jQuery(element).text() === jQuery(element).html()) {
                        jQuery(element).text(jQuery("#translation").val());
                    }
                });
                var tr = jQuery('tr[data-original-text="' + jQuery("#originaltext").val() + '"]')
                    .attr("data-original-text", jQuery("#text").val());
                tr.find("td:nth-child(2)").text(jQuery("#translation").val());
                jQuery("#edit_window").dialog('close');
            }
        });
    },
    'check': function () {
        if (jQuery("#text").val().split(/\%s/).length !== jQuery("#translation").val().split(/\%s/).length) {
            jQuery("#php_format_error").show();
        } else {
            jQuery("#php_format_error").hide();
        }
    }
};
jQuery(function () {
    jQuery("span.nottranslated, span.translation").bind("click", function (event) {
        if (event.ctrlKey || event.altKey) {
            var string = jQuery(this).attr("data-original-string");
            var language_id = jQuery("#language_id").val();
            jQuery.ajax({
                'url': STUDIP.ABSOLUTE_URI_STUDIP + "plugins.php/easytranslator/get_text",
                'data': {
                    'string': string,
                    'language_id': language_id
                },
                'dataType': "json",
                'success': function (data) {
                    var title = data.string 
                        ? jQuery("#edit_window_edit_entry_title").text()
                        : jQuery("#edit_window_new_entry_title").text();
                    STUDIP.i18n.showEditWindow(
                        title, 
                        data.string, 
                        data.translation, 
                        data.origin
                    );
                }
            });
            event.stopImmediatePropagation();
            return false;
        }
    });
    
    jQuery("#translation_table > tbody > tr ").bind("click", STUDIP.i18n.edit);
    jQuery("#text, #translation").bind("keyup", STUDIP.i18n.check);
});

function getSelected() {
    if(window.getSelection) {return window.getSelection();}
    else if(document.getSelection) {return document.getSelection();}
                else {
                        var selection = document.selection && document.selection.createRange();
                        if(selection.text) {return selection.text;}
            return false;
        }
        return false;
    }

