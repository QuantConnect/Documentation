<p>
The following reference table lists the Future assets available for use on QuantConnect.
    They can be requested using either the short code ticker or the helper static class below.
</p>
<style>
.futures-type {
    width: 30% !important;
    word-wrap: break-word !important;
}
.futures-type > code, .futures-type > p {
    word-break: break-all;
}
</style>
<?php
$inspector = "https://www.quantconnect.com/services/inspector?type=T:";
$decoded = json_decode(file_get_contents($inspector . "QuantConnect.Securities.Futures"), true);
foreach ($decoded['types'] as $type)
{
    $decoded = json_decode(file_get_contents($inspector . urlencode($type)), true);
    $typeName = $decoded['type-name'] ?>
    <table class="table qc-table futures-table table-itemized table-reflow">
        <thead>
        <tr>
            <th colspan="3">
                <?=$typeName?>
            </th>
        </tr>
        <tr>
            <th>Name</th>
            <th>Ticker</th>
            <th>Code</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($decoded['fields'] as $field)
        { ?>
            <tr>
                <td class="futures-type">
                    <p><?=htmlentities($field['field-description'])?></p><br/>
                </td>
                <td>
                    <p><?=$field['field-name']?></p>
                </td>
                <td>
                    <code><?php echo "Futures.{$typeName}.{$field['field-name']}"; ?></code>
                </td>
            </tr>
  <?php } ?>
    </tbody>
</table>
<?php
} ?>