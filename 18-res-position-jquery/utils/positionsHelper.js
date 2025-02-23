
$('document').ready(function() {
  const prevCount = $('positions-container').children.length;

  let posId = prevCount + 1;
  const MAX_POSITIONS = 9 - prevCount;

  $('#add-position-button').click(function () {
    if (posId > MAX_POSITIONS) return;
  
    $('#positions-container').append(`
      <div id="position-${posId}">
        <br>
        <div class="form-group">
          <label for="year-${posId}-input" class="col-sm-2 control-label">Year:</label>
          <div class="col-sm-2">
            <input type="text" name="year${posId}" id="year-${posId}-input" class="form-control">
          </div>
          <button type="button" class="btn btn-link" onclick="$('#position-${posId}').remove(); return false;">
            <span class="text-danger">
              <span class="glyphicon glyphicon-remove" style="position: static; bottom: 0; font-size: 0.8em;" aria-hidden="true"></span>
              Remove Position
            </span>
          </button>
        </div>
        <div class="form-group" id="position-${posId}">
          <label for="desc-${posId}-input" class="col-sm-2 control-label">Description:</label>
          <div class="col-sm-6">
            <textarea name="desc${posId}" id="desc-${posId}-input" class="form-control" rows="8" cols="80"></textarea>
          </div>
        </div>
      </div>
    `);
  
    posId++;
  
    if (posId > MAX_POSITIONS) {
      $('#add-position-button').addClass('disabled').prop('disabled', true);
      $('#add-position-button > span').addClass('text-muted');
    }
  });
})
