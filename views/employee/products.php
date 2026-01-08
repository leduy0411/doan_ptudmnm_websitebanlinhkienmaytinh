<?php
$pageTitle = 'Quản lý sản phẩm';
include __DIR__ . '/../layouts/admin-header.php';

// Variables are already set by controller:
// $products, $totalProducts, $totalPages, $categories
$search = $_GET['search'] ?? '';
$categoryFilter = $_GET['category'] ?? '';
$currentPage = isset($_GET['p']) ? (int) $_GET['p'] : 1;
?>

<div class="admin-content">
    <!-- Header Actions -->
    <div class="content-header">
        <div class="header-left">
            <h4>Quản lý sản phẩm</h4>
            <p>
                <?= $totalProducts ?> sản phẩm
            </p>
        </div>
        <div class="header-right">
            <a href="<?= BASE_URL ?>employee?page=product-add" class="btn btn-admin-primary">
                <i class="fas fa-plus"></i> Thêm sản phẩm
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="admin-card mb-4">
        <div class="card-body">
            <form id="filterForm" class="filter-form" method="GET">
                <input type="hidden" name="page" value="products">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="search" placeholder="Tìm kiếm sản phẩm..."
                            value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="category">
                            <option value="">Tất cả danh mục</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= $categoryFilter == $cat['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="status">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active" <?= ($statusFilter ?? '') == 'active' ? 'selected' : '' ?>>Đang bán
                            </option>
                            <option value="inactive" <?= ($statusFilter ?? '') == 'inactive' ? 'selected' : '' ?>>Ngừng bán
                            </option>
                            <option value="low_stock" <?= ($statusFilter ?? '') == 'low_stock' ? 'selected' : '' ?>>Sắp hết
                                hàng</option>
                            <option value="out_stock" <?= ($statusFilter ?? '') == 'out_stock' ? 'selected' : '' ?>>Hết
                                hàng</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="sort">
                            <option value="newest">Mới nhất</option>
                            <option value="oldest">Cũ nhất</option>
                            <option value="price_asc">Giá tăng dần</option>
                            <option value="price_desc">Giá giảm dần</option>
                            <option value="name_asc">Tên A-Z</option>
                            <option value="best_selling">Bán chạy nhất</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-admin-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="admin-card" style="margin-top: 20px;">
        <div class="card-body p-0">
            <?php if (empty($products)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Không có sản phẩm nào</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="admin-table" id="productsTable">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>SKU</th>
                                <th>Danh mục</th>
                                <th>Giá</th>
                                <th>Tồn kho</th>
                                <th>Trạng thái</th>
                                <th width="120">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td>
                                        <div class="product-cell">
                                            <?php
                                            $imgPath = $product['primary_image'] ?? '';
                                            $hasImage = !empty($imgPath);
                                            if ($hasImage && strpos($imgPath, 'http') !== 0) {
                                                $imgPath = BASE_URL . $imgPath;
                                            }
                                            ?>
                                            <?php if ($hasImage): ?>
                                                <img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($product['name']) ?>"
                                                    class="product-thumb"
                                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div class="product-thumb-placeholder" style="display:none;">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            <?php else: ?>
                                                <div class="product-thumb-placeholder">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div class="product-info">
                                                <a href="<?= BASE_URL ?>employee?page=product-edit&id=<?= $product['id'] ?>"
                                                    class="product-name">
                                                    <?= htmlspecialchars($product['name']) ?>
                                                </a>
                                                <span class="product-brand">
                                                    <?= htmlspecialchars($product['brand'] ?? '') ?>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($product['sku'] ?? 'N/A') ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($product['category_name'] ?? 'N/A') ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                                            <span class="price-sale">
                                                <?= formatPrice($product['sale_price']) ?>
                                            </span>
                                            <span class="price-original">
                                                <?= formatPrice($product['price']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="price-current">
                                                <?= formatPrice($product['price']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $stockClass = 'normal';
                                        if ($product['stock'] <= 0)
                                            $stockClass = 'out';
                                        elseif ($product['stock'] <= 10)
                                            $stockClass = 'low';
                                        ?>
                                        <span class="stock-badge <?= $stockClass ?>">
                                            <?= $product['stock'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="status-badge <?= ($product['status'] ?? 'active') == 'active' ? 'active' : 'inactive' ?>">
                                            <?= ($product['status'] ?? 'active') == 'active' ? 'Đang bán' : 'Ngừng bán' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?= BASE_URL ?>san-pham/<?= $product['slug'] ?>" class="btn-icon"
                                                title="Xem" target="_blank">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= BASE_URL ?>employee?page=product-edit&id=<?= $product['id'] ?>"
                                                class="btn-icon edit" title="Sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <nav class="admin-pagination">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                        <a class="page-link" href="?page=products&p=<?= $i ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<style>
    /* Content Header */
    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .content-header h4 {
        font-size: 24px;
        font-weight: 600;
        margin: 0;
    }

    .content-header p {
        color: #64748b;
        margin: 5px 0 0;
    }

    .header-right {
        display: flex;
        gap: 10px;
    }

    /* Filter Form */
    .filter-form .form-control,
    .filter-form .form-select {
        border-radius: 10px;
        padding: 10px 15px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        min-width: 0;
    }

    .filter-form .form-select {
        padding-right: 35px;
        background-position: right 10px center;
    }

    .filter-form .row {
        flex-wrap: nowrap;
    }

    .filter-form [class*="col-"] {
        flex-shrink: 1;
        min-width: 0;
    }

    /* Product Cell */
    .product-cell {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .product-cell img.product-thumb {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        object-fit: cover;
        background: #f1f5f9;
    }

    .product-thumb-placeholder {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 20px;
        flex-shrink: 0;
    }

    .product-info {
        display: flex;
        flex-direction: column;
    }

    .product-name {
        font-weight: 500;
        color: #1e293b;
        max-width: 250px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .product-name:hover {
        color: var(--admin-primary);
    }

    .product-brand {
        font-size: 12px;
        color: #94a3b8;
    }

    /* Price */
    .price-sale {
        font-weight: 600;
        color: #dc2626;
    }

    .price-original {
        font-size: 12px;
        color: #94a3b8;
        text-decoration: line-through;
        display: block;
    }

    .price-current {
        font-weight: 600;
        color: #1e293b;
    }

    /* Stock Badge */
    .stock-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
    }

    .stock-badge.normal {
        background: #d1fae5;
        color: #059669;
    }

    .stock-badge.low {
        background: #fef3c7;
        color: #d97706;
    }

    .stock-badge.out {
        background: #fee2e2;
        color: #dc2626;
    }

    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
    }

    .status-badge.active {
        background: #d1fae5;
        color: #059669;
    }

    .status-badge.inactive {
        background: #f1f5f9;
        color: #64748b;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #f1f5f9;
        color: #64748b;
        transition: all 0.3s;
    }

    .btn-icon:hover {
        background: var(--admin-primary);
        color: #fff;
    }

    .btn-icon.edit:hover {
        background: #10b981;
    }
</style>

<?php include __DIR__ . '/../layouts/admin-footer.php'; ?>