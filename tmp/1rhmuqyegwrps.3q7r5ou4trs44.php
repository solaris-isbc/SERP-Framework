<p>
    <?= ($question)."
" ?>
</p>
<div>
    <table>
        <tbody>
            <tr>
                <td>
                    <?= ($minDescription)."
" ?>
                </td>
                <?php $cnt=0; foreach (($answers?:[]) as $answer): $cnt++; ?>

                    <td>
                        <label for="<?= ($id) ?>_<?= ($cnt) ?>">
                            <?= ($answer)."
" ?>
                        </label>
                    </td>
                <?php endforeach; ?>
                <td>
                    <?= ($maxDescription)."
" ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <?php $cnt=0; foreach (($answers?:[]) as $answer): $cnt++; ?>
                    <td>
                        <input type="radio" <?php if ($isRequired): ?>required<?php endif; ?> name="<?= ($id) ?>" id="<?= ($id) ?>_<?= ($cnt) ?>" value="<?= ($cnt) ?>">
                    </td>
                <?php endforeach; ?>
                <td></td>
            </tr>
        </tbody>
    </table>

</div>

I AM A LIKERT SCALE