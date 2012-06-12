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
            <td><?= htmlReady($translation['string']) ?></td>
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
    <?= new Studip\Button(l("speichern"), "") ?>
    </form>
</div>
<div id="edit_window_new_entry_title" style="display: none;"><?= ll("Neuer Übersetzungseintrag") ?></div>

<script>
STUDIP.i18n = {
    'edit': function (entry_id) {
        if (entry_id) {
            
        } else {
            jQuery("#edit_window").dialog({
                'title': jQuery("#edit_window_new_entry_title").text(),
                'show': "fade",
                'hide': "fade"
            });
        }
    }
};
</script>

<?

$infobox = array(
    array(
        'kategorie' => ll("Aktionen"),
        'eintrag' => array(
            array(
                'icon' => "icons/16/black/star", 
                'text' => '<a href="" onClick="STUDIP.i18n.edit(); return false;">'.ll("Neuen Eintrag erstellen")."</a>"
            )
        )
    )
);

$infobox = array(
    'picture' => $GLOBALS['ABSOLUTE_URI_STUDIP'].$plugin->getPluginPath()."/assets/rosettastone.jpg",
    'content' => $infobox
);