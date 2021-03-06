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

<form action="?" method="get">
    <input type="hidden" id="language_id" name="language_id" value="<?= htmlReady(Request::get("language_id")) ?>">
    <table align="center">
        <tbody>
            <tr>
                <td><label for="searchword"><?= l("Filter") ?></label></td>
                <td><input type="text" name="searchword" id="searchword" value="<?= htmlReady(Request::get("searchword")) ?>"></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;"><?= Studip\Button::create(ll("filtern")) ?></td>
            </tr>
        </tbody>
    </table>
</form>

<table id="translation_table" class="select_table">
    <thead>
        <tr>
            <th><?= l("Text-String") ?></th>
            <th><?= l("‹bersetzung") ?></th>
            <th><?= l("Ursprung") ?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <? if (count($translations)) : ?>
        <? foreach ($translations as $translation) : ?>
        <tr data-original-text="<?= htmlReady($translation['string']) ?>">
            <td class="original">
                <?= htmlReady($translation['string']) ?>
                <div class="metadata"><?= 
                    json_encode(array(
                        'string' => studip_utf8encode($translation['string']), 
                        'translation' => studip_utf8encode($translation['translation']), 
                        'origin' => studip_utf8encode($translation['origin'])
                    )) ?></div>
            </td>
            <td class="translation"><?= htmlReady($translation['translation']) ?></td>
            <td class="origin"><?= htmlReady($translation['origin']) ?></td>
            <td><a href="#" onClick="return false;"><?= Assets::img("icons/16/blue/search") ?></a></td>
        </tr>
        <? endforeach ?>
        <? else : ?>
        <tr>
            <td colspan="4">
                <?= ll("Keine ‹bersetzungen gefunden.") ?>
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

<div id="translation_download_window_title" style="display: none;"><?= l("PO-Datei herunterladen") ?></div>
<div id="translation_download_window" style="display: none;">
    <table>
        <tbody>
            <tr>
                <td><label for="origin"><?= l("Ursprung/Kontext") ?></label></td>
                <td>
                    <select id="download_origin">
                        <option><?= l("alles") ?></option>
                        <? foreach ($origins as $origin) : ?>
                        <option value="<?= htmlReady($origin) ?>"><?= htmlReady($origin) ?></option>
                        <? endforeach ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><button class="button" onClick="location.href = STUDIP.URLHelper.getURL('<?= PluginEngine::getURL($plugin, array('language_id' => Request::get("language_id")), 'download') ?>', {'origin': jQuery('#download_origin').val()});"><?= l("runterladen") ?></button></td>
            </tr>
        </tbody>
    </table>
</div>

<?

$actions = new ActionsWidget();
$actions->addLink(
    ll("Neuen Eintrag erstellen"),
    "#",
    Icon::create("add", "info"),
    array('onClick' => "STUDIP.i18n.edit(); return false;")
);
$actions->addLink(
    ll("PO-Datei hochladen"),
    "#2",
    Icon::create("upload", "info"),
    array('onClick' => "jQuery('#translation_upload_window').dialog({show: 'fade', hide: 'fade', modal: true, width: '400px', title: jQuery('#translation_upload_window_title').text() }); return false;")
);
$actions->addLink(
    ll("PO-Datei runterladen"),
    "#3",
    Icon::create("download", "info"),
    array('onClick' => "jQuery('#translation_download_window').dialog({show: 'fade', hide: 'fade', modal: true, width: '400px', title: jQuery('#translation_upload_window_title').text() }); return false;")
);
Sidebar::Get()->addWidget($actions);