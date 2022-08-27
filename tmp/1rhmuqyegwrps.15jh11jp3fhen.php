<label for="<?= ($id) ?>">
    <?= ($question)."
" ?>
</label>
<input type="text" <?php if ($isRequired): ?>required<?php endif; ?> name="<?= ($id) ?>" id="<?= ($id) ?>">  