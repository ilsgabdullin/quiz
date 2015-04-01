<h2><?= $model->isNewRecord() ? 'Добавление' : 'Редактирование'; ?> опроса</h2>

<?php if ($model->hasErrors()) : ?>
    <div class="alert alert-danger"><?= $model->getErrors(); ?></div>
<?php endif; ?>

<form role="form" method="post">
    <div class="form-group">
        <label for="Poll_id">Название опроса</label>
        <input type="text" value="<?= !empty($data['title']) ? $data['title'] : ''; ?>" class="form-control"
               name="Poll[title]" id="Poll_id" placeholder="Введите название опроса">
    </div>
    <?php
    $questionsCount = 0;
    $answersCount = 0;
    if (!empty($data['question'])) : ?>
        <?php foreach ($data['question'] as $item) : ?>
            <?php $questionsCount++; ?>
            <div class="poll-question" id="poll-question-<?= $questionsCount; ?>" data-id="<?= $questionsCount; ?>">
                <div class="form-group">
                    <label for="Poll_question_1_title">Вопрос</label>
                    <input type="hidden" value="<?= !empty($item['id']) ? $item['id'] : ''; ?>"
                           name="Poll[question][<?= $questionsCount; ?>][id]"
                           id="Poll_question_<?= $questionsCount; ?>_id">
                    <input type="text" value="<?= !empty($item['title']) ? $item['title'] : ''; ?>" class="form-control"
                           name="Poll[question][<?= $questionsCount; ?>][title]"
                           id="Poll_question_<?= $questionsCount; ?>_title" placeholder="Введите вопрос">
                </div>
                <div class="checkbox">
                    <a class="pull-right question-delete" href="javascript:;">Удалить вопрос</a>
                    <label><input type="checkbox" name="Poll[question][<?= $questionsCount; ?>][required]"
                                  id="Poll_question_<?= $questionsCount; ?>_required"<?= !empty($item['required']) ? ' checked' : ''; ?>>
                        Обязательный вопрос</label>
                    <label><input type="checkbox" name="Poll[question][<?= $questionsCount; ?>][type]"
                                  id="Poll_question_<?= $questionsCount; ?>_type"<?= !empty($item['type']) ? ' checked' : ''; ?>>
                        Множественный выбор</label>
                </div>
                <label>Ответы</label>
                <?php if (!empty($item['answer'])) : ?>
                    <?php foreach ($item['answer'] as $sub) : ?>
                        <?php $answersCount++; ?>
                        <div class="form-group poll-answer" data-id="<?= $answersCount; ?>">
                            <a class="pull-right answer-delete" href="javascript:;"><span
                                    class="glyphicon glyphicon-remove"></span></a>
                            <input type="hidden" value="<?= !empty($sub['id']) ? $sub['id'] : ''; ?>"
                                   name="Poll[question][<?= $questionsCount; ?>][answer][<?= $answersCount; ?>][id]"
                                   id="Poll_question_<?= $questionsCount; ?>_answer_<?= $answersCount; ?>_id">
                            <input type="text" value="<?= !empty($sub['title']) ? $sub['title'] : ''; ?>"
                                   class="form-control"
                                   name="Poll[question][<?= $questionsCount; ?>][answer][<?= $answersCount; ?>][title]"
                                   id="Poll_question_<?= $questionsCount; ?>_answer_<?= $answersCount; ?>_title"
                                   placeholder="Введите вариант ответа">
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <div class="form-group" class="fake-answer">
                    <div class="form-control fake-answer fake-input">Введите вариант ответа</div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <div id="fake-question">
        <div class="form-group">
            <label for="fake_question_title">Вопрос</label>

            <div class="form-control fake-input" id="fake_question">Введите вопрос</div>
        </div>
    </div>

    <button id="save-button" type="submit" data-questions-count="<?= $questionsCount; ?>"
            data-answers-count="<?= $answersCount; ?>" class="btn btn-default">Сохранить
    </button>
</form>