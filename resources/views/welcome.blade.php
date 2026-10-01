<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#f4f1ea">
        <title>Paperlock | PDF protection API</title>
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body>
        <div class="site-shell">
            <nav class="topbar" aria-label="Primary navigation">
                <a class="brand" href="{{ url('/') }}" aria-label="Paperlock home">
                    <span class="brand-mark" aria-hidden="true"><span></span><span></span><span></span></span>
                    <span>paperlock</span>
                </a>
                <div class="nav-links">
                    <a href="#how-it-works">How it works</a>
                    <a href="#security">Security</a>
                    <a class="nav-button" href="#upload">Try the demo <span aria-hidden="true">↗</span></a>
                </div>
            </nav>

            <main>
                <section class="hero" aria-labelledby="hero-title">
                    <div class="hero-copy">
                        <p class="eyebrow"><span></span> PDF infrastructure, without the friction</p>
                        <h1 id="hero-title">Make every PDF<br><em>private by default.</em></h1>
                        <p class="hero-summary">A focused API for encrypting, protecting, and preparing documents for the real world. Built on battle-tested Ghostscript and qpdf.</p>
                        <div class="hero-actions">
                            <a class="button button-dark" href="#upload">Protect a PDF <span aria-hidden="true">→</span></a>
                            <a class="text-link" href="#api">Read the API docs <span aria-hidden="true">↗</span></a>
                        </div>
                        <div class="proof-row" id="security">
                            <span class="proof-icon" aria-hidden="true">✓</span>
                            <span>Files are processed securely and never stored longer than needed.</span>
                        </div>
                    </div>

                    <div class="hero-art" aria-label="Encrypted PDF preview" role="img">
                        <div class="orbit orbit-one"></div>
                        <div class="orbit orbit-two"></div>
                        <div class="document-card">
                            <div class="document-topline"><span>PDF / 001</span><span class="status-dot">encrypted</span></div>
                            <div class="document-page">
                                <div class="page-kicker">CONFIDENTIAL</div>
                                <div class="page-title">Your document.<br><span>Under lock.</span></div>
                                <div class="page-lines"><i></i><i></i><i></i><i></i></div>
                                <div class="page-seal" aria-hidden="true"><span>✦</span><small>PL</small></div>
                            </div>
                            <div class="document-footer"><span>256-bit AES</span><span>● protected</span></div>
                        </div>
                        <div class="floating-tag tag-engine"><span class="tag-dot"></span> qpdf engine</div>
                        <div class="floating-tag tag-lock"><span aria-hidden="true">⌁</span> locked</div>
                    </div>
                </section>

                <section class="upload-section" id="upload" aria-labelledby="upload-title">
                    <div class="section-heading">
                        <p class="eyebrow"><span></span> See it in action</p>
                        <h2 id="upload-title">Drop a PDF.<br><em>Get peace of mind.</em></h2>
                    </div>
                    <div class="upload-panel" id="drop-zone">
                        <input id="pdf-input" type="file" accept="application/pdf,.pdf" hidden>
                        <div class="upload-icon" aria-hidden="true">↑</div>
                        <p class="upload-title">Drop your PDF here</p>
                        <p class="upload-subtitle">or <button type="button" id="browse-button">browse your files</button></p>
                        <p class="upload-note">PDF only <span>·</span> up to 25 MB</p>
                        <p class="upload-result" id="upload-result" aria-live="polite"></p>
                    </div>
                </section>

                <section class="capabilities" id="how-it-works" aria-label="Platform capabilities">
                    <div><span class="capability-number">01</span><strong>Upload once</strong><p>Send a document to one clean endpoint.</p></div>
                    <div><span class="capability-number">02</span><strong>Protect deeply</strong><p>Encryption and permissions handled server-side.</p></div>
                    <div><span class="capability-number">03</span><strong>Ship confidently</strong><p>Receive a ready-to-share PDF in seconds.</p></div>
                </section>
            </main>

            <footer class="footer" id="api">
                <span>paperlock / document security infrastructure</span>
                <span>Powered by <strong>Ghostscript</strong> + <strong>qpdf</strong></span>
            </footer>
        </div>
    </body>
</html>