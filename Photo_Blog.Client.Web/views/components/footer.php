<div class="modal fade" id="editPostModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title">Edit Post</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="text-center mb-3">
            <img id="editPreview" 
                 src="https://via.placeholder.com/400x400?text=No+Image" 
                 alt="Preview" 
                 class="img-fluid rounded mb-2"
                 style="max-height: 300px; object-fit: contain;">
            <input type="file" id="imageInputEdit" accept="image/*" style="display: none;">
            <button type="button" class="btn btn-primary" id="selectImageButton">
                Select Image
            </button>
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea id="editDescription" class="form-control bg-secondary text-white" rows="3" required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="saveEditBtn">Save Changes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="deletePostModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this post?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
      </div>
    </div>
  </div>
</div>


<footer class="mt-auto">
    <div class="text-bg-dark p-3 text-center">
        &copy; Copyright <?= date('Y') ?>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
<script src="assets/js/scripts.js"></script>
</body>
</html>
