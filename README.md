# F&B Business Audit Landing Page

**Live URL:** `https://bizbuddyhq.com/business-audit/`  
**Tech:** Static HTML + Tailwind CSS v3 + Vanilla JS  
**Owner:** Najmi Latif — BizBuddy Agency

---

## Local Development

```bash
# Install dependencies (first time only)
npm install

# Watch + rebuild CSS as you edit index.html
node_modules/.bin/tailwindcss -i ./src/input.css -o ./assets/css/styles.css --watch

# Production build (minified)
node_modules/.bin/tailwindcss -i ./src/input.css -o ./assets/css/styles.css --minify
```

Open `index.html` directly in a browser — no server needed.

---

## Deploy to cPanel

1. Run production Tailwind build (minified command above)
2. Compress the entire `/business-audit/` folder to a `.zip`
3. cPanel File Manager → navigate to `public_html/`
4. Upload zip → Extract Here → result: `public_html/business-audit/`
5. Test at `https://bizbuddyhq.com/business-audit/`

---

## Content Updates

### WhatsApp number
Search & replace `60123456789` with your actual number in `index.html`.  
It appears in every CTA button href.

### Copy / pricing
Edit directly in `index.html` — all copy is inline.

### Brand colors
Edit `tailwind.config.js` → run production build again.

### Ebook link
Search for `href="#"` near "Sistem F&B Ebook" in index.html and replace `#` with your actual ebook funnel URL.

### Najmi's portrait photo
Replace `assets/img/najmi.jpg` (create this file — editorial portrait, dark bg, warm lighting).  
Update the `<img>` tag in Section 06 — currently shows a styled placeholder div.

---

## Images Needed

| File | Size | Notes |
|------|------|-------|
| `assets/img/og-image.jpg` | 1200×630px | Social share image — forest bg, 5-bar mark, headline |
| `assets/img/najmi.jpg` | ~600×800px | Editorial portrait — dark bg, warm lighting |

See `assets/img/og-image-placeholder.txt` for design guidance.

---

## Project Structure

```
business-audit/
├── index.html          ← Single page (all content here)
├── src/
│   └── input.css       ← Tailwind source (edit to add custom CSS)
├── assets/
│   ├── css/
│   │   └── styles.css  ← Compiled Tailwind (auto-generated, don't edit)
│   ├── js/
│   │   └── main.js     ← Vanilla JS: topbar, drawer, FAQ accordion, animations
│   └── img/            ← Place images here
├── tailwind.config.js  ← Brand colors + font config
├── package.json
└── README.md
```

---

## Future Enhancements (not in v1)

- PHP form handler for email capture (WhatsApp-only currently)
- English language version
- A/B test on hero headline
- Testimonial video embeds
- Calendly booking widget as alternative to WhatsApp
- Lazy-load Najmi's portrait with blur-up technique
