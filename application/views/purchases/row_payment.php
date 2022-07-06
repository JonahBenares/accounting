<tr id='item_row<?php echo $list['count']; ?>'>  
    <td class="reference_number">
        <input type="hidden" name="purchase_id[]" value="<?php echo $list['purchase_id']; ?>">
        <input type="text" name="reference_number[]" style = "width:100%;border:1px transparent;color:#020202" value="<?php echo $list['reference_number']; ?>" readonly>       
    </td>
    <td align="right" >
        <input type="text" name="total_amount[]" class="total_amount" style = "text-align:center;width:100%;border:1px transparent;color:#020202" value="<?php echo $list['total_amount']; ?>" readonly>


        <input type="hidden" id="total_vatable_purchase" name="total_vatable_purchase[]" value="<?php echo $list['total_vatable_purchase']; ?>">
        <input type="hidden" id="total_vat" name="total_vat[]" value="<?php echo $list['total_vat']; ?>">
        <input type="hidden" id="total_ewt" name="total_ewt[]" value="<?php echo $list['total_ewt']; ?>">
    </td>
</tr>

