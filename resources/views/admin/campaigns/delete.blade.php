<!-- Delete Modal -->
<div class="modal fade" id="deleteModal{{ $campaign->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Delete Campaign</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="fas fa-exclamation-circle text-warning" style="font-size: 3rem;"></i>
                </div>
                <h5 class="mb-3">Are you sure you want to delete this campaign?</h5>
                <p class="text-muted mb-0">Campaign: <strong>{{ $campaign->title }}</strong></p>
                <p class="text-muted">This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('campaigns.destroy', $campaign->id) }}" 
                      method="POST" 
                      class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        Delete Permanently
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.modal-content {
    border: none;
    border-radius: 15px;
}
.modal-header {
    padding: 1.5rem 1.5rem 0.5rem;
}
.modal-body {
    padding: 1.5rem;
}
.modal-footer {
    padding: 0.5rem 1.5rem 1.5rem;
}
.btn-close {
    background-size: 0.8em;
}
</style>
