<footer style="background-color: black; color: white;">
    <div class="footer-container">
        <div class="footer-grid">
            <!-- Left Column -->
            <div class="footer-left">
                <img src="{{ asset('images/spartavlogofooter.png') }}" alt="SPARTAV Logo" class="footer-logo" />
                <p class="footer-description">
                    SPARTAV adalah platform manajemen periklanan dan pemasaran digital yang memfasilitasi advertiser untuk
                    memperluas target market dengan memberdayakan pasukan netizen sebagai 'adsman' untuk melakukan kegiatan
                    branding, marketing, dan selling secara online.
                </p>
            </div>

            <!-- Right Column -->
            <div class="footer-right">
                <h2 class="footer-title">PT Sinergi Mitra Mediatama</h2>
                <div class="footer-info">
                    <div class="footer-item">
                        <i class="fa-regular fa-envelope"></i>
                        <a href="mailto:eov.eventrue@gmail.com" class="footer-link">eov.eventrue@gmail.com</a>
                    </div>
                    <div class="footer-item">
                        <i class="fa-brands fa-whatsapp"></i>
                        <a href="tel:08999950006" class="footer-link">08999950006</a>
                    </div>
                    <div class="footer-item">
                        <i class="fa-solid fa-location-dot"></i>
                        <span class="footer-text">Semarang, Indonesia</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    /* Footer Styling */
    .footer-container {
        max-width: 1200px;finisj
        margin: 0 auto;
        padding: 24px 48px;
    }

    .footer-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 48px;
        align-items: start;
    }

    /* Left Column */
    .footer-left {
        grid-column: span 1;
    }

    .footer-logo {
        height: 64px;
        object-fit: contain;
        display: block;
        margin-bottom: 16px;
    }

    .footer-description {
        color: rgba(255, 255, 255, 0.9);
        max-width: 64rem;
        font-size: 16px;
        line-height: 1.625;
    }

    /* Right Column */
    .footer-right {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .footer-title {
        font-size: 18px;
        font-weight: bold;
    }

    .footer-info {
        display: flex;
        flex-direction: column;
        gap: 12px;
        font-weight: bold;
    }

    .footer-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .footer-item i {
        font-size: 16px;
        color: white;
    }

    .footer-link {
        color: inherit;
        text-decoration: none;
        font-size: 14px;
    }

    .footer-link:hover {
        color: rgba(255, 255, 255, 0.8);
    }

    .footer-text {
        font-size: 14px;
    }

    /* Responsive */
    @media (min-width: 1024px) {
        .footer-grid {
            grid-template-columns: 2fr 1fr;
        }

        .footer-logo {
            height: 96px;
        }

        .footer-title {
            font-size: 20px;
        }

        .footer-link, .footer-text {
            font-size: 16px;
        }

        .footer-item i {
            font-size: 18px;
        }
    }
</style>

<!-- Font Awesome -->
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
