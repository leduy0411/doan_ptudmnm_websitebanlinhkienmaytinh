<?php
$pageTitle = $category ? htmlspecialchars($category['name']) : 'Tất cả sản phẩm';

// Khởi tạo các biến mặc định
$products = $products ?? [];
$total = $total ?? 0;
$totalPages = $totalPages ?? 1;
$page = $page ?? 1;
$categories = $categories ?? [];
$category = $category ?? null;
$sort = $sort ?? 'newest';
$minPrice = $minPrice ?? null;
$maxPrice = $maxPrice ?? null;

// Parse selected brands from URL
$selectedBrands = [];
if (!empty($_GET['brands'])) {
    $selectedBrands = explode(',', $_GET['brands']);
}

// Parse selected rating from URL
$selectedRating = !empty($_GET['rating']) ? (int)$_GET['rating'] : 0;

// Helper function to remove filter parameters
function removeFilterParam($params) {
    $url = $_SERVER['REQUEST_URI'];
    $parsed = parse_url($url);
    $query = [];
    if (isset($parsed['query'])) {
        parse_str($parsed['query'], $query);
    }
    
    if (is_array($params)) {
        foreach ($params as $param) {
            unset($query[$param]);
        }
    } else {
        unset($query[$params]);
    }
    
    // Remove page parameter
    unset($query['p']);
    
    $basePath = BASE_URL . 'products';
    if (empty($query)) {
        return $basePath;
    }
    return $basePath . '?' . http_build_query($query);
}

include __DIR__ . '/../layouts/header.php';
?>

<!-- Products Section -->
<section class="products-page py-5">
    <div class="container">
        <div class="row">
            <!-- Sidebar Filter -->
            <div class="col-lg-3">
                <div class="filter-sidebar">
                    <!-- Categories -->
                    <div class="filter-card">
                        <h5 class="filter-title">
                            <i class="fas fa-list"></i> Danh mục
                        </h5>
                        <div class="filter-body">
                            <ul class="category-list">
                                <li class="<?= !$category ? 'active' : '' ?>">
                                    <a href="<?= BASE_URL ?>products">Tất cả sản phẩm</a>
                                </li>
                                <?php foreach ($categories as $cat): ?>
                                    <li class="<?= ($category && $category['id'] == $cat['id']) ? 'active' : '' ?>">
                                        <a href="<?= BASE_URL ?>products?category=<?= $cat['id'] ?>">
                                            <?= htmlspecialchars($cat['name']) ?>
                                            <?php if (!empty($cat['product_count'])): ?>
                                                <span class="count">(<?= $cat['product_count'] ?>)</span>
                                            <?php endif; ?>
                                        </a>
                                        <?php if (!empty($cat['children'])): ?>
                                            <ul class="sub-category-list">
                                                <?php foreach ($cat['children'] as $child): ?>
                                                    <li>
                                                        <a href="<?= BASE_URL ?>products?category=<?= $child['id'] ?>">
                                                            <?= htmlspecialchars($child['name']) ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Price Filter -->
                    <div class="filter-card">
                        <h5 class="filter-title">
                            <i class="fas fa-dollar-sign"></i> Khoảng giá
                        </h5>
                        <div class="filter-body">
                            <form id="priceFilterForm">
                                <div class="price-range">
                                    <input type="number" name="min_price" class="form-control" 
                                           placeholder="Từ" value="<?= $minPrice ?? '' ?>">
                                    <span>-</span>
                                    <input type="number" name="max_price" class="form-control" 
                                           placeholder="Đến" value="<?= $maxPrice ?? '' ?>">
                                </div>
                                <button type="submit" class="btn btn-filter-apply w-100 mt-3">
                                    Áp dụng
                                </button>
                            </form>
                            <div class="price-quick mt-3">
                                <button type="button" class="price-btn" data-min="0" data-max="5000000">
                                    Dưới 5 triệu
                                </button>
                                <button type="button" class="price-btn" data-min="5000000" data-max="10000000">
                                    5 - 10 triệu
                                </button>
                                <button type="button" class="price-btn" data-min="10000000" data-max="20000000">
                                    10 - 20 triệu
                                </button>
                                <button type="button" class="price-btn" data-min="20000000" data-max="">
                                    Trên 20 triệu
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Brand Filter -->
                    <div class="filter-card">
                        <h5 class="filter-title">
                            <i class="fas fa-tag"></i> Thương hiệu
                        </h5>
                        <div class="filter-body">
                            <div class="brand-list">
                                <?php 
                                $brandList = $brands ?? [];
                                if (empty($brandList)):
                                ?>
                                    <p class="text-muted small">Chưa có thương hiệu</p>
                                <?php else:
                                foreach ($brandList as $brand): 
                                    $brandName = is_array($brand) ? ($brand['name'] ?? '') : $brand;
                                    $brandId = is_array($brand) ? (string)($brand['id'] ?? '') : '';
                                    $productCount = is_array($brand) ? ($brand['product_count'] ?? 0) : 0;
                                    if (empty($brandName)) continue;
                                    $checkValue = $brandId ?: $brandName;
                                    $isChecked = in_array($checkValue, $selectedBrands) || in_array($brandName, $selectedBrands) || in_array((string)$brandId, $selectedBrands);
                                ?>
                                    <label class="brand-checkbox">
                                        <input type="checkbox" name="brands[]" 
                                               value="<?= htmlspecialchars($checkValue) ?>"
                                               <?= $isChecked ? 'checked' : '' ?>>
                                        <span><?= htmlspecialchars($brandName) ?></span>
                                        <?php if ($productCount > 0): ?>
                                            <small class="text-muted">(<?= $productCount ?>)</small>
                                        <?php endif; ?>
                                    </label>
                                <?php endforeach; 
                                endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Rating Filter -->
                    <div class="filter-card">
                        <h5 class="filter-title">
                            <i class="fas fa-star"></i> Đánh giá
                        </h5>
                        <div class="filter-body">
                            <div class="rating-filter">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <label class="rating-checkbox">
                                        <input type="radio" name="rating" value="<?= $i ?>"
                                               <?= ($selectedRating ?? 0) == $i ? 'checked' : '' ?>>
                                        <span class="stars">
                                            <?php for ($j = 1; $j <= 5; $j++): ?>
                                                <i class="fas fa-star <?= $j <= $i ? 'text-warning' : 'text-muted' ?>"></i>
                                            <?php endfor; ?>
                                        </span>
                                        <span class="rating-text">từ <?= $i ?> sao</span>
                                    </label>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Clear Filter -->
                    <?php
                    $hasFilters = !empty($minPrice) || !empty($maxPrice) || !empty($selectedBrands) || !empty($selectedRating) || !empty($category);
                    if ($hasFilters):
                    ?>
                    <a href="<?= BASE_URL ?>products" class="btn btn-clear-filter w-100">
                        <i class="fas fa-times"></i> Xóa bộ lọc
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9">
                <!-- Active Filters Display -->
                <?php if ($hasFilters): ?>
                <div class="active-filters mb-3">
                    <span class="filter-label">Đang lọc:</span>
                    <?php if (!empty($category)): ?>
                        <span class="filter-tag">
                            <?= htmlspecialchars($category['name']) ?>
                            <a href="<?= removeFilterParam('category') ?>"><i class="fas fa-times"></i></a>
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($minPrice) || !empty($maxPrice)): ?>
                        <span class="filter-tag">
                            Giá: <?= $minPrice ? number_format($minPrice) . 'đ' : '0' ?> - <?= $maxPrice ? number_format($maxPrice) . 'đ' : '∞' ?>
                            <a href="<?= removeFilterParam(['min_price', 'max_price']) ?>"><i class="fas fa-times"></i></a>
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($selectedBrands)): ?>
                        <span class="filter-tag">
                            Thương hiệu: <?= count($selectedBrands) ?> đã chọn
                            <a href="<?= removeFilterParam('brands') ?>"><i class="fas fa-times"></i></a>
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($selectedRating)): ?>
                        <span class="filter-tag">
                            Từ <?= $selectedRating ?> sao
                            <a href="<?= removeFilterParam('rating') ?>"><i class="fas fa-times"></i></a>
                        </span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <!-- Toolbar -->
                <div class="products-toolbar">
                    <div class="toolbar-left">
                        <h4 class="products-title"><?= $pageTitle ?></h4>
                        <p class="products-count">
                            Hiển thị <?= count($products) ?> / <?= $total ?> sản phẩm
                        </p>
                    </div>
                    <div class="toolbar-right">
                        <div class="view-mode">
                            <button type="button" class="view-btn active" data-view="grid">
                                <i class="fas fa-th-large"></i>
                            </button>
                            <button type="button" class="view-btn" data-view="list">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                        <div class="sort-dropdown">
                            <select class="form-select" id="sortSelect">
                                <option value="newest" <?= ($sort ?? '') == 'newest' ? 'selected' : '' ?>>
                                    Mới nhất
                                </option>
                                <option value="price_asc" <?= ($sort ?? '') == 'price_asc' ? 'selected' : '' ?>>
                                    Giá thấp đến cao
                                </option>
                                <option value="price_desc" <?= ($sort ?? '') == 'price_desc' ? 'selected' : '' ?>>
                                    Giá cao đến thấp
                                </option>
                                <option value="bestselling" <?= ($sort ?? '') == 'bestselling' ? 'selected' : '' ?>>
                                    Bán chạy nhất
                                </option>
                                <option value="rating" <?= ($sort ?? '') == 'rating' ? 'selected' : '' ?>>
                                    Đánh giá cao
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <?php if (empty($products)): ?>
                    <!-- Empty State -->
                    <div class="empty-products text-center py-5">
                        <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                        <h5>Không tìm thấy sản phẩm</h5>
                        <p class="text-muted">Thử thay đổi bộ lọc hoặc tìm kiếm với từ khóa khác</p>
                        <a href="<?= BASE_URL ?>products" class="btn btn-primary">
                            Xem tất cả sản phẩm
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Products Grid -->
                    <div class="products-grid" id="productsContainer">
                        <?php foreach ($products as $product): ?>
                            <?php include __DIR__ . '/../components/product-card.php'; ?>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php 
                    // Helper function cho pagination URL
                    if (!function_exists('buildPaginationUrl')) {
                        function buildPaginationUrl($pageNum) {
                            $params = $_GET;
                            $params['p'] = $pageNum;
                            unset($params['page']); // Remove old page param
                            return BASE_URL . 'products?' . http_build_query($params);
                        }
                    }
                    
                    if ($totalPages > 1): 
                    ?>
                        <nav class="products-pagination">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link" href="<?= buildPaginationUrl($page - 1) ?>">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                                
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                        <a class="page-link custom-page-link" href="<?= buildPaginationUrl($i) ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                                
                                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                    <a class="page-link custom-page-link" href="<?= buildPaginationUrl($page + 1) ?>">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php
function buildPaginationUrl($page) {
    $params = $_GET;
    $params['p'] = $page;
    return BASE_URL . '?' . http_build_query($params);
}
?>

<style>
/* Custom Pagination Fix */
.products-pagination .custom-page-link {
    width: 42px !important;
    height: 42px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    border-radius: 10px !important;
    margin: 0 3px !important;
    font-weight: 500 !important;
    color: #333 !important;
    background: #fff !important;
    border: 1px solid #e0e0e0 !important;
    opacity: 1 !important;
    visibility: visible !important;
}

.products-pagination .custom-page-link:hover {
    background: #f8f9fa !important;
    color: #2563eb !important;
    border-color: #2563eb !important;
    text-decoration: none !important;
}

.products-pagination .page-item.active .custom-page-link {
    background: #2563eb !important;
    color: #fff !important;
    border-color: #2563eb !important;
}

.products-pagination .page-item.disabled .custom-page-link {
    opacity: 0.5 !important;
    background: #f8f9fa !important;
    color: #999 !important;
    cursor: not-allowed !important;
}

/* Responsive */
@media (max-width: 991px) {
    .filter-sidebar {
        display: none;
    }
    
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .products-grid {
        grid-template-columns: 1fr;
    }
    
    .products-toolbar {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sort select
    document.getElementById('sortSelect').addEventListener('change', function() {
        const url = new URL(window.location.href);
        url.searchParams.set('sort', this.value);
        url.searchParams.delete('p');
        window.location.href = url.toString();
    });
    
    // Price quick buttons
    document.querySelectorAll('.price-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const min = this.dataset.min;
            const max = this.dataset.max;
            document.querySelector('input[name="min_price"]').value = min;
            document.querySelector('input[name="max_price"]').value = max;
            document.getElementById('priceFilterForm').dispatchEvent(new Event('submit'));
        });
    });
    
    // Price filter form
    document.getElementById('priceFilterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const url = new URL(window.location.href);
        const min = document.querySelector('input[name="min_price"]').value;
        const max = document.querySelector('input[name="max_price"]').value;
        
        if (min) url.searchParams.set('min_price', min);
        else url.searchParams.delete('min_price');
        
        if (max) url.searchParams.set('max_price', max);
        else url.searchParams.delete('max_price');
        
        url.searchParams.delete('p');
        window.location.href = url.toString();
    });
    
    // View mode toggle
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const container = document.getElementById('productsContainer');
            if (this.dataset.view === 'list') {
                container.classList.add('list-view');
            } else {
                container.classList.remove('list-view');
            }
        });
    });
    
    // Brand checkboxes - apply filter immediately when changed
    document.querySelectorAll('.brand-checkbox input').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            applyBrandFilter();
        });
    });
    
    function applyBrandFilter() {
        const url = new URL(window.location.href);
        const brands = [];
        document.querySelectorAll('.brand-checkbox input:checked').forEach(cb => {
            brands.push(cb.value);
        });
        
        if (brands.length > 0) {
            url.searchParams.set('brands', brands.join(','));
        } else {
            url.searchParams.delete('brands');
        }
        
        url.searchParams.delete('p');
        window.location.href = url.toString();
    }
    
    // Rating filter - click again to remove
    let currentRating = <?= $selectedRating ?>;
    document.querySelectorAll('.rating-checkbox input').forEach(radio => {
        radio.addEventListener('click', function(e) {
            const url = new URL(window.location.href);
            const clickedValue = parseInt(this.value);
            
            if (clickedValue === currentRating) {
                // Clicking same value - remove filter
                url.searchParams.delete('rating');
                this.checked = false;
            } else {
                // New value - apply filter
                url.searchParams.set('rating', this.value);
            }
            
            url.searchParams.delete('p');
            window.location.href = url.toString();
        });
    });
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>


