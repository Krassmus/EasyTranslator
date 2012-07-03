<style>
    .select_table {
        border-collapse: collapse;
        margin-left: auto;
        margin-right: auto;
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

<table id="translation_table" class="select_table">
    <thead>
        <tr>
            <th><?= l("Text-String") ?></th>
            <th><?= l("Übersetzung") ?></th>
            <th><?= l("Ursprung") ?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <? if (count($translations)) : ?>
        <? foreach ($translations as $translation) : ?>
        <tr data-original-text="<?= htmlReady($translation['string']) ?>">
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
            <td><a href="#" onClick="return false;"><?= Assets::img("icons/16/blue/search") ?></a></td>
        </tr>
        <? endforeach ?>
        <? else : ?>
        <tr>
            <td colspan="3">
                <?= ll("Keine Übersetzungen gefunden.") ?>
            </td>
        </tr>
        <? endif ?>
    </tbody>
</table>

<div id="translation_upload_window_title" style="display: none;"><?= ll("PO-Datei hochladen") ?></div>
<div id="translation_upload_window" style="display: none;">
    <form action ="?" method="post" enctype="multipart/form-data">
        <input type="hidden" name="language_id" value="<?= Request::option('language_id') ?>">
        <table>
            <tbody>
                <tr>
                    <td><label for="po_file"><?= l("Datei") ?></label></td>
                    <td><input type="file" name="po_file" id="po_file"></td>
                </tr>
                <tr>
                    <td><label for="origin"><?= l("Ursprung/Kontext") ?></label></td>
                    <td><input type="text" name="origin" id="origin"></td>
                </tr>
                <tr>
                    <td></td>
                    <td><button class="button" type="submit"><?= l("PO-Datei hochladen") ?></button></td>
                </tr>
            </tbody>
        </table>
    </form>
</div>

<?

$infobox = array(
    array(
        'kategorie' => l("Aktionen"),
        'eintrag' => array(
            array(
                'icon' => "icons/16/black/plus", 
                'text' => '<a href="" onClick="STUDIP.i18n.edit(); return false;">'.l("Neuen Eintrag erstellen")."</a>"
            ),
            array(
                'icon' => "icons/16/black/upload",
                'text' => '<a href="#" onClick="jQuery(\'#translation_upload_window\').dialog({show: \'fade\', hide: \'fade\', modal: true, width: \'400px\', title: jQuery(\'#translation_upload_window_title\').text() }); return false;">'.ll("PO-Datei hochladen").'</a>'
            ),
            array(
                'icon' => "icons/16/black/download",
                'text' => '<a href="'.PluginEngine::getLink($plugin, array('language_id' => Request::get("language_id")), 'download').'">'.ll("PO-Datei runterladen").'</a>'
            )
        )
    )
);

$infobox = array(
    'picture' => $GLOBALS['ABSOLUTE_URI_STUDIP'].$plugin->getPluginPath()."/assets/rosettastone.jpg",
    'content' => $infobox
);