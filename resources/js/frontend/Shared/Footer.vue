<template>
    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="text-primary mb-3">
                        <i class="fas fa-book-open me-2"></i>
                        English ⇄ Bangla Dictionary
                    </h5>
                    <p class="text-muted mb-3">
                        A comprehensive online dictionary to help you translate between English and Bangla languages with ease.
                    </p>
                </div>
                <div class="col-md-6">
                    <h6 class="mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="/about" class="text-light text-decoration-none">About Dictionary</a></li>
                        <li><a href="/contact" class="text-light text-decoration-none">Contact & Feedback</a></li>
                        <li><a href="/privacy" class="text-light text-decoration-none">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-muted">
                        &copy; {{ currentYear }} English ⇄ Bangla Dictionary. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="social-icons">
                        <a href="#" class="text-light me-3" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-light me-3" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-light" title="GitHub">
                            <i class="fab fa-github"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>

<script>
export default {
    computed: {
        currentYear() {
            return new Date().getFullYear();
        }
    }
};
</script>

<style scoped>
footer a:hover {
    color: var(--bs-primary) !important;
    transition: color 0.3s ease;
}

.social-icons a {
    font-size: 1.2rem;
    transition: transform 0.3s ease;
}

.social-icons a:hover {
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .col-md-6:last-child {
        text-align: center !important;
        margin-top: 1rem;
    }
}
</style>
