<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    body { font-family: 'Helvetica Neue', Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
    .wrapper { max-width: 560px; margin: 40px auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
    .header { background: linear-gradient(135deg, #f97316, #ef4444, #ec4899); padding: 2rem; text-align: center; }
    .header h1 { color: #fff; font-size: 1.8rem; letter-spacing: 3px; text-transform: uppercase; margin: 0; }
    .body { padding: 2rem; }
    .body h2 { color: #333; font-size: 1.1rem; margin-bottom: 1rem; }
    .message-bubble { background: #f9f0ff; border-left: 4px solid #ec4899; border-radius: 8px; padding: 1rem 1.25rem; font-size: 0.95rem; color: #444; font-style: italic; margin: 1.5rem 0; }
    .sender { font-size: 0.9rem; color: #666; margin-bottom: 1.5rem; }
    .btn { display: inline-block; background: linear-gradient(135deg, #f97316, #ef4444); color: #fff; text-decoration: none; padding: 0.85rem 2rem; border-radius: 999px; font-weight: 700; font-size: 0.95rem; }
    .footer { background: #f9f9f9; text-align: center; padding: 1rem; font-size: 0.75rem; color: #aaa; }
</style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>Meetly</h1>
    </div>
    <div class="body">
        <h2>Tu as reçu une demande de message!</h2>
        <div class="sender">
            <strong>{{ $sender->prenom }} {{ $sender->nom }}</strong> t'a envoyé un message :
        </div>
        <div class="message-bubble">
            "{{ $firstMessage }}"
        </div>
        <p style="color:#666;font-size:0.88rem;margin-bottom:1.5rem;">
            Connecte-toi à Meetly pour accepter ou refuser cette demande.
        </p>
        <a href="{{ config('app.url') }}/chats" class="btn">Voir la demande</a>
    </div>
    <div class="footer">
        Tu reçois cet email car tu es inscrit(e) sur Meetly.<br>
        Si tu ne veux plus recevoir ces emails, modifie tes préférences dans ton profil.
    </div>
</div>
</body>
</html>
