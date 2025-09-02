<form action="{{ route('admin.import.lang.json.process') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="file" class="form-label">{{ __('Upload Zip File') }}</label>
                    <input type="file" name="file" id="file" class="form-control" required>
                    <div class="text-xs mt-1">
                        <span
                            class="text-danger text-xs">{{ __('Import Zip file which you have downloaded from old version') }}</span><br>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{ __('Import') }}" class="btn btn-primary">
    </div>
</form>
