
function postEducationRender(eduId, MAX_EDUCATIONS) {
  $('.school').autocomplete({ source: 'school.php' });

  if (eduId > MAX_EDUCATIONS) {
    $('#add-education-button').addClass('disabled').prop('disabled', true);
    $('#add-education-button > span').addClass('text-muted');
  }
}

$('document').ready(function() {
  const prevCount = $('educaions-container').children.length;
  let eduId = 1 + prevCount;
  const MAX_EDUCATIONS = 9;

  $('#add-education-button').click(function () {
    if (eduId > MAX_EDUCATIONS) return;
  
    $('#educations-container').append(`
      <div id="education-${eduId}">
        <br>
        <div class="form-group">
          <label for="edu-year-${eduId}-input" class="col-sm-2 control-label">Year:</label>
          <div class="col-sm-2">
            <input type="text" name="edu_year_${eduId}" id="edu-year-${eduId}-input" class="form-control">
          </div>
          <button type="button" class="btn btn-link" onclick="$('#education-${eduId}').remove(); return false;">
            <span class="text-danger">
              <span class="glyphicon glyphicon-remove" style="position: static; bottom: 0; font-size: 0.8em;" aria-hidden="true"></span>
              Remove Education
            </span>
          </button>
        </div>
        <div class="form-group">
          <label for="edu-school-${eduId}-input" class="col-sm-2 control-label">School:</label>
          <div class="col-sm-6">
            <input type="text" name="edu_school_${eduId}" id="edu-school-${eduId}-input" class="form-control school">
          </div>
        </div>
      </div>
    `);

    eduId++;
  
    postEducationRender(eduId, MAX_EDUCATIONS);
  });

  postEducationRender(eduId, MAX_EDUCATIONS);
});
