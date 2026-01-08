<?php
$pageTitle = $isEdit ? 'Chỉnh sửa sản phẩm' : 'Thêm sản phẩm mới';
include __DIR__ . '/../layouts/admin-header.php';
?>

<div class="admin-content">
    <!-- Header -->
    <div class="content-header">
        <div class="header-left">
            <a href="<?= BASE_URL ?>employee?page=products" class="btn btn-outline-secondary btn-sm me-3">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4><?= $pageTitle ?></h4>
                <p><?= $isEdit ? 'Cập nhật thông tin sản phẩm' : 'Tạo sản phẩm mới cho cửa hàng' ?></p>
            </div>
        </div>
        <div class="header-right">
            <button type="button" class="btn btn-outline-secondary me-2" id="previewBtn">
                <i class="fas fa-eye"></i> Xem trước
            </button>
            <button type="submit" form="productForm" class="btn btn-admin-primary">
                <i class="fas fa-save"></i> <?= $isEdit ? 'Cập nhật' : 'Thêm sản phẩm' ?>
            </button>
        </div>
    </div>

    <form id="productForm" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="<?= $isEdit ? 'update' : 'create' ?>">
        <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?= $product['id'] ?>">
        <?php endif; ?>
        
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Basic Info -->
                <div class="admin-card mb-4">
                    <div class="card-header">
                        <h6><i class="fas fa-info-circle me-2"></i>Thông tin cơ bản</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="productName"
                                       value="<?= htmlspecialchars($product['name'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">SKU (Mã sản phẩm)</label>
                                <input type="text" class="form-control" name="sku"
                                       value="<?= htmlspecialchars($product['sku'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Thương hiệu</label>
                                <select class="form-select" name="brand_id" id="brandSelect">
                                    <option value="">-- Chọn thương hiệu --</option>
                                    <?php 
                                    require_once __DIR__ . '/../../models/Brand.php';
                                    $brandModel = new Brand();
                                    $brandModel->ensureTable();
                                    $brands = $brandModel->getActive();
                                    foreach ($brands as $brand): 
                                    ?>
                                        <option value="<?= $brand['id'] ?>" 
                                            <?= ($product['brand_id'] ?? '') == $brand['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($brand['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Mô tả ngắn</label>
                                <textarea class="form-control" name="short_description" rows="2"><?= htmlspecialchars($product['short_description'] ?? '') ?></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Mô tả chi tiết</label>
                                <textarea class="form-control editor" name="description" id="description"><?= $product['description'] ?? '' ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Specifications -->
                <div class="admin-card mb-4">
                    <div class="card-header">
                        <h6><i class="fas fa-list-alt me-2"></i>Thông số kỹ thuật</h6>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control editor" name="specifications" id="specifications"><?= $product['specifications'] ?? '' ?></textarea>
                    </div>
                </div>

                <!-- Images -->
                <div class="admin-card mb-4">
                    <div class="card-header">
                        <h6><i class="fas fa-images me-2"></i>Hình ảnh sản phẩm</h6>
                    </div>
                    <div class="card-body">
                        <div class="image-upload-zone" id="imageUploadZone">
                            <input type="file" name="images[]" id="imageInput" multiple accept="image/*" hidden>
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Kéo thả hình ảnh vào đây hoặc <span>click để tải lên</span></p>
                            <small>PNG, JPG, JPEG - Tối đa 5MB mỗi ảnh</small>
                        </div>
                        
                        <div class="mt-3">
                            <label class="form-label">Hoặc nhập link ảnh</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="imageUrlInput" placeholder="https://example.com/image.jpg">
                                <button type="button" class="btn btn-outline-primary" id="addImageUrlBtn">
                                    <i class="fas fa-plus"></i> Thêm
                                </button>
                            </div>
                        </div>
                        
                        <div class="image-preview-grid" id="imagePreviewGrid">
                            <?php if (!empty($product['images'])): ?>
                                <?php foreach ($product['images'] as $index => $imageData): ?>
                                    <?php 
                                    $imageUrl = is_array($imageData) ? ($imageData['image_url'] ?? '') : $imageData;
                                    $isPrimary = is_array($imageData) ? ($imageData['is_primary'] ?? ($index === 0)) : ($index === 0);
                                    if (empty($imageUrl)) continue;
                                    ?>
                                    <div class="image-preview-item <?= $isPrimary ? 'main' : '' ?>" data-image="<?= htmlspecialchars($imageUrl) ?>">
                                        <img src="<?= (strpos($imageUrl, 'http') === 0) ? $imageUrl : BASE_URL . $imageUrl ?>" alt="">
                                        <div class="overlay">
                                            <button type="button" class="btn-set-main" title="Đặt làm ảnh chính">
                                                <i class="fas fa-star"></i>
                                            </button>
                                            <button type="button" class="btn-remove" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        <?php if ($isPrimary): ?>
                                            <span class="main-badge">Ảnh chính</span>
                                        <?php endif; ?>
                                        <input type="hidden" name="existing_images[]" value="<?= htmlspecialchars($imageUrl) ?>">
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Pricing -->
                <div class="admin-card mb-4">
                    <div class="card-header">
                        <h6><i class="fas fa-tag me-2"></i>Giá & Khuyến mãi</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Giá gốc <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="price" 
                                           value="<?= $product['price'] ?? '' ?>" required min="0">
                                    <span class="input-group-text">₫</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Giá khuyến mãi (nếu có)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="sale_price" 
                                           value="<?= $product['sale_price'] ?? '' ?>" min="0">
                                    <span class="input-group-text">₫</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Category & Status -->
                <div class="admin-card mb-4">
                    <div class="card-header">
                        <h6><i class="fas fa-folder me-2"></i>Danh mục & Trạng thái</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                                <select class="form-select" name="category_id" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>" 
                                            <?= ($product['category_id'] ?? '') == (string)$category['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Trạng thái</label>
                                <select class="form-select" name="status">
                                    <option value="active" <?= ($product['status'] ?? '') == 'active' ? 'selected' : '' ?>>Đang bán</option>
                                    <option value="inactive" <?= ($product['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>Ngừng bán</option>
                                    <option value="draft" <?= ($product['status'] ?? '') == 'draft' ? 'selected' : '' ?>>Bản nháp</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="featured" id="isFeatured" value="1"
                                           <?= !empty($product['featured']) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="isFeatured">Sản phẩm nổi bật</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory -->
                <div class="admin-card mb-4">
                    <div class="card-header">
                        <h6><i class="fas fa-warehouse me-2"></i>Kho hàng</h6>
                    </div>
                    <div class="card-body">
                        <div class="col-12">
                            <label class="form-label">Số lượng tồn kho</label>
                            <input type="number" class="form-control" name="stock" 
                                   value="<?= $product['stock'] ?? 0 ?>" min="0">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.image-upload-zone {
    border: 2px dashed #d1d5db;
    border-radius: 12px;
    padding: 40px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    margin-bottom: 20px;
}
.image-upload-zone:hover { border-color: #e53935; background: #fef2f2; }
.image-upload-zone i { font-size: 48px; color: #9ca3af; margin-bottom: 15px; }
.image-upload-zone p { margin: 0; color: #6b7280; }
.image-upload-zone span { color: #e53935; text-decoration: underline; }
.image-preview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
    margin-top: 15px;
}
.image-preview-item {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    aspect-ratio: 1;
}
.image-preview-item img { width: 100%; height: 100%; object-fit: cover; }
.image-preview-item .overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    opacity: 0;
    transition: opacity 0.3s;
}
.image-preview-item:hover .overlay { opacity: 1; }
.image-preview-item .overlay button {
    width: 36px; height: 36px;
    border-radius: 50%;
    border: none;
    color: #fff;
    cursor: pointer;
}
.image-preview-item .overlay .btn-set-main { background: #f59e0b; }
.image-preview-item .overlay .btn-remove { background: #dc2626; }
.image-preview-item .main-badge {
    position: absolute;
    top: 10px; left: 10px;
    background: #e53935;
    color: #fff;
    font-size: 11px;
    padding: 3px 8px;
    border-radius: 4px;
}
.image-preview-item.main { border: 3px solid #e53935; }
.editor { min-height: 300px; }
</style>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let descriptionEditor = null;
    let specificationsEditor = null;
    
    // Preview button
    document.getElementById('previewBtn').addEventListener('click', function() {
        <?php if ($isEdit && !empty($product['slug'])): ?>
        window.open('<?= BASE_URL ?>product/<?= $product['slug'] ?>', '_blank');
        <?php else: ?>
        Swal.fire({
            icon: 'info',
            title: 'Chưa thể xem trước',
            text: 'Vui lòng lưu sản phẩm trước khi xem trước.',
            confirmButtonText: 'Đã hiểu'
        });
        <?php endif; ?>
    });
    
    // CKEditor for description & specifications
    ClassicEditor.create(document.querySelector('#description'), {
        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable', 'undo', 'redo']
    }).then(editor => { descriptionEditor = editor; });
    
    ClassicEditor.create(document.querySelector('#specifications'), {
        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable', 'undo', 'redo']
    }).then(editor => { specificationsEditor = editor; });
    
    // Image upload zone
    const uploadZone = document.getElementById('imageUploadZone');
    const imageInput = document.getElementById('imageInput');
    const previewGrid = document.getElementById('imagePreviewGrid');
    
    uploadZone.addEventListener('click', () => imageInput.click());
    uploadZone.addEventListener('dragover', (e) => { e.preventDefault(); uploadZone.classList.add('dragover'); });
    uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('dragover'));
    uploadZone.addEventListener('drop', (e) => { e.preventDefault(); uploadZone.classList.remove('dragover'); handleFiles(e.dataTransfer.files); });
    imageInput.addEventListener('change', () => handleFiles(imageInput.files));
    
    function handleFiles(files) {
        Array.from(files).forEach(file => {
            if (!file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                const div = document.createElement('div');
                div.className = 'image-preview-item';
                div.innerHTML = `
                    <img src="${e.target.result}">
                    <div class="overlay">
                        <button type="button" class="btn-set-main" title="Đặt làm ảnh chính"><i class="fas fa-star"></i></button>
                        <button type="button" class="btn-remove" title="Xóa"><i class="fas fa-trash"></i></button>
                    </div>
                `;
                previewGrid.appendChild(div);
                bindImageEvents(div);
            };
            reader.readAsDataURL(file);
        });
    }
    
    function bindImageEvents(item) {
        item.querySelector('.btn-set-main').addEventListener('click', function() {
            document.querySelectorAll('.image-preview-item').forEach(i => {
                i.classList.remove('main');
                i.querySelector('.main-badge')?.remove();
            });
            item.classList.add('main');
            const badge = document.createElement('span');
            badge.className = 'main-badge';
            badge.textContent = 'Ảnh chính';
            item.appendChild(badge);
        });
        item.querySelector('.btn-remove').addEventListener('click', () => item.remove());
    }
    
    document.querySelectorAll('.image-preview-item').forEach(bindImageEvents);
    
    // Add image from URL
    document.getElementById('addImageUrlBtn').addEventListener('click', function() {
        const url = document.getElementById('imageUrlInput').value.trim();
        if (!url || !url.match(/^https?:\/\/.+/i)) {
            Swal.fire('Lỗi', 'URL không hợp lệ', 'error');
            return;
        }
        const div = document.createElement('div');
        div.className = 'image-preview-item';
        div.innerHTML = `
            <img src="${url}" onerror="this.src='<?= ASSETS_URL ?>/images/no-image.png'">
            <div class="overlay">
                <button type="button" class="btn-set-main"><i class="fas fa-star"></i></button>
                <button type="button" class="btn-remove"><i class="fas fa-trash"></i></button>
            </div>
            <input type="hidden" name="image_urls[]" value="${url}">
        `;
        previewGrid.appendChild(div);
        bindImageEvents(div);
        document.getElementById('imageUrlInput').value = '';
    });
    
    // Form submit
    document.getElementById('productForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        if (descriptionEditor) formData.set('description', descriptionEditor.getData());
        if (specificationsEditor) formData.set('specifications', specificationsEditor.getData());
        
        const imageItems = document.querySelectorAll('.image-preview-item');
        const mainIndex = Array.from(imageItems).findIndex(item => item.classList.contains('main'));
        formData.append('main_image_index', mainIndex >= 0 ? mainIndex : 0);
        
        Swal.fire({ title: 'Đang xử lý...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        
        fetch('<?= BASE_URL ?>api/admin/products.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(text => {
            try {
                const data = JSON.parse(text);
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        text: '<?= $isEdit ? "Đã cập nhật sản phẩm" : "Đã thêm sản phẩm mới" ?>',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = '<?= BASE_URL ?>employee?page=products';
                    });
                } else {
                    Swal.fire('Lỗi', data.message || 'Có lỗi xảy ra', 'error');
                }
            } catch(e) {
                Swal.fire('Lỗi', 'Server trả về dữ liệu không hợp lệ', 'error');
            }
        })
        .catch(error => Swal.fire('Lỗi', 'Không thể kết nối đến server', 'error'));
    });
});
</script>

<?php include __DIR__ . '/../layouts/admin-footer.php'; ?>
