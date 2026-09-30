---
paths:
  - 'app/Models/Portfolio.php,app/Http/Controllers/Admin/PortfolioController.php,resources/views/{welcome.blade.php,admin/portfolios/**}'
---

# Portfolios

## Portfolio cards open YouTube videos
A portfolio item uses an optional uploaded image as the homepage card thumbnail (falling back to the YouTube thumbnail), while clicking the card always opens its required YouTube URL in the modal. Do not reintroduce separate image-only and video portfolio modes.
