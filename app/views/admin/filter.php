<h2>Выборка по опросу «<?= $this->encode($model->title); ?>»</h2>

<form role="form" method="post">
    <input type="hidden" name="Filter[poll-id]" value="<?= $model->id; ?>">

    <div class="form-group">
        <?php foreach ($model->getQuestions() as $question) : ?>
            <h3><?= $this->encode($question->title); ?></h3>

            <?php foreach ($question->getAnswers() as $answer) : ?>
                <div class="checkbox">
                    <label><input type="checkbox"
                                  name="Filter[question-<?= $question->id; ?>][<?= $answer->id; ?>]"> <?= $this->encode($answer->title); ?>
                    </label>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>

    <button type="submit" class="btn btn-default">Показать результаты</button>
</form>