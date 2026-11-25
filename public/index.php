<?php
declare(strict_types=1);

// Report every PHP notice/warning so we can make sure there are none
error_reporting(E_ALL);

/**
 * ITCS333 A4 starter — contact form.
 * Requirements (see README rubric):
 *   - GET: render the form
 *   - POST: validate name (required, 2-60 chars), email (valid), message (required, 10-1000 chars)
 *   - invalid POST: re-render with inline errors + repopulated fields (200)
 *   - valid POST: redirect (302/303) to a success page (PRG)
 *   - escape all output with htmlspecialchars
 */

require_once __DIR__ . '/../src/validate.php';

// TODO: implement the form handling here.
// Escape output to prevent XSS
function e(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

$values = ['name' => '', 'email' => '', 'message' => ''];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read fields safely
    foreach ($values as $key => $_) {
        $raw = $_POST[$key] ?? '';
        $values[$key] = is_string($raw) ? trim($raw) : '';
    }

    // Name: required, 2-60 chars
    if (!validate_required($values['name'])) {
        $errors['name'] = 'Name is required.';
    } elseif (!validate_length($values['name'], 2, 60)) {
        $errors['name'] = 'Name must be between 2 and 60 characters.';
    }

    // Email: required and valid
    if (!validate_required($values['email'])) {
        $errors['email'] = 'Email is required.';
    } elseif (!validate_email($values['email'])) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    // Message: required, 10-1000 chars
    if (!validate_required($values['message'])) {
        $errors['message'] = 'Message is required.';
    } elseif (!validate_length($values['message'], 10, 1000)) {
        $errors['message'] = 'Message must be between 10 and 1000 characters.';
    }

    // PRG: valid input -> redirect to the success page
    if (!$errors) {
        header('Location: success.php', true, 303);
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Contact Us</title>
</head>
<body>
  <h1>Contact Us</h1>
  <!-- TODO: render the form, inline errors, and repopulated values -->
  <form method="post" action="index.php">
    <label>Name <input type="text" name="name" value="<?= e($values['name']) ?>"></label><br>
    <label>Email <input type="email" name="email" value="<?= e($values['email']) ?>"></label><br>
    <label>Message <textarea name="message"><?= e($values['message']) ?></textarea></label><br>
    <button type="submit">Send</button>
  </form>
</body>
</html>
    