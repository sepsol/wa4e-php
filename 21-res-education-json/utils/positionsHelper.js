
function postPositionRender(posId, MAX_POSITIONS) {
  if (posId > MAX_POSITIONS) {
    $('#add-position-button').addClass('disabled').prop('disabled', true);
    $('#add-position-button > span').addClass('text-muted');
  }
}


$('document').ready(function() {
  const prevCount = $('positions-container').children.length;
  let posId = 1 + prevCount;
  const MAX_POSITIONS = 9;

  $('#add-position-button').click(function () {
    if (posId > MAX_POSITIONS) return;
  
    $('#positions-container').append(`
      <div id="position-${posId}">
        <br>
        <div class="form-group">
          <label for="pos-year-${posId}-input" class="col-sm-2 control-label">Year:</label>
          <div class="col-sm-2">
            <input type="text" name="pos_year_${posId}" id="pos-year-${posId}-input" class="form-control">
          </div>
          <button type="button" class="btn btn-link" onclick="$('#position-${posId}').remove(); return false;">
            <span class="text-danger">
              <span class="glyphicon glyphicon-remove" style="position: static; bottom: 0; font-size: 0.8em;" aria-hidden="true"></span>
              Remove Position
            </span>
          </button>
        </div>
        <div class="form-group">
          <label for="pos-desc-${posId}-input" class="col-sm-2 control-label">Description:</label>
          <div class="col-sm-6">
            <textarea name="pos_desc_${posId}" id="pos-desc-${posId}-input" class="form-control" style="resize: vertical" rows="8" cols="80"></textarea>
          </div>
        </div>
      </div>
    `);
  
    posId++;
  
    postPositionRender(posId, MAX_POSITIONS); 
  });

  postPositionRender(posId, MAX_POSITIONS); 
});
