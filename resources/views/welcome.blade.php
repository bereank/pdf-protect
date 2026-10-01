<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#f4f1ea">
        <title>Paperlock | PDF protection API</title>
        <link rel="stylesheet" href="{{ asset('paperlock.css') }}">
        <link rel="stylesheet" href="{{ asset('api-docs.css') }}">
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
                    <a class="nav-button" href="#api">API reference <span aria-hidden="true">↗</span></a>
                </div>
            </nav>

            <main>
                <section class="hero" aria-labelledby="hero-title">
                    <div class="hero-copy">
                        <p class="eyebrow"><span></span> PDF infrastructure, without the friction</p>
                        <h1 id="hero-title">Make every PDF<br><em>private by default.</em></h1>
                        <p class="hero-summary">A focused API for encrypting, protecting, and preparing documents for the real world. Built on battle-tested Ghostscript and qpdf.</p>
                        <div class="hero-actions">
                            <a class="button button-dark" href="#api">Explore the endpoint <span aria-hidden="true">→</span></a>
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

                <section class="api-section" id="api" aria-labelledby="api-title">
                    <div class="section-heading">
                        <p class="eyebrow"><span></span> API reference</p>
                        <h2 id="api-title">One endpoint.<br><em>One protected PDF.</em></h2>
                    </div>
                    <div class="api-docs">
                        <div class="api-endpoint">
                            <span class="api-method">POST</span>
                            <code>/api/protect</code>
                        </div>
                        <p class="api-description">Upload a PDF to rasterize and encrypt it. The response body is the protected PDF, ready to save or stream to a viewer.</p>

                        <div class="api-block">
                            <h3>Request</h3>
                            <p>Send a <code>multipart/form-data</code> request.</p>
                            <div class="api-table-wrap">
                                <table class="api-table">
                                    <thead>
                                        <tr><th>Field</th><th>Type</th><th>Rules</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr><td><code>file</code></td><td>File</td><td>Required PDF, up to 20 MB</td></tr>
                                        <tr><td><code>resolution</code></td><td>Integer</td><td>Optional; 72–600 DPI, default 300</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="api-block">
                            <h3>Example</h3>
                            <pre class="api-code"><code>curl -X POST \
  -H "Accept: application/json" \
  -F "file=@@document.pdf;type=application/pdf" \
  -F "resolution=300" \
  --output protected.pdf \
  "{{ url('/api/protect') }}"</code></pre>
                        </div>

                        <div class="api-block">
                            <h3>Responses</h3>
                            <dl class="api-responses">
                                <div><dt><code>200</code> <span>application/pdf</span></dt><dd>Protected PDF bytes. Returned inline as <code>protected.pdf</code>; response is not cached.</dd></div>
                                <div><dt><code>422</code> <span>application/json</span></dt><dd>With <code>Accept: application/json</code>, request validation failures include a message and field errors.</dd></div>
                                <div><dt><code>500</code> <span>application/json</span></dt><dd>PDF processing failed: <code>{"message":"Unable to process PDF."}</code></dd></div>
                            </dl>
                        </div>

                        <p class="api-note">The PDF opens without a user password. Editing and content extraction are restricted; no owner password is returned.</p>
                    </div>
                </section>

                <section class="capabilities" id="how-it-works" aria-label="Platform capabilities">
                    <div><span class="capability-number">01</span><strong>Upload once</strong><p>Send a document to one clean endpoint.</p></div>
                    <div><span class="capability-number">02</span><strong>Protect deeply</strong><p>Encryption and permissions handled server-side.</p></div>
                    <div><span class="capability-number">03</span><strong>Ship confidently</strong><p>Receive a ready-to-share PDF in seconds.</p></div>
                </section>
            </main>

            <footer class="footer">
                <span>paperlock / document security infrastructure</span>
                <span>Powered by <strong>Ghostscript</strong> + <strong>qpdf</strong></span>
            </footer>
        </div>
    </body>
</html>