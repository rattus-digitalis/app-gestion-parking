<?php
$title = "Contact";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main class="container">
    <section class="contact-form">
        <h1>Contactez-nous</h1>
        <p>Vous avez une question, un problème ou une suggestion ? Envoyez-nous un message via ce formulaire :</p>

        <form action="/?page=contact" method="POST">
            <div class="form-group">
                <label for="name">Nom complet *</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Adresse email *</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="message">Message *</label>
                <textarea id="message" name="message" rows="6" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
    </section>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>

