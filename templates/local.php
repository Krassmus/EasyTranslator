<style>
    .select_table {
        border-collapse: collapse;
    }
    .select_table > thead > tr > th, .select_table > tbody > tr > td {
        padding: 6px;
        border: 1px solid #cccccc;
    }
    .select_table > tbody > tr:hover > td {
        cursor: pointer;
        background-color: #eeeeee;
    }
    .select_table .metadata {
        display: none;
    }
    #edit_window table {
        width: 100%;
    }
    #edit_window input[type=text] {
        width: 98%;
    }
    #php_format_error {
        text-align: center;
        padding: 10px;
    }
</style>

<input type="hidden" id="language_id" value="<?= htmlReady(Request::get("language_id")) ?>">

<table class="select_table">
    <thead>
        <tr>
            <th><?= l("Text-String") ?></th>
            <th><?= l("Übersetzung") ?></th>
            <th><?= l("Ursprung") ?></th>
        </tr>
    </thead>
    <tbody>
        <? if (count($translations)) : ?>
        <? foreach ($translations as $translation) : ?>
        <tr>
            <td>
                <?= htmlReady($translation['string']) ?>
                <div class="metadata"><?= 
                    json_encode(array(
                        'string' => studip_utf8encode($translation['string']), 
                        'translation' => studip_utf8encode($translation['translation']), 
                        'origin' => studip_utf8encode($translation['origin'])
                    )) ?></div>
            </td>
            <td><?= htmlReady($translation['translation']) ?></td>
            <td><?= htmlReady($translation['origin']) ?></td>
        </tr>
        <? endforeach ?>
        <? else : ?>
        <tr>
            <td colspan="3">
                <?= l("Keine Übersetzungen gefunden.") ?>
            </td>
        </tr>
        <? endif ?>
    </tbody>
</table>

<div id="edit_window" style="display: none;">
    <form action="?" method="post">
    <input type="hidden" name="language_id" value="<?= htmlReady(Request::get("language_id")) ?>">
    <table>
        <tr>
            <td><label for="text"><?= ll("Text-String") ?></label></td>
            <td><input type="text" name="text" id="text" value=""></td>
        </tr>
        <tr>
            <td><label for="translation"><?= ll("Übersetzung") ?></label></td>
            <td><input type="text" name="translation" id="translation" value=""></td>
        </tr>
    </table>
    <div id="php_format_error" style="display: none;"><?= Assets::img("icons/16/red/exclaim", array('class' => "text-bottom")) ?> <?= ll("Beide Strings müssen gleichviele %s enthalten.") ?></div>
    <?= new Studip\Button(l("speichern"), "") ?>
    </form>
</div>
<div id="edit_window_new_entry_title" style="display: none;"><?= ll("Neuer Übersetzungseintrag") ?></div>
<div id="edit_window_edit_entry_title" style="display: none;"><?= ll("Übersetzungseintrag bearbeiten") ?></div>

<script>
STUDIP.i18n = {
    'edit': function () {
        if (jQuery(this).is("tr")) {
            var metadata = jQuery.parseJSON(jQuery(this).find(".metadata").text());
            jQuery("#text").val(metadata.string);
            jQuery("#translation").val(metadata.translation);
            jQuery("#edit_window").dialog({
                'title': jQuery("#edit_window_edit_entry_title").text(),
                'show': "fade",
                'hide': "fade",
                'width': "80%",
                'modal': true
            });
            STUDIP.i18n.check();
        } else {
            jQuery("#text").val('');
            jQuery("#translation").val('');
            jQuery("#edit_window").dialog({
                'title': jQuery("#edit_window_new_entry_title").text(),
                'show': "fade",
                'hide': "fade",
                'width': "80%",
                'modal': true
            });
            STUDIP.i18n.check();
        }
    },
    'check': function () {
        console.log(jQuery("#text").val().search(/\%s/));
        if (jQuery("#text").val().split(/\%s/).length !== jQuery("#translation").val().split(/\%s/).length) {
            jQuery("#php_format_error").show();
        } else {
            jQuery("#php_format_error").hide();
        }
    }
};
jQuery(".select_table > tbody > tr ").bind("click", STUDIP.i18n.edit);
jQuery("#text, #translation").bind("keyup", STUDIP.i18n.check);
</script>

<?

$infobox = array(
    array(
        'kategorie' => ll("Aktionen"),
        'eintrag' => array(
            array(
                'icon' => "icons/16/black/plus", 
                'text' => '<a href="" onClick="STUDIP.i18n.edit(); return false;">'.ll("Neuen Eintrag erstellen")."</a>"
            )
        )
    )
);

$infobox = array(
    'picture' => $GLOBALS['ABSOLUTE_URI_STUDIP'].$plugin->getPluginPath()."/assets/rosettastone.jpg",
    'content' => $infobox
);