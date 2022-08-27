<p>
    <?= ($question)."
" ?>
</p>
<div>
    <?php $cnt=0; foreach (($answers?:[]) as $answer): $cnt++; ?>
        <label for="<?= ($id) ?>_<?= ($cnt) ?>">
            <?= ($answer)."
" ?>
        </label>
        <input type="radio" <?php if ($isRequired): ?>required<?php endif; ?> name="<?= ($id) ?>" id="<?= ($id) ?>_<?= ($cnt) ?>" value="<?= ($cnt) ?>">  
    <?php endforeach; ?>

</div>

