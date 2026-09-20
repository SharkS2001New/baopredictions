<?php
require_once __DIR__ . '/../config/load-env.php';
require_once __DIR__ . '/../src/Api/bootstrap.php';

use App\Services\ContactFormGuard;
use App\Services\ContactMailService;

$flashOk = false;
$flashError = '';
$old = [
    'name' => '',
    'email' => '',
    'subject' => 'General enquiry',
    'message' => '',
];

$subjects = [
    'General enquiry',
    'Tip correction / wrong score',
    'Partnership enquiry',
    'Link exchange request',
    'Press / media',
    'League request',
    'Other',
];

if (strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET')) === 'POST') {
    $old = [
        'name' => trim((string) ($_POST['name'] ?? '')),
        'email' => trim((string) ($_POST['email'] ?? '')),
        'subject' => trim((string) ($_POST['subject'] ?? 'General enquiry')),
        'message' => trim((string) ($_POST['message'] ?? '')),
    ];
    if (! in_array($old['subject'], $subjects, true)) {
        $old['subject'] = 'General enquiry';
    }

    $guard = new ContactFormGuard();
    if ($guard->isHoneypotTripped($_POST) || $guard->isTooFast($_POST)) {
        header('Location: /contact-us?sent=1', true, 303);
        exit;
    }
    if (! $guard->allowRequest()) {
        $flashError = 'Too many messages from your network. Please wait a few minutes and try again.';
    } else {
        $result = (new ContactMailService())->send($old);
        if (! empty($result['ok'])) {
            header('Location: /contact-us?sent=1', true, 303);
            exit;
        }
        $flashError = (string) ($result['error'] ?? 'Could not send your message. Please try again.');
    }
}

if (isset($_GET['sent']) && (string) $_GET['sent'] === '1') {
    $flashOk = true;
    $old = [
        'name' => '',
        'email' => '',
        'subject' => 'General enquiry',
        'message' => '',
    ];
}

$formStartedAt = time();
require_once __DIR__ . '/../components/seo.php';
$updatedIso = bao_reviewed_iso();
$updatedDate = bao_reviewed_date();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contact Bao Predictions</title>
  <meta name="description" content="Contact Bao Predictions for tip corrections, partnerships, league requests and press — send fixture details for score or tip fixes.">
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
  <link rel="canonical" href="https://www.baopredictions.com/contact-us">

  <meta name="keywords" content="contact bao predictions, tip corrections, football tips support">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Contact Bao Predictions">
  <meta name="twitter:description" content="Contact Bao Predictions for tip corrections, partnerships, league requests and press enquiries.">
  <link rel="alternate" hreflang="en" href="https://www.baopredictions.com/contact-us">
  <link rel="alternate" hreflang="x-default" href="https://www.baopredictions.com/contact-us">

  <meta property="og:locale" content="en_US">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Contact Bao Predictions">
  <meta property="og:description" content="Contact Bao Predictions for tip corrections, partnerships, league requests and press enquiries.">
  <meta property="og:url" content="https://www.baopredictions.com/contact-us">
  <meta property="og:site_name" content="Bao Predictions">
    <script>
  (function () {
    try {
      var t = localStorage.getItem('bao-theme');
      if (!t) t = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
      document.documentElement.setAttribute('data-theme', t);
    } catch (e) {}
  })();
  </script>
<?php require __DIR__ . '/../components/head-assets.php'; ?>
<?php require __DIR__ . '/../components/favicon.php'; ?>
</head>
<body>
    <?php require __DIR__ . '/../components/header.php'; ?>
<main id="main">
<div class="wrap">

  <nav aria-label="Breadcrumb">
  <ol class="breadcrumbs">
    <li><a href="/">Home</a></li>
    <li><span aria-current="page">Contact</span></li>
  </ol>
</nav>

  <header class="page-hero">
    <h1>Contact Us</h1>
<?php echo bao_last_updated_html($updatedIso); ?>
<p class="lede">Send tip corrections, partnership notes, league requests or press questions through the form. We read every message; replies are slower on heavy match weekends.</p>
  </header>

  <article class="prose">
    <p>Use this page when something on Bao Predictions needs a human reply: a settled tip that looks wrong, a partnership enquiry, a league request, or press. We do not sell private “fixed” tips from this form, and we do not take stakes.</p>
  </article>

<?php if ($flashOk): ?>
  <div class="contact-alert contact-alert-success" role="status">
    Thanks — your message was sent. We will get back to you as soon as we can.
  </div>
<?php elseif ($flashError !== ''): ?>
  <div class="contact-alert contact-alert-error" role="alert">
    <?php echo htmlspecialchars($flashError, ENT_QUOTES, 'UTF-8'); ?>
  </div>
<?php endif; ?>

  <div class="contact-layout">
    <form class="contact-form" method="post" action="/contact-us" novalidate>
      <input type="hidden" name="form_started_at" value="<?php echo (int) $formStartedAt; ?>">
      <p class="contact-honeypot" aria-hidden="true">
        <label for="company_website">Company website</label>
        <input type="text" id="company_website" name="company_website" tabindex="-1" autocomplete="off">
      </p>

      <div class="contact-field">
        <label for="contact_name">Name</label>
        <input
          type="text"
          id="contact_name"
          name="name"
          required
          maxlength="120"
          autocomplete="name"
          value="<?php echo htmlspecialchars($old['name'], ENT_QUOTES, 'UTF-8'); ?>"
        >
      </div>

      <div class="contact-field">
        <label for="contact_email">Email</label>
        <input
          type="email"
          id="contact_email"
          name="email"
          required
          maxlength="180"
          autocomplete="email"
          value="<?php echo htmlspecialchars($old['email'], ENT_QUOTES, 'UTF-8'); ?>"
        >
      </div>

      <div class="contact-field">
        <label for="contact_subject">Subject</label>
        <select id="contact_subject" name="subject" required>
<?php foreach ($subjects as $subjectOption): ?>
          <option value="<?php echo htmlspecialchars($subjectOption, ENT_QUOTES, 'UTF-8'); ?>"<?php echo $old['subject'] === $subjectOption ? ' selected' : ''; ?>>
            <?php echo htmlspecialchars($subjectOption, ENT_QUOTES, 'UTF-8'); ?>
          </option>
<?php endforeach; ?>
        </select>
      </div>

      <div class="contact-field">
        <label for="contact_message">Message</label>
        <textarea
          id="contact_message"
          name="message"
          required
          maxlength="5000"
          rows="10"
          placeholder="For score corrections: teams, date, published tip, and correct final score."
        ><?php echo htmlspecialchars($old['message'], ENT_QUOTES, 'UTF-8'); ?></textarea>
      </div>

      <button type="submit" class="btn btn-primary contact-submit">Send message</button>
      <p class="contact-form-note">Messages may be rate-limited. How we handle contact data is in the <a href="/privacy-policy">Privacy Policy</a>. 18+ only.</p>
    </form>
  </div>

  <article class="prose" style="margin-top:2rem">
    <h2>What to send for a correction</h2>
    <p>If Yesterday or Results looks wrong, include the home and away teams, the match date, the tip we published, and the score you believe is correct. That is enough for us to check the settled record without guessing the fixture.</p>
    <p>This contact page was last reviewed on <strong><?php echo bao_h($updatedDate); ?></strong>. For how tips are built, see <a href="/how-we-predict">How We Predict</a>; for the public record, see <a href="/results">Football Results</a>.</p>
    <p>Many tip sites only offer a generic mailbox. Bao’s form is built around the corrections that keep the audit trail honest — wrong scores and missing results — not private banker sales.</p>
  </article>
</div>

<?php
$faqs = [
  [
    'q' => 'How fast do you reply?',
    'a' => 'Usually within a few business days. Heavy match weekends can slow replies because the desk is also reviewing team news and board updates.

Tip corrections with full fixture details get priority over generic “best pick today?” messages.',
  ],
  [
    'q' => 'What should I include for a score correction?',
    'a' => 'Home and away teams, kick-off date, the published tip, and the correct final score. That is enough to check Yesterday or Results without guessing the fixture.

Stephen Karuku\'s desk reviews corrections through this form. We do not silently rewrite settled history.',
  ],
  [
    'q' => 'Can I request a league?',
    'a' => 'Yes — name the competition and why it matters for Kenyan readers. We still only publish when model output and book prices clear the same 55% floor as other boards.

FKF Premier League fixtures already ingest when odds allow; sparse pricing means fewer KPL cards than European leagues.',
  ],
  [
    'q' => 'Do you sell fixed tips or private bankers?',
    'a' => 'No. Everything we publish is on the public boards — Today, shortlists, jackpots, Yesterday and Results. We do not sell “sure” private tips by email.

Banker of the Day is the same free Prediction of the Day shown in the sidebar, not a paid product.',
  ],
  [
    'q' => 'Partnership or press enquiries?',
    'a' => 'Choose Partnership enquiry in the subject list, or see Partners for link-exchange details. Include your URL, niche, and proposed placement.

Bao does not sell guaranteed-win placements or hide losses for sponsors.',
  ],
];
?>

<section class="section section-tight bao-faq">
  <div class="wrap">
    <h2 class="section-title">Contact FAQ</h2>
    <?php echo bao_faq_items_html($faqs); ?>

  </div>
</section>

</main>
  <?php require __DIR__ . '/../components/footer.php'; ?>
<script src="/assets/js/theme.js?v=20260913c" defer></script>
<!--BAO_SCHEMA_START-->
<?php
echo bao_faq_schema($faqs);
echo bao_breadcrumb_schema([
  ['name' => 'Home', 'url' => '/'],
  ['name' => 'Contact', 'url' => '/contact-us'],
]);
echo bao_article_schema(
  'Contact Us',
  'Contact Bao Predictions for tip corrections, partnerships, league requests and press — use the form for fixture details and score fixes.',
  '/contact-us'
);
echo bao_organization_schema();
?>
<!--BAO_SCHEMA_END-->
</body>
</html>
