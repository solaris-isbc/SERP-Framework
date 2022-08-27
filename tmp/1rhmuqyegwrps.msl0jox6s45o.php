<p>
    <?= ($question->getQuestion())."
" ?>
</p>
<div>
    <?php $cnt=0; foreach (($question->getAnswers()?:[]) as $answer): $cnt++; ?>
        <label for="<?= ($question->getId()) ?>_<?= ($cnt) ?>">
            <?= ($answer)."
" ?>
        </label>
        <input type="radio" name="<?= ($question->getId()) ?>" id="<?= ($question->getId()) ?>_<?= ($cnt) ?>" value="<?= ($cnt) ?>">  
    <?php endforeach; ?>

</div>

