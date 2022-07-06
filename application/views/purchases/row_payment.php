<tr id='item_row<?php echo $list['count']; ?>'>  
    <td class="reference_number">
        <input type="hidden" name="purchase_id[]" value="<?php echo $list['purchase_id']; ?>">
        <?php echo $list['reference_number']; ?>        
    </td>
    <td align="right" class="total_amount"><?php echo $list['total_amount']; ?></td>
</tr>

