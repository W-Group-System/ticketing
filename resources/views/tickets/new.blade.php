<div class="modal" id="new">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Add new ticket</h6>
            </div>
            <form method="POST" action="{{ url('tickets/store') }}" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            Viber Number / Skype Number : <span class="text-danger">*</span>
                            <input type="tel" name="viber_number" class="form-control input-sm" placeholder="Ex: +639123456789" value="{{ old('viber_number') }}">
                        </div>
                        <div class="col-lg-6">
                            Department :
                            <input type="hidden" name="department" value="{{ auth()->user()->department_id }}">
                            <input type="text" class="form-control input-sm" value="{{ auth()->user()->department->name }}" readonly>
                        </div>
                        <div class="col-lg-6 mb-2">
                            Title : <span class="text-danger">*</span>
                            <input type="text" name="title" class="form-control input-sm" value="{{ old('title') }}">
                        </div>
                        <div class="col-lg-12">
                            Concern : <span class="text-danger">*</span>
                            <textarea name="task" class="form-control input-sm" id="summernote">{{ old('task') }}</textarea>
                        </div>
                        {{-- <div class="col-lg-12">
                            Attachment : (optional)
                            <input type="file" name="attachment" class="form-control input-sm">
                        </div> --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>