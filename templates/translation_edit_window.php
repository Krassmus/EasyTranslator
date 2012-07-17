<div id="edit_window" style="display: none;">
    <form action="?" method="post">
    <input type="hidden" id="language_id" name="language_id" value="<?= Request::get("language_id") ? htmlReady(Request::get("language_id")) : $GLOBALS['_language'] ?>">
    <table>
        <tr>
            <td><label for="translation_text"><?= l("Text-String") ?></label></td>
            <td>
                <input type="text" name="text" id="translation_text" value="">
                <input type="hidden" name="originaltext" id="originaltext" value="">
            </td>
        </tr>
        <tr>
            <td><label for="translation"><?= l("Übersetzung") ?></label></td>
            <td><input type="text" name="translation" id="translation" value=""></td>
        </tr>
        <tr>
            <td><label for="translation_origin"><?= l("Ursprung/Kontext") ?></label></td>
            <td><input type="text" id="translation_origin" value=""></td>
        </tr>
    </table>
    <div id="php_format_error" style="display: none;"><?= Assets::img("icons/16/red/exclaim", array('class' => "text-bottom")) ?> <?= l("Beide Strings müssen gleichviele %s enthalten.") ?></div>
    <?= Studip\LinkButton::create(ll("speichern"), "", array('onclick' => "STUDIP.i18n.save(); return false;")) ?>
    </form>
</div>
<div id="edit_window_new_entry_title" style="display: none;"><?= l("Neuer Übersetzungseintrag") ?></div>
<div id="edit_window_edit_entry_title" style="display: none;"><?= l("Übersetzungseintrag bearbeiten") ?></div>
