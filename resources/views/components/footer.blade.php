<div class="marquee-container">
    <marquee scrollamount="30" behavior="scroll" direction="right">
        {{$movingSentence}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        {{$movingSentence}}
    </marquee>
</div>
<footer>
    <section class="left-footer">
        <a href="/"><img src="/img/white-logo.svg" alt="Logo"></a>
        <div class="social-media">
            <a href="https://www.tiktok.com/@hayekgamingground?_t=ZS-8wv3bWKbLqz&_r=1" traget="_blank"><img
                    src="/img/tiktok.svg" alt="Tiktok" loading="lazy"> <span class="page-name">@hayekgaming</span></a>
            <a href="https://www.instagram.com/hayekgaming?igsh=MW1jaG53eTM2dGZ4NQ==" traget="_blank"><img
                    src="/img/insta.svg" alt="Instagram" loading="lazy"><span class="page-name">@hayekgaming</span></a>
            <a href="https://www.facebook.com/share/1CF7SLfHAN/" traget="_blank"><img src="/img/fb.svg" alt="Facebook"
                    loading="lazy"><span class="page-name">@hayekgaming</span></a>
        </div>
    </section>
    <section class="quick-links">
        <h3>Quick Links</h3>
        <ul>
            <a href="/">
                <li>Home</li>
            </a>
            @foreach ($categories as $category)
            <a href="/products/{{$category->id}}">
                <li>{{$category->name}}</li>
            </a>

            @endforeach
            <a href="/watches">
                <li>Watches</li>
            </a>
            <a href="/bracelets">
                <li>Bracelets</li>
            </a>


        </ul>
    </section>
    <section class="contact">
        <h3>Get in touch</h3>
        <ul>
            <a href="mailto:hgg@hayekgaming.com" target="_blank">
                <li><img src="/img/sms.webp" loading="lazy" /> hgg@hayekgaming.com</li>
            </a>
            <a href="https://wa.me/96178904703" target="_blank">
                <li><img src="/img/call.webp" loading="lazy" /> +961 78 904 703</li>
            </a>
        </ul>
    </section>

</footer>
<div style="width:100%;background-color:#2a2670; text-align:center;" class="poweredBy">
    <p class="small-text">
        Powered By
        <a target="_blank" href="https://brndnglb.com" style="text-decoration:none;">
            <span class="medium-text">
                Brndng
            </span>
            <span class="large-text">
                .
            </span>
        </a>
    </p>
</div>
<script type="application/ld+json">
    {
  "@context": "https://schema.org/",
  "@type": "Store",
  "name": "hayek gaming ground",
    "description": "Hayek Gaming is Lebanon’s leading gaming store offering consoles, accessories, and gear for all gamers.",
  "url": "https://www.hayekgaming.com",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "https://www.hayekgaming.com/searc/products?search={search_term_string}",
    "query-input": "required name=search_term_string"
  },
  "sameAs": [
    "https://www.facebook.com/share/1CF7SLfHAN/",
    "https://www.instagram.com/hayekgaming?igsh=MW1jaG53eTM2dGZ4NQ==",
    "https://www.tiktok.com/@hayekgamingground?_t=ZS-8wv3bWKbLqz&_r=1"
    ],"contactPoint": {
                    "@type": "ContactPoint",
                    "telephone": "+96178904703",
                    "contactType": "Customer Service"
                },
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": "Street Address, Beirut",
                    "addressLocality": "Beirut",
                    "addressCountry": "Lebanon"
                }
}
</script>