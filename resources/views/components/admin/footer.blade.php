<footer class="admin-footer bg-white mt-auto py-3 border-top">
    <div class="container-fluid">
        <div class="row align-items-center">
            <!-- Copyright info -->
            <div class="col-lg-6 mb-2 mb-lg-0">
                <div class="text-muted">
                    &copy; {{ date('Y') }} {{ config('app.name') }}
                </div>
                <div class="small text-muted">
                    {{-- <a href="#" class="text-decoration-none">Privacy Policy</a> | <a href="#" class="text-decoration-none">Terms of Service</a> --}}
                </div>
            </div>
            
            <!-- Quick links or stats -->
            <div class="col-lg-6">
                <div class="d-flex flex-wrap justify-content-center justify-content-lg-end gap-3">
                    <div class="text-center px-3">
                        <div class="h5 mb-0">{{ $userCount ?? 0 }}</div>
                        <div class="small text-muted">Users</div>
                    </div>
                    <div class="text-center px-3">
                        <div class="h5 mb-0">{{ $productCount ?? 0 }}</div>
                        <div class="small text-muted">Products</div>
                    </div>
                    {{-- <div class="text-center px-3">
                        <div class="h5 mb-0">{{ $transactionCount ?? 0 }}</div>
                        <div class="small text-muted">Transactions</div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Back to top button -->
    <button class="btn btn-primary btn-sm rounded-circle shadow back-to-top" id="backToTop" title="Back to top">
        <i class="fas fa-arrow-up"></i>
    </button>
</footer>

<style>
    .admin-footer {
        font-size: 0.9rem;
    }
    
    .back-to-top {
        position: fixed;
        bottom: 25px;
        right: 25px;
        display: none;
        width: 40px;
        height: 40px;
        line-height: 40px;
        padding: 0;
        z-index: 999;
    }
</style>

<script>
    // Show back to top button when user scrolls down
    window.addEventListener('scroll', function() {
        const backToTopBtn = document.getElementById('backToTop');
        if (window.pageYOffset > 300) {
            backToTopBtn.style.display = 'block';
        } else {
            backToTopBtn.style.display = 'none';
        }
    });
    
    // Scroll to top when button is clicked
    document.getElementById('backToTop').addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
</script>
