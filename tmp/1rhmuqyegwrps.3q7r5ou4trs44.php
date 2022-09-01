<h6>
    <?= ($question)."
" ?>
</h6>
<div>
    <table class="table-likert">
        <tbody>
            <tr>
                <td class="likert-descr-left">
                    <?= ($minDescription)."
" ?>
                </td>
                <?php $cnt=0; foreach (($answers?:[]) as $answer): $cnt++; ?>
                    <td class="likert-label">
                        <label for="<?= ($id) ?>_<?= ($cnt) ?>">
                            <?= ($answer)."
" ?>
                        </label>
                    </td>
                <?php endforeach; ?>
                <td class="likert-descr-right">
                    <?= ($maxDescription)."
" ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <?php $cnt=0; foreach (($answers?:[]) as $answer): $cnt++; ?>
                    <td class="likert-answer">
                        <input type="radio" <?php if ($isRequired): ?>required<?php endif; ?> name="<?= ($id) ?>" id="<?= ($id) ?>_<?= ($cnt) ?>" value="<?= ($cnt) ?>">
                    </td>
                <?php endforeach; ?>
                <td></td>
            </tr>
        </tbody>
    </table>

</div>
