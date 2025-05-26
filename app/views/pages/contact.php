<?php
$title = "Contact";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main class="container" role="main">
    <section class="contact-form">
        <h1>📨 Contactez-nous</h1>
        <p>Vous avez une question, un problème ou une suggestion ? Envoyez-nous un message via ce formulaire :</p>

        <form action="/?page=contact" method="POST" aria-label="Formulaire de contact">
            <div class="form-group">
                <label for="name">Nom complet <span aria-hidden="true">*</span></label>
                <input type="text" id="name" name="name" required aria-required="true">
            </div>

            <div class="form-group">
                <label for="email">Adresse email <span aria-hidden="true">*</span></label>
                <input type="email" id="email" name="email" required aria-required="true">
            </div>

            <div class="form-group">
                <label for="message">Message <span aria-hidden="true">*</span></label>
                <textarea id="message" name="message" rows="6" required aria-required="true"></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary" aria-label="Envoyer le message">📤 Envoyer</button>
            </div>
        </form>
    </section>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
