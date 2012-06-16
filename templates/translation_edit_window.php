<div id="edit_window" style="display: none;">
    <form action="?" method="post">
    <input type="hidden" id="language_id" name="language_id" value="<?= Request::get("language_id") ? htmlReady(Request::get("language_id")) : $GLOBALS['_language'] ?>">
    <table>
        <tr>
            <td><label for="text"><?= l("Text-String") ?></label></td>
            <td>
                <input type="text" name="text" id="text" value="">
                <input type="hidden" name="originaltext" id="originaltext" value="">
            </td>
        </tr>
        <tr>
            <td><label for="translation"><?= l("�bersetzung") ?></label></td>
            <td><input type="text" name="translation" id="translation" value=""></td>
        </tr>
    </table>
    <div id="php_format_error" style="display: none;"><?= Assets::img("icons/16/red/exclaim", array('class' => "text-bottom")) ?> <?= l("Beide Strings m�ssen gleichviele %s enthalten.") ?></div>
    <?= Studip\LinkButton::create(ll("speichern"), "", array('onclick' => "STUDIP.i18n.save(); return false;")) ?>
    </form>
</div>
<div id="edit_window_new_entry_title" style="display: none;"><?= l("Neuer �bersetzungseintrag") ?></div>
<div id="edit_window_edit_entry_title" style="display: none;"><?= l("�bersetzungseintrag bearbeiten") ?></div>