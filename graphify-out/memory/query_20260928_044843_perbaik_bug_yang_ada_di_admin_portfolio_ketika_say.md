---
type: "query"
date: "2026-09-28T04:48:43.747774+00:00"
question: "perbaik bug yang ada di admin/portfolio ketika saya klik detail terplay video tapi ketika saya close video seperti masih terputar padahal sudah tercolse karena suara dari video masih jalan faham?"
contributor: "graphify"
outcome: "useful"
source_nodes: ["Portfolio", "KedubesStarterIntegrationTest.php"]
---

# Q: perbaik bug yang ada di admin/portfolio ketika saya klik detail terplay video tapi ketika saya close video seperti masih terputar padahal sudah tercolse karena suara dari video masih jalan faham?

## Answer

Expanded from original query via graph vocab: [portfolio, modal, video, youtube, close]. Fixed resources/views/admin/portfolios/index.blade.php by deferring iframe src assignment until the detail dialog opens and removing iframe src on the dialog close event. The close event covers the close button, backdrop closure, and Escape, stopping YouTube playback and audio. Added regression assertions in tests/Feature/KedubesStarterIntegrationTest.php. Verification passed: 32 tests and 164 assertions; Pint passed; git diff check found no whitespace errors.

## Outcome

- Signal: useful

## Source Nodes

- Portfolio
- KedubesStarterIntegrationTest.php