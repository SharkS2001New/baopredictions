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
        // Pretend success to bots.
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contact Us | Bao Predictions</title>
  <meta name="description" content="Contact Bao Predictions — partnerships, corrections, and media enquiries. 18+ only.">
  <link rel="canonical" href="https://www.baopredictions.com/contact-us">
  <meta name="robots" content="index,follow">
  <meta name="title" content="Contact Us | Bao Predictions">
  <meta name="author" content="Bao Predictions Analysis Team">
  <meta property="og:title" content="Contact Us | Bao Predictions">
  <meta property="og:description" content="Contact Bao Predictions — partnerships, corrections, and media enquiries. 18+ only.">
  <meta property="og:url" content="https://www.baopredictions.com/contact-us">
  <meta property="og:type" content="website">
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
<?php require_once __DIR__ . '/../components/seo.php'; echo bao_last_updated_html(); ?>
<p class="lede">For tip corrections, partnership enquiries, or press — send a message below. We read every note; response times vary on matchdays.</p>
<p class="seo-related"><strong>Related:</strong> <a href="/about-us">About us</a> · <a href="/faq">FAQ</a> · <a href="/how-we-predict">How we predict</a></p>
  </header>

<?php if ($flashOk): ?>
  <div class="contact-alert contact-alert-success" role="status">
    Thanks — your message was sent. We’ll get back to you as soon as we can.
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
          placeholder="Include fixture details for score corrections when you can."
        ><?php echo htmlspecialchars($old['message'], ENT_QUOTES, 'UTF-8'); ?></textarea>
      </div>

      <button type="submit" class="btn btn-primary contact-submit">Send message</button>
      <p class="contact-form-note">We do not provide private “fixed” tips or guaranteed scores. 18+ only.</p>
    </form>
  </div>
</div>

  <section class="section"><div class="wrap"><h2 class="section-title">FAQ</h2><ul class="faq-list"><li><details><summary>How fast do you reply?</summary><p>Usually within a few business days; slower on heavy match weekends.</p></details></li><li><details><summary>Can I request a league?</summary><p>Yes — tell us which competition and why it matters to Kenyan bettors.</p></details></li><li><details><summary>Where do I report a wrong score?</summary><p>Use the form above with the fixture, published tip, and correct result — we fix settled records promptly.</p></details></li></ul></div></section>
</main>
  <?php require __DIR__ . '/../components/footer.php'; ?>
<script src="/assets/js/theme.js" defer></script>
<?php require_once __DIR__ . '/../components/seo.php'; echo bao_faq_schema(array (
  0 =>
  array (
    'q' => 'How fast do you reply?',
    'a' => 'Usually within a few business days; slower on heavy match weekends.',
  ),
  1 =>
  array (
    'q' => 'Can I request a league?',
    'a' => 'Yes — tell us which competition and why it matters to Kenyan bettors.',
  ),
  2 =>
  array (
    'q' => 'Where do I report a wrong score?',
    'a' => 'Use the contact form with the fixture, published tip, and correct result — we fix settled records promptly.',
  ),
)); echo bao_breadcrumb_schema(array (
  0 =>
  array (
    'name' => 'Home',
    'url' => '/',
  ),
  1 =>
  array (
    'name' => 'Contact',
    'url' => '/contact-us',
  ),
)); echo bao_article_schema('Contact Us', 'Contact Bao Predictions — partnerships, corrections, and media enquiries. 18+ only.', '/contact-us'); echo bao_organization_schema(); ?>
</body>
</html>
