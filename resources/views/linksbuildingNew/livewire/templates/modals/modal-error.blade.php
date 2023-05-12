<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{__('Confirm delete')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p>{{__("The data doesn't exist, reload page and try again")}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger close-modal" data-dismiss="modal">{{__('OK')}}</button>
            </div>
        </div>
    </div>
</div>
