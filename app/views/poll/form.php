<a class="btn btn-default pull-right" href="<?= $this->url('poll/results'); ?>">Посмотреть результаты</a>
<h2><?= $this->encode($model->title); ?></h2>

<?php if ($model->hasErrors()) : ?>
    <div class="alert alert-danger"><?= $model->getErrors(); ?></div>
<?php endif; ?>

<form role="form" method="post">
    <input type="hidden" name="Result[poll-id]" value="<?= $model->id; ?>">

    <div class="form-group">
        <?php foreach ($model->getQuestions() as $question) : ?>
            <h3><?= $this->encode($question->title); ?><?= $question->required ? '*' : ''; ?></h3>

            <?php foreach ($question->getAnswers() as $answer) : ?>
                <?php if ($question->type == 2) : ?>
                    <div class="checkbox">
                        <label><input
                                type="checkbox" <?= in_array($answer->id, $results->get($question->id)) ? ' checked' : ''; ?>
                                name="Result[question-<?= $question->id; ?>][<?= $answer->id; ?>]"> <?= $this->encode($answer->title); ?>
                        </label>
                    </div>
                <?php else : ?>
                    <div class="radio">
                        <label><input type="radio" <?= $results->get($question->id) == $answer->id ? ' checked' : ''; ?>
                                      name="Result[question-<?= $question->id; ?>]"
                                      value="<?= $answer->id; ?>"> <?= $answer->title; ?></label>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

        <?php endforeach; ?>
    </div>

    <button type="submit" class="btn btn-default">Сохранить результаты</button>
</form>