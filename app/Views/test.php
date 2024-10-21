<ul>
    <?php

use CodeIgniter\I18n\Time;

 if (count($notes) == 0): ?>
        <p class="h3 opacity-7">
            <?= lang('Label.no-notes') ?>
        </p>
    <?php else: ?>
        <?php foreach($notes as $note): ?>
            <li class="trans-1" 
                id="<?= esc($note->id, "attr") ?>" 
                style="background-color: <?= esc($note->color, "attr") ?>;" 
                color="<?= esc($note->color, "attr") ?>"
            >
                <h3 
                style="font-family: <?= esc($note->font, "attr") ?>;" 
                full-data="<?= esc($note->title, "attr") ?>"><?= esc(character_limiter($note->title, 20, "..."), "attr") ?></h3>

                <textarea 
                class="textarea is-warning" 
                style="font-family: <?= esc($note->font, "attr") ?>;" 
                readonly="readonly"><?= esc($note->body) ?></textarea>
        
                <button 
                    type="button" 
                    class="button is-info is-fullwidth is-light is-bold trans-1" 
                    click-flag="0"
                >
                    <span>Select</span>
                    <i class="fa fa-check-circle is-hidden"></i>
                </button>
                <div class="is-flex is-flex-direction-row is-flex-wrap-wrap is-justify-content-space-between mt-1 opacity-7" style="width: 100%;">
                    <div class="tag is-black is-light is-small">
                        <?= esc(Time::createFromFormat("Y-m-d H:i:s", $note->created_at)->humanize()) ?>
                    </div>

                    <div class="tag is-black is-light is-small">
                        <?= esc($note->src) ?>
                    </div>
                </div>
            </li>
        <?php endforeach ?>
    <?php endif ?>
</ul>