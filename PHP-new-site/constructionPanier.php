<input type="hidden" name="cart[<?php echo htmlspecialchars($key); ?>][name]" value="<?php echo htmlspecialchars($data['name']); ?>">
<input type="hidden" name="cart[<?php echo htmlspecialchars($key); ?>][quantite]" value="<?php echo (int) $data['quantite']; ?>">
<input type="hidden" name="cart[<?php echo htmlspecialchars($key); ?>][price]" value="<?php echo (int) $data['price']; ?>">
<input type="hidden" name="cart[<?php echo htmlspecialchars($key); ?>][discount]" value="<?php echo (int) $data['discount']; ?>">