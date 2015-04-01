$(document).ready(function() {
  $(document).on('click', 'a.question-delete', function(e) {
    e.preventDefault();
    $(this).parent().parent().remove();
  });

  $(document).on('click', '.answer-delete', function(e) {
    e.preventDefault();
    $(this).parent().remove();
  });

  $(document).on('click', '.js-action', function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    var id = $(this).attr('rel');
    
    $('#form').attr('action', url);
    $('#data-id').val(id);
    $('#form').submit();
  });

  $(document).on('click', '#fake_question', function(e) {
    var questionId = parseInt($('#save-button').attr('data-questions-count'))+1;
    var structure = '\
      <div class="poll-question" id="poll-question-'+questionId+'" data-id="'+questionId+'">\
    <div class="form-group">\
      <label for="Poll_question_1_title">Вопрос</label>\
      <input type="hidden" name="Poll[question]['+questionId+'][id]" id="Poll_question_'+questionId+'_id">\
      <input type="text" class="form-control" name="Poll[question]['+questionId+'][title]" id="Poll_question_'+questionId+'_title" placeholder="Введите вопрос">\
    </div>\
    <div class="checkbox">\
      <a class="pull-right question-delete" href="javascript:;">Удалить вопрос</a>\
      <label><input type="checkbox" name="Poll[question]['+questionId+'][required]" id="Poll_question_'+questionId+'_required"> Обязательный вопрос</label>\
      <label><input type="checkbox" name="Poll[question]['+questionId+'][type]" id="Poll_question_'+questionId+'_type"> Множественный выбор</label>\
    </div>\
    <div class="form-group" class="fake-answer">\
      <div class="form-control fake-answer fake-input">Введите вариант ответа</div>\
    </div>\
  </div>';
  
    $('#fake-question').before(structure);
    $('#Poll_question_'+questionId+'_title').focus();
    $('#save-button').attr('data-questions-count', questionId);
  });

  $(document).on('click', '.fake-answer', function(e) {
    var questionId = parseInt($(this).parent().parent().attr('data-id'));
    var answerId = parseInt($('#save-button').attr('data-answers-count'))+1;
    var structure = '\
    <div class="form-group poll-answer" data-id="'+answerId+'">\
      <a class="pull-right answer-delete" href="javascript:;"><span class="glyphicon glyphicon-remove"></span></a>\
      <input type="hidden" name="Poll[question]['+questionId+'][answer]['+answerId+'][id]" id="Poll_question_'+questionId+'_answer_'+answerId+'_id">\
      <input type="text" class="form-control" name="Poll[question]['+questionId+'][answer]['+answerId+'][title]" id="Poll_question_'+questionId+'_answer_'+answerId+'_title" placeholder="Введите вариант ответа">\
    </div>';
  
    $(this).before(structure);
    $('#Poll_question_'+questionId+'_answer_'+answerId+'_title').focus();
    $('#save-button').attr('data-answers-count', answerId);
  });
});