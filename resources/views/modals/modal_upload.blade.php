<!-- Modal Upload-->
<?php
	$ts = time();
	$user_id = Auth::user()->id;
	$date = date("Y-m-d");
?>
<div class="modal fade" id="uploaderModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="uploadModalLabel">{{ __('Upload file') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
				<div class="form-group row">
					<h5>{{ __('Drag and drop multipe files') }}</h5>
				</div>

      	<div id="uploaderHolder">
	      	<form action="{{ route('file-upload') }}"
	              class="dropzone"
	              id="datanodeupload">

	            <input type="file" name="file"  style="display: none;">
	            <input type="hidden" name="dataTS" id="dataTS" value="{{ $ts }}">
	            <input type="hidden" name="dataDATE" id="dataDATE" value="{{ $date }}">
	            @csrf
	        </form>
	    </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onClick="window.location.reload();">{{ __('Done') }}</button>
      </div>
    </div>
  </div>
</div>
