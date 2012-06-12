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
<table class="select_table">
    <thead>
        <tr>
            <th><?= l("Sprach-Kürzel") ?></th>
            <th><?= l("Sprache") ?></th>
        </tr>
    </thead>
    <tbody>
        <? if (count($languages)) : ?>
        <? foreach ($languages as $language) : ?>
        <tr id="<?= htmlReady($language['id']) ?>">
            <td><?= htmlReady($language['id']) ?></td>
            <td><?= htmlReady($language['name']) ?></td>
        </tr>
        <? endforeach ?>
        <? else : ?>
        <tr>
            <td colspan="2">
                <?= l("Keine Sprachen hinterlegt.") ?>
            </td>
        </tr>
        <? endif ?>
    </tbody>
</table>

<script>
jQuery(function () {
    jQuery(".select_table > tbody > tr").bind("click", function () {
        location.href = STUDIP.URLHelper.getURL(
            '<?= PluginEngine::getLink($plugin, array(), 'local') ?>', 
            { 
                'language_id' : jQuery(this).attr('id')
            }
        );
    });
});
</script>

<?

$infobox = array();

$infobox = array(
    'picture' => $GLOBALS['ABSOLUTE_URI_STUDIP'].$plugin->getPluginPath()."/assets/rosettastone.jpg",
    'content' => $infobox
);