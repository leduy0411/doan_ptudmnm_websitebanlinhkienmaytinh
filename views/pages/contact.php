<?php
/**
 * Contact Page - TechShop
 */

$pageTitle = 'Liên hệ - ' . SITE_NAME;
include __DIR__ . '/../layouts/header.php';
?>

<!-- Hero Section -->
<section class="contact-hero py-5 bg-light">
    <div class="container">
        <div class="text-center">
            <h1 class="fw-bold mb-3">Liên hệ với chúng tôi</h1>
            <p class="text-muted lead">
                Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn
            </p>
        </div>
    </div>
</section>

<!-- Contact Info & Form -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Contact Info -->
            <div class="col-lg-5">
                <h3 class="fw-bold mb-4">Thông tin liên hệ</h3>
                
                <div class="contact-item d-flex mb-4">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle me-3 flex-shrink-0" 
                         style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-map-marker-alt fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Địa chỉ</h5>
                        <p class="text-muted mb-0">
                            126 Nguyễn Thiện Thành<br>
                            Phường 5, Trà Vinh, Việt Nam
                        </p>
                    </div>
                </div>
                
                <div class="contact-item d-flex mb-4">
                    <div class="icon-box bg-success bg-opacity-10 text-success rounded-circle me-3 flex-shrink-0" 
                         style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-phone-alt fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Số điện thoại</h5>
                        <p class="text-muted mb-0">
                            <a href="tel:0348137209" class="text-decoration-none">0348 137 209</a>
                        </p>
                    </div>
                </div>
                
                <div class="contact-item d-flex mb-4">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-circle me-3 flex-shrink-0" 
                         style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-envelope fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Email</h5>
                        <p class="text-muted mb-0">
                            <a href="mailto:leduytctv2019@gmail.com" class="text-decoration-none">leduytctv2019@gmail.com</a>
                        </p>
                    </div>
                </div>
                
                <div class="contact-item d-flex mb-4">
                    <div class="icon-box bg-info bg-opacity-10 text-info rounded-circle me-3 flex-shrink-0" 
                         style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Giờ làm việc</h5>
                        <p class="text-muted mb-1">
                            Thứ 2 - Thứ 6: 8:00 - 21:00
                        </p>
                        <p class="text-muted mb-0">
                            Thứ 7 - Chủ nhật: 9:00 - 18:00
                        </p>
                    </div>
                </div>
                
                <!-- Social Links -->
                <div class="social-links mt-4">
                    <h5 class="fw-bold mb-3">Kết nối với chúng tôi</h5>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-primary rounded-circle" style="width: 45px; height: 45px;">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="btn btn-outline-danger rounded-circle" style="width: 45px; height: 45px;">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="#" class="btn btn-outline-dark rounded-circle" style="width: 45px; height: 45px;">
                            <i class="fab fa-tiktok"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-lg-5">
                        <h3 class="fw-bold mb-4">Gửi tin nhắn cho chúng tôi</h3>
                        
                        <form id="contactForm" action="/api/contact.php" method="POST">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="tel" name="phone" class="form-control">
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Chủ đề</label>
                                    <select name="subject" class="form-select">
                                        <option value="general">Câu hỏi chung</option>
                                        <option value="order">Hỏi về đơn hàng</option>
                                        <option value="product">Tư vấn sản phẩm</option>
                                        <option value="warranty">Bảo hành</option>
                                        <option value="complaint">Khiếu nại</option>
                                        <option value="partnership">Hợp tác kinh doanh</option>
                                    </select>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Nội dung <span class="text-danger">*</span></label>
                                    <textarea name="message" class="form-control" rows="5" required 
                                              placeholder="Nhập nội dung tin nhắn của bạn..."></textarea>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="fas fa-paper-plane me-2"></i>Gửi tin nhắn
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map -->
<section class="py-5 bg-light">
    <div class="container">
        <h3 class="fw-bold mb-4 text-center">Vị trí cửa hàng</h3>
        <div class="ratio ratio-21x9 rounded-4 overflow-hidden shadow-sm">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3929.4!2d106.3399!3d9.9347!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a0175c2e7b8b9d%3A0x6d6b6b6b6b6b6b6b!2zMTI2IE5ndXnhu4VuIFRoaeG7h24gVGjDoG5oLCBQaMaw4budbmcgNSwgVHLDoCBWaW5oLCBWaeG7h3QgTmFt!5e0!3m2!1svi!2s"
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h3 class="fw-bold">Câu hỏi thường gặp</h3>
            <p class="text-muted">Những thắc mắc phổ biến của khách hàng</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Tôi có thể đặt hàng online không?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Có, bạn hoàn toàn có thể đặt hàng online trên website của chúng tôi. 
                                Chúng tôi sẽ giao hàng đến địa chỉ của bạn trong thời gian sớm nhất.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Chính sách bảo hành như thế nào?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Tất cả sản phẩm tại TechShop đều được bảo hành chính hãng theo quy định của nhà sản xuất. 
                                Vui lòng liên hệ hotline để được hỗ trợ bảo hành.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Thời gian giao hàng mất bao lâu?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Thời gian giao hàng tùy thuộc vào địa chỉ của bạn, thông thường từ 2-5 ngày làm việc 
                                đối với các tỉnh thành trên toàn quốc.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                Tôi có thể thanh toán bằng những hình thức nào?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Chúng tôi hỗ trợ thanh toán khi nhận hàng (COD), chuyển khoản ngân hàng, 
                                và các ví điện tử phổ biến.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/api/contact.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Gửi thành công!',
                text: 'Chúng tôi sẽ phản hồi sớm nhất có thể.',
                confirmButtonColor: '#e63946'
            });
            this.reset();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: data.message || 'Có lỗi xảy ra, vui lòng thử lại.',
                confirmButtonColor: '#e63946'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: 'Có lỗi xảy ra, vui lòng thử lại.',
            confirmButtonColor: '#e63946'
        });
    });
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>


