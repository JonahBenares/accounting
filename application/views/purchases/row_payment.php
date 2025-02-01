<?php $x=0; foreach($list['purchase_transaction_head'] AS $l){ ?>
<tr id='item_row<?php echo $x; ?>'>  
    <td class="reference_number">
        <input type="hidden" name="purchase_id[]" value="<?php echo $l->purchase_id; ?>">
        <input type="text" name="reference_number[]" id="ref_no" style = "width:100%;border:1px transparent;color:#020202" value="<?php echo $l->reference_number; ?>" readonly>       
    </td>
    <td>
        <input type="text" name="market_fee[]" style = "width:100%;border:1px transparent;color:#020202" value="" readonly> 
    </td>
    <td>
        <input type="text" name="withholding_tax[]" style = "width:100%;border:1px transparent;color:#020202" value="" readonly> 
    </td>
    <td align="right" >
        <input type="text" name="total_amount[]" class="total_amount" style = "text-align:center;width:100%;border:1px transparent;color:#020202" value="<?php echo $list['total_amount'][$x]; ?>" readonly>
        
        <input type="hidden" id="total_vatable_purchase" name="total_vatable_purchase[]" value="<?php echo $list['total_vatable_purchase'][$x]; ?>">
        <input type="hidden" id="total_vat" name="total_vat[]" value="<?php echo $list['total_vat'][$x]; ?>">
        <input type="hidden" id="total_ewt" name="total_ewt[]" value="<?php echo $list['total_ewt'][$x]; ?>">
    </td>
    <td >
        <center>
            <a class="btn btn-danger table-remove btn-sm text-white" onclick="remove_item(<?php echo $x; ?>,<?php echo '`'.$l->reference_number.'`'; ?>,<?php echo $l->purchase_id; ?>)"><span class=" fa fa-times"></span></a>
        </center>
    </td>
</tr>
<?php $x++; } ?>
